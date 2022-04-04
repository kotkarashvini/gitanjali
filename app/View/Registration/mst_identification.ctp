<script type="text/javascript">

    $(document).ready(function () {

        var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;

        $('#district_id').change(function () {
            var dist = $("#district_id option:selected").val();
            $.post("<?php echo $this->webroot; ?>Citizenentry/district_change_event", {dist: dist, csrftoken: csrftoken}, function (data)
            {
                var sc = '<option>--select--</option>';
                $.each(data.taluka, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#taluka_id").prop("disabled", false);
                $("#taluka_id option").remove();
                $("#taluka_id").append(sc);

            }, 'json');

        });


        $('#taluka_id').change(function () {
            var tal = $("#taluka_id option:selected").val();

            $.post('<?php echo $this->webroot; ?>Citizenentry/taluka_change_event', {tal: tal, csrftoken: csrftoken}, function (data)
            {

                var sc = '<option>--select--</option>';
                $.each(data.village, function (index, val) {

                    sc += "<option value=" + index + ">" + val + "</option>";
                });

                $("#village_id option").remove();
                $("#village_id").append(sc);
            }, 'json');
        });

        $("#village_id").on('change', function () {
            $.post('<?php echo $this->webroot; ?>Citizenentry/mst_behavioral_patterns', {village_id: $("#village_id").val(), ref_id: 5, behavioral_id: 2, csrftoken: csrftoken}, function (data)
            {
                $('.partyaddress').html(data);
                $(document).trigger('_page_ready');
//                show_data_messages();
//                show_error_messages();

            });

        });
        if ($.isNumeric($("#village_id").val())) {
            $.post('<?php echo $this->webroot; ?>Citizenentry/mst_behavioral_patterns', {village_id: $("#village_id").val(), ref_id: 9999, behavioral_id: 2, csrftoken: csrftoken, ref_val: $("#identification_id").val()}, function (data)
            {
                $('.partyaddress').html(data);
                $(document).trigger('_page_ready');
//                show_data_messages();
//                show_error_messages();

            });
        }
        $('#dob').datepicker({
            maxDate: '+0d',
            yearRange: '1920:2010',
            changeMonth: true,
            changeYear: true,
            dateFormat: "dd MM yy",
        });
        $("#dob").change(function ()
        {
            // alert();
//
            var today = new Date(),
                    dob = new Date($("#dob").val()),
                    age = new Date(today - dob).getFullYear() - 1970;

            $('#age').val(age);
        });
        $("#identificationtype_desc_en").blur(function () {

            var type = $("#identificationtype_id option:selected").val();
            var desc = $("#identificationtype_desc_en").val();
            if (type != '')
            {
                $.post('<?php echo $this->webroot; ?>Citizenentry/get_validation_rule', {type: type, csrftoken: csrftoken}, function (data)
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
                }, 'json');
            }
        });
    });

</script>
<?php
echo $this->Form->create('mst_identification', array(
    'url' => array(
        'controller' => 'Registration',
        'action' => 'mst_identification'
    )
));
?>
<div>
    <div class="box box-primary">
        <div class="box-header with-border"><center><?php echo __('lblidentityinfo'); ?></center></div>
        <div class="box-body">
            <?php
