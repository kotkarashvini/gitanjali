
<script type="text/javascript">

    $(document).ready(function () {

        var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;

        $('#district_id').change(function () {
            var dist = $("#district_id option:selected").val();
            $.post("<?php echo $this->webroot; ?>LegacyWitness/district_change_event", {dist: dist, csrftoken: csrftoken}, function (data)
            {
                var sc = '<option>--select--</option>';
                $.each(data.taluka, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#taluka_id").prop("disabled", false);
                $("#taluka_id option").remove();
                $("#taluka_id").append(sc);
 sortSelect('#taluka_id', 'text', 'asc');
            }, 'json');

        });


        $('#taluka_id').change(function () {
            var tal = $("#taluka_id option:selected").val();

            $.post('<?php echo $this->webroot; ?>LegacyWitness/taluka_change_event', {tal: tal, csrftoken: csrftoken}, function (data)
            {

                var sc = '<option>--select--</option>';
                $.each(data.village, function (index, val) {

                    sc += "<option value=" + index + ">" + val + "</option>";
                });

                $("#village_id option").remove();
                $("#village_id").append(sc);
                 sortSelect('#village_id', 'text', 'asc');
            }, 'json');
        });

        $("#village_id").on('change', function () {
            $.post('<?php echo $this->webroot; ?>LegacyWitness/behavioral_patterns', {village_id: $("#village_id").val(), ref_id: 3, behavioral_id: 2, csrftoken: csrftoken}, function (data)
            {

                $('.partyaddress').html(data);
                 $(document).trigger('_page_ready');
            show_data_messages();
            show_error_messages();
                 

            });

        });

        $('#dob').datepicker({
            maxDate: '+0d',
            yearRange: '1920:2010',
            changeMonth: true,
            changeYear: true,
             format: "dd-mm-yyyy"
        });
        $("#dob").change(function ()
        {

            var dateString = $("#dob").val();
             var d1 = dateString.split("-");
           var dateString = d1[1]+'/'+d1[0]+'/'+d1[2];
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
         $("#identificationtype_desc_en").blur(function () {
             
            var type = $("#identificationtype_id option:selected").val();
            var desc = $("#identificationtype_desc_en").val();
            if (type != '')
            {
                $.post(host + 'LegacyWitness/get_validation_rule', {type: type,csrftoken:csrftoken}, function (data)
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
                        $("#identificationtype_desc_en").val('');
                       // $("#identificationtype_desc_en").focus();
                        $("#identificationtype_desc_en_error").text(message);
                        return false;
                    } else
                    {
                        $("#identificationtype_desc_en_error").text('');
                        return true;
                    }
                },'json');
            }
        });
    });
 
</script>
<?php $this->Form->create('witness'); ?>
    <div>
    <div class="box box-primary">
        <div class="box-body">
            <?php
        
            $doc_lang = $this->Session->read('doc_lang');

// ngdrstab_mst_party_category_fields
            if (isset($witnessfields) && !empty($witnessfields)) {
                if ($doc_lang == 'en') {
                    $info = NULL;
                } else {
                    $info = '[ENGLISH]';
                }
                ?>
                <div class="col-md-12">
                    <?php
                    $upadteflag = 0;
                    foreach ($witnessfields as $field) {
                        $field = $field['witness_fields'];
                        if (isset($rec) && !empty($rec)) {
                            $upadteflag = 1;
                            $witness[$field['field_id_name_en']] = $rec[0]['Leg_witness'][$field['field_id_name_en']];
                            if ($field['field_id_name_ll']) {
                                $witness[$field['field_id_name_ll']] = $rec[0]['Leg_witness'][$field['field_id_name_ll']];
                            }
                            
                        } else {
                            $witness[$field['field_id_name_en']] = '';
                            $witness[$field['field_id_name_ll']] = '';
                           
                        }
                        ?>
                        <div  class="rowht">&nbsp;</div>
                        <div class="row">
                            <div class="form-group">
                                <?php
                                if (!empty($doc_lang) and $doc_lang != 'en') {
                                    if ($field['field_id_name_ll'] != '') {
                                        ?>
                                        <label  class="col-sm-3 control-label"> <?php echo $field['field_name_' . $doc_lang] ?> <span style="color: #ff0000"><?php echo $field['is_required'] ?></span></label>
                                        <div class="col-sm-3">
                                             <?php if($field['is_mask']=='Y'){?>
                                            <?php echo $this->Form->input($field['field_id_name_ll'], array('label' => false, 'id' => $field['field_id_name_ll'], 'class' => 'form-control input-sm', 'type' => 'password', 'default' => $witness[$field['field_id_name_ll']])) ?>
                                             <?php }else{ ?>
                                             <?php echo $this->Form->input($field['field_id_name_ll'], array('label' => false, 'id' => $field['field_id_name_ll'], 'class' => 'form-control input-sm', 'type' => 'text', 'default' => $witness[$field['field_id_name_ll']])) ?>
                                             <?php } ?>
                                            <span id="<?php echo $field['field_id_name_ll']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];   ?></span>
                                              <!--<span id="party_fname_ll_error" class="form-error"><?php //echo //$errarr['party_fname_ll_error'];   ?></span>-->
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                                <label  class="col-sm-3 control-label"> <?php echo $field['field_name_' . $doc_lang] ?> :-<?php echo $info; ?> <span style="color: #ff0000"><?php echo $field['is_required'] ?></span></label>
                                <div class="col-sm-3">
                                    <?php
                                    switch ($field['field_id_name_en']) {
                                        case 'bank_id': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'], 'class' => 'form-control input-sm chosen-select', 'label' => false, 'options' => $bank_master, 'default' => $witness[$field['field_id_name_en']]));
                                            ?>
                                            <span id="bank_id_error" class="form-error"><?php //echo $errarr['bank_id_error'];              ?></span>
                                            <?php
                                            break;
                                        case 'is_executer': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'], 'class' => 'form-control input-sm chosen-select', 'label' => false, 'options' => $executer, 'default' => $witness[$field['field_id_name_en']]));
                                            ?>
                                            <span id="is_executer_error" class="form-error"><?php //echo $errarr['is_executer_error'];              ?></span>
                                            <?php
                                            break;
                                        case 'salutation':echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'], 'class' => 'form-control input-sm chosen-select', 'label' => false, 'options' => $salutation, 'default' => $witness[$field['field_id_name_en']]));
                                            ?>
                                            <span id="salutation_id_error" class="form-error"><?php // echo $errarr['salutation_id_error'];              ?></span>
                                            <?php
                                            break;
                                        case 'gender_id': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'], 'class' => 'form-control input-sm chosen-select', 'label' => false, 'options' => $gender, 'default' => $witness[$field['field_id_name_en']]));
                                            ?>
                                            <span id="gender_id_error" class="form-error"><?php ///echo $errarr['gender_id_error'];              ?></span>
                                           
<?php
                                            break;
                                        case 'marital_status': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'], 'class' => 'form-control input-sm chosen-select', 'label' => false, 'options' => $marital_status, 'default' => $witness[$field['field_id_name_en']]));
                                            ?>
                                            <span id="marital_status_error" class="form-error"><?php ///echo $errarr['gender_id_error'];              ?></span>
                                            <?php
                                            break;
                                        case 'nationality': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'], 'class' => 'form-control input-sm chosen-select', 'label' => false, 'options' => $nationality, 'default' => $witness[$field['field_id_name_en']]));
                                            ?>
                                            <span id="nationality_error" class="form-error"><?php ///echo $errarr['gender_id_error'];              ?></span>
 <?php
                                            break;
                                        case 'cast_id': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'], 'class' => 'form-control input-sm chosen-select', 'label' => false, 'options' => $category, 'default' => $witness[$field['field_id_name_en']]));
                                            ?>
                                            <span id="cast_id_error" class="form-error"><?php ///echo $errarr['gender_id_error'];              ?></span>

                                            <?php
                                            break;
                                        case 'occupation_id': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'], 'class' => 'form-control input-sm chosen-select', 'label' => false, 'options' => $occupation, 'default' => $witness[$field['field_id_name_en']]));
                                            ?>
                                            <span id="occupation_id_error" class="form-error"><?php //echo $errarr['occupation_id_error'];              ?></span>
                                            <?php
                                            break;
                                        case 'identificationtype_id': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'], 'class' => 'form-control input-sm chosen-select', 'label' => false, 'options' => $identificatontype, 'empty' => '--Select--', 'default' => $witness[$field['field_id_name_en']]));
                                            ?>
                                            <span id="identificationtype_id_error" class="form-error"><?php //echo $errarr['identificationtype_id_error'];              ?></span>
                                            <?php
                                            break;
                                        case 'exemption_id': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'], 'class' => 'form-control input-sm chosen-select', 'label' => false, 'options' => $exemption, 'default' => $witness[$field['field_id_name_en']]));
                                            ?>
                                            <span id="exemption_id_error" class="form-error"><?php //echo $errarr['exemption_id_error'];              ?></span>
                                            <?php
                                            break;
                                        case 'district_id': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'], 'class' => 'form-control input-sm chosen-select', 'label' => false, 'options' => $districtdata, 'empty' => '--Select--', 'default' => $witness[$field['field_id_name_en']]));
                                            ?>
                                            <span id="district_id_error" class="form-error"><?php //echo $errarr['district_id_error'];              ?></span>
                                            <?php
                                            break;
                                        case 'taluka_id': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'], 'class' => 'form-control input-sm chosen-select', 'label' => false, 'options' => $taluka, 'selected' => $witness[$field['field_id_name_en']]));
                                            ?>
                                            <span id="taluka_id_error" class="form-error"><?php //echo $errarr['taluka_id_error'];              ?></span>
                                            <?php
                                            break;
                                        case 'village_id': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'], 'class' => 'form-control input-sm chosen-select', 'label' => false, 'options' => $villagelist, 'default' => $witness[$field['field_id_name_en']]));
                                            ?>
                                            <span id="village_id_error" class="form-error"><?php //echo $errarr['village_id_error'];              ?></span>
                                            <?php
                                            break;
                                        default:
                                             if($field['is_mask']=='Y'){
                                            
                                            echo $this->Form->input($field['field_id_name_en'], array('label' => false, 'id' => $field['field_id_name_en'], 'class' => 'form-control input-sm', 'type' => 'password', 'value' => $witness[$field['field_id_name_en']]));
                                             }else{
                                               echo $this->Form->input($field['field_id_name_en'], array('label' => false, 'id' => $field['field_id_name_en'], 'class' => 'form-control input-sm', 'type' => 'text', 'value' => $witness[$field['field_id_name_en']]));  
                                             }
                                            ?>
                                            <span id="<?php echo $field['field_id_name_en']; ?>_error" class="form-error"><?php //echo $errarr['party_fname_en_error'];                                          ?></span>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?> 
                </div> 
            <?php } ?>
            <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
         </div>
    </div>
</div>
<div class="box box-primary">
  
        <div class="box-body partyaddress" ></div>
  
</div>
