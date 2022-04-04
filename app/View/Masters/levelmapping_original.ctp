<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>
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
        //-------------------Level 1 Grid---------------------------------------------------------------------------------------------------------
        if ($('#hfhidden1').val() == 'Y') {
            $('#tablelevel1').dataTable({
                "iDisplayLength": 5,
                "aLengthMenu": [[10, 15, -1], [10, 15, "All"]]
            });
        }

//-------------------Level 1 List Grid---------------------------------------------------------------------------------------------------
        if ($('#hfhidden2').val() == 'Y') {
            $('#tablelevel1list').dataTable({
                "iDisplayLength": 5,
                "aLengthMenu": [[10, 15, -1], [10, 15, "All"]]
            });
        }
//-------------------Level 2 Grid---------------------------------------------------------------------------------------------------
        if ($('#hfhidden3').val() == 'Y') {
            $('#tablelevel2').dataTable({
                "iDisplayLength": 5,
                "aLengthMenu": [[10, 15, -1], [10, 15, "All"]]
            });
        }
//-------------------Level 2 List Grid---------------------------------------------------------------------------------------------------
        if ($('#hfhidden4').val() == 'Y') {
            $('#tablelevel2list').dataTable({
                "iDisplayLength": 5,
                "aLengthMenu": [[10, 15, -1], [10, 15, "All"]]
            });
        }
//-------------------Level 3 Grid---------------------------------------------------------------------------------------------------
        if ($('#hfhidden5').val() == 'Y') {
            $('#tablelevel3').dataTable({
                "iDisplayLength": 5,
                "aLengthMenu": [[10, 15, -1], [10, 15, "All"]]
            });
        }
//-------------------Level 3 List Grid---------------------------------------------------------------------------------------------------
        if ($('#hfhidden6').val() == 'Y') {
            $('#tablelevel3list').dataTable({
                "iDisplayLength": 5,
                "aLengthMenu": [[10, 15, -1], [10, 15, "All"]]
            });
        }
//-------------------Level 4 Grid---------------------------------------------------------------------------------------------------
        if ($('#hfhidden7').val() == 'Y') {
            $('#tablelevel4').dataTable({
                "iDisplayLength": 5,
                "aLengthMenu": [[10, 15, -1], [10, 15, "All"]]
            });
        }
//-------------------Level 4 List Grid---------------------------------------------------------------------------------------------------
        if ($('#hfhidden8').val() == 'Y') {
            $('#tablelevel4list').dataTable({
                "iDisplayLength": 5,
                "aLengthMenu": [[10, 15, -1], [10, 15, "All"]]
            });
        }
        var actiontype = $('#actiontype').val()
        if (actiontype == '2') {
            $('#level_1_desc_en').focus();
            $('#btnaddlevel1').html('Save');
        }
        if (actiontype == '3') {
            $('#list_1_desc_en').focus();
            $('#btnaddlevellist1').html('Save');
        }
        if (actiontype == '4') {
            $('#level_2_desc_en').focus();
            $('#btnaddlevel2').html('Save');
        }
        if (actiontype == '5') {
            $('#list_2_desc_en').focus();
            $('#btnaddlevellist2').html('Save');
        }
        if (actiontype == '6') {
            $('#level_3_desc_en').focus();
            $('#btnaddlevel3').html('Save');
        }
        if (actiontype == '7') {
            $('#list_3_desc_en').focus();
            $('#btnaddlevellist3').html('Save');
        }
        if (actiontype == '8') {
            $('#level_4_desc_en').focus();
            $('#btnaddlevel4').html('Save');
        }
        if (actiontype == '9') {
            $('#list_4_desc_en').focus();
            $('#btnaddlevellist4').html('Save');
        }

        if ($('#village_id').val() != '') {
            $('#div1').slideDown(1000);
            $("#village_id").prop("disabled", false);
        } else {
            $('#div1').hide();
            $("#village_id").prop("disabled", true);
        }

        if ($('#village_id').val() != '' && $('#level_1_desc_en').val() != '') {
            $('#div2').slideDown(1000);
        } else {
            $('#div2').hide();
        }

        if ($('#village_id').val() != '' && $('#level_1_desc_en').val() != '' && $('#list_1_desc_en').val() != '') {
            $('#div3').slideDown(1000);
        } else {
            $('#div3').hide();
        }

        if ($('#village_id').val() != '' && $('#level_1_desc_en').val() != '' && $('#list_1_desc_en').val() != '' && $('#level_2_desc_en').val() != '') {
            $('#div4').slideDown(1000);
        } else {
            $('#div4').hide();
        }

        if ($('#village_id').val() != '' && $('#level_1_desc_en').val() != '' && $('#list_1_desc_en').val() != '' && $('#level_2_desc_en').val() != '' && $('#list_2_desc_en').val() != '') {
            $('#div5').slideDown(1000);
        } else {
            $('#div5').hide();
        }
        if ($('#village_id').val() != '' && $('#level_1_desc_en').val() != '' && $('#list_1_desc_en').val() != '' && $('#level_2_desc_en').val() != '' && $('#list_2_desc_en').val() != '' && $('#level_3_desc_en').val() != '') {
            $('#div6').slideDown(1000);
        } else {
            $('#div6').hide();
        }
        if ($('#village_id').val() != '' && $('#level_1_desc_en').val() != '' && $('#list_1_desc_en').val() != '' && $('#level_2_desc_en').val() != '' && $('#list_2_desc_en').val() != '' && $('#level_3_desc_en').val() != '' && $('#list_3_desc_en').val() != '') {
            $('#div7').slideDown(1000);
        } else {
            $('#div7').hide();
        }
        if ($('#village_id').val() != '' && $('#level_1_desc_en').val() != '' && $('#list_1_desc_en').val() != '' && $('#level_2_desc_en').val() != '' && $('#list_2_desc_en').val() != '' && $('#level_3_desc_en').val() != '' && $('#list_3_desc_en').val() != '' && $('#level_4_desc_en').val() != '') {
            $('#div8').slideDown(1000);
        } else {
            $('#div8').hide();
        }
//
// ---------------Level 1 hide show--------------------------------------------------------------------------------------------------
        $('#btnhidelevel1').hide();
        $('#divlevel1').hide();

        if ($('#level_1_desc_en').val() != '') {
            $('#btnshowlevel1').show();
            $('#btnhidelevel1').hide();
            $('#divlevel1').slideDown(1000);
        } else {
            $('#btnshowlevel1').show();
            $('#btnhidelevel1').hide();
            $('#divlevel1').hide();
            $('#level_1_desc_en').val('');
            $('#surveynotype_id').val('');
        }

        $("#btnshowlevel1").click(function () {
            if ($('#village_id').val() != '') {
                $('#btnhidelevel1').show();
                $('#btnshowlevel1').hide();
                $('#divlevel1').slideDown(1000);
                return false;
            } else {
                alert('Please Select Village...!!!');
                return false;
            }
        });

        $("#btnhidelevel1").click(function () {
            $('#btnshowlevel1').show();
            $('#btnhidelevel1').hide();
            $('#divlevel1').hide();
            $('#level_1_desc_en').val('');
            $('#surveynotype_id').val('');
            $('#hfupdateflag').val('');
            return false;
        });

        $("#district_id,#taluka_id,#village_id").click(function () {

            if ($('#level_1_desc_en').val() !== '' && $('#list_1_desc_en').val() !== '' && $('#level_2_desc_en').val() !== '' && $('#list_2_desc_en').val() !== '' && $('#level_3_desc_en').val() !== '' && $('#list_3_desc_en').val() !== '' && $('#level_4_desc_en').val() !== '' && $('#list_4_desc_en').val() !== '') {

                $('#level_1_desc_en').val('');
                $('#surveynotype_id').val('');
                $('#hfupdateflag').val('');
                $('#btnaddlevel1').html('Add');
                $('#btnshowlevel1').show();
                $('#btnhidelevel1').hide();
                $('#divlevel1').hide();

                $('#list_1_desc_en').val('');
                $('#level_1_from_range').val('');
                $('#level_1_to_range').val('');
                $('#btnshowlevel1list').show();
                $('#btnhidelevel1list').hide();
                $('#divlevellist1').hide();

                $('#level_2_desc_en').val('');
                $('#surveynotype_id1').val('');
                $('#btnshowlevel2').show();
                $('#btnhidelevel2').hide();
                $('#divlevel2').hide();

                $('#btnshowlevel2list').show();
                $('#btnhidelevel2list').hide();
                $('#divlevellist2').hide();
                $('#list_2_desc_en').val('');
                $('#level_2_from_range').val('');
                $('#level_2_to_range').val('');

                $('#btnshowlevel3').show();
                $('#btnhidelevel3').hide();
                $('#divlevel3').hide();
                $('#level_3_desc_en').val('');
                $('#surveynotype_id2').val('');

                $('#list_3_desc_en').val('');
                $('#level_3_from_range').val('');
                $('#level_3_to_range').val('');
                $('#btnshowlevel3list').show();
                $('#btnhidelevel3list').hide();
                $('#divlevellist3').hide();

                $('#btnshowlevel4').show();
                $('#btnhidelevel4').hide();
                $('#divlevel4').hide();
                $('#level_4_desc_en').val('');
                $('#surveynotype_id3').val('');

                $('#btnshowlevel4list').show();
                $('#btnhidelevel4list').hide();
                $('#divlevellist4').hide();
                $('#list_4_desc_en').val('');
                $('#level_4_from_range').val('');
                $('#level_4_to_range').val('');

            } else if ($('#level_1_desc_en').val() !== '' && $('#list_1_desc_en').val() !== '' && $('#level_2_desc_en').val() !== '' && $('#list_2_desc_en').val() !== '' && $('#level_3_desc_en').val() !== '' && $('#list_3_desc_en').val() !== '' && $('#level_4_desc_en').val() !== '') {

                $('#level_1_desc_en').val('');
                $('#surveynotype_id').val('');
                $('#hfupdateflag').val('');
                $('#btnaddlevel1').html('Add');
                $('#btnshowlevel1').show();
                $('#btnhidelevel1').hide();
                $('#divlevel1').hide();

                $('#list_1_desc_en').val('');
                $('#level_1_from_range').val('');
                $('#level_1_to_range').val('');
                $('#btnshowlevel1list').show();
                $('#btnhidelevel1list').hide();
                $('#divlevellist1').hide();

                $('#level_2_desc_en').val('');
                $('#surveynotype_id1').val('');
                $('#btnshowlevel2').show();
                $('#btnhidelevel2').hide();
                $('#divlevel2').hide();

                $('#btnshowlevel2list').show();
                $('#btnhidelevel2list').hide();
                $('#divlevellist2').hide();
                $('#list_2_desc_en').val('');
                $('#level_2_from_range').val('');
                $('#level_2_to_range').val('');

                $('#btnshowlevel3').show();
                $('#btnhidelevel3').hide();
                $('#divlevel3').hide();
                $('#level_3_desc_en').val('');
                $('#surveynotype_id2').val('');

                $('#list_3_desc_en').val('');
                $('#level_3_from_range').val('');
                $('#level_3_to_range').val('');
                $('#btnshowlevel3list').show();
                $('#btnhidelevel3list').hide();
                $('#divlevellist3').hide();

                $('#btnshowlevel4').show();
                $('#btnhidelevel4').hide();
                $('#divlevel4').hide();
                $('#level_4_desc_en').val('');
                $('#surveynotype_id3').val('');

            } else if ($('#level_1_desc_en').val() !== '' && $('#list_1_desc_en').val() !== '' && $('#level_2_desc_en').val() !== '' && $('#list_2_desc_en').val() !== '' && $('#level_3_desc_en').val() !== '' && $('#list_3_desc_en').val() !== '') {

                $('#level_1_desc_en').val('');
                $('#surveynotype_id').val('');
                $('#hfupdateflag').val('');
                $('#btnaddlevel1').html('Add');
                $('#btnshowlevel1').show();
                $('#btnhidelevel1').hide();
                $('#divlevel1').hide();

                $('#list_1_desc_en').val('');
                $('#level_1_from_range').val('');
                $('#level_1_to_range').val('');
                $('#btnshowlevel1list').show();
                $('#btnhidelevel1list').hide();
                $('#divlevellist1').hide();

                $('#level_2_desc_en').val('');
                $('#surveynotype_id1').val('');
                $('#btnshowlevel2').show();
                $('#btnhidelevel2').hide();
                $('#divlevel2').hide();

                $('#btnshowlevel2list').show();
                $('#btnhidelevel2list').hide();
                $('#divlevellist2').hide();
                $('#list_2_desc_en').val('');
                $('#level_2_from_range').val('');
                $('#level_2_to_range').val('');

                $('#btnshowlevel3').show();
                $('#btnhidelevel3').hide();
                $('#divlevel3').hide();
                $('#level_3_desc_en').val('');
                $('#surveynotype_id2').val('');

                $('#list_3_desc_en').val('');
                $('#level_3_from_range').val('');
                $('#level_3_to_range').val('');
                $('#btnshowlevel3list').show();
                $('#btnhidelevel3list').hide();
                $('#divlevellist3').hide();

            } else if ($('#level_1_desc_en').val() !== '' && $('#list_1_desc_en').val() !== '' && $('#level_2_desc_en').val() !== '' && $('#list_2_desc_en').val() !== '' && $('#level_3_desc_en').val() !== '') {

                $('#level_1_desc_en').val('');
                $('#surveynotype_id').val('');
                $('#hfupdateflag').val('');
                $('#btnaddlevel1').html('Add');
                $('#btnshowlevel1').show();
                $('#btnhidelevel1').hide();
                $('#divlevel1').hide();

                $('#list_1_desc_en').val('');
                $('#level_1_from_range').val('');
                $('#level_1_to_range').val('');
                $('#btnshowlevel1list').show();
                $('#btnhidelevel1list').hide();
                $('#divlevellist1').hide();

                $('#level_2_desc_en').val('');
                $('#surveynotype_id1').val('');
                $('#btnshowlevel2').show();
                $('#btnhidelevel2').hide();
                $('#divlevel2').hide();

                $('#btnshowlevel2list').show();
                $('#btnhidelevel2list').hide();
                $('#divlevellist2').hide();
                $('#list_2_desc_en').val('');
                $('#level_2_from_range').val('');
                $('#level_2_to_range').val('');

                $('#btnshowlevel3').show();
                $('#btnhidelevel3').hide();
                $('#divlevel3').hide();
                $('#level_3_desc_en').val('');
                $('#surveynotype_id2').val('');

            } else if ($('#level_1_desc_en').val() !== '' && $('#list_1_desc_en').val() !== '' && $('#level_2_desc_en').val() !== '' && $('#list_2_desc_en').val() !== '') {

                $('#level_1_desc_en').val('');
                $('#surveynotype_id').val('');
                $('#hfupdateflag').val('');
                $('#btnaddlevel1').html('Add');
                $('#btnshowlevel1').show();
                $('#btnhidelevel1').hide();
                $('#divlevel1').hide();

                $('#list_1_desc_en').val('');
                $('#level_1_from_range').val('');
                $('#level_1_to_range').val('');
                $('#btnshowlevel1list').show();
                $('#btnhidelevel1list').hide();
                $('#divlevellist1').hide();

                $('#level_2_desc_en').val('');
                $('#surveynotype_id1').val('');
                $('#btnshowlevel2').show();
                $('#btnhidelevel2').hide();
                $('#divlevel2').hide();

                $('#btnshowlevel2list').show();
                $('#btnhidelevel2list').hide();
                $('#divlevellist2').hide();
                $('#list_2_desc_en').val('');
                $('#level_2_from_range').val('');
                $('#level_2_to_range').val('');


            } else if ($('#level_1_desc_en').val() !== '' && $('#list_1_desc_en').val() !== '' && $('#level_2_desc_en').val() !== '') {
                $('#level_1_desc_en').val('');
                $('#surveynotype_id').val('');
                $('#hfupdateflag').val('');
                $('#btnaddlevel1').html('Add');
                $('#btnshowlevel1').show();
                $('#btnhidelevel1').hide();
                $('#divlevel1').hide();

                $('#list_1_desc_en').val('');
                $('#level_1_from_range').val('');
                $('#level_1_to_range').val('');
                $('#btnshowlevel1list').show();
                $('#btnhidelevel1list').hide();
                $('#divlevellist1').hide();

                $('#level_2_desc_en').val('');
                $('#surveynotype_id1').val('');
                $('#btnshowlevel2').show();
                $('#btnhidelevel2').hide();
                $('#divlevel2').hide();

            } else if ($('#level_1_desc_en').val() !== '' && $('#list_1_desc_en').val() !== '') {
                $('#level_1_desc_en').val('');
                $('#surveynotype_id').val('');
                $('#hfupdateflag').val('');
                $('#btnaddlevel1').html('Add');
                $('#btnshowlevel1').show();
                $('#btnhidelevel1').hide();
                $('#divlevel1').hide();

                $('#list_1_desc_en').val('');
                $('#level_1_from_range').val('');
                $('#level_1_to_range').val('');
                $('#btnshowlevel1list').show();
                $('#btnhidelevel1list').hide();
                $('#divlevellist1').hide();

            } else if ($('#level_1_desc_en').val() !== '') {
                $('#level_1_desc_en').val('');
                $('#surveynotype_id').val('');
                $('#hfupdateflag').val('');
                $('#btnaddlevel1').html('Add');
                $('#btnshowlevel1').show();
                $('#btnhidelevel1').hide();
                $('#divlevel1').hide();
            }
        });

