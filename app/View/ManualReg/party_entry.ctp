<?php
echo $this->Html->script('bootstrap-datepicker.min');
echo $this->Html->css('bootstrap-datepicker3.min');
?>

<script type="text/javascript">
    $(document).ready(function () {
        getPartyFields();
        $('#7_12list').hide();
        $('#submitbutton').show();



        var host = '<?php echo $this->webroot; ?>';
        //----------------------------------------------------------------------------------
        if ($('#property_flag').val() == 'N')
        {
             get_old_party();
             var category = $("#party_catg_id").val();
            $.post(host + 'Citizenentry/get_party_feilds', {category: category}, function (data1)
            {

                $("#partyentry").html(data1);
                $(document).trigger('_page_ready');
                show_error_messages();
                $("#partyentry").show();
            });
        }
        else
        {
            $('#7_12list').hide();

            //$('#submitbutton').hide();
        }

        //----------------------------------------------------------------------------------------

        $("#party_type_id").change(function ()
        {
            if ($('#property_flag').val() == 'Y') {
                $('#7_12list').hide();

                //$('#submitbutton').hide();
            }
        });
        //----------------------------------------------------------------------------------------
        //party category change function
        $('#party_catg_id').change(function () {
            party_cat_change();

        });

        //----------------------------------------------------------------------
        $("#identificationtype_desc_en").blur(function () {
            var type = $("#identificationtype_id option:selected").val();
            var desc = $("#identificationtype_desc_en").val();
            if (type != '')
            {
                $.getJSON(host + 'Citizenentry/get_validation_rule', {type: type}, function (data)
                {
                    var pattern = $.trim(data.pattern);
                    var message = data.message;
                    var error_code = data.error_code;
                    switch (error_code) {
<?php foreach ($allrule as $rule) { ?>
                            case '<?php echo $rule[0]['error_code'] ?>' :
                                var regex = <?php echo $rule[0]['pattern_rule_client']; ?>;
                                var message = '<?php echo $rule[0]['error_messages_' . $laug]; ?>';
                                break;
<?php } ?>
                    }
                    if (!desc.match(regex))
                    {
                        $("#identificationtype_desc_en").focus();
                        $("#identificationtype_desc_en_error").text(message);
                        return false;
                    } else
                    {
                        $("#identificationtype_desc_en_error").text('');
                        return true;
                    }
                });
            }
        });
        //----------------------------------------------------------------------------------------------
<?php if (!empty($party_record)) { ?>
            $('#tableParty').dataTable({
                "iDisplayLength": 10,
                "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
            });<?php } ?>
        $('#prop_list_tbl').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
    });
    //-------------------------------------------------------------------/

    var host = '<?php echo $this->webroot; ?>';
    var nameformat = '<?php echo $name_format ?>';
    var lang = '<?php echo $laug ?>';

    function getPartyFields() {
        var category = $("#party_catg_id").val();
        $.post(host + 'Citizenentry/get_party_feilds', {category: category}, function (fields)
        {
            $("#partyentry").html(fields);
            $(document).trigger('_page_ready');
            show_data_messages();
            show_error_messages();
        });
    }

    //---------------------------------------------------//
    function formsave() {
        document.getElementById("actiontype").value = '1';
        document.getElementById("hfaction").value = 'S';
        $('#csrftoken').val('<?php echo $this->Session->read('csrftoken'); ?>');
        $.post(host + 'Citizenentry/is_party_ekyc_auth_compusory', {}, function (data)
        {
            if (data == 'Y')
            {
                if (!$('#uid').val())
                {
                    alert('Please Enter UID');
                    return false;
                } else {
                    $.post(host + 'Citizenentry/party_ekyc_authentication', {}, function (data1)
                    {
                        if (data1 == 'Y')
                        {
                            $('#party_entry').submit();
                        }
                        if (data1 == 'N')
                        {
                            if (!$('#identificationtype_id').val())
                            {
                                alert('Please select identity');
                                return false;
                            }
                            if (!$('#"identificationtype_desc_en"').val())
                            {
                                alert('Please Enter identity value');
                                return false;
                            }
                        }
                    });
                }
            } else if (data == 'N')
            {

                if ($('#property_flag').val() == 'Y' && $('#propertyid').val() && $('#val_id').val()) {
                    $('#party_entry').submit();
                }
                else if ($('#property_flag').val() == 'N') {
                    $('#party_entry').submit();

                } else {
                    alert('Please Select Property');
                }
            }

        });
//          alert($('#csrftoken').val());
//          return false;
    }
    //------------------------------------------------
    //
