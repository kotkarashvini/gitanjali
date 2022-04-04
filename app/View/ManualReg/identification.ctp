<script type="text/javascript">
    $(document).ready(function () {



        if ($("#identificationtype_id option:selected").val() != '') {
            $('#identificationinfo').show();
        }
        else
        {
            $('#identificationinfo').hide();
        }
        if ($("#district_id option:selected").val() != '') {
            var dist = $("#district_id option:selected").val();
            $.getJSON("<?php echo $this->webroot; ?>Citizenentry/district_change_event", {dist: dist}, function (data)
            {

                var sc = '<option>--select--</option>';
                $.each(data.taluka, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#taluka_id").prop("disabled", false);
                $("#taluka_id option").remove();
                $("#taluka_id").append(sc);
            });
        }

        if ($("#taluka_id option:selected").val() != '') {

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
        }


        $("#identificationtype_desc_en").blur(function () {
            var type = $("#identificationtype_id option:selected").val();
            var desc = $("#identificationtype_desc_en").val();
            if (type != '')
            {
                $.getJSON('<?php echo $this->webroot; ?>Citizenentry/get_validation_rule', {type: type}, function (data)
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
                    }
                    else
                    {
                        $("#identificationtype_desc_en_error").text('');
                        return true;
                    }

                });
            }
        });

        if ($("#village_id option:selected").val() != '') {
            var village_id = $("#village_id option:selected").val();
            $.post('<?php echo $this->webroot; ?>Citizenentry/behavioral_patterns', {ref_id: 5, behavioral_id: 2, village_id: village_id}, function (data)
            {

                $("#address").html(data);
            });
        }


        if (document.getElementById('hfhidden1').value == 'Y') {

            $('#dividentification').slideDown(1000);
        }
        else {
            $('#dividentification').hide();
        }
        $('#tableidentification').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });


        $('#district_id').change(function () {

            var dist = $("#district_id option:selected").val();
            $.getJSON("<?php echo $this->webroot; ?>Citizenentry/district_change_event", {dist: dist}, function (data)
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
            $.post('<?php echo $this->webroot; ?>Citizenentry/behavioral_patterns', {village_id: $("#village_id").val(), ref_id: 5, behavioral_id: 2}, function (data)
            {

                $("#address").html(data);

            });

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





        $('#identificationtype_id').change(function () {
            var iden = $('#identificationtype_id').val();
            if (iden == '')
            {
                $('#identificationinfo').hide()

            }
            else
            {
                $('#identificationinfo').show()
            }
        });

        $('#party_type_id').change(function () {
            var party_type_id = $("#party_type_id").val();
            $.post('<?php echo $this->webroot; ?>Citizenentry/get_party_flag', {party_type_id: party_type_id}, function (data)
            {
//            alert(data);return false;
                $('#party_type_flag').val(data);


            });

        });

        $("#identifire_type").change(function ()
        {
            var type = $("#identifire_type").val();
            var token = '<?php echo $Selectedtoken; ?>';


            if (type == 2)
            {
<?php if (isset($sro) && !empty($sro)) { ?>
                    $('#salutation').val('<?php echo $sro[0][0]['salutation']; ?>');
                    $('#fname_en').val('<?php echo $sro[0][0]['emp_fname'] ?>');
                    $('#mname_en').val('<?php echo $sro[0][0]['emp_mname'] ?>');
                    $('#lname_en').val('<?php echo $sro[0][0]['emp_lname'] ?>');
                    $('#mobile_no').val('<?php echo $sro[0][0]['mobile_no'] ?>');
                    $('#email_id').val('<?php echo $sro[0][0]['email_id'] ?>');
                    $('#pincode').val('<?php echo $sro[0][0]['pincode'] ?>');
                    $('#uid_no').val('<?php echo $sro[0][0]['uid_no'] ?>');
                    $('#district_id').val('<?php echo $sro[0][0]['dist_id'] ?>');
                    $.getJSON("<?php echo $this->webroot; ?>districtchangeevent", {dist: '<?php echo $sro[0][0]['dist_id'] ?>'}, function (data)
                    {
                        var sc = '<option>--select--</option>';
                        $.each(data.taluka, function (index, val) {
                            if (index == '<?php echo $sro[0][0]['taluka_id'] ?>') {
                                sc += "<option value=" + index + " selected>" + val + "</option>";
                            }
                            else
                            {
                                sc += "<option value=" + index + " >" + val + "</option>";
                            }
                        });
                        $("#taluka_id").prop("disabled", false);
                        $("#taluka_id option").remove();
                        $("#taluka_id").append(sc);

                    });
                    $('#taluka_id').val('<?php echo $sro[0][0]['taluka_id'] ?>');
                    $('#identification_full_name_en').val('<?php echo $sro[0][0]['full_name'] ?>');
<?php } ?>
            } else
            {

                $('#fname_en').val('');
                $('#mname_en').val('');
                $('#lname_en').val('');
                $('#mobile_no').val('');
                $('#email_id').val('');
                $('#pincode').val('');
                $('#uid_no').val('');
                $('#identification_full_name_en').val('');

            }
        });
<?php
if (isset($checkuid_entry) && !empty($checkuid_entry)) {
    $p1flag = 'N';
    $p2flag = 'N';
    foreach ($checkuid_entry as $entry) {
        if (!empty($entry['party_entry']['uid'])) {
            if ($entry['party_entry']['party_type_id'] == '1') {
                $p1flag = 'Y';
            } elseif ($entry['party_entry']['party_type_id'] == '2') {
                $p2flag = 'Y';
            }
        }
    }
    if ($p1flag == 'Y' || $p1flag == 'Y') {
        ?>
                $(':input').change(function () {
        <?php if ($p1flag == 'Y') { ?>
                        if ($('#party_type_id').val() === '1') {
                            alert("UID is Given . No Need Identifire of this type");
                        }
        <?php } ?>

        <?php if ($p2flag == 'Y') { ?>
                        if ($('#party_type_id').val() === '2') {
                            alert("UID is Given . No Need Identifire  of this type");
                        }
        <?php } ?>
                    return false;
                });
        <?php
    }
}
?>
    });

    function formadd() {
        document.getElementById("actiontype").value = '1';
        document.getElementById("hfaction").value = 'S';
        $('#csrftoken').val('<?php echo $this->Session->read('csrftoken'); ?>');


//        $.post('<?php //echo $this->webroot;              ?>Citizenentry/is_uid_compulsary', {'id':1}, function (data)
//        {
//            if(data=='Y')
//            {
//                var uid_no=$("#uid_no").val();
//                if(uid_no=='')
//                {
//                    alert('Please enter UID number');
//                    $("#uid_no").focus();
//                    return false;
//                }else
//                {
//                  var  a='Y';
//                }
//            }
//            else
//                {
//                  var  a='Y';
//                }
//            
//        });
//        $.post('<?php // echo $this->webroot;              ?>Citizenentry/is_identity_compulsary', {'id':1}, function (data1)
//        {
//            if(data1=='Y')
//            {
//                var identificationtype_id=$("#identificationtype_id").val();
//                if(identificationtype_id=='')
//                {
//                    alert('Please select identification details');
//                    $("#identificationtype_id").focus();
//                    return false;
//                }else
//                {var b='Y';
//                }
//            }
//            else
//            {
//               var b='Y';
//            }
//                
//            
//        });
//        
//      if(a=='Y' && b=='Y'){     
//        
//             $("#identification").submit();
//         }


    }



    function forcancel() {
        document.getElementById("actiontype").value = '2';
    }

    function formupdate(id, token_no, salutation, party_type_id, party_type_flag, fname, mname, lname, identificationname_en, aliasname,
            pincode, uid_no, identificationtype_id, identificationtype_desc,
            fname1, mname1, lname1, identificationname_ll, aliasname1,
            dob, age, gender_id, mobile_no, email_id, occupation_id, district_id, taluka_id, village_id,
            identificationtype_desc1, identifire_type, idetification_mark1_en, idetification_mark1_ll, idetification_mark2_en, idetification_mark2_ll, pan_no)

    {

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
                if (index1 == taluka_id)
                {
                    $.post('<?php echo $this->webroot; ?>Citizenentry/behavioral_patterns', {village_id: index1, ref_id: 5, ref_val: id, behavioral_id: 2}, function (data)
                    {

                        $("#address").html(data);

                    });

                    sc += "<option value=" + index1 + " selected>" + val1 + "</option>";
                } else
                {
                    sc += "<option value=" + index1 + ">" + val1 + "</option>";
                }
            });

            $("#village_id option").remove();
            $("#village_id").append(sc);
            $('#village_id ').val(village_id);
        });