// ----------------Level 1 List hide show-------------------------------------------------------------------------------------------
        $('#btnhidelevel1list').hide();
        $('#divlevellist1').hide();
        if ($('#level_1_desc_en').val() != '' && $('#list_1_desc_en').val() != '') {
            $('#btnshowlevel1list').show();
            $('#btnhidelevel1list').hide();
            $('#divlevellist1').slideDown(1000);
        } else {
            $('#btnshowlevel1list').show();
            $('#btnhidelevel1list').hide();
            $('#divlevellist1').hide();
            $('#list_1_desc_en').val('');
            $('#level_1_from_range').val('');
            $('#level_1_to_range').val('');
        }

        $("#btnshowlevel1list").click(function () {
//            alert(level1);
            if ($('#village_id').val() != '' && $('#level_1_desc_en').val() != '') {
                $('#btnhidelevel1list').show();
                $('#btnshowlevel1list').hide();
                $('#divlevellist1').slideDown(1000);
                return false;
            } else {
                alert('Please Select Level 1 description!!!');
                return false;
            }
        });

        $("#btnhidelevel1list").click(function () {
            $('#btnshowlevel1list').show();
            $('#btnhidelevel1list').hide();
            $('#divlevellist1').hide();
            $('#list_1_desc_en').val('');
            $('#level_1_from_range').val('');
            $('#level_1_to_range').val('');
            $('#hfupdateflag').val('');
            return false;
        });

        $("#level_1_desc_en,#surveynotype_id,#btnaddlevel1,#btnupdatelevel1").click(function () {
            if ($('#list_1_desc_en').val() !== '' && $('#level_2_desc_en').val() !== '' && $('#list_2_desc_en').val() !== '' && $('#level_3_desc_en').val() !== '' && $('#list_3_desc_en').val() !== '' && $('#level_4_desc_en').val() !== '' && $('#list_4_desc_en').val() !== '') {
                $('#list_1_desc_en').val('');
                $('#level_1_from_range').val('');
                $('#level_1_to_range').val('');
                $('#btnaddlevel1').html('Add');
                $('#btnshowlevel1list').show();
                $('#btnhidelevel1list').hide();
                $('#divlevellist1').hide();

                $('#level_2_desc_en').val('');
                $('#surveynotype_id1').val('');
                $('#btnshowlevel2').show();
                $('#btnhidelevel2').hide();
                $('#divlevel2').hide();

                $('#btnshowlevel2list').show();
                $('#btnhidelevel2list').hide();
                $('#divlevellist2').hide();
                $('#list_2_desc_en').val('');
                $('#level_2_from_range').val('');
                $('#level_2_to_range').val('');

                $('#btnshowlevel3').show();
                $('#btnhidelevel3').hide();
                $('#divlevel3').hide();
                $('#level_3_desc_en').val('');
                $('#surveynotype_id2').val('');

                $('#list_3_desc_en').val('');
                $('#level_3_from_range').val('');
                $('#level_3_to_range').val('');
                $('#btnshowlevel3list').show();
                $('#btnhidelevel3list').hide();
                $('#divlevellist3').hide();

                $('#btnshowlevel4').show();
                $('#btnhidelevel4').hide();
                $('#divlevel4').hide();
                $('#level_4_desc_en').val('');
                $('#surveynotype_id3').val('');

                $('#btnshowlevel4list').show();
                $('#btnhidelevel4list').hide();
                $('#divlevellist4').hide();
                $('#list_4_desc_en').val('');
                $('#level_4_from_range').val('');
                $('#level_4_to_range').val('');

            } else if ($('#list_1_desc_en').val() !== '' && $('#level_2_desc_en').val() !== '' && $('#list_2_desc_en').val() !== '' && $('#level_3_desc_en').val() !== '' && $('#list_3_desc_en').val() !== '' && $('#level_4_desc_en').val() !== '') {
                $('#list_1_desc_en').val('');
                $('#level_1_from_range').val('');
                $('#level_1_to_range').val('');
                $('#btnaddlevel1').html('Add');
                $('#btnshowlevel1list').show();
                $('#btnhidelevel1list').hide();
                $('#divlevellist1').hide();

                $('#level_2_desc_en').val('');
                $('#surveynotype_id1').val('');
                $('#btnshowlevel2').show();
                $('#btnhidelevel2').hide();
                $('#divlevel2').hide();

                $('#btnshowlevel2list').show();
                $('#btnhidelevel2list').hide();
                $('#divlevellist2').hide();
                $('#list_2_desc_en').val('');
                $('#level_2_from_range').val('');
                $('#level_2_to_range').val('');

                $('#btnshowlevel3').show();
                $('#btnhidelevel3').hide();
                $('#divlevel3').hide();
                $('#level_3_desc_en').val('');
                $('#surveynotype_id2').val('');

                $('#list_3_desc_en').val('');
                $('#level_3_from_range').val('');
                $('#level_3_to_range').val('');
                $('#btnshowlevel3list').show();
                $('#btnhidelevel3list').hide();
                $('#divlevellist3').hide();

                $('#btnshowlevel4').show();
                $('#btnhidelevel4').hide();
                $('#divlevel4').hide();
                $('#level_4_desc_en').val('');
                $('#surveynotype_id3').val('');

            } else if ($('#list_1_desc_en').val() !== '' && $('#level_2_desc_en').val() !== '' && $('#list_2_desc_en').val() !== '' && $('#level_3_desc_en').val() !== '' && $('#list_3_desc_en').val() !== '') {
                $('#list_1_desc_en').val('');
                $('#level_1_from_range').val('');
                $('#level_1_to_range').val('');
                $('#btnaddlevel1').html('Add');
                $('#btnshowlevel1list').show();
                $('#btnhidelevel1list').hide();
                $('#divlevellist1').hide();

                $('#level_2_desc_en').val('');
                $('#surveynotype_id1').val('');
                $('#btnshowlevel2').show();
                $('#btnhidelevel2').hide();
                $('#divlevel2').hide();

                $('#btnshowlevel2list').show();
                $('#btnhidelevel2list').hide();
                $('#divlevellist2').hide();
                $('#list_2_desc_en').val('');
                $('#level_2_from_range').val('');
                $('#level_2_to_range').val('');

                $('#btnshowlevel3').show();
                $('#btnhidelevel3').hide();
                $('#divlevel3').hide();
                $('#level_3_desc_en').val('');
                $('#surveynotype_id2').val('');

                $('#list_3_desc_en').val('');
                $('#level_3_from_range').val('');
                $('#level_3_to_range').val('');
                $('#btnshowlevel3list').show();
                $('#btnhidelevel3list').hide();
                $('#divlevellist3').hide();

            } else if ($('#list_1_desc_en').val() !== '' && $('#level_2_desc_en').val() !== '' && $('#list_2_desc_en').val() !== '' && $('#level_3_desc_en').val() !== '') {
                $('#list_1_desc_en').val('');
                $('#level_1_from_range').val('');
                $('#level_1_to_range').val('');
                $('#btnaddlevel1').html('Add');
                $('#btnshowlevel1list').show();
                $('#btnhidelevel1list').hide();
                $('#divlevellist1').hide();

                $('#level_2_desc_en').val('');
                $('#surveynotype_id1').val('');
                $('#btnshowlevel2').show();
                $('#btnhidelevel2').hide();
                $('#divlevel2').hide();

                $('#btnshowlevel2list').show();
                $('#btnhidelevel2list').hide();
                $('#divlevellist2').hide();
                $('#list_2_desc_en').val('');
                $('#level_2_from_range').val('');
                $('#level_2_to_range').val('');

                $('#btnshowlevel3').show();
                $('#btnhidelevel3').hide();
                $('#divlevel3').hide();
                $('#level_3_desc_en').val('');
                $('#surveynotype_id2').val('');

            } else if ($('#list_1_desc_en').val() !== '' && $('#level_2_desc_en').val() !== '' && $('#list_2_desc_en').val() !== '') {
                $('#list_1_desc_en').val('');
                $('#level_1_from_range').val('');
                $('#level_1_to_range').val('');
                $('#btnaddlevel1').html('Add');
                $('#btnshowlevel1list').show();
                $('#btnhidelevel1list').hide();
                $('#divlevellist1').hide();

                $('#level_2_desc_en').val('');
                $('#surveynotype_id1').val('');
                $('#btnshowlevel2').show();
                $('#btnhidelevel2').hide();
                $('#divlevel2').hide();

                $('#btnshowlevel2list').show();
                $('#btnhidelevel2list').hide();
                $('#divlevellist2').hide();
                $('#list_2_desc_en').val('');
                $('#level_2_from_range').val('');
                $('#level_2_to_range').val('');
            } else if ($('#list_1_desc_en').val() !== '' && $('#level_2_desc_en').val() !== '') {
                $('#list_1_desc_en').val('');
                $('#level_1_from_range').val('');
                $('#level_1_to_range').val('');
                $('#btnaddlevel1').html('Add');
                $('#btnshowlevel1list').show();
                $('#btnhidelevel1list').hide();
                $('#divlevellist1').hide();

                $('#level_2_desc_en').val('');
                $('#surveynotype_id1').val('');
                $('#btnshowlevel2').show();
                $('#btnhidelevel2').hide();
                $('#divlevel2').hide();
            } else if ($('#list_1_desc_en').val() !== '') {
                $('#list_1_desc_en').val('');
                $('#level_1_from_range').val('');
                $('#level_1_to_range').val('');
                $('#btnaddlevel1').html('Add');
                $('#btnshowlevel1list').show();
                $('#btnhidelevel1list').hide();
                $('#divlevellist1').hide();
            }
        });

// ----------------Level 2 hide show-------------------------------------------------------------------------------------------
        $('#btnhidelevel2').hide();
        $('#divlevel2').hide();

        if ($('#level_1_desc_en').val() != '' && $('#list_1_desc_en').val() != '' && $('#level_2_desc_en').val() != '') {
            $('#btnshowlevel2').show();
            $('#btnhidelevel2').hide();
            $('#divlevel2').slideDown(1000);
        } else {
            $('#btnshowlevel2').show();
            $('#btnhidelevel2').hide();
            $('#divlevel2').hide();
            $('#level_2_desc_en').val('');
            $('#surveynotype_id1').val('');
        }
        $("#btnshowlevel2").click(function () {
//            alert(level1);
            if ($('#village_id').val() != '' && $('#level_1_desc_en').val() != '' && $('#list_1_desc_en').val() != '') {
                $('#btnhidelevel2').show();
                $('#btnshowlevel2').hide();
                $('#divlevel2').slideDown(1000);
                return false;
            } else {
                alert('Please Select Level 1 List description!!!');
                return false;
            }
        });

        $("#btnhidelevel2").click(function () {
            $('#btnshowlevel2').show();
            $('#btnhidelevel2').hide();
            $('#divlevel2').hide();
            $('#level_2_desc_en').val('');
            $('#surveynotype_id1').val('');
            $('#hfupdateflag').val('');
            return false;
        });

        $("#list_1_desc_en,#level_1_from_range,#level_1_to_range,#btnaddlevellist1,#btnupdatelevel1list").click(function () {
            if ($('#level_2_desc_en').val() !== '' && $('#list_2_desc_en').val() !== '' && $('#level_3_desc_en').val() !== '' && $('#list_3_desc_en').val() !== '' && $('#level_4_desc_en').val() !== '' && $('#list_4_desc_en').val() !== '') {
                $('#level_2_desc_en').val('');
                $('#surveynotype_id1').val('');
                $('#btnaddlevel2').html('Add');
                $('#btnshowlevel2').show();
                $('#btnhidelevel2').hide();
                $('#divlevel2').hide();

                $('#btnshowlevel2list').show();
                $('#btnhidelevel2list').hide();
                $('#divlevellist2').hide();
                $('#list_2_desc_en').val('');
                $('#level_2_from_range').val('');

                $('#btnshowlevel3').show();
                $('#btnhidelevel3').hide();
                $('#divlevel3').hide();
                $('#level_3_desc_en').val('');
                $('#surveynotype_id2').val('');

                $('#list_3_desc_en').val('');
                $('#level_3_from_range').val('');
                $('#level_3_to_range').val('');
                $('#btnshowlevel3list').show();
                $('#btnhidelevel3list').hide();
                $('#divlevellist3').hide();

                $('#btnshowlevel4').show();
                $('#btnhidelevel4').hide();
                $('#divlevel4').hide();
                $('#level_4_desc_en').val('');
                $('#surveynotype_id3').val('');

                $('#btnshowlevel4list').show();
                $('#btnhidelevel4list').hide();
                $('#divlevellist4').hide();
                $('#list_4_desc_en').val('');
                $('#level_4_from_range').val('');
                $('#level_4_to_range').val('');

            } else if ($('#level_2_desc_en').val() !== '' && $('#list_2_desc_en').val() !== '' && $('#level_3_desc_en').val() !== '' && $('#list_3_desc_en').val() !== '' && $('#level_4_desc_en').val() !== '') {
                $('#level_2_desc_en').val('');
                $('#surveynotype_id1').val('');
                $('#btnaddlevel2').html('Add');
                $('#btnshowlevel2').show();
                $('#btnhidelevel2').hide();
                $('#divlevel2').hide();

                $('#btnshowlevel2list').show();
                $('#btnhidelevel2list').hide();
                $('#divlevellist2').hide();
                $('#list_2_desc_en').val('');
                $('#level_2_from_range').val('');

                $('#btnshowlevel3').show();
                $('#btnhidelevel3').hide();
                $('#divlevel3').hide();
                $('#level_3_desc_en').val('');
                $('#surveynotype_id2').val('');

                $('#list_3_desc_en').val('');
                $('#level_3_from_range').val('');
                $('#level_3_to_range').val('');
                $('#btnshowlevel3list').show();
                $('#btnhidelevel3list').hide();
                $('#divlevellist3').hide();

                $('#btnshowlevel4').show();
                $('#btnhidelevel4').hide();
                $('#divlevel4').hide();
                $('#level_4_desc_en').val('');
                $('#surveynotype_id3').val('');

            } else if ($('#level_2_desc_en').val() !== '' && $('#list_2_desc_en').val() !== '' && $('#level_3_desc_en').val() !== '' && $('#list_3_desc_en').val() !== '') {
                $('#level_2_desc_en').val('');
                $('#surveynotype_id1').val('');
                $('#btnaddlevel2').html('Add');
                $('#btnshowlevel2').show();
                $('#btnhidelevel2').hide();
                $('#divlevel2').hide();

                $('#btnshowlevel2list').show();
                $('#btnhidelevel2list').hide();
                $('#divlevellist2').hide();
                $('#list_2_desc_en').val('');
                $('#level_2_from_range').val('');

                $('#btnshowlevel3').show();
                $('#btnhidelevel3').hide();
                $('#divlevel3').hide();
                $('#level_3_desc_en').val('');
                $('#surveynotype_id2').val('');

                $('#list_3_desc_en').val('');
                $('#level_3_from_range').val('');
                $('#level_3_to_range').val('');
                $('#btnshowlevel3list').show();
                $('#btnhidelevel3list').hide();
                $('#divlevellist3').hide();

            } else if ($('#level_2_desc_en').val() !== '' && $('#list_2_desc_en').val() !== '' && $('#level_3_desc_en').val() !== '') {
                $('#level_2_desc_en').val('');
                $('#surveynotype_id1').val('');
                $('#btnaddlevel2').html('Add');
                $('#btnshowlevel2').show();
                $('#btnhidelevel2').hide();
                $('#divlevel2').hide();

                $('#btnshowlevel2list').show();
                $('#btnhidelevel2list').hide();
                $('#divlevellist2').hide();
                $('#list_2_desc_en').val('');
                $('#level_2_from_range').val('');

                $('#btnshowlevel3').show();
                $('#btnhidelevel3').hide();
                $('#divlevel3').hide();
                $('#level_3_desc_en').val('');
                $('#surveynotype_id2').val('');
            } else if ($('#level_2_desc_en').val() !== '' && $('#list_2_desc_en').val() !== '') {
                $('#level_2_desc_en').val('');
                $('#surveynotype_id1').val('');
                $('#btnaddlevel2').html('Add');
                $('#btnshowlevel2').show();
                $('#btnhidelevel2').hide();
                $('#divlevel2').hide();

                $('#btnshowlevel2list').show();
                $('#btnhidelevel2list').hide();
                $('#divlevellist2').hide();
                $('#list_2_desc_en').val('');
                $('#level_2_from_range').val('');
            } else if ($('#level_2_desc_en').val() !== '') {
                $('#level_2_desc_en').val('');
                $('#surveynotype_id1').val('');
                $('#btnaddlevel2').html('Add');
                $('#btnshowlevel2').show();
                $('#btnhidelevel2').hide();
                $('#divlevel2').hide();
            }
        });

