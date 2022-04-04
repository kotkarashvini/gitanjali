<script type="text/javascript">
    $(document).ready(function () {
        if ($("#identificationtype_id option:selected").val() != '') {
            $('#identification').show();
        }
        else
        {
            $('#identification').hide();
        }

        if ($("#district_id option:selected").val() != '') {
            var dist = $("#district_id option:selected").val();
            $.post("<?php echo $this->webroot; ?>Citizenentry/district_change_event", {dist: dist}, function (data)
            {

                var sc = '<option>--select--</option>';
                $.each(data.taluka, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#taluka_id").prop("disabled", false);
                $("#taluka_id option").remove();
                $("#taluka_id").append(sc);
            },'json');
        }

        if ($("#taluka_id option:selected").val() != '') {

            var tal = $("#taluka_id option:selected").val();
            $.post('<?php echo $this->webroot; ?>Citizenentry/taluka_change_event', {tal: tal}, function (data)
            {

                var sc = '<option>--select--</option>';
                $.each(data.village, function (index, val) {

                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                // $("#corp_id").prop("disabled", false);
                // $("#valutation_zone_id").prop("disabled", false);

                $("#village_id option").remove();
                $("#village_id").append(sc);
            },'json');
        }


        $("#identificationtype_desc_en").blur(function () {
            var type = $("#identificationtype_id option:selected").val();
            var desc = $("#identificationtype_desc_en").val();
            if (type != '')
            {
                $.post('<?php echo $this->webroot; ?>Citizenentry/get_validation_rule', {type: type}, function (data)
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

                },'json');
            }
        });

        if ($("#village_id option:selected").val() != '') {
            var village_id = $("#village_id option:selected").val();
            $.post('<?php echo $this->webroot; ?>Citizenentry/behavioral_patterns', {ref_id: 4, behavioral_id: 2, village_id: village_id}, function (data)
            {

                $("#address").html(data);
            });
        }
        $("#ll_fdate").datepicker({
            yearRange: "-20:+100",
            changeMonth: true,
            changeYear: true,
            dateFormat: "d-M-y"
        });
        $("#ll_tdate").datepicker({
            yearRange: "-20:+100",
            changeMonth: true,
            changeYear: true,
            dateFormat: "d-M-y"
        });

        if (document.getElementById('hfhidden1').value == 'Y') {
            $('#divwitness').slideDown(1000);
        }
        else {
            $('#divwitness').hide();
        }
        $('#tablewitness').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });

        $("#ll_tdate").change(function () {
            var frmDate = new Date($("#ll_fdate").val());
            var toDate = new Date($("#ll_tdate").val());
            var frmYear, frmMonth = frmDate.getMonth() + 1;
            var toYear, toMonth = toDate.getMonth() + 1;
            if ((frmYear = frmDate.getFullYear()) < (toYear = toDate.getFullYear())) {
                toMonth += (toYear - frmYear) * 12;
            }
            var diffMonths = toMonth - frmMonth;
            if (frmDate.getDate() > toDate.getDate())
                diffMonths--;
//            alert("There are " + diffMonths + " months between " + frmDate + " and " + toDate);
            $('#ll_month').val(diffMonths);
        })


        $('#district_id').change(function () {

            var dist = $("#district_id option:selected").val();
            $.post("<?php echo $this->webroot; ?>Citizenentry/district_change_event", {dist: dist}, function (data)
            {

                var sc = '<option>--select--</option>';
                $.each(data.taluka, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#taluka_id").prop("disabled", false);
                $("#taluka_id option").remove();
                $("#taluka_id").append(sc);

            },'json');
        });
        $('#taluka_id').change(function () {
            var tal = $("#taluka_id option:selected").val();

            $.post('<?php echo $this->webroot; ?>Citizenentry/taluka_change_event', {tal: tal}, function (data)
            {

                var sc = '<option>--select--</option>';
                $.each(data.village, function (index, val) {

                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                // $("#corp_id").prop("disabled", false);
                // $("#valutation_zone_id").prop("disabled", false);

                $("#village_id option").remove();
                $("#village_id").append(sc);
            },'json');
        });

        $("#village_id").on('change', function () {
            $.post('<?php echo $this->webroot; ?>Citizenentry/behavioral_patterns', {village_id: $("#village_id").val(), ref_id: 4, behavioral_id: 2}, function (data)
            {

                $("#address").html(data);

            });

        });

        $('#dob').datepicker({
            maxDate: '+0d',
            yearRange: '1920:2010',
            changeMonth: true,
            changeYear: true,
            dateFormat: "d-M-y",
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
                $('#identification').hide()

            }
            else
            {
                $('#identification').show()
            }
        });


    });

    function formadd() {
        document.getElementById("actiontype").value = '1';
    }

    function forcancel() {
        document.getElementById("actiontype").value = '2';
    }

    function formupdate(id, token_no, salutation, fname, mname, lname, fname_ll, mname_ll, lname_ll, ll_fdate, ll_tdate,
            ll_month, rent_permonth, deposite_refundable,
            father_fname_en, father_fname_ll, father_mname_en, father_mname_ll, father_lname_en, father_lname_ll, mother_fname_en, mother_fname_ll, mother_mname_en, mother_mname_ll,
            mother_lname_en, mother_lname_ll, dob, age, gender_id, uid, mobile_no, email_id, occupation_id, district_id, taluka_id, village_id,
            party_full_name_en, party_full_name_ll, father_full_name_en, father_full_name_ll, mother_full_name_en, mother_full_name_ll, identificationtype_id, identificationtype_desc_en, deposite_amount, idetification_mark1_en, idetification_mark1_ll, idetification_mark2_en, idetification_mark2_ll)
    {

        $.post("<?php echo $this->webroot; ?>districtchangeevent", {dist: district_id}, function (data)
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

        },'json');

        $.post('<?php echo $this->webroot; ?>Citizenentry/taluka_change_event', {tal: taluka_id}, function (data1)
        {

            var sc = '<option>--select--</option>';
            $.each(data1.village, function (index1, val1) {
                if (index1 == taluka_id)
                {
                    $.post('<?php echo $this->webroot; ?>Citizenentry/behavioral_patterns', {village_id: index1, ref_id: 4, ref_val: id, behavioral_id: 2}, function (data)
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
        },'json');


        $.post('<?php echo $this->webroot; ?>Citizenentry/behavioral_patterns', {ref_id: 4, ref_val: id, behavioral_id: 2}, function (data)
        {


            $("#address").html(data);

        });

        if (identificationtype_id == '')
        {
            $('#identification').hide()

        }
        else
        {
            $('#identification').show()
        }
        $('#hfid').val(id);
        $('#token_no').val(token_no);
        $('#salutation').val(salutation);
        $('#fname_en').val(fname);
        $('#mname_en').val(mname);
        $('#lname_en').val(lname);
        $('#fname_ll').val(fname_ll);
        $('#mname_ll').val(mname_ll);
        $('#lname_ll').val(lname_ll);
        $('#identificationtype_id').val(identificationtype_id);
        $('#identificationtype_desc_en').val(identificationtype_desc_en);


//         $('#lname_ll').val(aliasname_en);
//          $('#lname_ll').val(lname_ll);

        $('#ll_fdate').val(ll_fdate);
        $('#ll_tdate').val(ll_tdate);
        $('#ll_month').val(ll_month);
        $('#rent_permonth').val(rent_permonth);
        $('#deposite_amount').val(deposite_amount);

        $('#father_fname_en').val(father_fname_en);
        $('#father_fname_ll').val(father_fname_ll);
        $('#father_mname_en').val(father_mname_en);
        $('#father_mname_ll').val(father_mname_ll);
        $('#father_lname_en').val(father_lname_en);
        $('#father_lname_ll').val(father_lname_ll);
        $('#mother_fname_en').val(mother_fname_en);
        $('#mother_fname_ll').val(mother_fname_ll);
        $('#mother_mname_en').val(mother_mname_en);
        $('#mother_mname_ll').val(mother_mname_ll);
        $('#mother_lname_en').val(mother_lname_en);
        $('#mother_lname_ll').val(mother_lname_ll);

        $('#idetification_mark1_en').val(idetification_mark1_en);
        $('#idetification_mark1_ll').val(idetification_mark1_ll);
        $('#idetification_mark2_en').val(idetification_mark2_en);
        $('#idetification_mark2_ll').val(idetification_mark2_ll);

        $('#party_full_name_en').val(party_full_name_en);
        $('#party_full_name_ll').val(party_full_name_ll);

        $('#father_full_name_en').val(father_full_name_en);
        $('#father_full_name_ll').val(father_full_name_ll);

        $('#mother_full_name_en').val(mother_full_name_en);
        $('#mother_full_name_ll').val(mother_full_name_ll);

        $('#dob').val(dob);
        $('#age').val(age);
        $('#gender_id').val(gender_id);
        $('#uid').val(uid);
        $('#mobile_no').val(mobile_no);
        $('#email_id').val(email_id);
        $('#occupation_id').val(occupation_id);
        $('#district_id').val(district_id);
        $('#taluka_id').val(taluka_id);

        $("input:radio").attr("checked", false);
        $('input[name=deposite_refundable][value="' + deposite_refundable + '"]').prop('checked', 'checked');
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
$doc_lang = $this->Session->read('doc_lang');
?>
<?php echo $this->Form->create('leaseandlicense', array('id' => 'leaseandlicense', 'class' => 'form-vertical')); ?>
<?php
echo $this->element("Registration/main_menu");
?>
<?php
echo $this->element("Citizenentry/main_menu");
echo $this->element("Citizenentry/property_menu");
?>



<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblleaseandlicense'); ?></h3></center>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-12" style="text-align: right;">
                            <p style="color: red;"><b><?php echo __('lblnote'); ?>&nbsp;</b><?php echo __('lblengdatarequired'); ?></p>
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
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="party_type_id" class="col-sm-2 control-label"><?php echo __('lblpartytype'); ?></label> 
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('party_type_id', array('label' => false, 'id' => 'party_type_id', 'class' => 'form-control input-sm', 'options' => array($partytype))); ?>
                            <span id="party_type_id_error" class="form-error"><?php echo $errarr['party_type_id_error']; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title headbolder"><?php echo __('lblpersinfo'); ?></h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="salutation" class="col-sm-3 control-label" ><?php echo __('lblSalutation'); ?>:</label> 
                        <div class="col-sm-2"><?php echo $this->Form->input('salutation', array('label' => false, 'id' => 'salutation', 'class' => 'form-control input-sm', 'options' => array($salutation))); ?>
                            <span id="salutation_error" class="form-error"><?php echo $errarr['salutation_error']; ?></span>
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
                                <label for="fname_ll" class="control-label col-sm-3" ><?php echo __('lblpartyfirstname'); ?>: </label>
                                <div class="col-sm-3"><?php echo $this->Form->input('fname_ll', array('label' => false, 'id' => 'fname_ll', 'class' => 'form-control input-sm', 'type' => 'text')); ?>
                                    <span id="fname_ll_error" class="form-error"><?php echo $errarr['fname_ll_error']; ?></span>
                                </div>
                            <?php } ?>
                            <label for="fname_en" class="control-label col-sm-3" ><?php echo __('lblpartyfirstname'); ?>[ENGLISH] : </label>
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
                                <label for="mname_ll" class="control-label col-sm-3" ><?php echo __('lblpartymiddlename'); ?> : </label>
                                <div class="col-sm-3" ><?php echo $this->Form->input('mname_ll', array('label' => false, 'id' => 'mname_ll', 'class' => 'form-control input-sm', 'type' => 'text')); ?>
                                    <span id="mname_ll_error" class="form-error"><?php echo $errarr['mname_ll_error']; ?></span>
                                </div>
                            <?php } ?>
                            <label for="mname_en" class="control-label col-sm-3" ><?php echo __('lblpartymiddlename'); ?> [ENGLISH`]: </label>
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
                                <label for="lname_ll" class="control-label col-sm-3" ><?php echo __('lblpartylastname'); ?>: </label>
                                <div class="col-sm-3" ><?php echo $this->Form->input('lname_ll', array('label' => false, 'id' => 'lname_ll', 'class' => 'form-control input-sm', 'type' => 'text')); ?>
                                    <span id="lname_ll_error" class="form-error"><?php echo $errarr['lname_ll_error']; ?></span>
                                </div>
                            <?php } ?>
                            <label for="lname_en" class="control-label col-sm-3" ><?php echo __('lblpartylastname'); ?> [ENGLISH]: </label>
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
                                <label for="party_lname_ll" class="col-sm-3 control-label"><?php echo __('lblpartyfullname'); ?>:-</label> 
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('party_full_name_ll', array('label' => false, 'id' => 'party_full_name_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                    <span id="party_full_name_ll_error" class="form-error"><?php echo $errarr['party_full_name_ll_error']; ?></span>
                                </div>
                            <?php } ?>
                            <label for="party_lname_en" class="col-sm-3 control-label"><?php echo __('lblpartyfullname'); ?> [ENGLISH]:-</label> 
                            <div class="col-sm-3">
                                <?php echo $this->Form->input('party_full_name_en', array('label' => false, 'id' => 'party_full_name_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                <span id="party_full_name_en_error" class="form-error"><?php echo $errarr['party_full_name_en_error']; ?></span>
                            </div>

                        </div>
                    </div>
                <?php } ?>

                <?php if ($name_format == 'Y') { ?> 
                    <div  class="rowht">&nbsp;</div>
                    <div class="row">
                        <div class="form-group">
                            <?php
                            if (!empty($doc_lang) and $doc_lang != 'en') {
                                ?>
                                <label for="father_fname_ll" class="col-sm-3 control-label"><?php echo __('lblfathersfirstname'); ?>:-</label>    
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('father_fname_ll', array('label' => false, 'id' => 'father_fname_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                    <span id="father_fname_ll_error" class="form-error"><?php echo $errarr['father_fname_ll_error']; ?></span>
                                </div>
                            <?php } ?>
                            <label for="father_fname_en" class="col-sm-3 control-label"><?php echo __('lblfathersfirstname'); ?> [ENGLISH]:-</label>    
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
                           <label for="father_mname_ll" class="col-sm-3 control-label"><?php echo __('lblfathersmiddlename'); ?>:-</label>    
                            <div class="col-sm-3">
                                <?php echo $this->Form->input('father_mname_ll', array('label' => false, 'id' => 'father_mname_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                <span id="father_mname_ll_error" class="form-error"><?php echo $errarr['father_mname_ll_error']; ?></span>
                            </div>
                            <?php } ?>
                            <label for="father_mname_en" class="col-sm-3 control-label"><?php echo __('lblfathersmiddlename'); ?> [ENGLISH]:-</label>    
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
                            <label for="father_lname_ll" class="col-sm-3 control-label"><?php echo __('lblfatherslastname'); ?>:-</label> 
                            <div class="col-sm-3">
                                <?php echo $this->Form->input('father_lname_ll', array('label' => false, 'id' => 'father_lname_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                <span id="father_lname_ll_error" class="form-error"><?php echo $errarr['father_lname_ll_error']; ?></span>
                            </div>
                            <?php } ?>
                            <label for="father_lname_en" class="col-sm-3 control-label"><?php echo __('lblfatherslastname'); ?> [ENGLISH]:-</label> 
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
                            <label for="mother_fname_ll" class="col-sm-3 control-label"><?php echo __('lblmotherfname'); ?>:-</label>    
                            <div class="col-sm-3">
                                <?php echo $this->Form->input('mother_fname_ll', array('label' => false, 'id' => 'mother_fname_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                <span id="mother_fname_ll_error" class="form-error"><?php echo $errarr['mother_fname_ll_error']; ?></span>
                            </div>
                            <?php } ?>
                            <label for="mother_fname_en" class="col-sm-3 control-label"><?php echo __('lblmotherfname'); ?>[ENGLISH]:-</label>    
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
                            <label for="mother_mname_ll" class="col-sm-3 control-label"><?php echo __('lblmothermname'); ?>:-</label>    
                            <div class="col-sm-3">
                                <?php echo $this->Form->input('mother_mname_ll', array('label' => false, 'id' => 'mother_mname_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                <span id="mother_mname_ll_error" class="form-error"><?php echo $errarr['mother_mname_ll_error']; ?></span>
                            </div>
                            <?php } ?>
                            <label for="mother_mname_en" class="col-sm-3 control-label"><?php echo __('lblmothermname'); ?>[ENGLISH]:-</label>    
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
                            <label for="mother_lname_ll" class="col-sm-3 control-label"><?php echo __('lblmotherlname'); ?>:-</label> 
                            <div class="col-sm-3">
                                <?php echo $this->Form->input('mother_lname_ll', array('label' => false, 'id' => 'mother_lname_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                <span id="mother_lname_ll_error" class="form-error"><?php echo $errarr['mother_lname_ll_error']; ?></span>
                            </div>
                            <?php } ?>
                            <label for="mother_lname_en" class="col-sm-3 control-label"><?php echo __('lblmotherlname'); ?>[ENGLISH]:-</label> 
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
                             <label for="father_full_name_ll" class="col-sm-3 control-label"><?php echo __('lblfathersfullname'); ?>:-</label> 
                            <div class="col-sm-3">
                                <?php echo $this->Form->input('father_full_name_ll', array('label' => false, 'id' => 'father_full_name_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                <span id="father_full_name_ll_error" class="form-error"><?php echo $errarr['father_full_name_ll_error']; ?></span>
                            </div>
                            <?php } ?>
                            <label for="father_full_name_en" class="col-sm-3 control-label"><?php echo __('lblfathersfullname'); ?>[ENGLISH]:-</label> 
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
                             <label for="mother_full_name_ll" class="col-sm-3 control-label"><?php echo __('lblmothersfullname'); ?>:-</label> 
                            <div class="col-sm-3">
                                <?php echo $this->Form->input('mother_full_name_ll', array('label' => false, 'id' => 'mother_full_name_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                <span id="mother_full_name_ll_error" class="form-error"><?php echo $errarr['mother_full_name_ll_error']; ?></span>
                            </div>
                            <?php } ?>
                            <label for="mother_full_name_en" class="col-sm-3 control-label"><?php echo __('lblmothersfullname'); ?>[ENGLISH]:-</label> 
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
                        <label for="dob" class="col-sm-3 control-label"><?php echo __('lbldob'); ?></label> 
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('dob', array('label' => false, 'id' => 'dob', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="dob_error" class="form-error"><?php echo $errarr['dob_error']; ?></span>
                        </div>

                        <label for="age" class="col-sm-3 control-label"><?php echo __('lblage'); ?>:</label> 
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
                            <?php echo $this->Form->input('gender_id', array('label' => false, 'id' => 'gender_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $gender))); ?>
                            <span id="gender_id_error" class="form-error"><?php echo $errarr['gender_id_error']; ?></span>
                        </div>
                        <label for="occupation_id" class="col-sm-3 control-label"><?php echo __('lbloccupation'); ?>:</label> 
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('occupation_id', array('label' => false, 'id' => 'occupation_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $occupation))); ?>
                            <span id="occupation_id_error" class="form-error"><?php echo $errarr['occupation_id_error']; ?></span>
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
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="uid" class="col-sm-3 control-label"><?php echo __('lbluid'); ?></label> 
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
                            <?php echo $this->Form->input('identificationtype_id', array('label' => false, 'id' => 'identificationtype_id', 'class' => 'form-control input-sm', 'empty' => '--Select--', 'options' => array($identificatontype))); ?>
                            <span id="identificationtype_id_error" class="form-error"><?php echo $errarr['identificationtype_id_error']; ?></span>
                        </div>

                    </div>
                    <div id="identification">
                        <label for="identificationtype_desc_en" class="col-sm-3 control-label"><?php echo __('lblidentitydetails') ?>:</label> 
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('identificationtype_desc_en', array('label' => false, 'id' => 'identificationtype_desc_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="identificationtype_desc_en_error" class="form-error"><?php echo $errarr['identificationtype_desc_en_error']; ?></span>
                        </div>
                    </div>

                </div>

                <div class="box-body" id="address"></div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="district_id" class="col-sm-3 control-label"><?php echo __('lbladmdistrict'); ?>:</label> 
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('district_id', array('label' => false, 'id' => 'district_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $districtdata))); ?>
                            <span id="district_id_error" class="form-error"><?php echo $errarr['district_id_error']; ?></span>
                        </div>
                        <label for="taluka_id" class="col-sm-3 control-label"><?php echo __('lbladmtaluka'); ?>:</label> 
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('taluka_id', array('label' => false, 'id' => 'taluka_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--'))); ?>
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
                <h3 class="box-title headbolder"><?php echo __('lblleaseandlicensedetails'); ?></h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="ll_fdate" class="control-label col-sm-3" ><?php echo __('lblfromdate'); ?>  </label>
                        <div class="col-sm-3" ><?php echo $this->Form->input('ll_fdate', array('label' => false, 'id' => 'll_fdate', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="ll_fdate_error" class="form-error"><?php echo $errarr['ll_fdate_error']; ?></span>
                        </div>
                        <label for="ll_tdate" class="control-label col-sm-3" ><?php echo __('lbltodate'); ?> : </label>
                        <div class="col-sm-3"><?php echo $this->Form->input('ll_tdate', array('label' => false, 'id' => 'll_tdate', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="ll_tdate_error" class="form-error"><?php echo $errarr['ll_tdate_error']; ?></span>
                        </div>

                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="ll_month" class="control-label col-sm-3" ><?php echo __('lblmonth'); ?> : </label>
                        <div class="col-sm-3" ><?php echo $this->Form->input('ll_month', array('label' => false, 'id' => 'll_month', 'class' => 'form-control input-sm', 'type' => 'text', 'readonly' => 'readonly')); ?>
                            <span id="ll_month_error" class="form-error"><?php echo $errarr['ll_month_error']; ?></span>
                        </div>
                        <label for="rent_permonth" class="control-label col-sm-3" ><?php echo __('lblratepermonth'); ?> : </label>
                        <div class="col-sm-3" ><?php echo $this->Form->input('rent_permonth', array('label' => false, 'id' => 'rent_permonth', 'class' => 'form-control input-sm', 'type' => 'text')); ?>
                            <span id="rent_permonth_error" class="form-error"><?php echo $errarr['rent_permonth_error']; ?></span>
                        </div>

                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="deposite_amount" class="control-label col-sm-3" ><?php echo __('lbldepamt'); ?> : </label>
                        <div class="col-sm-3" ><?php echo $this->Form->input('deposite_amount', array('label' => false, 'id' => 'deposite_amount', 'class' => 'form-control input-sm', 'type' => 'text')); ?>
                            <span id="deposite_amount_error" class="form-error"><?php echo $errarr['deposite_amount_error']; ?></span>
                        </div>
                        <label for="deposite_refundable" class="control-label col-sm-3" > <?php echo __('lbldepref'); ?> : </label>
                        <div class="col-sm-3" ><?php echo $this->Form->input('deposite_refundable', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => '', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'deposite_refundable', 'name' => 'deposite_refundable')); ?>
                            <span id="deposite_refundable_error" class="form-error"><?php echo $errarr['deposite_refundable_error']; ?></span>
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
                <div  class="rowht">&nbsp;</div> <div  class="rowht">&nbsp;</div>
                <div class="row center">
                    <div class="form-group">
                        <button type="submit" id="btnCancel" name="btnCancel" class="btn btn-info" onclick="javascript: return formadd();"><?php echo __('btnsave'); ?></button>
                        <button type="submit" id="btnNext" name="btnNext" class="btn btn-info" onclick="javascript: return forcancel();"><?php echo __('btncancel'); ?></button>
                    </div>
                </div>
            </div>

        </div>
        <div class="box box-primary">

            <div class="box-body" id="divwitness">
                <div class="table-responsive">
                    <table id="tablewitness" class="table table-striped table-bordered table-hover">  
                        <thead >  
                            <tr>  
                                <th class="center"><?php echo __('lbllevelname'); ?></th>
                                <th class="center"><?php echo __('lblnoofmonths'); ?></th>
                                <th class="center"><?php echo __('lbldipositamt'); ?></th>
                                <th class="center width10"><?php echo __('lblaction'); ?></th>
                            </tr>  
                        </thead>

                        <?php for ($i = 0; $i < count($leaseandlicense); $i++) { ?>
                            <tr>
                                <td ><?php echo $leaseandlicense[$i][0]['party_full_name_' . $doc_lang]; ?></td>
                                <td ><?php echo $leaseandlicense[$i][0]['ll_month']; ?></td>
                                <td ><?php echo $leaseandlicense[$i][0]['deposite_amount']; ?></td>
                                <td >
                                    <button id="btnupdate" name="btnupdate" class="btn btn-default " style="text-align: center;" onclick="javascript: return formupdate(
                                                    ('<?php echo $leaseandlicense[$i][0]['id']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['token_no']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['salutation']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['fname_en']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['mname_en']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['lname_en']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['fname_ll']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['mname_ll']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['lname_ll']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['ll_fdate']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['ll_tdate']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['ll_month']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['rent_permonth']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['deposite_refundable']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['father_fname_en']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['father_fname_ll']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['father_mname_en']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['father_mname_ll']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['father_lname_en']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['father_lname_ll']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['mother_fname_en']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['mother_fname_ll']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['mother_mname_en']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['mother_mname_ll']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['mother_lname_en']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['mother_lname_ll']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['dob']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['age']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['gender_id']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['uid']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['mobile_no']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['email_id']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['occupation_id']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['district_id']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['taluka_id']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['village_id']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['party_full_name_en']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['party_full_name_ll']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['father_full_name_en']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['father_full_name_ll']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['mother_full_name_en']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['mother_full_name_ll']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['identificationtype_id']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['identificationtype_desc_en']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['deposite_amount']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['idetification_mark1_en']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['idetification_mark1_ll']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['idetification_mark2_en']; ?>'),
                                                    ('<?php echo $leaseandlicense[$i][0]['idetification_mark2_ll']; ?>')

                                                    );">
                                        <span class="glyphicon glyphicon-pencil"></span></button>

                                    <button id="btndelete" name="btndelete" class="btn btn-default " style="text-align: center;" onclick="javascript: return formdelete(('<?php echo $leaseandlicense[$i][0]['id']; ?>'));">
                                        <span class="glyphicon glyphicon-remove"></span></button>
                                </td>
                            </tr>
                        <?php } ?>
                    </table> 
                    <?php if (!empty($leaseandlicense)) { ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
                </div>
            </div>
        </div>

    </div>
    <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
</div>

<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>