//form edit
    function edit_party(category, id, party_type_id, property_id)
    {
        if (category === '' && id === '')
        {
        } else {

            $.post(host + 'Citizenentry/get_party_feilds', {category: category, id: id}, function (data)
            {
                $('#submitbutton').show();
                $('#hfid').val(id);
                $('#hfupdateflag').val('Y');
                $('#party_id').val(id);
                $('#party_catg_id').val(category);
                $('#party_type_id').val(party_type_id);
                $('#partyentry').html(data);
                $('#partyentry').show();
                $('#curr_cat').val(category);

                // show_error_messages();
                $.post(host + 'Citizenentry/get_valuation_id', {property_id: property_id}, function (val_id)
                {
                    $('#' + property_id).prop('checked', true);
                    var edit_flag = 'Y';
                    formview(property_id, val_id, edit_flag);
                });

                if ($('#village_id').length && $("#village_id option:selected").val() != '' ) {
                    var village_id = $("#village_id option:selected").val();
                    $.post(host + 'Citizenentry/behavioral_patterns', {ref_id: 2, behavioral_id: 2, village_id: village_id, ref_val: id}, function (data1)
                    {

                        $('.partyaddress').html(data1);
                    });
                }
                $(document).trigger('_page_ready');
            });
        }
    }
    //-------------------------------------------//
    function forcancel() {
        document.getElementById("actiontype").value = '2';
        location.reload();
    }
    function formdelete(id) {
        var result = confirm("Are you sure you want to delete this record?");
        $('#hfid').val(id);
        if (result) {
            $.post(host + 'Citizenentry/delete_party', {id: id}, function (data1)
            {
                if (data1 == 1)
                {
                    alert('Party deleted successfully');
                    location.reload();
                } else
                {
                    alert('Error');
                }
            });
        } else {
            return false;
        }
    }