// ----------------Level 2 List hide show-------------------------------------------------------------------------------------------
        $('#btnhidelevel2list').hide();
        $('#divlevellist2').hide();

        if ($('#level_1_desc_en').val() != '' && $('#list_1_desc_en').val() != '' && $('#level_2_desc_en').val() != '' && $('#list_2_desc_en').val() != '') {
            $('#btnshowlevel2list').show();
            $('#btnhidelevel2list').hide();
            $('#divlevellist2').slideDown(1000);
        } else {
            $('#btnshowlevel2list').show();
            $('#btnhidelevel2list').hide();
            $('#divlevellist2').hide();
            $('#list_2_desc_en').val('');
            $('#level_2_from_range').val('');
            $('#level_2_to_range').val('');
        }
        $("#btnshowlevel2list").click(function () {
//            alert(level1);
            if ($('#village_id').val() != '' && $('#level_1_desc_en').val() != '' && $('#list_1_desc_en').val() != '' && $('#level_2_desc_en').val() != '') {
                $('#btnhidelevel2list').show();
                $('#btnshowlevel2list').hide();
                $('#divlevellist2').slideDown(1000);
                return false;
            } else {
                alert('Please Select Level 2 description!!!');
                return false;
            }
        });

        $("#btnhidelevel2list").click(function () {
            $('#btnshowlevel2list').show();
            $('#btnhidelevel2list').hide();
            $('#divlevellist2').hide();
            $('#list_2_desc_en').val('');
            $('#level_2_from_range').val('');
            $('#level_2_to_range').val('');
            $('#hfupdateflag').val('');
            return false;
        });

        $("#level_2_desc_en,#surveynotype_id1,#btnaddlevel2,#btnupdatelevel2").click(function () {
            if ($('#list_2_desc_en').val() !== '' && $('#level_3_desc_en').val() !== '' && $('#list_3_desc_en').val() !== '' && $('#level_4_desc_en').val() !== '' && $('#list_4_desc_en').val() !== '') {
                $('#list_2_desc_en').val('');
                $('#level_2_from_range').val('');
                $('#level_2_to_range').val('');
                $('#btnaddlevellist2').html('Add');
                $('#btnshowlevel2list').show();
                $('#btnhidelevel2list').hide();
                $('#divlevellist2').hide();

                $('#btnshowlevel3').show();
                $('#btnhidelevel3').hide();
                $('#divlevel3').hide();
                $('#level_3_desc_en').val('');
                $('#surveynotype_id2').val('');

                $('#list_3_desc_en').val('');
                $('#level_3_from_range').val('');
                $('#level_3_to_range').val('');
                $('#btnshowlevel3list').show();
                $('#btnhidelevel3list').hide();
                $('#divlevellist3').hide();

                $('#btnshowlevel4').show();
                $('#btnhidelevel4').hide();
                $('#divlevel4').hide();
                $('#level_4_desc_en').val('');
                $('#surveynotype_id3').val('');

                $('#btnshowlevel4list').show();
                $('#btnhidelevel4list').hide();
                $('#divlevellist4').hide();
                $('#list_4_desc_en').val('');
                $('#level_4_from_range').val('');
                $('#level_4_to_range').val('');

            } else if ($('#list_2_desc_en').val() !== '' && $('#level_3_desc_en').val() !== '' && $('#list_3_desc_en').val() !== '' && $('#level_4_desc_en').val() !== '') {
                $('#list_2_desc_en').val('');
                $('#level_2_from_range').val('');
                $('#level_2_to_range').val('');
                $('#btnaddlevellist2').html('Add');
                $('#btnshowlevel2list').show();
                $('#btnhidelevel2list').hide();
                $('#divlevellist2').hide();

                $('#btnshowlevel3').show();
                $('#btnhidelevel3').hide();
                $('#divlevel3').hide();
                $('#level_3_desc_en').val('');
                $('#surveynotype_id2').val('');

                $('#list_3_desc_en').val('');
                $('#level_3_from_range').val('');
                $('#level_3_to_range').val('');
                $('#btnshowlevel3list').show();
                $('#btnhidelevel3list').hide();
                $('#divlevellist3').hide();

                $('#btnshowlevel4').show();
                $('#btnhidelevel4').hide();
                $('#divlevel4').hide();
                $('#level_4_desc_en').val('');
                $('#surveynotype_id3').val('');

            } else if ($('#list_2_desc_en').val() !== '' && $('#level_3_desc_en').val() !== '' && $('#list_3_desc_en').val() !== '') {
                $('#list_2_desc_en').val('');
                $('#level_2_from_range').val('');
                $('#level_2_to_range').val('');
                $('#btnaddlevellist2').html('Add');
                $('#btnshowlevel2list').show();
                $('#btnhidelevel2list').hide();
                $('#divlevellist2').hide();

                $('#btnshowlevel3').show();
                $('#btnhidelevel3').hide();
                $('#divlevel3').hide();
                $('#level_3_desc_en').val('');
                $('#surveynotype_id2').val('');

                $('#list_3_desc_en').val('');
                $('#level_3_from_range').val('');
                $('#level_3_to_range').val('');
                $('#btnshowlevel3list').show();
                $('#btnhidelevel3list').hide();
                $('#divlevellist3').hide();

            } else if ($('#list_2_desc_en').val() !== '' && $('#level_3_desc_en').val() !== '') {
                $('#list_2_desc_en').val('');
                $('#level_2_from_range').val('');
                $('#level_2_to_range').val('');
                $('#btnaddlevellist2').html('Add');
                $('#btnshowlevel2list').show();
                $('#btnhidelevel2list').hide();
                $('#divlevellist2').hide();

                $('#btnshowlevel3').show();
                $('#btnhidelevel3').hide();
                $('#divlevel3').hide();
                $('#level_3_desc_en').val('');
                $('#surveynotype_id2').val('');
            } else if ($('#list_2_desc_en').val() !== '') {
                $('#list_2_desc_en').val('');
                $('#level_2_from_range').val('');
                $('#level_2_to_range').val('');
                $('#btnaddlevellist2').html('Add');
                $('#btnshowlevel2list').show();
                $('#btnhidelevel2list').hide();
                $('#divlevellist2').hide();
            }
        });

        // ----------------Level 3 hide show-------------------------------------------------------------------------------------------
        $('#btnhidelevel3').hide();
        $('#divlevel3').hide();

        if ($('#level_1_desc_en').val() != '' && $('#list_1_desc_en').val() != '' && $('#level_2_desc_en').val() != '' && $('#list_2_desc_en').val() != '' && $('#level_3_desc_en').val() != '') {
            $('#btnshowlevel3').show();
            $('#btnhidelevel3').hide();
            $('#divlevel3').slideDown(1000);
        } else {
            $('#btnshowlevel3').show();
            $('#btnhidelevel3').hide();
            $('#divlevel3').hide();
            $('#level_3_desc_en').val('');
            $('#surveynotype_id2').val('');
        }
        $("#btnshowlevel3").click(function () {
//            alert(level1);
            if ($('#village_id').val() != '' && $('#level_1_desc_en').val() != '' && $('#list_1_desc_en').val() != '' && $('#level_2_desc_en').val() != '' && $('#list_2_desc_en').val() != '') {
                $('#btnhidelevel3').show();
                $('#btnshowlevel3').hide();
                $('#divlevel3').slideDown(1000);
                return false;
            } else {
                alert('Please Select Level 2 List description!!!');
                return false;
            }
        });

        $("#btnhidelevel2list").click(function () {
            $('#btnshowlevel3').show();
            $('#btnhidelevel3').hide();
            $('#divlevel3').hide();
            $('#level_3_desc_en').val('');
            $('#surveynotype_id2').val('');
            $('#hfupdateflag').val('');
            return false;
        });

        $("#list_2_desc_en,#level_2_from_range,#level_2_to_range,#btnaddlevellist2,#btnupdatelevel2list").click(function () {
            if ($('#level_3_desc_en').val() !== '' && $('#list_3_desc_en').val() !== '' && $('#level_4_desc_en').val() !== '' && $('#list_4_desc_en').val() !== '') {
                $('#level_3_desc_en').val('');
                $('#surveynotype_id2').val('');
                $('#btnaddlevel3').html('Add');
                $('#btnshowlevel3').show();
                $('#btnhidelevel3').hide();
                $('#divlevel3').hide();

                $('#list_3_desc_en').val('');
                $('#level_3_from_range').val('');
                $('#level_3_to_range').val('');
                $('#btnshowlevel3list').show();
                $('#btnhidelevel3list').hide();
                $('#divlevellist3').hide();

                $('#btnshowlevel4').show();
                $('#btnhidelevel4').hide();
                $('#divlevel4').hide();
                $('#level_4_desc_en').val('');
                $('#surveynotype_id3').val('');

                $('#btnshowlevel4list').show();
                $('#btnhidelevel4list').hide();
                $('#divlevellist4').hide();
                $('#list_4_desc_en').val('');
                $('#level_4_from_range').val('');
                $('#level_4_to_range').val('');

            } else if ($('#level_3_desc_en').val() !== '' && $('#list_3_desc_en').val() !== '' && $('#level_4_desc_en').val() !== '') {
                $('#level_3_desc_en').val('');
                $('#surveynotype_id2').val('');
                $('#btnaddlevel3').html('Add');
                $('#btnshowlevel3').show();
                $('#btnhidelevel3').hide();
                $('#divlevel3').hide();

                $('#list_3_desc_en').val('');
                $('#level_3_from_range').val('');
                $('#level_3_to_range').val('');
                $('#btnshowlevel3list').show();
                $('#btnhidelevel3list').hide();
                $('#divlevellist3').hide();

                $('#btnshowlevel4').show();
                $('#btnhidelevel4').hide();
                $('#divlevel4').hide();
                $('#level_4_desc_en').val('');
                $('#surveynotype_id3').val('');

            } else if ($('#level_3_desc_en').val() !== '' && $('#list_3_desc_en').val() !== '' && $('#level_4_desc_en').val() !== '') {
                $('#level_3_desc_en').val('');
                $('#surveynotype_id2').val('');
                $('#btnaddlevel3').html('Add');
                $('#btnshowlevel3').show();
                $('#btnhidelevel3').hide();
                $('#divlevel3').hide();

                $('#list_3_desc_en').val('');
                $('#level_3_from_range').val('');
                $('#level_3_to_range').val('');
                $('#btnshowlevel3list').show();
                $('#btnhidelevel3list').hide();
                $('#divlevellist3').hide();

            } else if ($('#level_3_desc_en').val() !== '') {
                $('#level_3_desc_en').val('');
                $('#surveynotype_id2').val('');
                $('#btnaddlevel3').html('Add');
                $('#btnshowlevel3').show();
                $('#btnhidelevel3').hide();
                $('#divlevel3').hide();
            }
        });
// ----------------Level 3 List hide show-------------------------------------------------------------------------------------------
        $('#btnhidelevel3list').hide();
        $('#divlevellist3').hide();

        if ($('#level_1_desc_en').val() != '' && $('#list_1_desc_en').val() != '' && $('#level_2_desc_en').val() != '' && $('#list_2_desc_en').val() != '' && $('#level_3_desc_en').val() != '' && $('#list_3_desc_en').val() != '') {
            $('#btnshowlevel3list').show();
            $('#btnhidelevel3list').hide();
            $('#divlevellist3').slideDown(1000);
        } else {
            $('#btnshowlevel3list').show();
            $('#btnhidelevel3list').hide();
            $('#divlevellist3').hide();
            $('#list_3_desc_en').val('');
            $('#level_3_from_range').val('');
            $('#level_3_to_range').val('');

        }
        $("#btnshowlevel3list").click(function () {
//            alert(level1);
            if ($('#village_id').val() != '' && $('#level_1_desc_en').val() != '' && $('#list_1_desc_en').val() != '' && $('#level_2_desc_en').val() != '' && $('#list_2_desc_en').val() != '' && $('#level_3_desc_en').val() != '') {
                $('#btnhidelevel3list').show();
                $('#btnshowlevel3list').hide();
                $('#divlevellist3').slideDown(1000);
                return false;
            } else {
                alert('Please Select Level 3 description!!!');
                return false;
            }
        });

        $("#btnhidelevel3list").click(function () {
            $('#btnshowlevel3list').show();
            $('#btnhidelevel3list').hide();
            $('#divlevellist3').hide();
            $('#list_3_desc_en').val('');
            $('#level_3_from_range').val('');
            $('#level_3_to_range').val('');
            $('#hfupdateflag').val('');
            return false;
        });

        $("#level_3_desc_en,#surveynotype_id2,#btnaddlevel3,#btnupdatelevel3").click(function () {
            if ($('#list_3_desc_en').val() !== '' && $('#level_4_desc_en').val() !== '' && $('#list_4_desc_en').val() !== '') {
                $('#list_3_desc_en').val('');
                $('#level_3_from_range').val('');
                $('#level_3_to_range').val('');
                $('#btnaddlevel3list').html('Add');
                $('#btnshowlevel3list').show();
                $('#btnhidelevel3list').hide();
                $('#divlevellist3').hide();

                $('#btnshowlevel4').show();
                $('#btnhidelevel4').hide();
                $('#divlevel4').hide();
                $('#level_4_desc_en').val('');
                $('#surveynotype_id3').val('');

                $('#btnshowlevel4list').show();
                $('#btnhidelevel4list').hide();
                $('#divlevellist4').hide();
                $('#list_4_desc_en').val('');
                $('#level_4_from_range').val('');
                $('#level_4_to_range').val('');

            } else if ($('#list_3_desc_en').val() !== '' && $('#level_4_desc_en').val() !== '') {
                $('#list_3_desc_en').val('');
                $('#level_3_from_range').val('');
                $('#level_3_to_range').val('');
                $('#btnaddlevel3list').html('Add');
                $('#btnshowlevel3list').show();
                $('#btnhidelevel3list').hide();
                $('#divlevellist3').hide();

                $('#btnshowlevel4').show();
                $('#btnhidelevel4').hide();
                $('#divlevel4').hide();
                $('#level_4_desc_en').val('');
                $('#surveynotype_id3').val('');

            } else if ($('#list_3_desc_en').val() !== '') {
                $('#list_3_desc_en').val('');
                $('#level_3_from_range').val('');
                $('#level_3_to_range').val('');
                $('#btnaddlevel3list').html('Add');
                $('#btnshowlevel3list').show();
                $('#btnhidelevel3list').hide();
                $('#divlevellist3').hide();
            }
        });
