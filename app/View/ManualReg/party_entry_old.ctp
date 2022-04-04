<?php
echo $this->Html->script('bootstrap-datepicker.min');
echo $this->Html->css('bootstrap-datepicker3.min');
?>

<script type="text/javascript">

    $(document).ready(function () {
        $('#partyentry').hide();
        $('#identification').hide();
        $('#bank').hide();
        $('#govern').hide();
        $('#company').hide();


        if ($("#village_id option:selected").val() != '') {
            var village_id = $("#village_id option:selected").val();
            $.post('<?php echo $this->webroot; ?>Citizenentry/behavioral_patterns', {ref_id: 2, behavioral_id: 2, village_id: village_id}, function (data)
            {

                $("#address").html(data);
            });
        }

        if ($('#prop_flag').val() == 2)
        {
            $('#partyentry').show();
        }
        $("#party_type_id").change(function ()
        {
            if ($('#prop_flag').val() == 1) {
                $('#7_12list').hide();

                $('#partyentry').hide();
                alert('Please Select Property');
            }

<?php if ($name_format == 'Y') { ?>
                $('#party_fname_en').val('');
                $('#party_mname_en').val('');
                $('#party_lname_en').val('');
                $('#party_fname_en').attr('readonly', false);
                $('#party_mname_en').attr('readonly', false);
                $('#party_lname_en').attr('readonly', false);
<?php } else { ?>
                $('#party_full_name_en').val('');
                $('#party_full_name_en').attr('readonly', false);
<?php } ?>

        });

        $('#dob').datepicker({
            maxDate: '+0d',
            yearRange: '1920:2010',
            changeMonth: true,
            changeYear: true,
            dateFormat: "dd MM yy",
        });
        $("#dob").change(function ()
        {

            var dateString = $("#dob").val();
            var today = new Date();
            var birthDate = new Date(dateString);
            var age = today.getFullYear() - birthDate.getFullYear();
            var m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate()))
            {
                age--;
            }
            $("#age").val(age);
        });