//--------------------------------------------------------------------//
//Formview

    function formview(id, val_id, edit_flag)
    {

        var party = $("#party_type_id").val();
        var category = $("#party_catg_id").val();
        $('#propertyid').val(id);
        $('#val_id').val(val_id);
        $.post(host + 'Citizenentry/get_valuation_amt', {val_id: val_id}, function (amount) {

            $('#valuation_amt').val(amount);
        });

        if (category == 1)
        {
            $.post(host + 'Citizenentry/check_land_record_fetching', {party: party}, function (data1)
            {

                if (data1 == 'Y')
                {

                    //$('#partyentry').hide();
                    $('#submitbutton').hide();
                    if (nameformat == 'Y') {
                        //$('#party_fname_en,#party_mname_en,#party_lname_en').val('');
                        $('#party_fname_en,#party_mname_en,#party_lname_en').attr('readonly', false);
                    } else
                    {
                        // $('#party_full_name_en').val(' ');
                        $('#party_full_name_en').attr('readonly', false);
                    }


                    $.post(host + 'Citizenentry/get_7_12_record', {id: id, party: party}, function (data2)
                    {

                        if (data2 == 1)//data not found on LR
                        {
                            $.post(host + 'Citizenentry/check_7_12_compulsary', {party: party}, function (data3)
                            {

                                if (data3 == 'Y')
                                {
                                    alert('Data not found on land record! Sorry You are unable to proceed');
                                    // $('#partyentry').hide();
                                    $('#submitbutton').hide();
                                } else if (data3 == 'N')
                                {
                                    alert('Data not found on land record! You can enter details');
                                    $('#submitbutton').show();
                                    // getPartyFields();
                                }

                            });
                            return true;
                        } else if (data2 == 'o')
                        {
                            alert('Enter correct area value');
                            window.location.href = host + "Citizenentry/property_details";
                        } else // record found on LR
                        {

                            if (data2 != 1)
                            {
                                $("#7_12list").html(data2);
                                $('#7_12list').show();

                                $.post(host + 'Citizenentry/check_7_12_compulsary', {party: party}, function (data3)
                                {

                                    if (data3 == 'Y')
                                    {

                                        // $('#partyentry').hide();
                                        $('#submitbutton').hide();
                                        if (nameformat == 'Y') {
                                            if (edit_flag != 'Y') {
                                                $('#party_fname_en,#party_mname_en,#party_lname_en').val('');
                                            }
                                            $('#party_fname_en,#party_mname_en,#party_lname_en').attr('readonly', true);
                                        } else {
                                            if (edit_flag != 'Y') {
                                                $('#party_full_name_en').val(' ');
                                            }
                                            $('#party_full_name_en').attr('readonly', true);
                                        }
                                    } else
                                    {
                                        $('#submitbutton').show();
                                        //  getPartyFields();
                                        if (nameformat == 'Y') {
                                            $('#party_fname_en,#party_mname_en,#party_lname_en').attr('readonly', false);
                                        } else {
                                            //  $('#party_full_name_en').val(' ');
                                            $('#party_full_name_en').attr('readonly', false);
                                        }
                                    }
                                });
                            }
                        }

                    });
                } else
                {
                    //  getPartyFields();
                    $('#submitbutton').show();
                    if (nameformat == 'Y') {
                       // $('#party_fname_en,#party_mname_en,#party_lname_en').val('');
                        $('#party_fname_en,#party_mname_en,#party_lname_en').attr('readonly', false);
                    } else {
                       // $('#party_full_name_en').val(' ');
                        $('#party_full_name_en').attr('readonly', false);
                    }
                }

            });
        } else
        {//category other than 1
            //getPartyFields();
            $('#submitbutton').show();
            $('#7_12list').hide();
        }
    }

    //-----------------------------------------//
    function setval(fname, mname, lname)
    {



        if (nameformat == 'Y') {
            $('#party_fname_' + lang).val(fname);
            $('#party_mname_' + lang).val(mname);
            $('#party_lname_' + lang).val(lname);

        } else {
            $('#party_full_name_' + lang).val(fname + ' ' + ' ' + mname + ' ' + lname);
            $('#party_full_name_' + lang).attr('readonly', true);
        }
        $('#submitbutton').show();

    }
//-----------------------------------------------/
    function ispresenter(id)
    {
        $.post(host + 'Citizenentry/check_presenter', {id: id}, function (data)
        {
            if (data > 0)
            {
                var result = confirm("Presenter already selected Are you sure you want to change Presenter?");
                if (result) {
                    $.post('<?php echo $this->webroot; ?>Citizenentry/set_presenter', {id: id}, function (data1)
                    {
                        alert('Party successfully set as presenter');
                        return false;
                    });
                }
            } else
            {
                $.post(host + 'Citizenentry/set_presenter', {id: id}, function (data1)
                {
                    if (data1 == 1)
                    {
                        alert('Party successfully set as presenter');
                        return false;
                    }

                });
            }
        });
    }

    //---------------------------------------------//
    function party_cat_change()
    {

        var current_party = $('#party_id').val();
        var category = $("#party_catg_id").val();
        var curr_cat = $('#curr_cat').val();

        var party = $("#party_type_id").val();
        if ($('#property_flag').val() == "N")
        {
            if(category==1)
            {
             get_old_party();
              $('#7_12list').show();
         }else{
             $('#7_12list').hide();
         }
            var praty_id = $("#hfid").val();
            
            $.post(host + 'Citizenentry/get_party_feilds', {category: category, party_id: praty_id}, function (data1)
            {
                $("#partyentry").html(data1);
               // $('#7_12list').hide();
                $(document).trigger('_page_ready');
                show_error_messages();
            });
        } else if ($('#property_flag').val() == "Y")
        {
            var intRegex = /^\d+$/;
            if ($('#hfupdateflag').val() == 'Y' && intRegex.test($('#party_id').val()))
            {

                var prop_id = $('input[name=prop_id]:checked', '#party_entry').val();
                if (curr_cat == category)
                {
                    edit_party(category, current_party, party, prop_id);
                } else {
                    getPartyFields();
                }
            }
            else
            {
                getPartyFields();
            }
        } else {
            getPartyFields();
            $('#7_12list').hide();
        }

    }