// ----------------Level 4 hide show-------------------------------------------------------------------------------------------
        $('#btnhidelevel4').hide();
        $('#divlevel4').hide();

        if ($('#level_1_desc_en').val() != '' && $('#list_1_desc_en').val() != '' && $('#level_2_desc_en').val() != '' && $('#list_2_desc_en').val() != '' && $('#level_3_desc_en').val() != '' && $('#list_3_desc_en').val() != '' && $('#level_4_desc_en').val() != '') {
            $('#btnshowlevel4').show();
            $('#btnhidelevel4').hide();
            $('#divlevel4').slideDown(1000);
        } else {
            $('#btnshowlevel4').show();
            $('#btnhidelevel4').hide();
            $('#divlevel4').hide();
            $('#level_4_desc_en').val('');
            $('#surveynotype_id3').val('');

        }
        $("#btnshowlevel4").click(function () {
//            alert(level1);
            if ($('#village_id').val() != '' && $('#level_1_desc_en').val() != '' && $('#list_1_desc_en').val() != '' && $('#level_2_desc_en').val() != '' && $('#list_2_desc_en').val() != '' && $('#level_3_desc_en').val() != '' && $('#list_3_desc_en').val() != '') {
                $('#btnhidelevel4').show();
                $('#btnshowlevel4').hide();
                $('#divlevel4').slideDown(1000);
                return false;
            } else {
                alert('Please Select Level 3 List description!!!');
                return false;
            }
        });

        $("#btnhidelevel4").click(function () {
            $('#btnshowlevel4').show();
            $('#btnhidelevel4').hide();
            $('#divlevel4').hide();
            $('#level_4_desc_en').val('');
            $('#surveynotype_id3').val('');
            $('#hfupdateflag').val('');
            return false;
        });

        $("#list_3_desc_en,#level_3_from_range,#level_3_to_range,#btnaddlevellist3,#btnupdatelevel3list").click(function () {
            if ($('#level_4_desc_en').val() !== '' && $('#list_4_desc_en').val() !== '') {
                $('#level_4_desc_en').val('');
                $('#surveynotype_id3').val('');
                $('#btnaddlevel4').html('Add');
                $('#btnshowlevel4').show();
                $('#btnhidelevel4').hide();
                $('#divlevel4').hide();

                $('#btnshowlevel4list').show();
                $('#btnhidelevel4list').hide();
                $('#divlevellist4').hide();
                $('#list_4_desc_en').val('');
                $('#level_4_from_range').val('');
                $('#level_4_to_range').val('');
            } else if ($('#level_4_desc_en').val() !== '') {
                $('#level_4_desc_en').val('');
                $('#surveynotype_id3').val('');
                $('#btnaddlevel4').html('Add');
                $('#btnshowlevel4').show();
                $('#btnhidelevel4').hide();
                $('#divlevel4').hide();
            }
        });
// ----------------Level 4 List hide show-------------------------------------------------------------------------------------------
        $('#btnhidelevel4list').hide();
        $('#divlevel4list').hide();

        if ($('#level_1_desc_en').val() != '' && $('#list_1_desc_en').val() != '' && $('#level_2_desc_en').val() != '' && $('#list_2_desc_en').val() != '' && $('#level_3_desc_en').val() != '' && $('#list_3_desc_en').val() != '' && $('#level_4_desc_en').val() != '' && $('#list_4_desc_en').val() != '') {
            $('#btnshowlevel4list').show();
            $('#btnhidelevel4list').hide();
            $('#divlevellist4').slideDown(1000);
        } else {
            $('#btnshowlevel4list').show();
            $('#btnhidelevel4list').hide();
            $('#divlevellist4').hide();
            $('#list_4_desc_en').val('');
            $('#level_4_from_range').val('');
            $('#level_4_to_range').val('');

        }
        $("#btnshowlevel4list").click(function () {
//            alert(level1);
            if ($('#village_id').val() != '' && $('#level_1_desc_en').val() != '' && $('#list_1_desc_en').val() != '' && $('#level_2_desc_en').val() != '' && $('#list_2_desc_en').val() != '' && $('#level_3_desc_en').val() != '' && $('#list_3_desc_en').val() != '' && $('#level_4_desc_en').val() != '') {
                $('#btnhidelevel4list').show();
                $('#btnshowlevel4list').hide();
                $('#divlevellist4').slideDown(1000);
                return false;
            } else {
                alert('Please Select Level 4 description!!!');
                return false;
            }
        });

        $("#btnhidelevel4list").click(function () {
            $('#btnshowlevel4list').show();
            $('#btnhidelevel4list').hide();
            $('#divlevellist4').hide();
            $('#list_4_desc_en').val('');
            $('#level_4_from_range').val('');
            $('#level_4_to_range').val('');
            $('#hfupdateflag').val('');
            return false;
        });

        $("#level_4_desc_en,#surveynotype_id3,#btnaddlevel4,#btnupdatelevel4").click(function () {
            if ($('#list_4_desc_en').val() !== '') {
                $('#list_4_desc_en').val('');
                $('#level_4_from_range').val('');
                $('#level_4_to_range').val('');
                $('#btnaddlevellist4').html('Add');
                $('#btnshowlevel4list').show();
                $('#btnhidelevel4list').hide();
                $('#divlevellist4').hide();
            }
        });

    });
</script>
<script>
    $(document).ready(function () {

        $('#district_id').change(function () {
            var district = $("#district_id option:selected").val();
            var token = $("#token").val();
            $.getJSON("get_taluka_name", {district: district, token: token}, function (data)
            {
                var sc = '<option value="">--Select--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#taluka_id option").remove();
                $("#taluka_id").append(sc);
            });
        })

        $('#taluka_id').change(function () {
            var taluka = $("#taluka_id option:selected").val();
            var token = $("#token").val();
            $.getJSON("get_village_name", {taluka: taluka, token: token}, function (data)
            {
                var sc = '<option value="">--Select--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#village_id").prop("disabled", false);
                $("#village_id option").remove();
                $("#village_id").append(sc);
            });
        })

        $("#level_id").change(function () {
            document.getElementById("actiontype").value = '2';
            $('#levelname').val($("#level_id option:selected").text());
            $('#level').submit();
        });

        $('#village_id').change(function () {
//            var village = $("#village_id option:selected").val();
//            $.getJSON("getvillagedata", {village: village}, function (data)
//            {
//                for (var i = 0; i < data.length; i++) {
//                    $('#tablelevel1 tr:last').after('<tr id="' + data[i][0].id + '"><td style="text-align: center;">' + data[i][0].state_name_en + '</td><td style="text-align: center;">' + data[i][0].village_name_en + '</td> <td style="text-align: center;">' + data[i][0].level_1_desc_en + '</td> <td style="text-align: center;">' + data[i][0].surveynotype_desc_en + '</td> <td style="text-align: center;"><button id="btnupdatelevel1" name="btnupdatelevel1" class="btn btn-default " style="text-align: center;" onclick="javascript: return formupdatelevel1();"><span class="glyphicon glyphicon-pencil"></span></button></td></tr>');
//                }
//
//            });
            document.getElementById("actiontype").value = '1';
//            $("#village_id").prop("disabled", false);
            $('#levelmapping').submit();
        });

    });
</script>
<script>
    $(document).ready(function () {

        $('#btnaddlevel1').click(function () {

            $(':input').each(function () {
                $(this).val($.trim($(this).val()));
            });

            var village_id = $("#village_id option:selected").val();
            var village_name = $("#village_id option:selected").text();
            var level_1_desc_en = $('#level_1_desc_en').val();
            var surveynotype_id = $("#surveynotype_id option:selected").val();
            var surveynotype_name = $("#surveynotype_id option:selected").text();
            var state = '<?php echo $state ?>';

            if (level_1_desc_en === '') {
                alert('Please enter Level 1 description!!!');
                $('#level_1_desc_en').focus();
                return false;
            }

            var actiontype = 'S';
            if ($('#hfupdateflag').val() === 'L1U') {
                actiontype = 'U';
            }
            var id = $('#hflevel1id').val();
          
            $.post('<?php echo $this->webroot; ?>Masters/savelevel1', {village_id: village_id, level_1_desc_en: level_1_desc_en, surveynotype_id: surveynotype_id, actiontype: actiontype, id: id}, function (data)
                {
               var data=$.parseJSON(data);
               
                    if (data !== 'Record Already Exist' && data !== 'Record Not Saved' && data !== 'Record Not Updated') {
//
                    var village = "'" + village_id + "'";
                    var level1en = "'" + level_1_desc_en + "'";
                    var surveyno = "'" + surveynotype_id + "'";
                   
                    var level1id = "'" + data.level1_id+ "'";
                    var rid = "'" + data.id + "'";

                    if (actiontype === 'S') {
                        $('#tablelevel1 tr:last').after('<tr id="' + data.id + '"><td style="text-align: center;">' + state + '</td><td style="text-align: center;">' + village_name + '</td> <td style="text-align: center;">' + level_1_desc_en + '</td> <td style="text-align: center;">' + surveynotype_name + '</td><td style="text-align: center;"><button id="btnupdatelevel1" name="btnupdatelevel1" class="btn btn-default " style="text-align: center;" onclick="javascript: return formupdatelevel1(' + level1en + ',' + surveyno + ',' + village + ',' + level1id + ',' + rid + ');"><span class="glyphicon glyphicon-pencil"></span></button></td></tr>');
                        alert('Record Saved');

                    } else if (actiontype === 'U') {
                        $('#' + 'tablelevel1_' + id).fadeOut();
                        $('#tablelevel1 tr:last').after('<tr id="' + data.id + '"><td style="text-align: center;">' + state + '</td><td style="text-align: center;">' + village_name + '</td> <td style="text-align: center;">' + level_1_desc_en + '</td> <td style="text-align: center;">' + surveynotype_name + '</td><td style="text-align: center;"><button id="btnupdatelevel1" name="btnupdatelevel1" class="btn btn-default " style="text-align: center;" onclick="javascript: return formupdatelevel1(' + level1en + ',' + surveyno + ',' + village + ',' + level1id + ',' + rid + ');"><span class="glyphicon glyphicon-pencil"></span></button></td></tr>');
                        alert('Record Update');
                    }
                    $('#level_1_desc_en').val('');
                    $('#surveynotype_id').val('');
                    $('#hfupdateflag').val('');
                    $('#btnaddlevel1').html('Add');
                } else {
                    $('#level_1_desc_en').val('');
                    $('#surveynotype_id').val('');
                    $('#hfupdateflag').val('');
                    $('#btnaddlevel1').html('Add');
                    alert(data);
                }
                 
                });
                  return false;  
        });

    });

    function formupdatelevel1(level_desc, surveynotype_id, village_id, level_1_id, id) {
   
        document.getElementById("actiontype").value = '2';
        $('#level_1_desc_en').val(level_desc);
        $('#surveynotype_id').val(surveynotype_id);
        $('#village_id').val(village_id);
        $('#hflevel1id').val(id);
        $('#hflevel1code').val(level_1_id);
        $('#hfupdateflag').val('L1U');
    }
</script> 
<script>
    $(document).ready(function () {

        $('#btnaddlevellist1').click(function () {

            $(':input').each(function () {
                $(this).val($.trim($(this).val()));
            });

            var village_id = $("#village_id option:selected").val();
            var village_name = $("#village_id option:selected").text();
            var list_1_desc_en = $('#list_1_desc_en').val();
            var level_1_from_range = $("#level_1_from_range").val();
            var level_1_to_range = $("#level_1_to_range").val();
            var level1code = $('#hflevel1code').val();
            var state = '<?php echo $state ?>';

            if (list_1_desc_en === '') {
                alert('Please enter Level 1 List description!!!');
                $('#list_1_desc_en').focus();
                return false;
            }

            var actiontype = 'S';
            if ($('#hfupdateflag').val() === 'LL1U') {
                actiontype = 'U';
            }
            var id = $('#hflevellist1id').val();
            $.post('<?php echo $this->webroot; ?>Masters/savelevellist1', {village_id: village_id, list_1_desc_en: list_1_desc_en, level_1_from_range: level_1_from_range, level_1_to_range: level_1_to_range, actiontype: actiontype, id: id, level1code: level1code}, function (data)
            {
                 var data=$.parseJSON(data);
                if (data !== 'Record Already Exist' && data !== 'Record Not Saved' && data !== 'Record Not Updated') {
                    var village = "'" + village_id + "'";
                    var levellist1en = "'" + list_1_desc_en + "'";
                    var rangefrom = "'" + level_1_from_range + "'";
                    var rangeto = "'" + level_1_to_range + "'";
                    var prop_level1_list_id = "'" + data.prop_level1_list_id + "'";
                    var rid = "'" + data.id + "'";

                    if (actiontype === 'S') {
                        $('#tablelevel1list tr:last').after('<tr id="' + data.id + '"><td style="text-align: center;">' + state + '</td><td style="text-align: center;">' + village_name + '</td> <td style="text-align: center;">' + list_1_desc_en + '</td> <td style="text-align: center;">' + level_1_from_range + '</td><td style="text-align: center;">' + level_1_to_range + '</td><td style="text-align: center;"><button id="btnupdatelevel1list" name="btnupdatelevel1list" class="btn btn-default " style="text-align: center;" onclick="javascript: return formupdatelevel1list(' + levellist1en + ',' + village + ',' + prop_level1_list_id + ',' + rangefrom + ',' + rangeto + ',' + rid + ');"><span class="glyphicon glyphicon-pencil"></span></button></td></tr>');
                        alert('Record Saved');

                    } else if (actiontype === 'U') {
                        $('#' + 'tablelevel1list_' + id).fadeOut();
                        $('#tablelevel1list tr:last').after('<tr id="' + data.id + '"><td style="text-align: center;">' + state + '</td><td style="text-align: center;">' + village_name + '</td> <td style="text-align: center;">' + list_1_desc_en + '</td> <td style="text-align: center;">' + level_1_from_range + '</td><td style="text-align: center;">' + level_1_to_range + '</td><td style="text-align: center;"><button id="btnupdatelevel1list" name="btnupdatelevel1list" class="btn btn-default " style="text-align: center;" onclick="javascript: return formupdatelevel1list(' + levellist1en + ',' + village + ',' + prop_level1_list_id + ',' + rangefrom + ',' + rangeto + ',' + rid + ');"><span class="glyphicon glyphicon-pencil"></span></button></td></tr>');
                        alert('Record Update');
                    }
                    $('#list_1_desc_en').val('');
                    $('#level_1_from_range').val('');
                    $('#level_1_to_range').val('');
                    $('#hfupdateflag').val('');
                    $('#btnaddlevellist1').html('Add');
                } else {
                    $('#list_1_desc_en').val('');
                    $('#level_1_from_range').val('');
                    $('#level_1_to_range').val('');
                    $('#hfupdateflag').val('');
                    $('#btnaddlevellist1').html('Add');
                    alert(data);
                }
            });
            return false;

        });
    });

    function formupdatelevel1list(list_1_desc_en, village_id, prop_level1_list_id, level_1_from_range, level_1_to_range, id) {
        document.getElementById("actiontype").value = '3';
        $('#list_1_desc_en').val(list_1_desc_en);
        $('#village_id').val(village_id);
        $('#hflevellist1id').val(id);
        $('#hflevellist1code').val(prop_level1_list_id);
        $('#level_1_from_range').val(level_1_from_range);
        $('#level_1_to_range').val(level_1_to_range);
        $('#hfupdateflag').val('LL1U');
    }