//        alert(village_id);




        if (identificationtype_id == '')
        {
            $('#identificationinfo').hide()

        }
        else
        {
            $('#identificationinfo').show()
        }

        $('#pan_no').val(pan_no);
        $('#party_type_id').val(party_type_id);
        $('#party_type_flag').val(party_type_flag);
        $('#idetification_mark1_en').val(idetification_mark1_en);
        $('#idetification_mark1_ll').val(idetification_mark1_ll);
        $('#idetification_mark2_en').val(idetification_mark2_en);
        $('#idetification_mark2_ll').val(idetification_mark2_ll);
        $('#hfid').val(id);
        $('#identifire_type').val(identifire_type);
        $('#token_no').val(token_no);
        $('#salutation').val(salutation);
        $('#fname_en').val(fname);
        $('#mname_en').val(mname);
        $('#lname_en').val(lname);
        $('#identification_full_name_en').val(identificationname_en);
        $('#aliasname_en').val(aliasname);

        $('#pincode').val(pincode);
        $('#uid_no').val(uid_no);
        $('#identificationtype_id').val(identificationtype_id);
        $('#identificationtype_desc_en').val(identificationtype_desc);
        $('#fname_ll').val(fname1);
        $('#mname_ll').val(mname1);
        $('#lname_ll').val(lname1);
        $('#identification_full_name_ll').val(identificationname_ll);
        $('#aliasname_ll').val(aliasname1);

        $('#dob').val(dob);
        $('#age').val(age);
        $('#gender_id').val(gender_id);
        //   $('#pan_no').val(pan_no);
        $('#mobile_no').val(mobile_no);
        $('#email_id').val(email_id);
        $('#occupation_id').val(occupation_id);
        $('#district_id').val(district_id);
        $('#taluka_id').val(taluka_id);