// ngdrstab_mst_party_category_fields
            //pr($identifirefields);exit;
            if (isset($identifirefields) && !empty($identifirefields)) {
                if ($laug == 'en') {
                    $info = NULL;
                } else {
                    $info = '';
                }
                ?>
                <div class="col-md-12">
                    <?php
                    $upadteflag = 0;
                    foreach ($identifirefields as $field) {
                        $field = $field['identification_fields'];
                        if (isset($rec) && !empty($rec)) {
                            $upadteflag = 1;
                            $iden[$field['field_id_name_en']] = $rec[0]['identification'][$field['field_id_name_en']];
                            if ($field['field_id_name_ll']) {
                                $iden[$field['field_id_name_ll']] = $rec[0]['identification'][$field['field_id_name_ll']];
                            }
                        } else {
                            $iden[$field['field_id_name_en']] = '';
                            $iden[$field['field_id_name_ll']] = '';
                        }
                        ?>
                        <div  class="rowht">&nbsp;</div>
                        <div class="row">
                            <div class="form-group">
                                <?php
                                //  if (!empty($laug) and $laug != 'en') {
                                if ($field['field_id_name_ll'] != '') {
                                    foreach ($languagelist as $singlelang) {
                                        if ($singlelang['mainlanguage']['language_code'] != 'en') {
                                            $fieldarr = explode("_", $field['field_id_name_ll']);
                                            //  pr($fieldarr);
                                            $field_name = "";
                                            for ($i = 0; $i <= count($fieldarr) - 2; $i++) {
                                                $field_name = $field_name . $fieldarr[$i] . "_";
                                                //    pr($field_name);
                                            }
                                            // pr($field_name);
                                            $field_name.=$singlelang['mainlanguage']['language_code'];
                                            // pr($field_name);exit;
                                            ?> 
                                            <div class="col-md-2">
                                                <div class="form-group">

                                                    <label  class=""> <?php echo $field['field_name_' . $laug] ?> <span style="color: #ff0000"><?php echo $field['is_required'] ?></span></label>

                                                    <?php if ($field['is_mask'] == 'Y') { ?>
                                                        <?php echo $this->Form->input($field_name, array('label' => false, 'id' => $field_name, 'class' => 'form-control input-sm', 'type' => 'password', 'placeholder' => $singlelang['mainlanguage']['language_name'])) ?>
                                                    <?php } else { ?>
                                                        <?php echo $this->Form->input($field_name, array('label' => false, 'id' => $field_name, 'class' => 'form-control input-sm', 'type' => 'text', 'placeholder' => $singlelang['mainlanguage']['language_name'])) ?>
                    <?php } ?>
                                                    <span id="<?php echo $field['field_id_name_ll']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];     ?></span>
                                                      <!--<span id="party_fname_ll_error" class="form-error"><?php //echo //$errarr['party_fname_ll_error'];     ?></span>-->
                                                </div>
                                            </div>

                                            <?php
                                        }
                                    }
                                }
                                // }
                                ?>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label> <?php echo $field['field_name_' . $laug] ?> :-<?php echo $info; ?> <span style="color: #ff0000"><?php echo $field['is_required'] ?></span></label>

                                        <?php
                                        switch ($field['field_id_name_en']) {
                                            case 'bank_id': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'], 'class' => 'form-control input-sm chosen-select', 'label' => false, 'options' => $bank_master));
                                                ?>
                                                <span id="bank_id_error" class="form-error"><?php //echo $errarr['bank_id_error'];               ?></span>
                                                <?php
                                                break;
                                            case 'is_executer': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'], 'class' => 'form-control input-sm chosen-select', 'label' => false, 'options' => $executer));
                                                ?>
                                                <span id="is_executer_error" class="form-error"><?php //echo $errarr['is_executer_error'];               ?></span>
                                                <?php
                                                break;
                                            case 'salutation':echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'], 'class' => 'form-control input-sm chosen-select', 'label' => false, 'options' => $salutation));
                                                ?>
                                                <span id="salutation_id_error" class="form-error"><?php // echo $errarr['salutation_id_error'];               ?></span>
                                                <?php
                                                break;
                                            case 'gender_id': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'], 'class' => 'form-control input-sm chosen-select', 'label' => false, 'options' => $gender));
                                                ?>
                                                <span id="gender_id_error" class="form-error"><?php ///echo $errarr['gender_id_error'];               ?></span>
                                                <?php
                                                break;
                                            case 'cast_id': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'], 'class' => 'form-control input-sm chosen-select', 'label' => false, 'options' => $category));
                                                ?>
                                                <span id="cast_id_error" class="form-error"><?php ///echo $errarr['gender_id_error'];                ?></span>

                                                <?php
                                                break;
                                            case 'occupation_id': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'], 'class' => 'form-control input-sm chosen-select', 'label' => false, 'options' => $occupation));
                                                ?>
                                                <span id="occupation_id_error" class="form-error"><?php //echo $errarr['occupation_id_error'];               ?></span>
                                                <?php
                                                break;
                                            case 'identificationtype_id': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'], 'class' => 'form-control input-sm chosen-select', 'label' => false, 'options' => $identificatontype, 'empty' => '--Select--'));
                                                ?>
                                                <span id="identificationtype_id_error" class="form-error"><?php //echo $errarr['identificationtype_id_error'];               ?></span>
                                                <?php
                                                break;
                                            case 'exemption_id': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'], 'class' => 'form-control input-sm chosen-select', 'label' => false, 'options' => $exemption));
                                                ?>
                                                <span id="exemption_id_error" class="form-error"><?php //echo $errarr['exemption_id_error'];               ?></span>
                                                <?php
                                                break;
                                            case 'district_id': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'], 'class' => 'form-control input-sm chosen-select', 'label' => false, 'options' => $districtdata, 'empty' => '--Select--'));
                                                ?>
                                                <span id="district_id_error" class="form-error"><?php //echo $errarr['district_id_error'];               ?></span>
                                                <?php
                                                break;
                                            case 'taluka_id': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'], 'class' => 'form-control input-sm chosen-select', 'label' => false, 'options' => $taluka));
                                                ?>
                                                <span id="taluka_id_error" class="form-error"><?php //echo $errarr['taluka_id_error'];               ?></span>
                                                <?php
                                                break;
                                            case 'village_id': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'], 'class' => 'form-control input-sm chosen-select', 'label' => false, 'options' => $villagelist));
                                                ?>
                                                <span id="village_id_error" class="form-error"><?php //echo $errarr['village_id_error'];               ?></span>
                                                <?php
                                                break;
                                            default:
                                                if ($field['is_mask'] == 'Y') {
                                                    echo $this->Form->input($field['field_id_name_en'], array('label' => false, 'id' => $field['field_id_name_en'], 'class' => 'form-control input-sm', 'type' => 'password', 'placeholder' => 'English'));
                                                } else {
                                                    echo $this->Form->input($field['field_id_name_en'], array('label' => false, 'id' => $field['field_id_name_en'], 'class' => 'form-control input-sm', 'type' => 'text', 'placeholder' => 'English'));
                                                }
                                                ?>
                                                <span id="<?php echo $field['field_id_name_en']; ?>_error" class="form-error"><?php //echo $errarr['party_fname_en_error'];                                           ?></span>
                                            <?php
                                        }
                                        ?>
                                    </div>
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

<div class="box box-primary">
    <div class="box-body">
        <div class="row center">
            <div class="form-group">
                <?php
                if (isset($this->request->data['mst_identification']['identification_id'])) {
                    echo $this->Form->input('identification_id', array('label' => false, 'class' => 'form-control', 'type' => 'hidden', 'id' => 'identification_id', 'value' => $this->request->data['mst_identification']['identification_id']));
                }
                echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken')));
                ?>
                <button type="submit" id="btnNext" name="btnNext" class="btn btn-info" ><?php echo __('btnsave'); ?></button>
                <button type="submit" id="btnCancel" name="btnCancel" class="btn btn-info" ><?php echo __('btncancel'); ?></button>
            </div>
        </div>
    </div>
</div>

<?php echo $this->Form->end(); ?>
<table class="table">
    <thead>
        <tr>
            <th scope="col"><?php echo __('lblsrno'); ?></th>
            <th scope="col"><?php echo __('lblname'); ?></th>
            <th scope="col"><?php echo __('lblAddress'); ?></th>
            <th scope="col"><?php echo __('lblaction'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 0;
        foreach ($identifirelist as $identifire) {
            $i++;
            $patterns= $this->requestAction(
                    array('controller' => 'Registration', 'action' => 'mst_pattern_detail',$identifire['MstIdentification']['identification_id'])
            );
            ?>   
            <tr>
                <td><?php echo $i; ?></td>
                <th><?php echo $identifire['salutation']['salutation_desc_' . $laug] . " " . $identifire['MstIdentification']['identification_full_name_' . $laug]; ?></th>

                <td><?php
                    if (!empty($patterns)) {
                        foreach ($patterns as $pattern) {
                            echo $pattern['pattern']['pattern_desc_' . $laug] . " : " . $pattern['TrnBehavioralPatterns']['field_value_' . $laug] . "<br>";
                            // pr(); //mapping_ref_val
                        }
                    }
                    ?></td>
                <td>
                    <a href="<?php echo $this->webroot; ?>Registration/mst_identification/<?php echo $identifire['MstIdentification']['identification_id']; ?>" class="btn btn-primary"><?php echo __('lblbtnedit'); ?></a>
                    <a href="<?php echo $this->webroot; ?>Registration/mst_identification_remove/<?php echo $identifire['MstIdentification']['identification_id']; ?>" class="btn btn-danger"><?php echo __('lblbtndelete'); ?></a>

                </td>
            </tr>
<?php } ?>


    </tbody>
</table>