</script>
<script>
    $(document).ready(function () {

        $('#btnaddlevel2').click(function () {

            $(':input').each(function () {
                $(this).val($.trim($(this).val()));
            });

            var village_id = $("#village_id option:selected").val();
            var village_name = $("#village_id option:selected").text();
            var level_2_desc_en = $('#level_2_desc_en').val();
            var surveynotype_id1 = $("#surveynotype_id1 option:selected").val();
            var surveyname = $("#surveynotype_id1 option:selected").text();
            var level1code = $('#hflevel1code').val();
            var levellist1code = $('#hflevellist1code').val();
            var state = '<?php echo $state ?>';

            if (level_2_desc_en === '') {
                alert('Please enter Level 2 description!!!');
                $('#level_2_desc_en').focus();
                return false;
            }

            var actiontype = 'S';
            if ($('#hfupdateflag').val() === 'L2U') {
                actiontype = 'U';
            }
            var id = $('#hflevel2id').val();
            $.post('<?php echo $this->webroot; ?>Masters/savelevel2', {village_id: village_id, level_2_desc_en: level_2_desc_en, surveynotype_id1: surveynotype_id1, actiontype: actiontype, id: id, level1code: level1code, levellist1code: levellist1code}, function (data)
            {
                var data=$.parseJSON(data);
                if (data !== 'Record Already Exist' && data !== 'Record Not Saved' && data !== 'Record Not Updated') {
                    var village = "'" + village_id + "'";
                    var level2en = "'" + level_2_desc_en + "'";
                    var survey = "'" + surveynotype_id1 + "'";
                    var level2_id = "'" + data.level2_id + "'";
                    var rid = "'" + data.id + "'";


                    if (actiontype === 'S') {
                        $('#tablelevel2 tr:last').after('<tr id="' + data.id + '"><td style="text-align: center;">' + state + '</td><td style="text-align: center;">' + village_name + '</td> <td style="text-align: center;">' + level_2_desc_en + '</td> <td style="text-align: center;">' + surveyname + '</td><td style="text-align: center;"><button id="btnupdatelevel2" name="btnupdatelevel2" class="btn btn-default " style="text-align: center;" onclick="javascript: return formupdatelevel2(' + village + ',' + level2en + ',' + survey + ',' + level2_id + ',' + rid + ');"><span class="glyphicon glyphicon-pencil"></span></button></td></tr>');
                        alert('Record Saved');

                    } else if (actiontype === 'U') {
                        $('#' + 'tablelevel2_' + id).fadeOut();
                        $('#tablelevel2 tr:last').after('<tr id="' + data.id + '"><td style="text-align: center;">' + state + '</td><td style="text-align: center;">' + village_name + '</td> <td style="text-align: center;">' + level_2_desc_en + '</td> <td style="text-align: center;">' + surveyname + '</td><td style="text-align: center;"><button id="btnupdatelevel2" name="btnupdatelevel2" class="btn btn-default " style="text-align: center;" onclick="javascript: return formupdatelevel2(' + village + ',' + level2en + ',' + survey + ',' + level2_id + ',' + rid + ');"><span class="glyphicon glyphicon-pencil"></span></button></td></tr>');
                        alert('Record Update');
                    }
                    $('#level_2_desc_en').val('');
                    $('#surveynotype_id1').val('');
                    $('#hfupdateflag').val('');
                    $('#btnaddlevel2').html('Add');
                } else {
                    $('#level_2_desc_en').val('');
                    $('#surveynotype_id1').val('');
                    $('#hfupdateflag').val('');
                    $('#btnaddlevel2').html('Add');
                    alert(data);
                }
            });
            return false;

        });
    });

    function formupdatelevel2(village_id, level_2_desc_en, surveynotype_id, level_2_id, id) {
        document.getElementById("actiontype").value = '4';
        $('#level_2_desc_en').val(level_2_desc_en);
        $('#surveynotype_id1').val(surveynotype_id);
        $('#village_id').val(village_id);
        $('#hflevel2id').val(id);
        $('#hflevel2code').val(level_2_id);
        $('#hfupdateflag').val('L2U');
    }
</script> 
<script>
    $(document).ready(function () {

        $('#btnaddlevellist2').click(function () {

            $(':input').each(function () {
                $(this).val($.trim($(this).val()));
            });

            var village_id = $("#village_id option:selected").val();
            var village_name = $("#village_id option:selected").text();
            var list_2_desc_en = $('#list_2_desc_en').val();
            var level_2_from_range = $("#level_2_from_range").val();
            var level_2_to_range = $("#level_2_to_range").val();
            var level1code = $('#hflevel1code').val();
            var levellist1code = $('#hflevellist1code').val();
            var level2code = $('#hflevel2code').val();
            var state = '<?php echo $state ?>';

            if (list_2_desc_en === '') {
                alert('Please enter Level 2 List description!!!');
                $('#list_2_desc_en').focus();
                return false;
            }

            var actiontype = 'S';
            if ($('#hfupdateflag').val() === 'LL2U') {
                actiontype = 'U';
            }
            var id = $('#hflevellist2id').val();
            $.post('<?php echo $this->webroot; ?>Masters/savelevellist2', {village_id: village_id, list_2_desc_en: list_2_desc_en, level_2_from_range: level_2_from_range, level_2_to_range: level_2_to_range, actiontype: actiontype, id: id, level1code: level1code, levellist1code: levellist1code, level2code: level2code}, function (data)
            {
                var data=$.parseJSON(data);
                if (data !== 'Record Already Exist' && data !== 'Record Not Saved' && data !== 'Record Not Updated') {
                    var village = "'" + village_id + "'";
                    var levellist12en = "'" + list_2_desc_en + "'";
                    var rangefrom = "'" + level_2_from_range + "'";
                    var rangeto = "'" + level_2_to_range + "'";
                    var prop_level2_list_id = "'" + data.prop_level2_list_id + "'";
                    var rid = "'" + data.id + "'";


                    if (actiontype === 'S') {
                        $('#tablelevel2list tr:last').after('<tr id="' + data.id + '"><td style="text-align: center;">' + state + '</td><td style="text-align: center;">' + village_name + '</td> <td style="text-align: center;">' + list_2_desc_en + '</td> <td style="text-align: center;">' + level_2_from_range + '</td><td style="text-align: center;">' + level_2_to_range + '</td><td style="text-align: center;"><button id="btnupdatelevel2list" name="btnupdatelevel2list" class="btn btn-default " style="text-align: center;" onclick="javascript: return formupdatelevel2list(' + levellist12en + ',' + village + ',' + prop_level2_list_id + ',' + rangefrom + ',' + rangeto + ',' + rid + ');"><span class="glyphicon glyphicon-pencil"></span></button></td></tr>');
                        alert('Record Saved');

                    } else if (actiontype === 'U') {
                        $('#' + 'tablelevel2list_' + id).fadeOut();
                        $('#tablelevel2list tr:last').after('<tr id="' + data.id + '"><td style="text-align: center;">' + state + '</td><td style="text-align: center;">' + village_name + '</td> <td style="text-align: center;">' + list_2_desc_en + '</td> <td style="text-align: center;">' + level_2_from_range + '</td><td style="text-align: center;">' + level_2_to_range + '</td><td style="text-align: center;"><button id="btnupdatelevel2list" name="btnupdatelevel2list" class="btn btn-default " style="text-align: center;" onclick="javascript: return formupdatelevel2list(' + levellist12en + ',' + village + ',' + prop_level2_list_id + ',' + rangefrom + ',' + rangeto + ',' + rid + ');"><span class="glyphicon glyphicon-pencil"></span></button></td></tr>');
                        alert('Record Update');
                    }
                    $('#list_2_desc_en').val('');
                    $('#level_2_from_range').val('');
                    $('#level_2_to_range').val('');
                    $('#hfupdateflag').val('');
                    $('#btnaddlevellist2').html('Add');
                } else {
                    $('#list_2_desc_en').val('');
                    $('#level_2_from_range').val('');
                    $('#level_2_to_range').val('');
                    $('#hfupdateflag').val('');
                    $('#btnaddlevellist2').html('Add');
                    alert(data);
                }
            });
            return false;

        });
    });

    function formupdatelevel2list(list_2_desc_en, village_id, prop_level2_list_id, level_2_from_range, level_2_to_range, id) {
        document.getElementById("actiontype").value = '5';
        $('#list_2_desc_en').val(list_2_desc_en);
        $('#village_id').val(village_id);
        $('#hflevellist2id').val(id);
        $('#hflevellist2code').val(prop_level2_list_id);
        $('#level_2_from_range').val(level_2_from_range);
        $('#level_2_to_range').val(level_2_to_range);
        $('#hfupdateflag').val('LL2U');
    }
</script>
<script>
    $(document).ready(function () {

        $('#btnaddlevel3').click(function () {

            $(':input').each(function () {
                $(this).val($.trim($(this).val()));
            });

            var village_id = $("#village_id option:selected").val();
            var village_name = $("#village_id option:selected").text();
            var level_3_desc_en = $('#level_3_desc_en').val();
            var surveynotype_id2 = $("#surveynotype_id2 option:selected").val();
            var surveyname = $("#surveynotype_id2 option:selected").text();
            var level1code = $('#hflevel1code').val();
            var levellist1code = $('#hflevellist1code').val();
            var level2code = $('#hflevel2code').val();
            var levellist2code = $('#hflevellist2code').val();
            var state = '<?php echo $state ?>';

            if (level_3_desc_en === '') {
                alert('Please enter Level 3 description!!!');
                $('#level_3_desc_en').focus();
                return false;
            }

            var actiontype = 'S';
            if ($('#hfupdateflag').val() === 'L3U') {
                actiontype = 'U';
            }
            var id = $('#hflevel3id').val();
            $.post('<?php echo $this->webroot; ?>Masters/savelevel3', {village_id: village_id, level_3_desc_en: level_3_desc_en, surveynotype_id2: surveynotype_id2, actiontype: actiontype, id: id, level1code: level1code, levellist1code: levellist1code, level2code: level2code, levellist2code: levellist2code}, function (data)
            {
                 var data=$.parseJSON(data);
                if (data !== 'Record Already Exist' && data !== 'Record Not Saved' && data !== 'Record Not Updated') {
                    var village = "'" + village_id + "'";
                    var level3en = "'" + level_3_desc_en + "'";
                    var survey = "'" + surveynotype_id2 + "'";
                    var level3_id = "'" + data.level3_id + "'";
                    var rid = "'" + data.id + "'";


                    if (actiontype === 'S') {
                        $('#tablelevel3 tr:last').after('<tr id="' + data.id + '"><td style="text-align: center;">' + state + '</td><td style="text-align: center;">' + village_name + '</td> <td style="text-align: center;">' + level_3_desc_en + '</td> <td style="text-align: center;">' + surveyname + '</td><td style="text-align: center;"><button id="btnupdatelevel3" name="btnupdatelevel3" class="btn btn-default " style="text-align: center;" onclick="javascript: return formupdatelevel3(' + village + ',' + level3en + ',' + survey + ',' + level3_id + ',' + rid + ');"><span class="glyphicon glyphicon-pencil"></span></button></td></tr>');
                        alert('Record Saved');

                    } else if (actiontype === 'U') {
                        $('#' + 'tablelevel3_' + id).fadeOut();
                        $('#tablelevel3 tr:last').after('<tr id="' + data.id + '"><td style="text-align: center;">' + state + '</td><td style="text-align: center;">' + village_name + '</td> <td style="text-align: center;">' + level_3_desc_en + '</td> <td style="text-align: center;">' + surveyname + '</td><td style="text-align: center;"><button id="btnupdatelevel3" name="btnupdatelevel3" class="btn btn-default " style="text-align: center;" onclick="javascript: return formupdatelevel3(' + village + ',' + level3en + ',' + survey + ',' + level3_id + ',' + rid + ');"><span class="glyphicon glyphicon-pencil"></span></button></td></tr>');
                        alert('Record Update');
                    }
                    $('#level_3_desc_en').val('');
                    $('#surveynotype_id2').val('');
                    $('#hfupdateflag').val('');
                    $('#btnaddlevel3').html('Add');
                } else {
                    $('#level_3_desc_en').val('');
                    $('#surveynotype_id2').val('');
                    $('#hfupdateflag').val('');
                    $('#btnaddlevel3').html('Add');
                    alert(data);
                }
            });
            return false;

        });
    });

    function formupdatelevel3(village_id, level_3_desc_en, surveynotype_id, level_3_id, id) {
        document.getElementById("actiontype").value = '6';
        $('#level_3_desc_en').val(level_3_desc_en);
        $('#surveynotype_id2').val(surveynotype_id);
        $('#village_id').val(village_id);
        $('#hflevel3id').val(id);
        $('#hflevel3code').val(level_3_id);
        $('#hfupdateflag').val('L3U');
    }
</script> 
<script>
    $(document).ready(function () {

        $('#btnaddlevellist3').click(function () {

            $(':input').each(function () {
                $(this).val($.trim($(this).val()));
            });

            var village_id = $("#village_id option:selected").val();
            var village_name = $("#village_id option:selected").text();
            var list_3_desc_en = $('#list_3_desc_en').val();
            var level_3_from_range = $("#level_3_from_range").val();
            var level_3_to_range = $("#level_3_to_range").val();
            var level1code = $('#hflevel1code').val();
            var levellist1code = $('#hflevellist1code').val();
            var level2code = $('#hflevel2code').val();
            var levellist2code = $('#hflevellist2code').val();
            var level3code = $('#hflevel3code').val();
            var state = '<?php echo $state ?>';

            if (list_3_desc_en === '') {
                alert('Please enter Level 3 List description!!!');
                $('#list_3_desc_en').focus();
                return false;
            }

            var actiontype = 'S';
            if ($('#hfupdateflag').val() === 'LL3U') {
                actiontype = 'U';
            }
            var id = $('#hflevellist3id').val();
            $.post('<?php echo $this->webroot; ?>Masters/savelevellist3', {village_id: village_id, list_3_desc_en: list_3_desc_en, level_3_from_range: level_3_from_range, level_3_to_range: level_3_to_range, actiontype: actiontype, id: id, level1code: level1code, levellist1code: levellist1code, level2code: level2code, levellist2code: levellist2code, level3code: level3code}, function (data)
            {
                var data=$.parseJSON(data);
                if (data !== 'Record Already Exist' && data !== 'Record Not Saved' && data !== 'Record Not Updated') {
                    var village = "'" + village_id + "'";
                    var levellist13en = "'" + list_3_desc_en + "'";
                    var rangefrom = "'" + level_3_from_range + "'";
                    var rangeto = "'" + level_3_to_range + "'";
                    var prop_level3_list_id = "'" + data.prop_level3_list_id + "'";
                    var rid = "'" + data.id + "'";


                    if (actiontype === 'S') {
                        $('#tablelevel3list tr:last').after('<tr id="' + data.id + '"><td style="text-align: center;">' + state + '</td><td style="text-align: center;">' + village_name + '</td> <td style="text-align: center;">' + list_3_desc_en + '</td> <td style="text-align: center;">' + level_3_from_range + '</td><td style="text-align: center;">' + level_3_to_range + '</td><td style="text-align: center;"><button id="btnupdatelevel3list" name="btnupdatelevel3list" class="btn btn-default " style="text-align: center;" onclick="javascript: return formupdatelevel3list(' + levellist13en + ',' + village + ',' + prop_level3_list_id + ',' + rangefrom + ',' + rangeto + ',' + rid + ');"><span class="glyphicon glyphicon-pencil"></span></button></td></tr>');
                        alert('Record Saved');

                    } else if (actiontype === 'U') {
                        $('#' + 'tablelevel3list_' + id).fadeOut();
                        $('#tablelevel3list tr:last').after('<tr id="' + data.id + '"><td style="text-align: center;">' + state + '</td><td style="text-align: center;">' + village_name + '</td> <td style="text-align: center;">' + list_3_desc_en + '</td> <td style="text-align: center;">' + level_3_from_range + '</td><td style="text-align: center;">' + level_3_to_range + '</td><td style="text-align: center;"><button id="btnupdatelevel3list" name="btnupdatelevel3list" class="btn btn-default " style="text-align: center;" onclick="javascript: return formupdatelevel3list(' + levellist13en + ',' + village + ',' + prop_level3_list_id + ',' + rangefrom + ',' + rangeto + ',' + rid + ');"><span class="glyphicon glyphicon-pencil"></span></button></td></tr>');
                        alert('Record Update');
                    }
                    $('#list_3_desc_en').val('');
                    $('#level_3_from_range').val('');
                    $('#level_3_to_range').val('');
                    $('#hfupdateflag').val('');
                    $('#btnaddlevellist3').html('Add');
                } else {
                    $('#list_3_desc_en').val('');
                    $('#level_3_from_range').val('');
                    $('#level_3_to_range').val('');
                    $('#hfupdateflag').val('');
                    $('#btnaddlevellist3').html('Add');
                    alert(data);
                }
            });
            return false;

        });
    });

    function formupdatelevel3list(list_3_desc_en, village_id, prop_leve3_list_id, level_3_from_range, level_3_to_range, id) {
        document.getElementById("actiontype").value = '7';
        $('#list_3_desc_en').val(list_3_desc_en);
        $('#village_id').val(village_id);
        $('#hflevellist3id').val(id);
        $('#hflevellist3code').val(prop_leve3_list_id);
        $('#level_3_from_range').val(level_3_from_range);
        $('#level_3_to_range').val(level_3_to_range);
        $('#hfupdateflag').val('LL3U');
    }
