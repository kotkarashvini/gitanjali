<?php
echo $this->Html->script('bootstrap-datepicker.min');
echo $this->Html->css('bootstrap-datepicker3.min');
?>

<script type="text/javascript">

    $(document).ready(function () {
        $('#partyentry').hide();
        $('#identification').hide();


        $.post('<?php echo $this->webroot; ?>Citizenentry/behavioral_patterns', {ref_id: 2, behavioral_id: 2}, function (data)
        {

            $("#address").html(data);
        });

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
<?php if (!empty($resp_record)) { ?>
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

            $.getJSON('<?php echo $this->webroot; ?>Property/taluka_change_event', {tal: tal}, function (data)
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

        });


    });



    function formsave() {
        document.getElementById("actiontype").value = '1';
        document.getElementById("hfaction").value = 'S';
        $('#csrftoken').val('<?php echo $this->Session->read('csrftoken'); ?>');

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
            dob, age, sex, occupation_id, party_type_id, party_catg_id, property_id, identificationtype_id, identificationtype_desc_en, uid, mobile, email, district_id, taluka_id, village_id, party_full_name_en, party_full_name_ll, father_full_name_en, father_full_name_ll,
            mother_full_name_en, mother_full_name_ll, exemption_id, idetification_mark1_en, idetification_mark1_ll, idetification_mark2_en, idetification_mark2_ll, pan_no, is_executer) {

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

        $.getJSON('<?php echo $this->webroot; ?>Property/taluka_change_event', {tal: taluka_id}, function (data1)
        {

            var sc = '<option>--select--</option>';
            $.each(data1.village, function (index1, val1) {
                if (index1 == village_id)
                {

                    sc += "<option value=" + index1 + " selected>" + val1 + "</option>";
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
        $.post('<?php echo $this->webroot; ?>Citizenentry/behavioral_patterns', {ref_id: 2, ref_val: id, behavioral_id: 2}, function (data)
        {


            $("#address").html(data);

        });

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
        $('#party_full_name_en').val(party_full_name_en);
        $('#party_full_name_ll').val(party_full_name_ll);

        $('#father_full_name_en').val(father_full_name_en);
        $('#father_full_name_ll').val(father_full_name_ll);

        $('#mother_full_name_en').val(mother_full_name_en);
        $('#mother_full_name_ll').val(mother_full_name_ll);
        $('#pan_no').val(pan_no);


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
        $('#sex').val(sex);
        $('#district_id').val(district_id);
        $('#village_id').val(village_id);
        $('#exemption_id').val(exemption_id);
        $('#occupation_id').val(occupation_id);
        $('#party_type_id').val(party_type_id);
        $('#category_name').val(party_catg_id);
        $('#mobile_no').val(mobile);
        $('#identificationtype_id').val(identificationtype_id);
        $('#identificationtype_desc_en').val(identificationtype_desc_en);
        $('#uid').val(uid);
        $('#email_id').val(email);
        $('#hfupdateflag').val('Y');
        $('#btnadd').html('Save');
        return false;
    }

    function formview(id)
    {
        var party = $("#party_type_id").val();
        var category = $("#party_catg_id").val();
        if (category == 1)
        {
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
                                }
                                else if (data3 == 'N')
                                {
                                    alert('Data not found on land record! You can enter details');
                                    $('#partyentry').show();
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
            $('#individual').hide();

        }

    }

    function setval(fname, mname, lname)
    {
        $('#partyentry').show();


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
?>

<?php echo $this->Form->create('party_entry', array('id' => 'party_entry', 'class' => 'form-vertical')); ?>

<div class="row">
    <div class="col-lg-12">
        <?php
        echo $this->element("NewCase/main_menu");
        echo $this->element("NewCase/property_menu");
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
                        <label for="" class="col-sm-2 control-label"><?php echo __('lbltokenno'); ?> :-<span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('', array('label' => false, 'id' => '', 'value' => $case_id, 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php // if (!empty($property)) { ?>
        <!--            <div class="row" id="propertylist">
                        <div class="col-sm-12">
                            <div class="box box-primary">
        
                            </div>
                        </div> 
                    </div> -->
        <div id="partyentry">
            <div class="box box-primary">
                <div class="box-body">
                    <?php for ($i = 1; $i <= $noofresp1; $i++) { ?>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-sm-3">
                                    <label><?php echo __('salutation'); ?> </label>   
                                    <?php echo $this->Form->input('salutation.', array('label' => false, 'id' => 'salutation', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $salutation))); ?>
                                </div>
                                <div class="col-md-3"> 
                                    <label>   <?php echo __('respondent_f_name1'); ?></label>
                                    <?php echo $this->Form->input('respondent_f_name.', array('label' => false, 'id' => 'respondent_f_name1', 'type' => 'text', 'class' => 'form-control', 'data-placement' => 'bottom', 'autocomplete' => 'off')); ?>
                                </div> 
                                <div class="col-md-3">
                                    <label>   <?php echo __('respondent_m_name'); ?></label>
                                    <?php echo $this->Form->input('respondent_m_name.', array('label' => false, 'id' => 'respondent_m_name1', 'type' => 'text', 'class' => 'form-control', 'data-placement' => 'bottom', 'autocomplete' => 'off')); ?>
                                </div> 
                                <div class="col-md-3"> 
                                    <label>   <?php echo __('respondent_l_name'); ?></label>
                                    <?php echo $this->Form->input('respondent_l_name.', array('label' => false, 'id' => 'respondent_l_name1', 'type' => 'text', 'class' => 'form-control', 'data-placement' => 'bottom', 'autocomplete' => 'off')); ?>
                                </div> 
                            </div>
                        </div>
                    <?php } ?>
                    <div  class="rowht">&nbsp;</div>

                    <div class="box box-primary">
                        <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>

                        <div class="row center" >
                            <div class="col-sm-12">
                                <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
                                <button type="button" id="btnNext" name="btnNext" class="btn btn-info"><?php echo __('btnnext'); ?></button>
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
                    <h3 class="box-title headbolder"><?php echo __('List Of Saved Parties'); ?></h3>
                </div>
                <table id="tableParty" class="table table-striped table-bordered table-condensed">  
                    <thead>  
                        <tr>  

                            <th class="center"><?php echo __('Respondent Name'); ?></th>
                            <th class="center"><?php echo __('Respondent Advocate name'); ?></th>
                            <!--<th class="center"><?php echo __('lblpartycategory'); ?> </th>-->
                            <th class="center width16"><?php echo __('lblaction'); ?></th>
                        </tr>  
                    </thead>

                    <tr>
                        <?php
                        foreach ($resp_record as $resp_record1):
//                    pr($propertydetailsrecord1);
//                    exit;
                            ?>

                            <td class="tblbigdata"><?php echo $resp_record1[0]['party_full_name_en']; ?></td>
                            <td class="tblbigdata"><?php echo $resp_record1[0]['party_type_desc_en']; ?></td>
                            <!--<td class="tblbigdata"><?php echo $resp_record1[0]['category_name_en']; ?></td>-->
                            <td>
                                <input type="button" id="btnpren" name="btnpren" class="btn btn-info "  
                                       onclick="javascript: return ispresenter(('<?php echo $resp_record1[0]['id']; ?>'));"                                  
                                       value="Is Presenter" />
                                <!--                                <button id="btnupdate" name="btnupdate" class="btn btn-default "  onclick="javascript: return formupdate(
                                                                                    ('<?php echo $resp_record1[0]['party_id']; ?>'),
                                                                                    ('<?php echo $resp_record1[0]['salutation_id']; ?>'),
                                                                                    ('<?php echo $resp_record1[0]['party_fname_en']; ?>'),
                                                                                    ('<?php echo $resp_record1[0]['party_mname_en']; ?>'),
                                                                                    ('<?php echo $resp_record1[0]['party_lname_en']; ?>'),
                                                                                    ('<?php echo $resp_record1[0]['alias_name_en']; ?>'),
                                                                                    ('<?php echo $resp_record1[0]['father_fname_en']; ?>'),
                                                                                    ('<?php echo $resp_record1[0]['father_mname_en']; ?>'),
                                                                                    ('<?php echo $resp_record1[0]['father_lname_en']; ?>'),
                                                                                   
                                                                                    ('<?php echo $resp_record1[0]['pan_no']; ?>'),
                                                                                    ('<?php echo $resp_record1[0]['is_executer']; ?>')
                                                                                    );">
                                                                    <span class="glyphicon glyphicon-pencil"></span></button>-->

                                <button id="btndelete" name="btndelete" class="btn btn-default "  
                                        onclick="javascript: return formdelete(('<?php echo $resp_record1[0]['id']; ?>'));">
                                    <span class="glyphicon glyphicon-remove"></span></button>
                            </td>
                        </tr>
                    <?php endforeach;
                    ?>
                    <?php unset($resp_record1); ?>
                </table> 
            </div>
        </div>
<!--        <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
        <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
        <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
        <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
        <input type='hidden' value='' name='propertyid' id='propertyid'/>-->
    </div>
</div>

<?php echo $this->Form->end(); ?>                
<?php echo $this->Js->writeBuffer(); ?>