<?php if (!empty($party_record)) { ?>
            $('#tableParty').dataTable({
                "iDisplayLength": 10,
                "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
            });<?php } ?>

        $('#prop_list_tbl').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });

        $('#district_id').change(function () {
            var dist = $("#district_id option:selected").val();
            $.getJSON("<?php echo $this->webroot; ?>districtchangeevent", {dist: dist}, function (data)
            {
                var sc = '<option>--select--</option>';
                $.each(data.taluka, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#taluka_id").prop("disabled", false);
                $("#taluka_id option").remove();
                $("#taluka_id").append(sc);

            });

        });


        $('#taluka_id').change(function () {
            var tal = $("#taluka_id option:selected").val();

            $.getJSON('<?php echo $this->webroot; ?>Citizenentry/taluka_change_event', {tal: tal}, function (data)
            {

                var sc = '<option>--select--</option>';
                $.each(data.village, function (index, val) {

                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                // $("#corp_id").prop("disabled", false);
                // $("#valutation_zone_id").prop("disabled", false);

                $("#village_id option").remove();
                $("#village_id").append(sc);
            });
        });

        $("#village_id").on('change', function () {
            $.post('<?php echo $this->webroot; ?>Citizenentry/behavioral_patterns', {village_id: $("#village_id").val(), ref_id: 2, behavioral_id: 2}, function (data)
            {

                $("#address").html(data);

            });

        });
        // document.getElementById('newdoc').style.backgroundColor = 'wheat';

        $('#identificationtype_id').change(function () {
            var iden = $('#identificationtype_id').val();
            if (iden == '')
            {
                $('#identification').hide()

            }
            else
            {
                $('#identification').show()
            }
        });




        //party category change function

        $('#party_catg_id').change(function () {

            var category = $("#party_catg_id").val();
            if ($('#prop_flag').val() == 2)
            {

                if (category == 1)
                {
                    $('#partyentry').show();
                    $('#individual').show();
                    $('#bank').hide();
                    $('#govern').hide();
                    $('#company').hide();

                }
                else if (category == 2)
                {
                    $('#partyentry').show();
                    $('#individual').hide();
                    $('#7_12list').hide();
                    $('#bank').show();
                    $('#govern').hide();
                    $('#company').hide();

                }
                else if (category == 3)
                {
                    $('#partyentry').show();
                    $('#govern').show();
                    $('#individual').hide();
                    $('#bank').hide();
                    $('#company').hide();
                }
                else if (category == 4)
                {
                    $('#partyentry').show();
                    $('#govern').hide();
                    $('#individual').hide();
                    $('#bank').hide();
                    $('#company').show();

                }
            }
            else if ($('#prop_flag').val() == 1)
            {

                $('#partyentry').hide();
                $('#individual').hide();
                $('#7_12list').hide();
                $('#bank').hide();
                alert('Please select property');
            }


        });


    });



    function formsave() {
        document.getElementById("actiontype").value = '1';
        document.getElementById("hfaction").value = 'S';
        $('#csrftoken').val('<?php echo $this->Session->read('csrftoken'); ?>');
       

$('#party_entry').submit();
        $.post('<?php echo $this->webroot; ?>Citizenentry/is_party_ekyc_auth_compusory', {}, function (data)
        {
            if (data == 'Y')
            {
                $.post('<?php echo $this->webroot; ?>Citizenentry/party_ekyc_authentication', {}, function (data)
                {
                    //code pending
                });
            }
        });


//          alert($('#csrftoken').val());
//          return false;
    }

    function forcancel() {
        document.getElementById("actiontype").value = '2';
    }

    function formdelete(id) {
        var result = confirm("Are you sure you want to delete this record?");
        if (result) {
            document.getElementById("actiontype").value = '3';
            $('#hfid').val(id);
        } else {
            return false;
        }
    }

    function formupdate(id, salutation_id, party_fname_en, party_mname_en, party_lname_en, alias_name_en, father_fname_en, father_mname_en, father_lname_en, mother_fname_en, mother_mname_en, mother_lname_en,
            party_fname_ll, party_mname_ll, party_lname_ll, alias_name_ll, father_fname_ll, father_mname_ll, father_lname_ll, mother_fname_ll, mother_mname_ll, mother_lname_ll,
            dob, age, gender_id, occupation_id, party_type_id, party_catg_id, property_id, identificationtype_id, identificationtype_desc_en, uid, mobile, email, district_id, taluka_id, village_id, party_full_name_en, party_full_name_ll, father_full_name_en, father_full_name_ll,
            mother_full_name_en, mother_full_name_ll, exemption_id, idetification_mark1_en, idetification_mark1_ll, idetification_mark2_en, idetification_mark2_ll, pan_no, is_executer, bank_id, org_name, company_name, tan) {

        $.getJSON("<?php echo $this->webroot; ?>districtchangeevent", {dist: district_id}, function (data)
        {
            var sc = '<option>--select--</option>';
            $.each(data.taluka, function (index, val) {
                if (index == taluka_id)
                {
                    sc += "<option value=" + index + " selected>" + val + "</option>";
                }
                else
                {
                    sc += "<option value=" + index + ">" + val + "</option>";
                }
            });
            $("#taluka_id").prop("disabled", false);
            $("#taluka_id option").remove();
            $("#taluka_id").append(sc);

        });

        $.getJSON('<?php echo $this->webroot; ?>Citizenentry/taluka_change_event', {tal: taluka_id}, function (data1)
        {

            var sc = '<option>--select--</option>';
            $.each(data1.village, function (index1, val1) {
                if (index1 == village_id)
                {

                    sc += "<option value=" + index1 + " selected>" + val1 + "</option>";
                    $.post('<?php echo $this->webroot; ?>Citizenentry/behavioral_patterns', {village_id: index1, ref_id: 2, ref_val: id, behavioral_id: 2}, function (data)
                    {

                        $("#address").html(data);

                    });
                } else
                {
                    sc += "<option value=" + index1 + ">" + val1 + "</option>";
                }
            });

            $("#village_id option").remove();
            $("#village_id").append(sc);
        });
        $('#partyentry').show();

//for address


        $('#csrftoken').val(<?php echo $this->Session->read('csrftoken'); ?>);
        $('#hfid').val(id);
        $('#salutaion_desc').val(salutation_id);
        $('#propertyid').val(property_id);
        if (party_type_id == 1)
        {
<?php if ($name_format == 'Y') { ?>
                $('#party_fname_en').val(party_fname_en);
                $('#party_mname_en').val(party_mname_en);
                $('#party_lname_en').val(party_lname_en);
                $('#party_fname_en').attr('readonly', true);
                $('#party_mname_en').attr('readonly', true);
                $('#party_lname_en').attr('readonly', true);
<?php } else { ?>
                $('#party_full_name_en').val(party_full_name_en);
                $('#party_full_name_en').attr('readonly', true);
<?php } ?>

        } else
        {
<?php if ($name_format == 'Y') { ?>
                $('#party_fname_en').val(party_fname_en);
                $('#party_mname_en').val(party_mname_en);
                $('#party_lname_en').val(party_lname_en);
                $('#party_fname_en').attr('readonly', false);
                $('#party_mname_en').attr('readonly', false);
                $('#party_lname_en').attr('readonly', false);
<?php } else { ?>
                $('#party_full_name_en').val(party_fname_en + ' ' + ' ' + party_mname_en + ' ' + party_lname_en);
                $('#party_full_name_en').attr('readonly', true);
<?php } ?>
        }

        if (identificationtype_id == '')
        {
            $('#identification').hide()

        }
        else
        {
            $('#identification').show()
        }
        $('#is_executer').val(is_executer);
        $('#bank_id').val(bank_id);
        $('#org_name').val(org_name);
        $('#party_full_name_en').val(party_full_name_en);
        $('#party_full_name_ll').val(party_full_name_ll);

        $('#father_full_name_en').val(father_full_name_en);
        $('#father_full_name_ll').val(father_full_name_ll);

        $('#mother_full_name_en').val(mother_full_name_en);
        $('#mother_full_name_ll').val(mother_full_name_ll);
        $('#pan_no').val(pan_no);
        $('#company_name').val(company_name);
        $('#tan').val(tan);


        $('#alias_name_en').val(alias_name_en);
        $('#alias_name_en').val(alias_name_en);
        $('#father_fname_en').val(father_fname_en);
        $('#father_mname_en').val(father_mname_en);
        $('#father_lname_en').val(father_lname_en);
        $('#mother_fname_en').val(mother_fname_en);
        $('#mother_mname_en').val(mother_mname_en);
        $('#mother_lname_en').val(mother_lname_en);

        $('#father_fname_ll').val(father_fname_ll);
        $('#father_mname_ll').val(father_mname_ll);
        $('#father_lname_ll').val(father_lname_ll);
        $('#mother_fname_ll').val(mother_fname_ll);
        $('#mother_mname_ll').val(mother_mname_ll);
        $('#mother_lname_ll').val(mother_lname_ll);

        $('#party_fname_ll').val(party_fname_ll);
        $('#party_mname_ll').val(party_mname_ll);
        $('#party_lname_ll').val(party_lname_ll);

        $('#idetification_mark1_en').val(idetification_mark1_en);
        $('#idetification_mark1_ll').val(idetification_mark1_ll);
        $('#idetification_mark2_en').val(idetification_mark2_en);
        $('#idetification_mark2_ll').val(idetification_mark2_ll);


        $('#alias_name_ll').val(alias_name_ll);
        $('#dob').val(dob);
        $('#age').val(age);
        $('#gender_id').val(gender_id);
        $('#district_id').val(district_id);
        $('#village_id').val(village_id);
        $('#exemption_id').val(exemption_id);
        $('#occupation_id').val(occupation_id);
        $('#party_type_id').val(party_type_id);
        $('#party_catg_id').val(party_catg_id);
        $('#mobile_no').val(mobile);
        $('#identificationtype_id').val(identificationtype_id);
        $('#identificationtype_desc_en').val(identificationtype_desc_en);
        $('#uid').val(uid);
        $('#email_id').val(email);
        $('#hfupdateflag').val('Y');
        $('#btnadd').html('Save');
        if (party_catg_id == 1)
        {
            $('#partyentry').show();
            $('#bank').hide();
            $('#individual').show();
            $('#7_12list').hide();
            $('#govern').hide();
            $('#company').hide();

        }
        else if (party_catg_id == 2)
        {
            $('#partyentry').show();
            $('#bank').show();
            $('#individual').hide();
            $('#7_12list').hide();
            $('#govern').hide();
            $('#company').hide();

        }
        else if (party_catg_id == 3)
        {
            $('#partyentry').show();
            $('#bank').hide();
            $('#individual').hide();
            $('#7_12list').hide();
            $('#govern').show();
            $('#company').hide();

        }
        else if (party_catg_id == 4)
        {
            $('#partyentry').show();
            $('#bank').hide();
            $('#individual').hide();
            $('#7_12list').hide();
            $('#govern').hide();
            $('#company').show();

        }
        return false;
    }

//Formview

    function formview(id)
    {
        var party = $("#party_type_id").val();
        var category = $("#party_catg_id").val();
        if (category == 1)
        {
            $('#bank').hide();
            $('#govern').hide();
            $('#company').hide();
            $.post('<?php echo $this->webroot; ?>Citizenentry/check_land_record_fetching', {party: party}, function (data1)
            {
                if (data1 == 'Y')
                {

                    $('#partyentry').hide();
<?php if ($name_format == 'Y') { ?>
                        $('#party_fname_en').val('');
                        $('#party_mname_en').val('');
                        $('#party_lname_en').val('');
                        $('#party_fname_en').attr('readonly', false);
                        $('#party_mname_en').attr('readonly', false);
                        $('#party_lname_en').attr('readonly', false);
<?php } else { ?>
                        $('#party_full_name_en').val(' ');
                        $('#party_full_name_en').attr('readonly', false);
<?php } ?>



                    $('#propertyid').val(id);

                    $.post('<?php echo $this->webroot; ?>Citizenentry/get_7_12_record', {id: id, party: party}, function (data2)
                    {

                        if (data2 == 1)
                        {
                            $.post('<?php echo $this->webroot; ?>Citizenentry/check_7_12_compulsary', {party: party}, function (data3)
                            {

                                if (data3 == 'Y')
                                {
                                    alert('Data not found on land record! Sorry You are unable to proceed');
                                    $('#partyentry').hide();
                                    $('#individual').hide();
                                }
                                else if (data3 == 'N')
                                {
                                    alert('Data not found on land record! You can enter details');
                                    $('#partyentry').show();
                                    $('#individual').show();
                                }

                            });

                            return true;
                        }
                        else if (data2 == 'o')
                        {
                            alert('Enter correct area value');
                            window.location.href = "<?php echo $this->webroot; ?>Citizenentry/property_details";
                        }
                        else
                        {
                            if (data2 != 1)
                            {

                                $("#7_12list").html(data2);
                                $('#7_12list').show();
                                $.post('<?php echo $this->webroot; ?>Citizenentry/check_7_12_compulsary', {party: party}, function (data3)
                                {
                                    if (data3 == 'Y')
                                    {

                                        $('#partyentry').hide();
<?php if ($name_format == 'Y') { ?>
                                            $('#party_fname_en').attr('readonly', true);
                                            $('#party_mname_en').attr('readonly', true);
                                            $('#party_lname_en').attr('readonly', true);
<?php } else { ?>
                                            $('#party_full_name_en').val(' ');
                                            $('#party_full_name_en').attr('readonly', true);
<?php } ?>
                                    }
                                    else
                                    {
                                        $('#partyentry').show();
                                        $('#individual').show();
<?php if ($name_format == 'Y') { ?>
                                            $('#party_fname_en').attr('readonly', false);
                                            $('#party_mname_en').attr('readonly', false);
                                            $('#party_lname_en').attr('readonly', false);
<?php } else { ?>
                                            $('#party_full_name_en').val(' ');
                                            $('#party_full_name_en').attr('readonly', false);
<?php } ?>
                                    }
                                });
                            }
                        }

                    });

                }
                else
                {
                    $('#partyentry').show();
                    $('#individual').show();
<?php if ($name_format == 'Y') { ?>
                        $('#party_fname_en').attr('readonly', false);
                        $('#party_mname_en').attr('readonly', false);
                        $('#party_lname_en').attr('readonly', false);
                        $('#party_fname_en').val('');
                        $('#party_mname_en').val('');
                        $('#party_lname_en').val('');
<?php } else { ?>
                        $('#party_full_name_en').val(' ');
                        $('#party_full_name_en').attr('readonly', false);
<?php } ?>
                }

            });
        }
        else if (category == 2)
        {
            $('#partyentry').show();
            $('#bank').show();
            $('#individual').hide();
            $('#7_12list').hide();
            $('#govern').hide();
            $('#company').hide();

        }
        else if (category == 3)
        {
            $('#partyentry').show();
            $('#bank').hide();
            $('#individual').hide();
            $('#7_12list').hide();
            $('#govern').show();
            $('#company').hide();

        }
        else if (category == 4)
        {
            $('#partyentry').show();
            $('#bank').hide();
            $('#individual').hide();
            $('#7_12list').hide();
            $('#govern').hide();
            $('#company').show();

        }

    }

    function setval(fname, mname, lname)
    {
        $('#partyentry').show();
        $('#individual').show();


<?php if ($name_format == 'Y') { ?>
            $('#party_fname_en').val(fname);
            $('#party_mname_en').val(mname);
            $('#party_lname_en').val(lname);
            $('#party_fname_en').attr('readonly', true);
            $('#party_mname_en').attr('readonly', true);
            $('#party_lname_en').attr('readonly', true);
<?php } else { ?>
            $('#party_full_name_en').val(fname + ' ' + ' ' + mname + ' ' + lname);
            $('#party_full_name_en').attr('readonly', true);
<?php } ?>
    }


    function ispresenter(id)
    {
        $.post('<?php echo $this->webroot; ?>Citizenentry/check_presenter', {id: id}, function (data)
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
            }
            else
            {
                $.post('<?php echo $this->webroot; ?>Citizenentry/set_presenter', {id: id}, function (data1)
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


</script>

<?php
echo $this->Html->css('popup');
$tokenval = $this->Session->read("Selectedtoken");
$csrftoken = $this->Session->read('csrftoken');
$doc_lang = $this->Session->read('doc_lang');

?>

<?php echo $this->Form->create('party_entry', array('id' => 'party_entry', 'class' => 'form-vertical')); ?>

<div class="row">
    <div class="col-lg-12">
        <?php
         echo $this->element("ManualRegistration/main_menu");
        echo $this->element("ManualRegistration/property_menu");
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
                        <div class="col-sm-12" style="text-align: right;">
                            <p style="color: red;"><b><?php echo __('lblnote'); ?>&nbsp;</b><?php echo __('lblengdatarequired'); ?></p>
                            <p style="color: red;"><b><?php echo __('lblnote'); ?>&nbsp;</b><?php echo __('lblsellerandpurrequired'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <label for="" class="col-sm-4 control-label"><?php echo __('lbldocrno'); ?> :-<span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('', array('label' => false, 'id' => '', 'value' => $doc_reg_no, 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?>
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
                            <span id="party_type_id_error" class="form-error"><?php echo $errarr['party_type_id_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <label for="party_catg_id" class="col-sm-2 control-label"><?php echo __('lblpartycategory'); ?>:</label> 
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('party_catg_id', array('label' => false, 'id' => 'party_catg_id', 'class' => 'form-control input-sm', 'options' => array($party_category))); ?>
                        <span id="party_catg_id_error" class="form-error"><?php echo $errarr['party_catg_id_error']; ?></span>
                    </div>
                </div>
            </div>
        </div>
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
                                                            $prop_name.= "  " . $pattern[0]['pattern_desc_' . $doc_lang] . " : <small>" . $pattern[0]['field_value_' . $doc_lang] . "</small><br>";
                                                        }
                                                    }

                                                    echo substr($prop_name, 1);
                                                    ?>
                                                </td>
                                                <td>
                                                    <input type="button" class="btn btn-primary" value="<?php echo __('lblSelect'); ?>" onclick="javascript: return formview('<?php echo $property[0]['property_id']; ?>');">
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
            <div class="box box-primary">
                <div class="box-body">

                    <!--                    <div class="row">
                                            <div class="form-group">
                                                <label for="exemption_id" class="col-sm-3 control-label"><?php // echo __('Presentation Exemption');          ?></label> 
                                                <div class="col-sm-3">
                    <?php //echo $this->Form->input('exemption_id', array('label' => false, 'id' => 'exemption_id', 'class' => 'form-control input-sm', 'options' => array($exemption))); ?>
                                                </div>
                    
                                            </div>
                                        </div>-->
                    <div class="row">
                        <div class="form-group">
                            <label for="is_executer" class="col-sm-3 control-label"><?php echo __('lblisexecuter'); ?></label> 
                            <div class="col-sm-3">
                                <?php echo $this->Form->input('is_executer', array('label' => false, 'id' => 'is_executer', 'class' => 'form-control input-sm', 'options' => array($condition))); ?>
                                <!--<span id="is_executer_error" class="form-error"><?php // echo $errarr['is_executer_error'];   ?></span>-->
                            </div>


                        </div>
                    </div>
                    <div  class="rowht">&nbsp;</div>
                    <div id="individual">
                        <div class="row">
                            <div class="form-group">
                                <label for="salutation_id" class="col-sm-3 control-label"><?php echo __('lblSalutation'); ?></label> 
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('salutation_id', array('label' => false, 'id' => 'salutaion_desc', 'class' => 'form-control input-sm', 'options' => array($salutation))); ?>
                                    <!--<span id="salutation_id_error" class="form-error"><?php // echo $errarr['salutation_id_error'];   ?></span>-->
                                </div>

                            </div>
                        </div>
                        <?php if ($name_format == 'Y') { ?>
                            <div  class="rowht">&nbsp;</div>
                            <div class="row">
                                <div class="form-group">
                                    <?php
                                    if (!empty($doc_lang) and $doc_lang != 'en') {
                                        ?>
                                        <label for="party_fname_ll" class="col-sm-3 control-label">पक्ष पहिले नाव:-</label>    
                                        <div class="col-sm-3">
                                            <?php echo $this->Form->input('party_fname_ll', array('label' => false, 'id' => 'party_fname_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                            <span id="party_fname_ll_error" class="form-error"><?php echo $errarr['party_fname_ll_error']; ?></span>
                                        </div>
                                    <?php } ?>
                                    <label for="party_fname_en" class="col-sm-2 control-label">Party First Name:-[ENGLISH]</label>    
                                    <div class="col-sm-3">
                                        <?php echo $this->Form->input('party_fname_en', array('label' => false, 'id' => 'party_fname_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                        <span id="party_fname_en_error" class="form-error"><?php echo $errarr['party_fname_en_error']; ?></span>
                                    </div>

                                </div>
                            </div>
                            <div  class="rowht">&nbsp;</div>
                            <div class="row">
                                <div class="form-group">
                                    <?php
                                    if (!empty($doc_lang) and $doc_lang != 'en') {
                                        ?>
                                        <label for="party_mname_ll" class="col-sm-3 control-label">पक्ष मधले नाव:-</label>    
                                        <div class="col-sm-3">
                                            <?php echo $this->Form->input('party_mname_ll', array('label' => false, 'id' => 'party_mname_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                            <span id="party_mname_ll_error" class="form-error"><?php echo $errarr['party_mname_en_error']; ?></span>
                                        </div>
                                    <?php } ?>
                                    <label for="party_mname_en" class="col-sm-2 control-label">Party Middle Name:-[ENGLISH]</label>    
                                    <div class="col-sm-3">
                                        <?php echo $this->Form->input('party_mname_en', array('label' => false, 'id' => 'party_mname_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                        <span id="party_mname_en_error" class="form-error"><?php echo $errarr['party_mname_en_error']; ?></span>
                                    </div>

                                </div>
                            </div>

                            <div  class="rowht">&nbsp;</div>
                            <div class="row">
                                <div class="form-group">
                                    <?php
                                    if (!empty($doc_lang) and $doc_lang != 'en') {
                                        ?>
                                        <label for="party_lname_ll" class="col-sm-3 control-label">पक्ष आडनाव:-</label> 
                                        <div class="col-sm-3">
                                            <?php echo $this->Form->input('party_lname_ll', array('label' => false, 'id' => 'party_lname_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                            <span id="party_lname_ll_error" class="form-error"><?php echo $errarr['party_lname_ll_error']; ?></span>

                                        </div>
                                    <?php } ?>
                                    <label for="party_lname_en" class="col-sm-2 control-label">Party Last Name:-[ENGLISH]</label> 
                                    <div class="col-sm-3">
                                        <?php echo $this->Form->input('party_lname_en', array('label' => false, 'id' => 'party_lname_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>

                                        <span id="party_lname_en_error" class="form-error"><?php echo $errarr['party_lname_en_error']; ?></span>
                                    </div>

                                </div>
                            </div>
                        <?php } else { ?>
                            <div  class="rowht">&nbsp;</div>
                            <div class="row">
                                <div class="form-group">
                                    <?php
                                    if (!empty($doc_lang) and $doc_lang != 'en') {
                                        ?>
                                        <label for="party_full_name_ll" class="col-sm-3 control-label">पक्ष पूर्ण नाव:-</label> 
                                        <div class="col-sm-3">
                                            <?php echo $this->Form->input('party_full_name_ll', array('label' => false, 'id' => 'party_full_name_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                            <span id="party_full_name_ll_error" class="form-error"><?php echo $errarr['party_full_name_ll_error']; ?></span>
                                        </div>
                                    <?php } ?>
                                    <label for="party_full_name_en" class="col-sm-2 control-label">Party Full Name:-[ENGLISH]</label> 
                                    <div class="col-sm-3">
                                        <?php echo $this->Form->input('party_full_name_en', array('label' => false, 'id' => 'party_full_name_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                        <span id="party_full_name_en_error" class="form-error"><?php echo $errarr['party_full_name_en_error']; ?></span>
                                    </div>

                                </div>
                            </div>
                        <?php } ?>

                        <div  class="rowht">&nbsp;</div>
                        <div class="row">

                            <div class="form-group">
                                <?php
                                if (!empty($doc_lang) and $doc_lang != 'en') {
                                    ?>
                                    <label for="alias_name_ll" class="col-sm-3 control-label">ऊर्फ नाव:-</label> 
                                    <div class="col-sm-3">
                                        <?php echo $this->Form->input('alias_name_ll', array('label' => false, 'id' => 'alias_name_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                        <span id="alias_name_ll_error" class="form-error"><?php echo $errarr['alias_name_ll_error']; ?></span>
                                    </div>
                                <?php } ?>
                                <label for="alias_name_en" class="col-sm-2 control-label">Alias Name:[ENGLISH]</label> 
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('alias_name_en', array('label' => false, 'id' => 'alias_name_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                    <span id="alias_name_en_error" class="form-error"><?php echo $errarr['alias_name_en_error']; ?></span>
                                </div>

                            </div>
                        </div>
                        <?php if ($name_format == 'Y') { ?>
                            <div  class="rowht">&nbsp;</div>
                            <div class="row">
                                <div class="form-group">
                                    <?php
                                    if (!empty($doc_lang) and $doc_lang != 'en') {
                                        ?>
                                        <label for="father_fname_ll" class="col-sm-3 control-label">वडिलांचे पहिले नाव:-</label>    
                                        <div class="col-sm-3">
                                            <?php echo $this->Form->input('father_fname_ll', array('label' => false, 'id' => 'father_fname_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                            <span id="father_fname_ll_error" class="form-error"><?php echo $errarr['father_fname_ll_error']; ?></span>
                                        </div>
                                    <?php } ?>
                                    <label for="father_fname_en" class="col-sm-2 control-label">Father's First Name:-[ENGLISH]</label>    
                                    <div class="col-sm-3">
                                        <?php echo $this->Form->input('father_fname_en', array('label' => false, 'id' => 'father_fname_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                        <span id="father_fname_en_error" class="form-error"><?php echo $errarr['father_fname_en_error']; ?></span>
                                    </div>


                                </div>
                            </div>
                            <div  class="rowht">&nbsp;</div>
                            <div class="row">
                                <div class="form-group">
                                    <?php
                                    if (!empty($doc_lang) and $doc_lang != 'en') {
                                        ?>
                                        <label for="father_mname_ll" class="col-sm-3 control-label">वडिलांचे मधले नाव:-</label>    
                                        <div class="col-sm-3">
                                            <?php echo $this->Form->input('father_mname_ll', array('label' => false, 'id' => 'father_mname_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                            <span id="father_mname_ll_error" class="form-error"><?php echo $errarr['father_mname_ll_error']; ?></span>
                                        </div>
                                    <?php } ?>
                                    <label for="father_mname_en" class="col-sm-2 control-label">Father's Middle Name:-[ENGLISH]</label>    
                                    <div class="col-sm-3">
                                        <?php echo $this->Form->input('father_mname_en', array('label' => false, 'id' => 'father_mname_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                        <span id="father_mname_en_error" class="form-error"><?php echo $errarr['father_mname_en_error']; ?></span>
                                    </div>


                                </div>
                            </div>
                            <div  class="rowht">&nbsp;</div>
                            <div class="row">
                                <div class="form-group">
                                    <?php
                                    if (!empty($doc_lang) and $doc_lang != 'en') {
                                        ?>
                                        <label for="father_lname_ll" class="col-sm-3 control-label">वडिलांचे आडनाव:-</label> 
                                        <div class="col-sm-3">
                                            <?php echo $this->Form->input('father_lname_ll', array('label' => false, 'id' => 'father_lname_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                            <span id="father_lname_ll_error" class="form-error"><?php echo $errarr['father_lname_ll_error']; ?></span>
                                        </div>
                                    <?php } ?>
                                    <label for="father_lname_en" class="col-sm-2 control-label">Father's Last Name:-[ENGLISH]</label> 
                                    <div class="col-sm-3">
                                        <?php echo $this->Form->input('father_lname_en', array('label' => false, 'id' => 'father_lname_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                        <span id="father_lname_en_error" class="form-error"><?php echo $errarr['father_lname_en_error']; ?></span>
                                    </div>


                                </div>
                            </div>
                            <div  class="rowht">&nbsp;</div>
                            <div class="row">
                                <div class="form-group">
                                    <?php
                                    if (!empty($doc_lang) and $doc_lang != 'en') {
                                        ?>
                                        <label for="mother_fname_ll" class="col-sm-3 control-label">आईचे पहिले नाव:-</label>    
                                        <div class="col-sm-3">
                                            <?php echo $this->Form->input('mother_fname_ll', array('label' => false, 'id' => 'mother_fname_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                            <span id="mother_fname_ll_error" class="form-error"><?php echo $errarr['mother_fname_ll_error']; ?></span>
                                        </div>
                                    <?php } ?>
                                    <label for="mother_fname_en" class="col-sm-2 control-label">Mother's First Name:-[ENGLISH]</label>    
                                    <div class="col-sm-3">
                                        <?php echo $this->Form->input('mother_fname_en', array('label' => false, 'id' => 'mother_fname_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                        <span id="mother_fname_en_error" class="form-error"><?php echo $errarr['mother_fname_en_error']; ?></span>
                                    </div>

                                </div>
                            </div>
                            <div  class="rowht">&nbsp;</div>
                            <div class="row">
                                <div class="form-group">
                                    <?php
                                    if (!empty($doc_lang) and $doc_lang != 'en') {
                                        ?>
                                        <label for="mother_mname_ll" class="col-sm-3 control-label">आईचे मधले नाव:-</label>    
                                        <div class="col-sm-3">
                                            <?php echo $this->Form->input('mother_mname_ll', array('label' => false, 'id' => 'mother_mname_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                            <span id="mother_mname_ll_error" class="form-error"><?php echo $errarr['mother_mname_ll_error']; ?></span>
                                        </div>
                                    <?php } ?>
                                    <label for="mother_mname_en" class="col-sm-2 control-label">Mother's Middle Name:-[ENGLISH]</label>    
                                    <div class="col-sm-3">
                                        <?php echo $this->Form->input('mother_mname_en', array('label' => false, 'id' => 'mother_mname_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                        <span id="mother_mname_en_error" class="form-error"><?php echo $errarr['mother_mname_en_error']; ?></span>
                                    </div>

                                </div>
                            </div>
                            <div  class="rowht">&nbsp;</div>
                            <div class="row">
                                <div class="form-group">
                                    <?php
                                    if (!empty($doc_lang) and $doc_lang != 'en') {
                                        ?>
                                        <label for="mother_lname_ll" class="col-sm-3 control-label">आईचे आडनाव:-</label> 
                                        <div class="col-sm-3">
                                            <?php echo $this->Form->input('mother_lname_ll', array('label' => false, 'id' => 'mother_lname_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                            <span id="mother_lname_ll_error" class="form-error"><?php echo $errarr['mother_lname_ll_error']; ?></span>
                                        </div>
                                    <?php } ?>
                                    <label for="mother_lname_en" class="col-sm-2 control-label">Mother's Last Name:-[ENGLISH]</label> 
                                    <div class="col-sm-3">
                                        <?php echo $this->Form->input('mother_lname_en', array('label' => false, 'id' => 'mother_lname_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                        <span id="mother_lname_en_error" class="form-error"><?php echo $errarr['mother_lname_en_error']; ?></span>
                                    </div>

                                </div>
                            </div>
                        <?php } else { ?>
                            <div  class="rowht">&nbsp;</div>
                            <div class="row">
                                <div class="form-group">
                                    <?php
                                    if (!empty($doc_lang) and $doc_lang != 'en') {
                                        ?>
                                        <label for="father_full_name_ll" class="col-sm-3 control-label">वडिलांचे पूर्ण नाव:-</label> 
                                        <div class="col-sm-3">
                                            <?php echo $this->Form->input('father_full_name_ll', array('label' => false, 'id' => 'father_full_name_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                            <span id="father_full_name_ll_error" class="form-error"><?php echo $errarr['father_full_name_ll_error']; ?></span>
                                        </div>
                                    <?php } ?>
                                    <label for="father_full_name_en" class="col-sm-2 control-label">Father's Full Name:-[ENGLISH]</label> 
                                    <div class="col-sm-3">
                                        <?php echo $this->Form->input('father_full_name_en', array('label' => false, 'id' => 'father_full_name_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                        <span id="father_full_name_en_error" class="form-error"><?php echo $errarr['father_full_name_en_error']; ?></span>
                                    </div>

                                </div>
                            </div>
                            <div  class="rowht">&nbsp;</div>
                            <div class="row">
                                <div class="form-group">
                                    <?php
                                    if (!empty($doc_lang) and $doc_lang != 'en') {
                                        ?>
                                        <label for="mother_full_name_ll" class="col-sm-3 control-label">आईचे पूर्ण नाव:-</label> 
                                        <div class="col-sm-3">
                                            <?php echo $this->Form->input('mother_full_name_ll', array('label' => false, 'id' => 'mother_full_name_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                            <span id="mother_full_name_ll_error" class="form-error"><?php echo $errarr['mother_full_name_ll_error']; ?></span>
                                        </div>
                                    <?php } ?>
                                    <label for="mother_full_name_en" class="col-sm-2 control-label">Mother's Full Name:-[ENGLISH]</label> 
                                    <div class="col-sm-3">
                                        <?php echo $this->Form->input('mother_full_name_en', array('label' => false, 'id' => 'mother_full_name_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                        <span id="mother_full_name_en_error" class="form-error"><?php echo $errarr['mother_full_name_en_error']; ?></span>
                                    </div>

                                </div>
                            </div>
                        <?php } ?>

                        <div  class="rowht">&nbsp;</div>


                        <div class="row">
                            <div class="form-group">
                                <?php
                                if (!empty($doc_lang) and $doc_lang != 'en') {
                                    ?>
                                    <label for="idetification_mark1_ll" class="col-sm-3 control-label"><?php echo __('ओळख चिन्ह 1'); ?>:</label> 
                                    <div class="col-sm-3">
                                        <?php echo $this->Form->input('idetification_mark1_ll', array('label' => false, 'id' => 'idetification_mark1_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                        <span id="idetification_mark1_ll_error" class="form-error"><?php echo $errarr['idetification_mark1_ll_error']; ?></span>
                                    </div>
                                <?php } ?>
                                <label for="idetification_mark1_en" class="col-sm-2 control-label"><?php echo __('lblIdentificationmark') . '1'; ?>[ENGLISH]</label> 
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('idetification_mark1_en', array('label' => false, 'id' => 'idetification_mark1_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                    <span id="idetification_mark1_en_error" class="form-error"><?php echo $errarr['idetification_mark1_en_error']; ?></span>
                                </div>


                            </div>
                        </div>
                        <div  class="rowht">&nbsp;</div>
                        <div class="row">
                            <div class="form-group">
                                <?php
                                if (!empty($doc_lang) and $doc_lang != 'en') {
                                    ?>
                                    <label for="idetification_mark2_ll" class="col-sm-3 control-label"><?php echo __('ओळख चिन्ह 2'); ?>:</label> 
                                    <div class="col-sm-3">
                                        <?php echo $this->Form->input('idetification_mark2_ll', array('label' => false, 'id' => 'idetification_mark2_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                        <span id="idetification_mark2_ll_error" class="form-error"><?php echo $errarr['idetification_mark2_ll_error']; ?></span>
                                    </div>
                                <?php } ?>
                                <label for="idetification_mark2_en" class="col-sm-2 control-label"><?php echo __('lblIdentificationmark') . '2'; ?>[ENGLISH]</label> 
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('idetification_mark2_en', array('label' => false, 'id' => 'idetification_mark2_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                    <span id="idetification_mark2_en_error" class="form-error"><?php echo $errarr['idetification_mark2_en_error']; ?></span>
                                </div>


                            </div>
                        </div>
                        <div  class="rowht">&nbsp;</div>
                        <div class="row">
                            <div class="form-group">
                                <label for="dob" class="col-sm-3 control-label"><?php echo __('lbldob'); ?></label> 
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('dob', array('label' => false, 'id' => 'dob', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                    <!--<span id="dob_error" class="form-error"><?php echo $errarr['dob_error']; ?></span>-->
                                </div>

                                <label for="age" class="col-sm-2 control-label"><?php echo __('lblage'); ?>:</label> 
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('age', array('label' => false, 'id' => 'age', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                    <span id="age_error" class="form-error"><?php echo $errarr['age_error']; ?></span>
                                </div>
                            </div>
                        </div>
                        <div  class="rowht">&nbsp;</div>
                        <div class="row">
                            <div class="form-group">

                                <label for="uid" class="col-sm-3 control-label"><?php echo __('lbluid'); ?>:</label> 
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('uid', array('label' => false, 'id' => 'uid', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                    <span id="uid_error" class="form-error"><?php echo $errarr['uid_error']; ?></span>
                                </div>

                            </div>
                        </div>
                        <div  class="rowht">&nbsp;</div>
                        <div class="row" >
                            <div class="form-group">
                                <label for="identificationtype_id" class="col-sm-3 control-label"><?php echo __('lblidentity'); ?>:</label> 
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('identificationtype_id', array('label' => false, 'id' => 'identificationtype_id', 'class' => 'form-control input-sm', 'empty' => '--Select--', 'options' => array($identificatontype))); ?></div>
                                <span id="identificationtype_id_error" class="form-error"><?php echo $errarr['identificationtype_id_error']; ?></span>
                                <div id="identification">

                                    <label for="identificationtype_desc_en" class="col-sm-2 control-label"><?php echo __('lblidentitydetails') ?>:</label> 
                                    <div class="col-sm-3">
                                        <?php echo $this->Form->input('identificationtype_desc_en', array('label' => false, 'id' => 'identificationtype_desc_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                        <!--<span id="identificationtype_desc_en_error" class="form-error"><?php // echo $errarr['identificationtype_desc_en_error']; ?></span>-->
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div  class="rowht">&nbsp;</div>
                        <div class="row">
                            <div class="form-group">
                                <label for="dob" class="col-sm-3 control-label"><?php echo __('lblfid'); ?>:</label> 
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('fid', array('label' => false, 'id' => 'fid', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                    <span id="fid_error" class="form-error"><?php echo $errarr['fid_error']; ?></span>
                                </div>

                                <label for="age" class="col-sm-2 control-label"><?php echo __('lblpancardno'); ?>:</label> 
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('pan_no', array('label' => false, 'id' => 'pan_no', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                    <span id="pan_no_error" class="form-error"><?php echo $errarr['pan_no_error']; ?></span>
                                </div>
                            </div>
                        </div>


                        <div  class="rowht">&nbsp;</div>

                        <div class="row">
                            <div class="form-group">
                                <label for="gender_id" class="col-sm-3 control-label"><?php echo __('lblgender'); ?>:</label> 
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('gender_id', array('label' => false, 'id' => 'gender_id', 'class' => 'form-control input-sm', 'options' => array($gender), 'empty' => '--Select--')); ?>
                                    <span id="gender_id_error" class="form-error"><?php echo $errarr['gender_id_error']; ?></span>
                                </div>
                                <label for="occupation_id" class="col-sm-2 control-label"><?php echo __('lbloccupation'); ?>:</label> 
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('occupation_id', array('label' => false, 'id' => 'occupation_id', 'class' => 'form-control input-sm', 'options' => array($occupation), 'empty' => '--Select--')); ?>
                                    <span id="occupation_id_error" class="form-error"><?php echo $errarr['occupation_id_error']; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div  class="rowht">&nbsp;</div>
                    <div id="bank"> 
                        <div  class="rowht">&nbsp;</div>
                        <div class="row">
                            <div class="form-group">
                                <label for="bank_id" class="col-sm-3 control-label"><?php echo __('lblselectbank'); ?>:</label> 
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('bank_id', array('label' => false, 'id' => 'bank_id', 'class' => 'form-control input-sm', 'options' => array($bank_master), 'empty' => '--Select--')); ?>
                                    <!--<span id="bank_id_error" class="form-error"><?php echo $errarr['bank_id_error']; ?></span>-->
                                </div>

                            </div>
                        </div>
                    </div>
                    <div id="govern">
                        <div class="row">
                            <div class="form-group">
                                <label for="dob" class="col-sm-3 control-label"><?php echo __('lblorgname'); ?>:</label> 
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('org_name', array('label' => false, 'id' => 'org_name', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                    <span id="org_name_error" class="form-error"><?php echo $errarr['org_name_error']; ?></span>
                                </div>


                            </div>
                        </div>
                    </div>

                    <div id="company">
                        <div class="row">
                            <div class="form-group">
                                <label for="company_name" class="col-sm-3 control-label"><?php echo __('lblcmpname'); ?>:</label> 
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('company_name', array('label' => false, 'id' => 'company_name', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                    <!--<span id="company_name_error" class="form-error"><?php echo $errarr['company_name_error']; ?></span>-->
                                </div>
                                <label for="tan" class="col-sm-2 control-label"><?php echo __('lbltan'); ?>:</label> 
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('tan', array('label' => false, 'id' => 'tan', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                    <!--<span id="tan_error" class="form-error"><?php echo $errarr['tan_error']; ?></span>-->
                                </div>


                            </div>
                        </div>
                    </div>
                    <div  class="rowht">&nbsp;</div>
                    <div class="row">
                        <div class="form-group">
                            <label for="dob" class="col-sm-3 control-label"><?php echo __('lblemailid'); ?>:</label> 
                            <div class="col-sm-3">
                                <?php echo $this->Form->input('email_id', array('label' => false, 'id' => 'email_id', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                <span id="email_id_error" class="form-error"><?php echo $errarr['email_id_error']; ?></span>
                            </div>

                            <label for="age" class="col-sm-2 control-label"><?php echo __('lblmobileno'); ?>:</label> 
                            <div class="col-sm-3">
                                <?php echo $this->Form->input('mobile_no', array('label' => false, 'id' => 'mobile_no', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                <span id="mobile_no_error" class="form-error"><?php echo $errarr['mobile_no_error']; ?></span>
                            </div>
                        </div>
                    </div>
                    <div  class="rowht">&nbsp;</div>

                    <div class="box-body" id="address"></div>


                    <div  class="rowht">&nbsp;</div>
                    <div class="row">
                        <div class="form-group">
                            <label for="district_id" class="col-sm-3 control-label"><?php echo __('lbladmdistrict'); ?>:</label> 
                            <div class="col-sm-3">
                                <?php echo $this->Form->input('district_id', array('label' => false, 'id' => 'district_id', 'class' => 'form-control input-sm', 'options' => array($districtdata), 'empty' => '--Select--',)); ?>
                                <span id="district_id_error" class="form-error"><?php echo $errarr['district_id_error']; ?></span>
                            </div>
                            <label for="taluka_id" class="col-sm-2 control-label"><?php echo __('lbladmtaluka'); ?>:</label> 
                            <div class="col-sm-3">
                                <?php echo $this->Form->input('taluka_id', array('label' => false, 'id' => 'taluka_id', 'class' => 'form-control input-sm', 'options' => array(), 'empty' => '--Select--')); ?>
                                <span id="taluka_id_error" class="form-error"><?php echo $errarr['taluka_id_error']; ?></span>
                            </div>
                        </div>
                    </div>
                    <div  class="rowht">&nbsp;</div>
                    <div class="row">
                        <div class="form-group">
                            <label for="village_id" class="col-sm-3 control-label"><?php echo __('lbladmvillage'); ?>:</label> 
                            <div class="col-sm-3">
                                <?php echo $this->Form->input('village_id', array('label' => false, 'id' => 'village_id', 'class' => 'form-control input-sm', 'options' => array(), 'empty' => '--Select--')); ?>
                                <span id="village_id_error" class="form-error"><?php echo $errarr['village_id_error']; ?></span>
                            </div>

                        </div>
                    </div>
                    <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
                    <div class="box box-primary">
                        <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>

                        <div class="row center" >
                            <div class="col-sm-12">
                                <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
                                <button type="submit"  id="btnNext" name="btnNext" class="btn btn-info" onclick="javascript: return formsave();"><?php echo __('btnsave'); ?></button>
                                <button type="submit"  id="btnCancel" name="btnCancel" class="btn btn-info" onclick="javascript: return forcancel();"><?php echo __('btncancel'); ?></button>
                            </div>
                        </div>
                        <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
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
                            <th class="center"><?php echo __('lblpartytype'); ?></th>
                            <th class="center"><?php echo __('lblpartycategory'); ?> </th>
                            <th class="center width16"><?php echo __('lblaction'); ?></th>
                        </tr>  
                    </thead>

                    <tr>
                        <?php
                        foreach ($party_record as $party_record1):
//                    pr($propertydetailsrecord1);
//                    exit;
                            ?>

                            <td class="tblbigdata"><?php echo $party_record1[0]['party_full_name_'.$doc_lang]; ?></td>
                            <td class="tblbigdata"><?php echo $party_record1[0]['party_type_desc_'.$doc_lang]; ?></td>
                            <td class="tblbigdata"><?php echo $party_record1[0]['category_name_'.$doc_lang]; ?></td>
                            <td >
                                <input type="button" id="btnpren" name="btnpren" class="btn btn-info "  
                                       onclick="javascript: return ispresenter(('<?php echo $party_record1[0]['id']; ?>'));"                                  
                                       value="Is Presenter" />
                                <button id="btnupdate" name="btnupdate" class="btn btn-default "  onclick="javascript: return formupdate(
                                                ('<?php echo $party_record1[0]['party_id']; ?>'),
                                                ('<?php echo $party_record1[0]['salutation_id']; ?>'),
                                                ('<?php echo $party_record1[0]['party_fname_en']; ?>'),
                                                ('<?php echo $party_record1[0]['party_mname_en']; ?>'),
                                                ('<?php echo $party_record1[0]['party_lname_en']; ?>'),
                                                ('<?php echo $party_record1[0]['alias_name_en']; ?>'),
                                                ('<?php echo $party_record1[0]['father_fname_en']; ?>'),
                                                ('<?php echo $party_record1[0]['father_mname_en']; ?>'),
                                                ('<?php echo $party_record1[0]['father_lname_en']; ?>'),
                                                ('<?php echo $party_record1[0]['mother_fname_en']; ?>'),
                                                ('<?php echo $party_record1[0]['mother_mname_en']; ?>'),
                                                ('<?php echo $party_record1[0]['mother_lname_en']; ?>'),
                                                ('<?php echo $party_record1[0]['party_fname_ll']; ?>'),
                                                ('<?php echo $party_record1[0]['party_mname_ll']; ?>'),
                                                ('<?php echo $party_record1[0]['party_lname_ll']; ?>'),
                                                ('<?php echo $party_record1[0]['alias_name_ll']; ?>'),
                                                ('<?php echo $party_record1[0]['father_fname_ll']; ?>'),
                                                ('<?php echo $party_record1[0]['father_mname_ll']; ?>'),
                                                ('<?php echo $party_record1[0]['father_lname_ll']; ?>'),
                                                ('<?php echo $party_record1[0]['mother_fname_ll']; ?>'),
                                                ('<?php echo $party_record1[0]['mother_mname_ll']; ?>'),
                                                ('<?php echo $party_record1[0]['mother_lname_ll']; ?>'),
                                                ('<?php echo $party_record1[0]['dob']; ?>'),
                                                ('<?php echo $party_record1[0]['age']; ?>'),
                                                ('<?php echo $party_record1[0]['gender_id']; ?>'),
                                                ('<?php echo $party_record1[0]['occupation_id']; ?>'),
                                                ('<?php echo $party_record1[0]['party_type_id']; ?>'),
                                                ('<?php echo $party_record1[0]['party_catg_id']; ?>'),
                                                ('<?php echo $party_record1[0]['property_id']; ?>'),
                                                ('<?php echo $party_record1[0]['identificationtype_id']; ?>'),
                                                ('<?php echo $party_record1[0]['identificationtype_desc_en']; ?>'),
                                                ('<?php echo $party_record1[0]['uid']; ?>'),
                                                ('<?php echo $party_record1[0]['mobile_no']; ?>'),
                                                ('<?php echo $party_record1[0]['email_id']; ?>'),
                                                ('<?php echo $party_record1[0]['district_id']; ?>'),
                                                ('<?php echo $party_record1[0]['taluka_id']; ?>'),
                                                ('<?php echo $party_record1[0]['village_id']; ?>'),
                                                ('<?php echo $party_record1[0]['party_full_name_en']; ?>'),
                                                ('<?php echo $party_record1[0]['party_full_name_ll']; ?>'),
                                                ('<?php echo $party_record1[0]['father_full_name_en']; ?>'),
                                                ('<?php echo $party_record1[0]['father_full_name_ll']; ?>'),
                                                ('<?php echo $party_record1[0]['mother_full_name_en']; ?>'),
                                                ('<?php echo $party_record1[0]['mother_full_name_ll']; ?>'),
                                                ('<?php echo $party_record1[0]['exemption_id']; ?>'),
                                                ('<?php echo $party_record1[0]['idetification_mark1_en']; ?>'),
                                                ('<?php echo $party_record1[0]['idetification_mark1_ll']; ?>'),
                                                ('<?php echo $party_record1[0]['idetification_mark2_en']; ?>'),
                                                ('<?php echo $party_record1[0]['idetification_mark2_ll']; ?>'),
                                                ('<?php echo $party_record1[0]['pan_no']; ?>'),
                                                ('<?php echo $party_record1[0]['is_executer']; ?>'),
                                                ('<?php echo $party_record1[0]['bank_id']; ?>'),
                                                ('<?php echo $party_record1[0]['org_name']; ?>'),
                                                ('<?php echo $party_record1[0]['company_name']; ?>'),
                                                ('<?php echo $party_record1[0]['tan']; ?>')
                                                );">
                                    <span class="glyphicon glyphicon-pencil"></span></button>

                                <button id="btndelete" name="btndelete" class="btn btn-default "  
                                        onclick="javascript: return formdelete(('<?php echo $party_record1[0]['id']; ?>'));">
                                    <span class="glyphicon glyphicon-remove"></span></button>
                            </td>

                        </tr>


                    <?php endforeach;
                    ?>
                    <?php unset($party_record1); ?>


                </table> 
            </div>
        </div>

        <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
        <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
        <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
        <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
        <input type='hidden' value='' name='propertyid' id='propertyid'/>
    </div>
</div>

<?php echo $this->Form->end(); ?>                
<?php echo $this->Js->writeBuffer(); ?>