</script>
<script>
    $(document).ready(function () {

        $('#btnaddlevel4').click(function () {

            $(':input').each(function () {
                $(this).val($.trim($(this).val()));
            });

            var village_id = $("#village_id option:selected").val();
            var village_name = $("#village_id option:selected").text();
            var level_4_desc_en = $('#level_4_desc_en').val();
            var surveynotype_id3 = $("#surveynotype_id3 option:selected").val();
            var surveyname = $("#surveynotype_id3 option:selected").text();
            var level1code = $('#hflevel1code').val();
            var levellist1code = $('#hflevellist1code').val();
            var level2code = $('#hflevel2code').val();
            var levellist2code = $('#hflevellist2code').val();
            var level3code = $('#hflevel3code').val();
            var levellist3code = $('#hflevellist3code').val();
            var state = '<?php echo $state ?>';

            if (level_4_desc_en === '') {
                alert('Please enter Level 4 description!!!');
                $('#level_4_desc_en').focus();
                return false;
            }

            var actiontype = 'S';
            if ($('#hfupdateflag').val() === 'L4U') {
                actiontype = 'U';
            }
            var id = $('#hflevel4id').val();
            $.post('<?php echo $this->webroot; ?>Masters/savelevel4', {village_id: village_id, level_4_desc_en: level_4_desc_en, surveynotype_id3: surveynotype_id3, actiontype: actiontype, id: id, level1code: level1code, levellist1code: levellist1code, level2code: level2code, levellist2code: levellist2code, level3code: level3code, levellist3code: levellist3code}, function (data)
            {
                var data=$.parseJSON(data);
                if (data !== 'Record Already Exist' && data !== 'Record Not Saved' && data !== 'Record Not Updated') {
                    var village = "'" + village_id + "'";
                    var level4en = "'" + level_4_desc_en + "'";
                    var survey = "'" + surveynotype_id3 + "'";
                    var level4_id = "'" + data.level4_id + "'";
                    var rid = "'" + data.id + "'";


                    if (actiontype === 'S') {
                        $('#tablelevel4 tr:last').after('<tr id="' + data.id + '"><td style="text-align: center;">' + state + '</td><td style="text-align: center;">' + village_name + '</td> <td style="text-align: center;">' + level_4_desc_en + '</td> <td style="text-align: center;">' + surveyname + '</td><td style="text-align: center;"><button id="btnupdatelevel4" name="btnupdatelevel4" class="btn btn-default " style="text-align: center;" onclick="javascript: return formupdatelevel4(' + village + ',' + level4en + ',' + survey + ',' + level4_id + ',' + rid + ');"><span class="glyphicon glyphicon-pencil"></span></button></td></tr>');
                        alert('Record Saved');

                    } else if (actiontype === 'U') {
                        $('#' + 'tablelevel4_' + data.id).fadeOut();
                        $('#tablelevel4 tr:last').after('<tr id="' + data.id + '"><td style="text-align: center;">' + state + '</td><td style="text-align: center;">' + village_name + '</td> <td style="text-align: center;">' + level_4_desc_en + '</td> <td style="text-align: center;">' + surveyname + '</td><td style="text-align: center;"><button id="btnupdatelevel4" name="btnupdatelevel4" class="btn btn-default " style="text-align: center;" onclick="javascript: return formupdatelevel4(' + village + ',' + level4en + ',' + survey + ',' + level4_id + ',' + rid + ');"><span class="glyphicon glyphicon-pencil"></span></button></td></tr>');
                        alert('Record Update');
                    }
                    $('#level_4_desc_en').val('');
                    $('#surveynotype_id3').val('');
                    $('#hfupdateflag').val('');
                    $('#btnaddlevel4').html('Add');
                } else {
                    $('#level_4_desc_en').val('');
                    $('#surveynotype_id3').val('');
                    $('#hfupdateflag').val('');
                    $('#btnaddlevel4').html('Add');
                    alert(data);
                }
            });
            return false;

        });
    });

    function formupdatelevel4(village_id, level_4_desc_en, surveynotype_id, level_4_id, id) {
        document.getElementById("actiontype").value = '8';
        $('#level_4_desc_en').val(level_4_desc_en);
        $('#surveynotype_id3').val(surveynotype_id);
        $('#village_id').val(village_id);
        $('#hflevel4id').val(id);
        $('#hflevel4code').val(level_4_id);
        $('#hfupdateflag').val('L4U');
    }
</script> 
<script>
    $(document).ready(function () {

        $('#btnaddlevellist4').click(function () {

            $(':input').each(function () {
                $(this).val($.trim($(this).val()));
            });

            var village_id = $("#village_id option:selected").val();
            var village_name = $("#village_id option:selected").text();
            var list_4_desc_en = $('#list_4_desc_en').val();
            var level_4_from_range = $("#level_4_from_range").val();
            var level_4_to_range = $("#level_4_to_range").val();
            var level1code = $('#hflevel1code').val();
            var levellist1code = $('#hflevellist1code').val();
            var level2code = $('#hflevel2code').val();
            var levellist2code = $('#hflevellist2code').val();
            var level3code = $('#hflevel3code').val();
            var levellist3code = $('#hflevellist3code').val();
            var level4code = $('#hflevel4code').val();
            var state = '<?php echo $state ?>';

            if (list_4_desc_en === '') {
                alert('Please enter Level 4 List description!!!');
                $('#list_4_desc_en').focus();
                return false;
            }

            var actiontype = 'S';
            if ($('#hfupdateflag').val() === 'LL4U') {
                actiontype = 'U';
            }
            var id = $('#hflevellist4id').val();
            $.post('<?php echo $this->webroot; ?>Masters/savelevellist4', {village_id: village_id, list_4_desc_en: list_4_desc_en, level_4_from_range: level_4_from_range, level_4_to_range: level_4_to_range, actiontype: actiontype, id: id, level1code: level1code, levellist1code: levellist1code, level2code: level2code, levellist2code: levellist2code, level3code: level3code, levellist3code: levellist3code, level4code: level4code}, function (data)
            {
                var data=$.parseJSON(data);
                if (data !== 'Record Already Exist' && data !== 'Record Not Saved' && data !== 'Record Not Updated') {
                    var village = "'" + village_id + "'";
                    var levellist4en = "'" + list_4_desc_en + "'";
                    var rangefrom = "'" + level_4_from_range + "'";
                    var rangeto = "'" + level_4_to_range + "'";
                    var prop_level4_list_id = "'" + data.prop_level4_list_id + "'";
                    var rid = "'" + data.id + "'";


                    if (actiontype === 'S') {
                        $('#tablelevel4list tr:last').after('<tr id="' + data.id + '"><td style="text-align: center;">' + state + '</td><td style="text-align: center;">' + village_name + '</td> <td style="text-align: center;">' + list_4_desc_en + '</td> <td style="text-align: center;">' + level_4_from_range + '</td><td style="text-align: center;">' + level_4_to_range + '</td><td style="text-align: center;"><button id="btnupdatelevel4list" name="btnupdatelevel4list" class="btn btn-default " style="text-align: center;" onclick="javascript: return formupdatelevel4list(' + levellist4en + ',' + village + ',' + prop_level4_list_id + ',' + rangefrom + ',' + rangeto + ',' + rid + ');"><span class="glyphicon glyphicon-pencil"></span></button></td></tr>');
                        alert('Record Saved');

                    } else if (actiontype === 'U') {
                        $('#' + 'tablelevel4list_' + id).fadeOut();
                        $('#tablelevel4list tr:last').after('<tr id="' + data.id + '"><td style="text-align: center;">' + state + '</td><td style="text-align: center;">' + village_name + '</td> <td style="text-align: center;">' + list_4_desc_en + '</td> <td style="text-align: center;">' + level_4_from_range + '</td><td style="text-align: center;">' + level_4_to_range + '</td><td style="text-align: center;"><button id="btnupdatelevel4list" name="btnupdatelevel4list" class="btn btn-default " style="text-align: center;" onclick="javascript: return formupdatelevel4list(' + levellist4en + ',' + village + ',' + prop_level4_list_id + ',' + rangefrom + ',' + rangeto + ',' + rid + ');"><span class="glyphicon glyphicon-pencil"></span></button></td></tr>');
                        alert('Record Update');
                    }
                    $('#list_4_desc_en').val('');
                    $('#level_4_from_range').val('');
                    $('#level_4_to_range').val('');
                    $('#hfupdateflag').val('');
                    $('#btnaddlevellist3').html('Add');
                } else {
                    $('#list_4_desc_en').val('');
                    $('#level_4_from_range').val('');
                    $('#level_4_to_range').val('');
                    $('#hfupdateflag').val('');
                    $('#btnaddlevellist3').html('Add');
                    alert(data);
                }
            });
            return false;

        });
    });

    function formupdatelevel4list(list_3_desc_en, village_id, prop_level4_list_id, level_4_from_range, level_4_to_range, id) {
        document.getElementById("actiontype").value = '9';
        $('#list_4_desc_en').val(list_3_desc_en);
        $('#village_id').val(village_id);
        $('#hflevellist4id').val(id);
        $('#hflevellist4code').val(prop_level4_list_id);
        $('#level_4_from_range').val(level_4_from_range);
        $('#level_4_to_range').val(level_4_to_range);
        $('#hfupdateflag').val('LL4U');
    }
</script>
<?php echo $this->Form->create('levelmapping', array('id' => 'levelmapping')); ?>