//        $('#village_id ').val(village_id);

        $('#identificationtype_desc_ll').val(identificationtype_desc1);
        $('#hfupdateflag').val('Y');
        $('#btnadd').html('Save');
        return false;
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
</script>

<?php
echo $this->Html->css('popup');
$tokenval = $this->Session->read("Selectedtoken");
?>
<?php echo $this->Form->create('identification', array('id' => 'identification', 'class' => 'form-vertical')); ?>

<?php
echo $this->element("Registration/main_menu");
?>
<?php
echo $this->element("Citizenentry/main_menu");
echo $this->element("Citizenentry/property_menu");
$doc_lang = $this->Session->read('doc_lang');
?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblidentification'); ?></h3></center>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <p style="color: red;"><b><?php echo __('lblnote'); ?>1&nbsp;</b><?php echo __('lblengdatarequired'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <label for="token_no" class="col-sm-2 control-label"><?php echo __('lbltokenno'); ?> :-<span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('token_no', array('label' => false, 'id' => 'token_no', 'value' => $Selectedtoken, 'class' => 'form-control input-sm', 'type' => 'text', 'readonly' => 'readonly')) ?>
                        </div>
                    </div> 
                </div>
                 <div  class="rowht"></div>
                <div class="row">
            <div class="form-group">
                <label for="party_type_id" class="col-sm-2 control-label"><?php echo __('lblpartytype'); ?></label> 
                <div class="col-sm-3">
                    <?php echo $this->Form->input('party_type_id', array('label' => false, 'id' => 'party_type_id', 'class' => 'form-control input-sm', 'options' => array($partytype))); ?>
                </div>
            </div>
        </div>
            </div>
        </div>
       
        

        <?php echo $this->Form->input('party_type_flag', array('label' => false, 'id' => 'party_type_flag', 'class' => 'form-control input-sm', 'type' => 'hidden')); ?>
        <?php if ($this->Session->read("user_role_id") == '999901' || $this->Session->read("user_role_id") == '999902' || $this->Session->read("user_role_id") == '999903') { ?>
            <div  class="rowht"></div>
            <div class="row">
                <div class="form-group">
                    <label for="Identifire" class="col-sm-2 control-label" ><?php echo __('lblselectidentificationtype'); ?>:</label> 
                    <div class="col-sm-2"><?php echo $this->Form->input('identifire_type', array('label' => false, 'id' => 'identifire_type', 'class' => 'form-control input-sm', 'empty' => '--select--', 'options' => array($identifire_type))); ?></div>
                </div>
            </div>

        <?php } else { ?>
            <?php echo $this->Form->input('identifire_type', array('label' => false, 'class' => 'form-control input-sm', 'type' => 'hidden', 'value' => 1)); ?>
        <?php } ?>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title headbolder"><?php echo __('lblpersinfo'); ?></h3>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="salutation" class="col-sm-3 control-label" ><?php echo __('lblSalutation'); ?>:</label> 
                        <div class="col-sm-3"><?php echo $this->Form->input('salutation', array('label' => false, 'id' => 'salutation', 'class' => 'form-control input-sm', 'options' => array($salutation))); ?></div>
                    </div>
                </div>
                <?php if ($name_format == 'Y') { ?>
                    <div  class="rowht">&nbsp;</div>
                    <div class="row">
                        <div class="form-group">
                            <?php
                            if (!empty($doc_lang) and $doc_lang != 'en') {
                                ?>
                                <label for="fname_ll" class="control-label col-sm-3" ><?php echo __('lblidentificationfirstname'); ?>  :</label>
                                <div class="col-sm-3"><?php echo $this->Form->input('fname_ll', array('label' => false, 'id' => 'fname_ll', 'class' => 'form-control input-sm', 'type' => 'text')); ?>
                                    <span id="fname_ll_error" class="form-error"><?php echo $errarr['fname_ll_error']; ?></span>
                                </div>
                            <?php } ?>
                            <label for="fname_en" class="control-label col-sm-3" ><?php echo __('lblidentificationfirstname'); ?> [ENGLISH]: </label>
                            <div class="col-sm-3"><?php echo $this->Form->input('fname_en', array('label' => false, 'id' => 'fname_en', 'class' => 'form-control input-sm', 'type' => 'text')); ?>
                                <span id="fname_en_error" class="form-error"><?php echo $errarr['fname_en_error']; ?></span>
                            </div>

                        </div>
                    </div>
                    <div  class="rowht">&nbsp;</div>
                    <div class="row">
                        <div class="form-group">
                            <?php
                            if (!empty($doc_lang) and $doc_lang != 'en') {
                                ?>
                                <label for="mname_ll" class="control-label col-sm-3" > <?php echo __('lblidentificationmiddlename'); ?>: </label>
                                <div class="col-sm-3" ><?php echo $this->Form->input('mname_ll', array('label' => false, 'id' => 'mname_ll', 'class' => 'form-control input-sm', 'type' => 'text')); ?>
                                    <span id="mname_ll_error" class="form-error"><?php echo $errarr['mname_ll_error']; ?></span>
                                </div>
                            <?php } ?>
                            <label for="mname_en" class="control-label col-sm-3" ><?php echo __('lblidentificationmiddlename'); ?>[ENGLISH]: </label>
                            <div class="col-sm-3" ><?php echo $this->Form->input('mname_en', array('label' => false, 'id' => 'mname_en', 'class' => 'form-control input-sm', 'type' => 'text')); ?>
                                <span id="mname_en_error" class="form-error"><?php echo $errarr['mname_en_error']; ?></span>
                            </div>

                        </div>
                    </div>
                    <div  class="rowht">&nbsp;</div>
                    <div class="row">
                        <div class="form-group">
                            <?php
                            if (!empty($doc_lang) and $doc_lang != 'en') {
                                ?>
                                <label for="lname_ll" class="control-label col-sm-3" ><?php echo __('lblidentificationlastname'); ?> : </label>
                                <div class="col-sm-3" ><?php echo $this->Form->input('lname_ll', array('label' => false, 'id' => 'lname_ll', 'class' => 'form-control input-sm', 'type' => 'text')); ?>
                                    <span id="lname_ll_error" class="form-error"><?php echo $errarr['lname_ll_error']; ?></span>
                                </div>
                            <?php } ?>
                            <label for="lname_en" class="control-label col-sm-3" ><?php echo __('lblidentificationlastname'); ?>[ENGLISH] : </label>
                            <div class="col-sm-3" ><?php echo $this->Form->input('lname_en', array('label' => false, 'id' => 'lname_en', 'class' => 'form-control input-sm', 'type' => 'text')); ?>
                                <span id="lname_en_error" class="form-error"><?php echo $errarr['lname_en_error']; ?></span>
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
                                <label for="identification_full_name_ll" class="col-sm-3 control-label"><?php echo __('lblidentificationfullname'); ?> :-</label> 
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('identification_full_name_ll', array('label' => false, 'id' => 'identification_full_name_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                    <span id="identification_full_name_ll_error" class="form-error"><?php echo $errarr['identification_full_name_ll_error']; ?></span>
                                </div>
                            <?php } ?>
                            <label for="identification_full_name_en" class="col-sm-3 control-label"><?php echo __('lblidentificationfullname'); ?>[ENGLISH]:-</label> 
                            <div class="col-sm-3">
                                <?php echo $this->Form->input('identification_full_name_en', array('label' => false, 'id' => 'identification_full_name_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                <span id="identification_full_name_en_error" class="form-error"><?php echo $errarr['identification_full_name_en_error']; ?></span>
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
                            <label for="aliasname_ll" class="control-label col-sm-3" ><?php echo __('lblaliasname'); ?> : </label>
                            <div class="col-sm-3"><?php echo $this->Form->input('aliasname_ll', array('label' => false, 'id' => 'aliasname_ll', 'class' => 'form-control input-sm', 'type' => 'text')); ?></div>
                            <span id="aliasname_ll_error" class="form-error"><?php echo $errarr['aliasname_ll_error']; ?></span>
                        <?php } ?>
                        <label for="aliasname_en" class="control-label col-sm-3" ><?php echo __('lblaliasname'); ?> [English]: </label>
                        <div class="col-sm-3" ><?php echo $this->Form->input('aliasname_en', array('label' => false, 'id' => 'aliasname_en', 'class' => 'form-control input-sm', 'type' => 'text')); ?>
                            <span id="aliasname_en_error" class="form-error"><?php echo $errarr['aliasname_en_error']; ?></span>
                        </div>

                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="dob" class="col-sm-3 control-label"><?php echo __('lbldob'); ?>[English]</label> 
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('dob', array('label' => false, 'id' => 'dob', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                       <!--<span id="dob_error" class="form-error"><?php // echo $errarr['dob_error'];    ?></span>-->
                        </div>

                        <label for="age" class="col-sm-3 control-label"><?php echo __('lblage'); ?>:[English]</label> 
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('age', array('label' => false, 'id' => 'age', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="age_error" class="form-error"><?php echo $errarr['age_error']; ?></span>
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
                        <label for="occupation_id" class="col-sm-3 control-label"><?php echo __('lbloccupation'); ?>:</label> 
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('occupation_id', array('label' => false, 'id' => 'occupation_id', 'class' => 'form-control input-sm', 'options' => array($occupation), 'empty' => '--Select--')); ?>
                        <!--<span id="occupation_id_error" class="form-error"><?php // echo $errarr['occupation_id_error'];    ?></span>-->
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

                        <label for="age" class="col-sm-3 control-label"><?php echo __('lblmobileno'); ?>:</label> 
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('mobile_no', array('label' => false, 'id' => 'mobile_no', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="mobile_no_error" class="form-error"><?php echo $errarr['mobile_no_error']; ?></span>
                        </div>
                    </div>
                </div>  
                <!--                    <div  class="rowht">&nbsp;</div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="pan_no" class="col-sm-3 control-label"><?php //echo __('lblpancardno');              ?>:</label> 
                                            <div class="col-sm-3">
                <?php //echo $this->Form->input('pan_no', array('label' => false, 'id' => 'pan_no', 'class' => 'form-control input-sm', 'type' => 'text'))  ?>
                                            </div>
                                        </div>
                                    </div>  -->

            </div>
        </div>

        <div class="box box-primary">
            <!--                <div class="box-header with-border">
                                <center><h3 class="box-title headbolder"><?php //echo __('lblAddress');         ?></h3></center>
                            </div>-->
            <div class="box-body">

                <div class="box-body" id="address"></div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="district_id" class="col-sm-3 control-label"><?php echo __('lbladmdistrict'); ?>:</label> 
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('district_id', array('label' => false, 'id' => 'district_id', 'class' => 'form-control input-sm', 'options' => array($districtdata), 'empty' => '--Select--')); ?>
                            <span id="district_id_error" class="form-error"><?php echo $errarr['district_id_error']; ?></span>
                        </div>
                        <label for="taluka_id" class="col-sm-3 control-label"><?php echo __('lbladmtaluka'); ?>:</label> 
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
            </div>
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title headbolder"><?php echo __('lblidentityinfo'); ?></h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="uid_no" class="control-label col-sm-3" ><?php echo __('lbluid'); ?>  </label>
                        <div class="col-sm-3" style="alignment-adjust: text-after-edge" ><?php echo $this->Form->input('uid_no', array('label' => false, 'id' => 'uid_no', 'class' => 'form-control input-sm', 'type' => 'text')); ?>
                            <span id="uid_no_error" class="form-error"><?php echo $errarr['uid_no_error']; ?></span>
                        </div>
                        <label for="identificationtype_id" class="control-label col-sm-3" ><?php echo __('lblidentity'); ?> : </label>
                        <div class="col-sm-3"><?php echo $this->Form->input('identificationtype_id', array('label' => false, 'id' => 'identificationtype_id', 'class' => 'form-control input-sm', 'empty' => '--Select--', 'options' => array($identificatontype))); ?>
                        <!--<span id="identificationtype_id_error" class="form-error"><?php // echo $errarr['identificationtype_id_error'];    ?></span>-->
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row" id="identificationinfo">
                    <div class="form-group">
                        <?php
                        if (!empty($doc_lang) and $doc_lang != 'en') {
                            ?>
                            <label for="identificationtype_desc_ll" class="control-label col-sm-3" ><?php echo __('lblidentityinfo'); ?>: </label>
                            <div class="col-sm-3" ><?php echo $this->Form->input('identificationtype_desc_ll', array('label' => false, 'id' => 'identificationtype_desc_ll', 'class' => 'form-control input-sm', 'type' => 'text')); ?>
                                <span id="identificationtype_desc_ll_error" class="form-error"><?php echo $errarr['identificationtype_desc_ll_error']; ?></span>
                            </div>
                        <?php } ?>
                        <label for="identificationtype_desc_en" class="control-label col-sm-3" ><?php echo __('lblidentityinfo'); ?> : </label>
                        <div class="col-sm-3" ><?php echo $this->Form->input('identificationtype_desc_en', array('label' => false, 'id' => 'identificationtype_desc_en', 'class' => 'form-control input-sm', 'type' => 'text')); ?>
                            <span id="identificationtype_desc_en_error" class="form-error"><?php echo $errarr['identificationtype_desc_en_error']; ?></span>
                        </div>

                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="pan_no" class="control-label col-sm-3" ><?php echo __('lblpancardno'); ?>  </label>
                        <div class="col-sm-3" style="alignment-adjust: text-after-edge" ><?php echo $this->Form->input('pan_no', array('label' => false, 'id' => 'pan_no', 'class' => 'form-control input-sm', 'type' => 'text')); ?>
                            <span id="pan_no_error" class="form-error"><?php echo $errarr['pan_no_error']; ?></span>
                        </div>

                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <?php
                        if (!empty($doc_lang) and $doc_lang != 'en') {
                            ?>
                            <label for="idetification_mark1_ll" class="col-sm-3 control-label"><?php echo __('lblidentificationmark1'); ?>:</label> 
                            <div class="col-sm-3">
                                <?php echo $this->Form->input('idetification_mark1_ll', array('label' => false, 'id' => 'idetification_mark1_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                <span id="idetification_mark1_ll_error" class="form-error"><?php echo $errarr['idetification_mark1_ll_error']; ?></span>
                            </div>
                        <?php } ?>
                        <label for="idetification_mark1_en" class="col-sm-3 control-label"><?php echo __('lblIdentificationmark') . '1'; ?></label> 
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
                            <label for="idetification_mark2_ll" class="col-sm-3 control-label"><?php echo __('lblidentificationmark2'); ?>:</label> 
                            <div class="col-sm-3">
                                <?php echo $this->Form->input('idetification_mark2_ll', array('label' => false, 'id' => 'idetification_mark2_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                <span id="idetification_mark2_ll_error" class="form-error"><?php echo $errarr['idetification_mark2_ll_error']; ?></span>
                            </div>
                        <?php } ?>
                        <label for="idetification_mark2_en" class="col-sm-3 control-label"><?php echo __('lblIdentificationmark') . '2'; ?></label> 
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('idetification_mark2_en', array('label' => false, 'id' => 'idetification_mark2_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="idetification_mark2_en_error" class="form-error"><?php echo $errarr['idetification_mark2_en_error']; ?></span>
                        </div>


                    </div>
                </div>


            </div>
        </div>



        <div class="box box-primary">
            <div class="box-body">
                <div  class="rowht">&nbsp;</div>
                <div class="row center">
                    <div class="form-group">
                        <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
                        <button type="submit" id="btnCancel" name="btnCancel" class="btn btn-info" onclick="javascript: return formadd();"><?php echo __('btnsave'); ?></button>
                        <button type="submit" id="btnNext" name="btnNext" class="btn btn-info" onclick="javascript: return forcancel();"><?php echo __('btncancel'); ?></button>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-primary" id="dividentification">
            <div class="box-body">
                <div class="table-responsive">
                    <table id="tableidentification" class="table table-striped table-bordered table-hover">  
                        <thead >  
                            <tr>  
                                <th class="center"><?php echo __('lblname'); ?></th>  
                                <th class="center"><?php echo __('lblidentificationtype'); ?></th>  
                                <th class="center"><?php echo __('lbluid'); ?></th>
                                <th class="center width10"><?php echo __('lblaction'); ?></th>
                            </tr>  
                        </thead>

                        <?php for ($i = 0; $i < count($identification); $i++) { ?>
                            <tr>
                                <td class="tblbigdata"><?php echo $identification[$i][0]['identification_full_name_en']; ?></td> 
                                <td class="tblbigdata"><?php
                                    if ($identification[$i][0]['identifire_type'] == 2) {
                                        echo 'SRO';
                                    } else {
                                        echo 'Other';
                                    }
                                    ?></td>
                                <td class="tblbigdata"><?php echo $identification[$i][0]['uid_no']; ?></td>
                                <td>

                                    <button id="btnupdate" name="btnupdate" class="btn btn-default "  onclick="javascript: return formupdate(
                                                    ('<?php echo $identification[$i][0]['id']; ?>'),
                                                    ('<?php echo $identification[$i][0]['token_no']; ?>'),
                                                    ('<?php echo $identification[$i][0]['salutation']; ?>'),
                                                    ('<?php echo $identification[$i][0]['party_type_id']; ?>'),
                                                    ('<?php echo $identification[$i][0]['party_type_flag']; ?>'),
                                                    ('<?php echo $identification[$i][0]['fname_en']; ?>'),
                                                    ('<?php echo $identification[$i][0]['mname_en']; ?>'),
                                                    ('<?php echo $identification[$i][0]['lname_en']; ?>'),
                                                    ('<?php echo $identification[$i][0]['identification_full_name_en']; ?>'),
                                                    ('<?php echo $identification[$i][0]['aliasname_en']; ?>'),
                                                    ('<?php echo $identification[$i][0]['pincode']; ?>'),
                                                    ('<?php echo $identification[$i][0]['uid_no']; ?>'),
                                                    ('<?php echo $identification[$i][0]['identificationtype_id']; ?>'),
                                                    ('<?php echo $identification[$i][0]['identificationtype_desc_en']; ?>'),
                                                    ('<?php echo $identification[$i][0]['fname_ll']; ?>'),
                                                    ('<?php echo $identification[$i][0]['mname_ll']; ?>'),
                                                    ('<?php echo $identification[$i][0]['lname_ll']; ?>'),
                                                    ('<?php echo $identification[$i][0]['identification_full_name_ll']; ?>'),
                                                    ('<?php echo $identification[$i][0]['aliasname_ll']; ?>'),
                                                    ('<?php echo $identification[$i][0]['dob']; ?>'),
                                                    ('<?php echo $identification[$i][0]['age']; ?>'),
                                                    ('<?php echo $identification[$i][0]['gender_id']; ?>'),
                                                    ('<?php echo $identification[$i][0]['mobile_no']; ?>'),
                                                    ('<?php echo $identification[$i][0]['email_id']; ?>'),
                                                    ('<?php echo $identification[$i][0]['occupation_id']; ?>'),
                                                    ('<?php echo $identification[$i][0]['district_id']; ?>'),
                                                    ('<?php echo $identification[$i][0]['taluka_id']; ?>'),
                                                    ('<?php echo $identification[$i][0]['village_id']; ?>'),
                                                    ('<?php echo $identification[$i][0]['identificationtype_desc_ll']; ?>'),
                                                    ('<?php echo $identification[$i][0]['identifire_type']; ?>'),
                                                    ('<?php echo $identification[$i][0]['idetification_mark1_en']; ?>'),
                                                    ('<?php echo $identification[$i][0]['idetification_mark1_ll']; ?>'),
                                                    ('<?php echo $identification[$i][0]['idetification_mark2_en']; ?>'),
                                                    ('<?php echo $identification[$i][0]['idetification_mark2_ll']; ?>'),
                                                    ('<?php echo $identification[$i][0]['pan_no']; ?>')
                                                    );">
                                        <span class="glyphicon glyphicon-pencil"></span></button>


                                    <button id="btndelete" name="btndelete" class="btn btn-default "  onclick="javascript: return formdelete(('<?php echo $identification[$i][0]['id']; ?>'));">
                                        <span class="glyphicon glyphicon-remove"></span></button>
                                </td>
                            </tr>
                        <?php } ?>
                    </table> 
                    <?php if (!empty($identification)) { ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
                </div>
            </div>
        </div>

    </div>
</div>

<input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
<input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
<input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>



<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>