//-------------------------------------------------//

//---------------------------get old party------------------------//
    function get_old_party()
    {
        var party = $("#party_type_id").val();
        var category = $("#party_catg_id").val();

        $.post(host + 'Citizenentry/check_land_record_fetching', {party: party}, function (data1)
        {

            if (data1 == 'Y')
            {

                //$('#partyentry').hide();
                $('#submitbutton').hide();
                if (nameformat == 'Y') {
                    //$('#party_fname_en,#party_mname_en,#party_lname_en').val('');
                    $('#party_fname_en,#party_mname_en,#party_lname_en').attr('readonly', false);
                } else
                {
                    // $('#party_full_name_en').val(' ');
                    $('#party_full_name_en').attr('readonly', false);
                }


                $.post(host + 'Citizenentry/get_record_old_party', {party: party}, function (data2)
                {

                    if (data2 == 1)//data not found on LR
                    {
                        $.post(host + 'Citizenentry/check_7_12_compulsary', {party: party}, function (data3)
                        {

                            if (data3 == 'Y')
                            {
                                alert('Data not found on land record! Sorry You are unable to proceed');
                                // $('#partyentry').hide();
                                $('#submitbutton').hide();
                            } else if (data3 == 'N')
                            {
                                alert('Data not found on land record! You can enter details');
                                $('#submitbutton').show();
                                // getPartyFields();
                            }

                        });
                        return true;
                    } else // record found on LR
                    {

                        if (data2 != 1)
                        {
                            $("#7_12list").html(data2);
                            $('#7_12list').show();

                            $.post(host + 'Citizenentry/check_7_12_compulsary', {party: party}, function (data3)
                            {

                                if (data3 == 'Y')
                                {

                                    // $('#partyentry').hide();
                                    $('#submitbutton').hide();
                                    if (nameformat == 'Y') {
                                        if (edit_flag != 'Y') {
                                            $('#party_fname_en,#party_mname_en,#party_lname_en').val('');
                                        }
                                        $('#party_fname_en,#party_mname_en,#party_lname_en').attr('readonly', true);
                                    } else {
                                        if (edit_flag != 'Y') {
                                            $('#party_full_name_en').val(' ');
                                        }
                                        $('#party_full_name_en').attr('readonly', true);
                                    }
                                } else
                                {
                                    $('#submitbutton').show();
                                    //  getPartyFields();
                                    if (nameformat == 'Y') {
                                        $('#party_fname_en,#party_mname_en,#party_lname_en').attr('readonly', false);
                                    } else {
                                        //  $('#party_full_name_en').val(' ');
                                        $('#party_full_name_en').attr('readonly', false);
                                    }
                                }
                            });
                        }
                    }

                });
            } else
            {
                //  getPartyFields();
                $('#submitbutton').show();
                if (nameformat == 'Y') {
                    $('#party_fname_en,#party_mname_en,#party_lname_en').val('');
                    $('#party_fname_en,#party_mname_en,#party_lname_en').attr('readonly', false);
                } else {
                    $('#party_full_name_en').val(' ');
                    $('#party_full_name_en').attr('readonly', false);
                }
            }

        });
    }

    function show_data_messages() {
<?php
if (isset($fromdata)) {
    ?>
    <?php
    foreach ($fromdata as $keyfield => $message) {
        ?>
                $("#<?php echo $keyfield ?>").val("<?php echo $message ?>");
    <?php } ?>

<?php }
?>
    }
    //-----------------------------------------------------------