<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <!--<div class="panel-heading" style="text-align: center;"><big><b>Level</b></big></div>-->

            <div class="panel-heading" style="text-align: center;"><b>Administrative Blocks</b></div>
            <div class="panel-body">
                <div class="row">
                    <label for="district_id" class="control-label col-sm-1"><?php echo __('lblDistrict'); ?></label>
                    <div class="col-sm-3" ><?php echo $this->Form->input('district_id', array('label' => false, 'id' => 'district_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $District))); ?></div>
                    <label for="taluka_id" class="control-label col-sm-1"><?php echo __('lbladmtaluka'); ?></label>
                    <div class="col-sm-3" ><?php echo $this->Form->input('taluka_id', array('label' => false, 'id' => 'taluka_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $taluka))); ?></div>
                    <label for="village_id" class="control-label col-sm-1"><?php echo __('lblVillage'); ?><span style="color: #ff0000">*</span></label>
                    <div class="col-sm-3" ><?php echo $this->Form->input('village_id', array('options' => array($village), 'empty' => '--select--', 'id' => 'village_id', 'class' => 'form-control input-sm', 'label' => false)); ?></div>
                </div>
            </div>


            <div class="row" id="div1">
                <div class="col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading" >
                            <table style="width: 100%"><tr><td><b>Level 1 Block</b></td><td style="text-align: right"><button id="btnshowlevel1"  class="btn btn-default " style="text-align: center;" ><span class="glyphicon glyphicon-plus"></span></button> <button id="btnhidelevel1" class="btn btn-default " style="text-align: center;" ><span class="glyphicon glyphicon-minus"></span></button> </td></tr></table>
                        </div>
                        <div class="row" id="divlevel1">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="level_1_desc_en" class="col-sm-2 control-label"><?php echo __('lblLevel1'); ?><span style="color: #ff0000">*</span></label>    
                                            <div class="col-sm-3" ><?php echo $this->Form->input('level_1_desc_en', array('label' => false, 'id' => 'level_1_desc_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?></div>
                                            <label for="surveynotype_id" class="col-sm-2 control-label">Survey/Door No Details<span style="color: #ff0000">*</span></label>    
                                            <div class="col-sm-3" ><?php echo $this->Form->input('surveynotype_id', array('options' => array($surveyno), 'empty' => '--select--', 'id' => 'surveynotype_id', 'class' => 'form-control input-sm', 'label' => false)); ?></div>
                                            <div class="col-sm-2 tdselect">
                                                <button id="btnaddlevel1" name="btnaddlevel1" class="btn btn-info " style="text-align: center;">
                                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;<?php echo __('lblbtnAdd'); ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <table id="tablelevel1" class="table table-striped table-bordered table-hover">  
                                        <thead style="background-color: rgb(204, 255, 229);">  
                                            <tr>  
                                                <td style="text-align: center;width: 10%"><b><?php echo __('lbladmstate'); ?></b></td>
                                                <td style="text-align: center; width: 8%"><b>Village</b></td>
                                                <td style="text-align: center;"><b><?php echo __('lblLevel1'); ?>&nbsp;<?php echo __('lbllevelname'); ?></b></td>
                                                <td style="text-align: center; width: 8%"><b>Survey/Door Details</b></td>
                                                <td style="text-align: center; width: 8%"><b><?php echo __('lblaction'); ?></b></td>
                                            </tr>  
                                        </thead>
                                        <tbody>
                                            <?php for ($i = 0; $i < count($level1record); $i++) { ?>
                                                <tr id="tablelevel1_<?php echo $level1record[$i][0]['id']; ?>">
                                                    <td style="text-align: center"><?php echo $state; ?></td>
                                                    <td style="text-align: center;"><?php echo $level1record[$i][0]['village_name_' . $laug]; ?></td>
                                                    <td style="text-align: center;"><?php echo $level1record[$i][0]['level_1_desc_' . $laug]; ?></td>
                                                    <td style="text-align: center;"><?php echo $level1record[$i][0]['surveynotype_desc_' . $laug]; ?></td>
                                                    <td style="text-align: center;">
                                                        <button id="btnupdatelevel1" name="btnupdatelevel1" class="btn btn-default " style="text-align: center;" 
                                                                onclick="javascript: return formupdatelevel1(
                                                                                    ('<?php echo $level1record[$i][0]['level_1_desc_' . $laug]; ?>'),
                                                                                    ('<?php echo $level1record[$i][0]['surveynotype_id']; ?>'),
                                                                                    ('<?php echo $level1record[$i][0]['village_id']; ?>'),
                                                                                    ('<?php echo $level1record[$i][0]['level_1_id']; ?>'),
                                                                                    ('<?php echo $level1record[$i][0]['id']; ?>'));">
                                                            <span class="glyphicon glyphicon-pencil"></span></button>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" id="div2">
                <div class="col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading" >
                            <table style="width: 100%"><tr><td><b>Level 1 List Block</b></td><td style="text-align: right"><button id="btnshowlevel1list"  class="btn btn-default " style="text-align: center;" ><span class="glyphicon glyphicon-plus"></span></button> <button id="btnhidelevel1list" class="btn btn-default " style="text-align: center;" ><span class="glyphicon glyphicon-minus"></span></button> </td></tr></table>
                        </div>
                        <div id="divlevellist1">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="usage_sub_catg_desc_en" class="col-sm-2 control-label"><?php echo __('lblLevel1list'); ?><span style="color: #ff0000">*</span></label>    
                                            <div class="col-sm-3" ><?php echo $this->Form->input('list_1_desc_en', array('label' => false, 'id' => 'list_1_desc_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?></div>
                                            <label for="usage_sub_catg_desc_en" class="col-sm-2 control-label"><?php echo __('lblrangefrom'); ?><span style="color: #ff0000">*</span></label>   
                                            <div class="col-sm-1" ><?php echo $this->Form->input('level_1_from_range', array('label' => false, 'id' => 'level_1_from_range', 'class' => 'form-control input-sm', 'type' => 'text')) ?></div>
                                            <label for="usage_sub_catg_desc_en" class="col-sm-2 control-label"><?php echo __('lblrangeto'); ?><span style="color: #ff0000">*</span></label>    
                                            <div class="col-sm-1" ><?php echo $this->Form->input('level_1_to_range', array('label' => false, 'id' => 'level_1_to_range', 'class' => 'form-control input-sm', 'type' => 'text')) ?></div>
                                            <div class="col-sm-1 tdselect">
                                                <button id="btnaddlevellist1" name="btnaddlevellist1" class="btn btn-info " style="text-align: center;" >
                                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;<?php echo __('lblbtnAdd'); ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div><br><br>

<!--                                <div class="col-sm-12">
                                    <div class="form-group">-->
                                        <table id="tablelevel1list" class="table table-striped table-bordered table-hover">  
                                            <thead style="background-color: rgb(204, 255, 229);">  
                                                <tr>  
                                                    <td style="text-align: center;font-weight:bold;"><?php echo __('lbladmstate'); ?></td>
                                                    <td style="text-align: center; width: 8%"><b>Village</b></td>
                                                    <td style="text-align: center;font-weight:bold;"><?php echo __('lblLevel1list'); ?>&nbsp;<?php echo __('lbllevelname'); ?></td>
                                                    <td style="text-align: center;font-weight:bold;"><?php echo __('lblrangefrom'); ?></td>
                                                    <td style="text-align: center;font-weight:bold;"><?php echo __('lblrangeto'); ?></td>
                                                    <td style="text-align: center; width: 8%; font-weight:bold;"><?php echo __('lblaction'); ?></td>
                                                </tr>  
                                            </thead>
                                            <tbody>
                                                <?php for ($i = 0; $i < count($levellist1record); $i++) { ?>
                                                    <tr id="tablelevel1list_<?php echo $levellist1record[$i][0]['id']; ?>">
                                                        <td style="text-align: center"><?php echo $state; ?></td>
                                                        <td style="text-align: center;"><?php echo $levellist1record[$i][0]['village_name_' . $laug]; ?></td>
                                                        <td style="text-align: center;"><?php echo $levellist1record[$i][0]['list_1_desc_' . $laug]; ?></td>
                                                        <td style="text-align: center;"><?php echo $levellist1record[$i][0]['level_1_from_range']; ?></td>
                                                        <td style="text-align: center;"><?php echo $levellist1record[$i][0]['level_1_to_range']; ?></td>
                                                        <td style="text-align: center;">
                                                            <button id="btnupdatelevel1list" name="btnupdatelevel1list" class="btn btn-default " style="text-align: center;" 
                                                                    onclick="javascript: return formupdatelevel1list(
                                                                                    ('<?php echo $levellist1record[$i][0]['list_1_desc_' . $laug]; ?>'),
                                                                                    ('<?php echo $levellist1record[$i][0]['village_id']; ?>'),
                                                                                    ('<?php echo $levellist1record[$i][0]['prop_level1_list_id']; ?>'),
                                                                                    ('<?php echo $levellist1record[$i][0]['level_1_from_range']; ?>'),
                                                                                    ('<?php echo $levellist1record[$i][0]['level_1_to_range']; ?>'),
                                                                                    ('<?php echo $levellist1record[$i][0]['id']; ?>'));">
                                                                <span class="glyphicon glyphicon-pencil"></span></button>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table> 
<!--                                    </div>
                                </div>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" id="div3">
                <div class="col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading" >
                            <table style="width: 100%"><tr><td><b>Level 2 Block</b></td><td style="text-align: right"><button id="btnshowlevel2"  class="btn btn-default " style="text-align: center;" ><span class="glyphicon glyphicon-plus"></span></button> <button id="btnhidelevel2" class="btn btn-default " style="text-align: center;" ><span class="glyphicon glyphicon-minus"></span></button> </td></tr></table>
                        </div>
                        <div id="divlevel2">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="usage_sub_catg_desc_en" class="col-sm-2 control-label"><?php echo __('lblLevel2'); ?><span style="color: #ff0000">*</span></label>    
                                            <div class="col-sm-3" ><?php echo $this->Form->input('level_2_desc_en', array('label' => false, 'id' => 'level_2_desc_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?></div>
                                            <label for="surveynotype_id1" class="col-sm-2 control-label">Survey/Door No Details<span style="color: #ff0000">*</span></label>    
                                            <div class="col-sm-3" ><?php echo $this->Form->input('surveynotype_id1', array('options' => array($surveyno), 'empty' => '--select--', 'id' => 'surveynotype_id1', 'class' => 'form-control input-sm', 'label' => false)); ?></div>
                                            <div class="col-sm-2 tdselect">
                                                <button id="btnaddlevel2" name="btnaddlevel2" class="btn btn-info " style="text-align: center;">
                                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;<?php echo __('lblbtnAdd'); ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            
<!--                            <div class="col-sm-12">
                                <div class="form-group">-->
                                    <table id="tablelevel2" class="table table-striped table-bordered table-hover">  
                                        <thead style="background-color: rgb(204, 255, 229);">  
                                            <tr>  
                                                <td style="text-align: center; font-weight: bold; width: 10%"><?php echo __('lbladmstate'); ?></td>
                                                <td style="text-align: center; width: 8%"><b>Village</b></td>
                                                <td style="text-align: center; font-weight: bold"><?php echo __('lblLevel2'); ?>&nbsp;<?php echo __('lbllevelname'); ?></td>
                                                <td style="text-align: center; width: 8%"><b>Survey/Door Details</b></td>
                                                <td style="text-align: center; font-weight: bold; width: 8%"><?php echo __('lblaction'); ?></td>
                                            </tr>  
                                        </thead>
                                        <tbody>
                                            <?php for ($i = 0; $i < count($level2record); $i++) { ?>
                                                <tr id="tablelevel2_<?php echo $level2record[$i][0]['id']; ?>">
                                                    <td style="text-align: center"><?php echo $state; ?></td>
                                                    <td style="text-align: center;"><?php echo $level2record[$i][0]['village_name_' . $laug]; ?></td>
                                                    <td style="text-align: center;"><?php echo $level2record[$i][0]['level_2_desc_' . $laug]; ?></td>
                                                    <td style="text-align: center;"><?php echo $level2record[$i][0]['surveynotype_desc_' . $laug]; ?></td>
                                                    <td style="text-align: center;">
                                                        <button id="btnupdatelevel2" name="btnupdatelevel2" class="btn btn-default " style="text-align: center;" 
                                                                onclick="javascript: return formupdatelevel2(
                                                                                    ('<?php echo $level2record[$i][0]['village_id']; ?>'),
                                                                                    ('<?php echo $level2record[$i][0]['level_2_desc_' . $laug]; ?>'),
                                                                                    ('<?php echo $level2record[$i][0]['surveynotype_id']; ?>'),
                                                                                    ('<?php echo $level2record[$i][0]['level_2_id']; ?>'),
                                                                                    ('<?php echo $level2record[$i][0]['id']; ?>'));">
                                                            <span class="glyphicon glyphicon-pencil"></span></button>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table> 
<!--                                </div>
                            </div>-->
                                </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" id="div4">
                <div class="col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading" >
                            <table style="width: 100%"><tr><td><big><b>Level 2 List Block</b></big></td><td style="text-align: right"><button id="btnshowlevel2list"  class="btn btn-default " style="text-align: center;" ><span class="glyphicon glyphicon-plus"></span></button> <button id="btnhidelevel2list" class="btn btn-default " style="text-align: center;" ><span class="glyphicon glyphicon-minus"></span></button> </td></tr></table>
                        </div>
                        <div id="divlevellist2">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="level_2_id" class="col-sm-2 control-label"><?php echo __('lblLevel2list'); ?><span style="color: #ff0000">*</span></label>    
                                            <div class="col-sm-2" ><?php echo $this->Form->input('list_2_desc_en', array('label' => false, 'id' => 'list_2_desc_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?></div>
                                            <label for="usage_sub_catg_desc_en" class="col-sm-2 control-label"><?php echo __('lblrangefrom'); ?> <span style="color: #ff0000">*</span></label>    
                                            <div class="col-sm-1" ><?php echo $this->Form->input('level_2_from_range', array('label' => false, 'id' => 'level_2_from_range', 'class' => 'form-control input-sm', 'type' => 'text')) ?></div>
                                            <label for="usage_sub_catg_desc_en" class="col-sm-2 control-label"><?php echo __('lblrangeto'); ?> <span style="color: #ff0000">*</span></label>    
                                            <div class="col-sm-1" ><?php echo $this->Form->input('level_2_to_range', array('label' => false, 'id' => 'level_2_to_range', 'class' => 'form-control input-sm', 'type' => 'text')) ?></div>
                                            <div class="col-sm-2 tdselect">
                                                <button id="btnaddlevellist2" name="btnaddlevellist2" class="btn btn-info " style="text-align: center;" ><span class="glyphicon glyphicon-plus"></span>&nbsp;<?php echo __('lblbtnAdd'); ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                           

<!--                            <div class="col-sm-12">
                                <div class="form-group">-->
                                    <table id="tablelevel2list" class="table table-striped table-bordered" cellspacing="0" width="100%">  
                                        <thead style="background-color: rgb(243, 214, 158);">  
                                            <tr>  
                                                <td style="text-align: center; font-weight:bold; width: 10%"><?php echo __('lbladmstate'); ?></td>
                                                <td style="text-align: center; width: 8%"><b>Village</b></td>
                                                <td style="text-align: center; font-weight:bold;"><?php echo __('lblLevel2list'); ?>&nbsp;<?php echo __('lbllevelname'); ?></td>
                                                <td style="text-align: center;font-weight:bold;"><?php echo __('lblrangefrom'); ?></td>
                                                <td style="text-align: center;font-weight:bold;"><?php echo __('lblrangeto'); ?></td>
                                                <td style="text-align: center; font-weight:bold; width: 8%"><?php echo __('lblaction'); ?></td>
                                            </tr>  
                                        </thead>
                                        <tbody>
                                            <?php for ($i = 0; $i < count($levellist2record); $i++) { ?>
                                                <tr id="tablelevel2list_<?php echo $levellist2record[$i][0]['id']; ?>">
                                                    <td style="text-align: center"><?php echo $state; ?></td>
                                                    <td style="text-align: center;"><?php echo $levellist2record[$i][0]['village_name_' . $laug]; ?></td>
                                                    <td style="text-align: center;"><?php echo $levellist2record[$i][0]['list_2_desc_' . $laug]; ?></td>
                                                    <td style="text-align: center;"><?php echo $levellist2record[$i][0]['level_2_from_range']; ?></td>
                                                    <td style="text-align: center;"><?php echo $levellist2record[$i][0]['level_2_to_range']; ?></td>
                                                    <td style="text-align: center;">
                                                        <button id="btnupdatelevel2list" name="btnupdatelevel2list" class="btn btn-default " style="text-align: center;" 
                                                                onclick="javascript: return formupdatelevel2list(
                                                                                    ('<?php echo $levellist2record[$i][0]['list_2_desc_' . $laug]; ?>'),
                                                                                    ('<?php echo $levellist2record[$i][0]['village_id']; ?>'),
                                                                                    ('<?php echo $levellist2record[$i][0]['prop_level2_list_id']; ?>'),
                                                                                    ('<?php echo $levellist2record[$i][0]['level_2_from_range']; ?>'),
                                                                                    ('<?php echo $levellist2record[$i][0]['level_2_to_range']; ?>'),
                                                                                    ('<?php echo $levellist2record[$i][0]['id']; ?>'));">
                                                            <span class="glyphicon glyphicon-pencil"></span></button>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table> 
<!--                                </div>
                            </div>-->
                                 </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" id="div5">
                <div class="col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading" >
                            <table style="width: 100%"><tr><td><big><b>Level 3 Block</b></big></td><td style="text-align: right"><button id="btnshowlevel3"  class="btn btn-default " style="text-align: center;" ><span class="glyphicon glyphicon-plus"></span></button> <button id="btnhidelevel3" class="btn btn-default " style="text-align: center;" ><span class="glyphicon glyphicon-minus"></span></button> </td></tr></table>
                        </div>
                        <div id="divlevel3">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="level_3_desc_en" class="col-sm-2 control-label"><?php echo __('lblLevel3'); ?><span style="color: #ff0000">*</span></label>    
                                            <div class="col-sm-2" ><?php echo $this->Form->input('level_3_desc_en', array('label' => false, 'id' => 'level_3_desc_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?></div>
                                            <div class="col-sm-1" >&nbsp;</div>
                                            <label for="surveynotype_id2" class="col-sm-2 control-label">Survey/Door No Details<span style="color: #ff0000">*</span></label>    
                                            <div class="col-sm-2" ><?php echo $this->Form->input('surveynotype_id2', array('options' => array($surveyno), 'empty' => '--select--', 'id' => 'surveynotype_id2', 'class' => 'form-control input-sm', 'label' => false)); ?></div>
                                            <div class="col-sm-2 tdselect">
                                                <button id="btnaddlevel3" name="btnaddlevel3" class="btn btn-info " style="text-align: center;">
                                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;<?php echo __('lblbtnAdd'); ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            
<!--                            <div class="row">
                                <div class="col-sm-12">-->
                                    <table id="tablelevel3" class="table table-striped table-bordered" cellspacing="0" width="100%">  
                                        <thead style="background-color: rgb(243, 214, 158);">  
                                            <tr>  
                                                <td style="text-align: center; font-weight: bold; width: 10%"><?php echo __('lbladmstate'); ?></td>
                                                <td style="text-align: center; width: 8%"><b>Village</b></td>
                                                <td style="text-align: center;font-weight: bold;"><?php echo __('lblLevel3list'); ?>&nbsp;<?php echo __('lbllevelname'); ?></td>
                                                <td style="text-align: center; width: 8%"><b>Survey/Door Details</b></td>
                                                <td style="text-align: center;font-weight: bold; width: 8%"><?php echo __('lblaction'); ?></td>
                                            </tr>  
                                        </thead>
                                        <tbody>
                                            <?php for ($i = 0; $i < count($level3record); $i++) { ?>
                                                <tr id="tablelevel3_<?php echo $level3record[$i][0]['id']; ?>">
                                                    <td style="text-align: center"><?php echo $state; ?></td>
                                                    <td style="text-align: center;"><?php echo $level3record[$i][0]['village_name_' . $laug]; ?></td>
                                                    <td style="text-align: center;"><?php echo $level3record[$i][0]['level_3_desc_' . $laug]; ?></td>
                                                    <td style="text-align: center;"><?php echo $level3record[$i][0]['surveynotype_desc_' . $laug]; ?></td>
                                                    <td style="text-align: center;">
                                                        <button id="btnupdatelevel3" name="btnupdatelevel3" class="btn btn-default " style="text-align: center;" 
                                                                onclick="javascript: return formupdatelevel3(
                                                                                    ('<?php echo $level3record[$i][0]['village_id']; ?>'),
                                                                                    ('<?php echo $level3record[$i][0]['level_3_desc_' . $laug]; ?>'),
                                                                                    ('<?php echo $level3record[$i][0]['surveynotype_id']; ?>'),
                                                                                    ('<?php echo $level3record[$i][0]['level_3_id']; ?>'),
                                                                                    ('<?php echo $level3record[$i][0]['id']; ?>'));">
                                                            <span class="glyphicon glyphicon-pencil"></span></button>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table> 
<!--                                </div>
                            </div>-->
                                </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" id="div6">
                <div class="col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading" >
                            <table style="width: 100%"><tr><td><big><b>Level 3 List Block</b></big></td><td style="text-align: right"><button id="btnshowlevel3list"  class="btn btn-default " style="text-align: center;" ><span class="glyphicon glyphicon-plus"></span></button> <button id="btnhidelevel3list" class="btn btn-default " style="text-align: center;" ><span class="glyphicon glyphicon-minus"></span></button> </td></tr></table>
                        </div>
                        <div id="divlevellist3">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="level_3_id" class="col-sm-2 control-label"><?php echo __('lblLevel3list'); ?><span style="color: #ff0000">*</span></label>    
                                            <div class="col-sm-2" ><?php echo $this->Form->input('list_3_desc_en', array('label' => false, 'id' => 'list_3_desc_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?></div>
                                            <label for="usage_sub_catg_desc_en" class="col-sm-2 control-label"><?php echo __('lblrangefrom'); ?><span style="color: #ff0000">*</span></label>    
                                            <div class="col-sm-1" ><?php echo $this->Form->input('level_3_from_range', array('label' => false, 'id' => 'level_3_from_range', 'class' => 'form-control input-sm', 'type' => 'text')) ?></div>
                                            <label for="usage_sub_catg_desc_en" class="col-sm-2 control-label"><?php echo __('lblrangeto'); ?><span style="color: #ff0000">*</span></label>    
                                            <div class="col-sm-1" ><?php echo $this->Form->input('level_3_to_range', array('label' => false, 'id' => 'level_3_to_range', 'class' => 'form-control input-sm', 'type' => 'text')) ?></div>
                                            <div class="col-sm-2 tdselect">
                                                <button id="btnaddlevellist3" name="btnaddlevellist3" class="btn btn-info " style="text-align: center;" ><span class="glyphicon glyphicon-plus"></span>&nbsp;<?php echo __('lblbtnAdd'); ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            
<!--                            <div class="row">
                                <div class="col-sm-12">-->
                                    <table id="tablelevel3list" class="table table-striped table-bordered" cellspacing="0" width="100%">  
                                        <thead style="background-color: rgb(243, 214, 158);">  
                                            <tr>  
                                                <td style="text-align: center;font-weight:bold; width: 15%"><?php echo __('lbladmstate'); ?></td>
                                                <td style="text-align: center; width: 8%"><b>Village</b></td>
                                                <td style="text-align: center;font-weight:bold;"><?php echo __('lblLevel3list'); ?>&nbsp;<?php echo __('lbllevelname'); ?></td>
                                                <td style="text-align: center;font-weight:bold;"><?php echo __('lblrangefrom'); ?></td>
                                                <td style="text-align: center;font-weight:bold;"><?php echo __('lblrangeto'); ?></td>
                                                <td style="text-align: center;font-weight:bold;width: 15%"><?php echo __('lblaction'); ?></td>
                                            </tr>   
                                        </thead>
                                        <tbody>
                                            <?php for ($i = 0; $i < count($levellist3record); $i++) { ?>
                                                <tr id="tablelevel3list_<?php echo $levellist3record[$i][0]['id']; ?>">
                                                    <td style="text-align: center"><?php echo $state; ?></td>
                                                    <td style="text-align: center;"><?php echo $levellist3record[$i][0]['village_name_' . $laug]; ?></td>
                                                    <td style="text-align: center;"><?php echo $levellist3record[$i][0]['list_3_desc_' . $laug]; ?></td>
                                                    <td style="text-align: center;"><?php echo $levellist3record[$i][0]['level_3_from_range']; ?></td>
                                                    <td style="text-align: center;"><?php echo $levellist3record[$i][0]['level_3_to_range']; ?></td>
                                                    <td style="text-align: center;">
                                                        <button id="btnupdatelevel3list" name="btnupdatelevel3list" class="btn btn-default " style="text-align: center;" 
                                                                onclick="javascript: return formupdatelevel3list(
                                                                                    ('<?php echo $levellist3record[$i][0]['list_3_desc_' . $laug]; ?>'),
                                                                                    ('<?php echo $levellist3record[$i][0]['village_id']; ?>'),
                                                                                    ('<?php echo $levellist3record[$i][0]['prop_leve3_list_id']; ?>'),
                                                                                    ('<?php echo $levellist3record[$i][0]['level_3_from_range']; ?>'),
                                                                                    ('<?php echo $levellist3record[$i][0]['level_3_to_range']; ?>'),
                                                                                    ('<?php echo $levellist3record[$i][0]['id']; ?>'));">
                                                            <span class="glyphicon glyphicon-pencil"></span></button>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table> 
<!--                                </div>
                            </div>-->
                                </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" id="div7">
                <div class="col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading" >
                            <table style="width: 100%"><tr><td><big><b>Level 4 Block</b></big></td><td style="text-align: right"><button id="btnshowlevel4"  class="btn btn-default " style="text-align: center;" ><span class="glyphicon glyphicon-plus"></span></button> <button id="btnhidelevel4" class="btn btn-default " style="text-align: center;" ><span class="glyphicon glyphicon-minus"></span></button> </td></tr></table>
                        </div>
                        <div id="divlevel4">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="level_4_desc_en" class="col-sm-2 control-label"><?php echo __('lblLevel4'); ?><span style="color: #ff0000">*</span></label>    
                                            <div class="col-sm-3" ><?php echo $this->Form->input('level_4_desc_en', array('label' => false, 'id' => 'level_4_desc_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?></div>
                                            <label for="surveynotype_id3" class="col-sm-2 control-label">Survey/Door No Details<span style="color: #ff0000">*</span></label>    
                                            <div class="col-sm-3" ><?php echo $this->Form->input('surveynotype_id3', array('options' => array($surveyno), 'empty' => '--select--', 'id' => 'surveynotype_id3', 'class' => 'form-control input-sm', 'label' => false)); ?></div>
                                            <div class="col-sm-2 tdselect">
                                                <button id="btnaddlevel4" name="btnaddlevel4" class="btn btn-info " style="text-align: center;">
                                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;<?php echo __('lblbtnAdd'); ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            
<!--                            <div class="row">
                                <div class="col-sm-12">-->
                                    <table id="tablelevel4" class="table table-striped table-bordered" cellspacing="0" width="100%">  
                                        <thead style="background-color: rgb(243, 214, 158);">  
                                            <tr>  
                                                <td style="text-align: center; font-weight: bold; width: 10%"><?php echo __('lbladmstate'); ?></td>
                                                <td style="text-align: center; width: 8%"><b>Village</b></td>
                                                <td style="text-align: center; font-weight: bold;"><?php echo __('lblLevel4'); ?>&nbsp;<?php echo __('lbllevelname'); ?></td>
                                                <td style="text-align: center; width: 8%"><b>Survey/Door Details</b></td>
                                                <td style="text-align: center; font-weight: bold; width: 8%"><?php echo __('lblaction'); ?></td>
                                            </tr>  
                                        </thead>
                                        <tbody>
                                            <?php for ($i = 0; $i < count($level4record); $i++) { ?>
                                                <tr id="tablelevel4_<?php echo $level4record[$i][0]['id']; ?>">
                                                    <td style="text-align: center"><?php echo $state; ?></td>
                                                    <td style="text-align: center;"><?php echo $level4record[$i][0]['village_name_' . $laug]; ?></td>
                                                    <td style="text-align: center;"><?php echo $level4record[$i][0]['level_4_desc_' . $laug]; ?></td>
                                                    <td style="text-align: center;"><?php echo $level4record[$i][0]['surveynotype_desc_' . $laug]; ?></td>
                                                    <td style="text-align: center;">
                                                        <button id="btnupdatelevel4" name="btnupdatelevel4" class="btn btn-default " style="text-align: center;" 
                                                                onclick="javascript: return formupdatelevel4(
                                                                                    ('<?php echo $level4record[$i][0]['village_id']; ?>'),
                                                                                    ('<?php echo $level4record[$i][0]['level_4_desc_' . $laug]; ?>'),
                                                                                    ('<?php echo $level4record[$i][0]['surveynotype_id']; ?>'),
                                                                                    ('<?php echo $level4record[$i][0]['level_4_id']; ?>'),
                                                                                    ('<?php echo $level4record[$i][0]['id']; ?>'));">
                                                            <span class="glyphicon glyphicon-pencil"></span></button>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table> 
<!--                                </div>
                            </div>-->
                                </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" id="div8">
                <div class="col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading" >
                            <table style="width: 100%"><tr><td><big><b>Level 4 List Block</b></big></td><td style="text-align: right"><button id="btnshowlevel4list"  class="btn btn-default " style="text-align: center;" ><span class="glyphicon glyphicon-plus"></span></button> <button id="btnhidelevel4list" class="btn btn-default " style="text-align: center;" ><span class="glyphicon glyphicon-minus"></span></button> </td></tr></table>
                        </div>
                        <div id="divlevellist4">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="level_4_id" class="col-sm-2 control-label"><?php echo __('lblLevel4list'); ?><span style="color: #ff0000">*</span></label>    
                                            <div class="col-sm-2" ><?php echo $this->Form->input('list_4_desc_en', array('label' => false, 'id' => 'list_4_desc_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?></div>
                                            <label for="usage_sub_catg_desc_en" class="col-sm-2 control-label"><?php echo __('lblrangefrom'); ?><span style="color: #ff0000">*</span></label>    
                                            <div class="col-sm-1" ><?php echo $this->Form->input('level_4_from_range', array('label' => false, 'id' => 'level_4_from_range', 'class' => 'form-control input-sm', 'type' => 'text')) ?></div>
                                            <label for="usage_sub_catg_desc_en" class="col-sm-2 control-label"><?php echo __('lblrangeto'); ?> <span style="color: #ff0000">*</span></label>    
                                            <div class="col-sm-1" ><?php echo $this->Form->input('level_4_to_range', array('label' => false, 'id' => 'level_4_to_range', 'class' => 'form-control input-sm', 'type' => 'text')) ?></div>
                                            <div class="col-sm-2 tdselect">
                                                <button id="btnaddlevellist4" name="btnaddlevellist4" class="btn btn-info " style="text-align: center;" ><span class="glyphicon glyphicon-plus"></span>&nbsp;<?php echo __('lblbtnAdd'); ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            
<!--                            <div class="row">
                                <div class="col-sm-12">-->
                                    <table id="tablelevel4list" class="table table-striped table-bordered" cellspacing="0" width="100%">  
                                        <thead style="background-color: rgb(243, 214, 158);">  
                                            <tr>  
                                                <td style="text-align: center;width: 10%; font-weight:bold;"><?php echo __('lbladmstate'); ?></td>
                                                <td style="text-align: center; width: 8%"><b>Village</b></td>
                                                <td style="text-align: center;font-weight:bold;"><?php echo __('lblLevel4list'); ?>&nbsp;<?php echo __('lbllevelname'); ?></td>
                                                <td style="text-align: center;font-weight:bold;"><?php echo __('lblrangefrom'); ?></td>
                                                <td style="text-align: center;font-weight:bold;"><?php echo __('lblrangeto'); ?></td>
                                                <td style="text-align: center; width: 8%; font-weight:bold;"><?php echo __('lblaction'); ?></td>
                                            </tr>   
                                        </thead>
                                        <tbody>
                                            <?php for ($i = 0; $i < count($levellist4record); $i++) { ?>
                                                <tr id="tablelevel4list_<?php echo $levellist4record[$i][0]['id']; ?>">
                                                    <td style="text-align: center"><?php echo $state; ?></td>
                                                    <td style="text-align: center;"><?php echo $levellist4record[$i][0]['village_name_' . $laug]; ?></td>
                                                    <td style="text-align: center;"><?php echo $levellist4record[$i][0]['list_4_desc_' . $laug]; ?></td>
                                                    <td style="text-align: center;"><?php echo $levellist4record[$i][0]['level_4_from_range']; ?></td>
                                                    <td style="text-align: center;"><?php echo $levellist4record[$i][0]['level_4_to_range']; ?></td>
                                                    <td style="text-align: center;">
                                                        <button id="btnupdatelevel4list" name="btnupdatelevel4list" class="btn btn-default " style="text-align: center;" 
                                                                onclick="javascript: return formupdatelevel4list(
                                                                                    ('<?php echo $levellist4record[$i][0]['list_4_desc_' . $laug]; ?>'),
                                                                                    ('<?php echo $levellist4record[$i][0]['village_id']; ?>'),
                                                                                    ('<?php echo $levellist4record[$i][0]['prop_level4_list_id']; ?>'),
                                                                                    ('<?php echo $levellist4record[$i][0]['level_4_from_range']; ?>'),
                                                                                    ('<?php echo $levellist4record[$i][0]['level_4_to_range']; ?>'),
                                                                                    ('<?php echo $levellist4record[$i][0]['id']; ?>'));">
                                                            <span class="glyphicon glyphicon-pencil"></span></button>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table> 
<!--                                </div>
                            </div>-->
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if (!empty($level1record)) { ?>
        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
        <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
    <?php if (!empty($levellist1record)) { ?>
        <input type="hidden" value="Y" id="hfhidden2"/><?php } else { ?>
        <input type="hidden" value="N" id="hfhidden2"/><?php } ?>
    <?php if (!empty($level2record)) { ?>
        <input type="hidden" value="Y" id="hfhidden3"/><?php } else { ?>
        <input type="hidden" value="N" id="hfhidden3"/><?php } ?>
    <?php if (!empty($levellist2record)) { ?>
        <input type="hidden" value="Y" id="hfhidden4"/><?php } else { ?>
        <input type="hidden" value="N" id="hfhidden4"/><?php } ?>
    <?php if (!empty($level3record)) { ?>
        <input type="hidden" value="Y" id="hfhidden5"/><?php } else { ?>
        <input type="hidden" value="N" id="hfhidden5"/><?php } ?>
    <?php if (!empty($levellist3record)) { ?>
        <input type="hidden" value="Y" id="hfhidden6"/><?php } else { ?>
        <input type="hidden" value="N" id="hfhidden6"/><?php } ?>
    <?php if (!empty($level4record)) { ?>
        <input type="hidden" value="Y" id="hfhidden7"/><?php } else { ?>
        <input type="hidden" value="N" id="hfhidden7"/><?php } ?>
    <?php if (!empty($levellist4record)) { ?>
        <input type="hidden" value="Y" id="hfhidden8"/><?php } else { ?>
        <input type="hidden" value="N" id="hfhidden8"/><?php } ?>

    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
    <input type='hidden' value='<?php echo $hflevel1id; ?>' name='hflevel1id' id='hflevel1id'/>
    <input type='hidden' value='<?php echo $hflevel1code; ?>' name='hflevel1code' id='hflevel1code'/>
    <input type='hidden' value='<?php echo $hflevel2id; ?>' name='hflevel2id' id='hflevel2id'/>
    <input type='hidden' value='<?php echo $hflevel2code; ?>' name='hflevel2code' id='hflevel2code'/>
    <input type='hidden' value='<?php echo $hflevel3id; ?>' name='hflevel3id' id='hflevel3id'/>
    <input type='hidden' value='<?php echo $hflevel3code; ?>' name='hflevel3code' id='hflevel3code'/>
    <input type='hidden' value='<?php echo $hflevel4id; ?>' name='hflevel4id' id='hflevel4id'/>
    <input type='hidden' value='<?php echo $hflevel4code; ?>' name='hflevel4code' id='hflevel4code'/>
    <input type='hidden' value='<?php echo $hflevellist1id; ?>' name='hflevellist1id' id='hflevellist1id'/>
    <input type='hidden' value='<?php echo $hflevellist1code; ?>' name='hflevellist1code' id='hflevellist1code'/>
    <input type='hidden' value='<?php echo $hflevellist2id; ?>' name='hflevellist2id' id='hflevellist2id'/>
    <input type='hidden' value='<?php echo $hflevellist2code; ?>' name='hflevellist2code' id='hflevellist2code'/>
    <input type='hidden' value='<?php echo $hflevellist3id; ?>' name='hflevellist3id' id='hflevellist3id'/>
    <input type='hidden' value='<?php echo $hflevellist3code; ?>' name='hflevellist3code' id='hflevellist3code'/>
    <input type='hidden' value='<?php echo $hflevellist4id; ?>' name='hflevellist4id' id='hflevellist4id'/>
    <input type='hidden' value='<?php echo $hflevellist4code; ?>' name='hflevellist4code' id='hflevellist4code'/>

</div>


<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>