</script>



<?php
echo $this->Html->css('popup');
$tokenval = $this->Session->read("Selectedtoken");
$csrftoken = $this->Session->read('csrftoken');
$doc_lang = $this->Session->read('doc_lang');
?>
<?php
//if (isset($errarr)) {
//    echo "<ul>";
//    foreach ($errarr as $key => $arr) {
//        if ($arr != '') {
//            echo "<li>" . $key . "-" . $arr . "</li>";
//        }
//    }
//    echo "</ul>";
//}
?>
<?php echo $this->Form->create('party_entry', array('id' => 'party_entry', 'class' => 'form-vertical')); ?>
<div class="row">
    <div class="col-lg-12">
        <?php
        echo $this->element("Registration/main_menu");
        echo $this->element("Citizenentry/main_menu");
        echo $this->element("Citizenentry/property_menu");
        ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblparty'); ?></h3></center>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <p style="color: red;"><b><?php echo __('lblnote'); ?>&nbsp;</b><?php echo __('lblengdatarequired'); ?></p>
                            <p style="color: red;"><b><?php echo __('lblnote'); ?>&nbsp;</b><?php echo __('lblsellerandpurrequired'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label"><?php echo __('lbltokenno'); ?> :-<span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('', array('label' => false, 'id' => '', 'value' => $Selectedtoken, 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="party_type_id" class="col-sm-2 control-label"><?php echo __('lblpartytype'); ?></label> 
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('party_type_id', array('label' => false, 'id' => 'party_type_id', 'class' => 'form-control input-sm', 'options' => array($partytype))); ?>
                            <!--<span id="party_type_id_error" class="form-error"><?php echo $errarr['party_type_id_error']; ?></span>-->
                        </div>

                        <label for="party_catg_id" class="col-sm-3 control-label"><?php echo __('lblpartycategory'); ?>:</label> 
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('party_catg_id', array('label' => false, 'id' => 'party_catg_id', 'class' => 'form-control input-sm', 'options' => array($party_category))); ?>
                            <!--<span id="party_catg_id_error" class="form-error"><?php echo $errarr['party_catg_id_error']; ?></span>-->
                        </div>
                    </div>
                </div>              
            </div>
        </div>
        <?php echo $this->Form->input('property_flag', array('id' => 'property_flag', 'type' => 'hidden', 'value' => (isset($property) && $property ) ? 'Y' : 'N')); ?>
        <?php if (!empty($property)) { ?>
            <div class="row" id="propertylist">
                <div class="col-sm-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title headbolder"><?php echo __('lbllistofproperties'); ?></h3>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="prop_list_tbl">
                                    <thead > 
                                        <tr class="table_title_red_brown">
                                            <th class="center"> <?php echo __('lbllocation'); ?></th>
                                            <th class="center">  <?php echo __('lblusage'); ?>    </th>
                                            <th class="center">        <?php echo __('lblpropertydetails'); ?>    </th>
                                            <th class="center width10"> <?php echo __('lblaction'); ?>     </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($property_list as $key => $property) { ?>
                                            <tr>
                                                <td class="tblbigdata">
                                                    <?php echo $property[0]['village_name_' . $doc_lang]; ?>
                                                </td>
                                                <td class="tblbigdata">
                                                    <?php echo $property[0]['evalrule_desc_' . $doc_lang]; ?>
                                                </td>
                                                <td class="tblbigdata">
                                                    <?php
                                                    $prop_name = "";
                                                    foreach ($property_pattern as $key1 => $pattern) {
                                                        if ($property[0]['property_id'] == $pattern[0]['mapping_ref_val']) {
                                                            $prop_name .= "  " . $pattern[0]['pattern_desc_' . $doc_lang] . " : <small>" . $pattern[0]['field_value_' . $doc_lang] . "</small><br>";
                                                        }
                                                    }

                                                    echo substr($prop_name, 1);
                                                    ?>
                                                </td>
                                                <td>
                                                    <input type="radio" class="btn btn-primary" name="prop_id" value="<?php echo $property[0]['property_id']; ?>" id="<?php echo $property[0]['property_id']; ?>" name="" onclick="javascript: return formview('<?php echo $property[0]['property_id']; ?>', '<?php echo $property[0]['val_id']; ?>');">
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
            <input type="hidden" name="prop_flag" id="prop_flag" value="1">
        <?php } else { ?>
            <input type="hidden" name="prop_flag" id="prop_flag" value="2">
        <?php } ?>

        <div  id="7_12list">
        </div>
        <div id="partyentry">


        </div>
        <div class="box box-primary">
            <div class="box-body">


                <br>
                <div class="row center"  id="submitbutton">
                    <div class="col-sm-12">
                        <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
                        <button type="button"  id="btnNext" name="btnNext" class="btn btn-info" onclick="javascript: return formsave();"><?php echo __('btnsave'); ?></button>
                        <button type="button"  id="btnCancel" name="btnCancel" class="btn btn-info" onclick="javascript: return forcancel();"><?php echo __('btncancel'); ?></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-primary">
            <div class="box-body">
                <div class="box-header with-border">
                    <h3 class="box-title headbolder"><?php echo __('lbllistofsavedparties'); ?></h3>
                </div>
                <table id="tableParty" class="table table-striped table-bordered table-condensed">  
                    <thead>  
                        <tr>  
                            <th class="center"><?php echo __('lblpartyname'); ?></th>
                            <th class="center"><?php echo __('lblpartytypeshow'); ?></th>
                            <th class="center"><?php echo __('lblpartycategoryshow'); ?> </th>
                            <th class="center width16"><?php echo __('lblaction'); ?></th>
                        </tr>  
                    </thead>
                    <tbody>

                        <?php
                        foreach ($party_record as $party_record1):
//                    pr($propertydetailsrecord1);
//                    exit;
                            ?>
                            <tr>
                                <td class="tblbigdata"><?php echo $party_record1[0]['party_full_name_' . $doc_lang]; ?></td>
                                <td class="tblbigdata"><?php echo $party_record1[0]['party_type_desc_' . $doc_lang]; ?></td>
                                <td class="tblbigdata"><?php echo $party_record1[0]['category_name_' . $doc_lang]; ?></td>
                                <td class="tblbigdata">
                                    <input type="button" id="btnpren" name="btnpren" class="btn btn-info "  
                                           onclick="javascript: return ispresenter(('<?php echo $party_record1[0]['id']; ?>'));"                                  
                                           value="<?php echo __('lblispresenter'); ?>" />
                                    <input type="button" class="btn btn-info" value="<?php echo __('lblbtnedit'); ?>" onclick="edit_party('<?php echo $party_record1[0]['party_catg_id']; ?>', '<?php echo $party_record1[0]['party_id']; ?>', '<?php echo $party_record1[0]['party_type_id']; ?>', '<?php echo $party_record1[0]['property_id']; ?>');"> 
                                   <input type="button" id="btndelete" class="btn btn-info" value="Delete" name="btndelete" class="btn btn-default "  
                                            onclick="javascript: return formdelete(('<?php echo $party_record1[0]['id']; ?>'));">
                                </td>
                            </tr>
                        <?php endforeach;
                        ?>
                        <?php unset($party_record1); ?>
                    </tbody>
                </table> 
            </div>
        </div>
        <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
        <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
        <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
        <input type='hidden' value='' name='party_id' id='party_id'/>
        <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
        <input type='hidden' value='' name='propertyid' id='propertyid'/>
        <input type='hidden' value='' name='curr_cat' id='curr_cat'/>
        <input type='hidden' value='' name='valuation_amt' id='valuation_amt'/>
        <input type='hidden' value='' name='val_id' id='val_id'/>
    </div>
</div>

<?php echo $this->Form->end(); ?>                
<?php echo $this->Js->writeBuffer(); ?>

