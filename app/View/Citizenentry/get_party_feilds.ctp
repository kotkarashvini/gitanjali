
<script type="text/javascript">

    $(document).ready(function () {
        $('.age').prop('readonly', false);
        //$('.pan_no').hide();
        $('.permission_case_number').hide();
        $('.guardian_full_name_en').hide();

        $('[data-toggle="tooltip"]').tooltip();

        var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;

        $('.district_id').change(function () {
            var dist = $(".district_id option:selected").val();

            dist_change_event(dist);

        });


        $('.taluka_id').change(function () {
            var tal = $(".taluka_id option:selected").val();
            taluka_change_event(tal);


        });

        $(".village_id").on('change', function () {
            var village = $(".village_id").val();
            village_change_event(village);


        });

        $("#identificationtype_desc_en").blur(function () {
            var type = $(".identificationtype_id option:selected").val();
            var desc = $("#identificationtype_desc_en").val();
            if (type != '')
            {
                $.post(host + 'Citizenentry/get_validation_rule', {type: type, csrftoken: csrftoken}, function (data)
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
            } else {
                $("#identificationtype_desc_en").val('');
                $("#identificationtype_desc_en_error").text('Please Select type');
                return true;
            }

        });
        var desc = $(".pan_form_list option:selected").val();
        if (desc != null) {
            if (desc.trim() == 5) {
                $('.pan_no').show();
            } else {
                $('.pan_no').val('');
                $('.pan_no').hide();
            }


            $(".pan_form_list").change(function ()
            {
                var desc = $(".pan_form_list option:selected").val();

                if (desc.trim() == 5) {
                    $('.pan_no').show();
                } else {
                    $('.pan_no').hide();
                }



            });
        }
        $('.dob').datepicker({
            maxDate: '+0d',
            yearRange: '1920:2010',
            changeMonth: true,
            changeYear: true,
            endDate: '+0d',
            format: "dd-mm-yyyy"
        });
        $('.embossing_doc_date').datepicker({
            maxDate: '+0d',
            yearRange: '1920:2010',
            changeMonth: true,
            changeYear: true,
            format: "dd-mm-yyyy"
        });
        $('.reg_date').datepicker({
            maxDate: '+0d',
            yearRange: '1920:2010',
            changeMonth: true,
            changeYear: true,
            format: "dd-mm-yyyy"
        });

        $(".dob").change(function ()
        {

            var dateString = $(".dob").val();
            var d1 = dateString.split("-");
            var dateString = d1[1] + '/' + d1[0] + '/' + d1[2];
            var today = new Date();
            var birthDate = new Date(dateString);
            var age = today.getFullYear() - birthDate.getFullYear();
            var m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate()))
            {
                age--;
            }
            
             var guardian = $('.guardian_full_name_en').val();
                if (age < 18) {
                    if (guardian != 'undefined') {
                        $('.guardian_full_name_en').show();
                    }

                } else {
                    $('.guardian_full_name_en').hide();
                }
            $(".age").val(age);
            $('.age').prop('readonly', true);
        });

        $(".is_executer").change(function ()
        {
            var val = $(".is_executer").val();
            if (val == 'N') {
                alert('By Selecting "No" for this party ,Photo capture details gets Disabled at SRO');

            }
        });

        $(".pin_code").blur(function () {
            var pin = $(".pin_code").val();
            if (pin) {
                $.post(host + 'Citizenentry/get_village_bypin', {pin: pin}, function (data)
                {
                    if (data != 0) {
                        assign_dist_tal_village(data, 1);
                    }
                });
            }

        });
        $(".repete_add").on('change', function () {
            var repete_add = $(".repete_add").val();
            if (repete_add) {
                $.post(host + 'Citizenentry/get_address', {repete_add: repete_add}, function (data)
                {
                    assign_dist_tal_village(data, 2, repete_add);

                });
            }
        });

        $(".party_state_id").change(function ()
        {
            var party_state_id = $(".party_state_id").val();

            var party_state_id = $(".party_state_id option:selected").val();
            state_change_event(party_state_id);

        });

        //check percentage

        $(".share_percentage").blur(function () {


            var share = $(".share_percentage").val();
            var type = $(".party_type_id").val();

            $.post("<?php echo $this->webroot; ?>Citizenentry/get_share_percentage", {share: share, type: type}, function (data)
            {

                var total = parseInt(data) + parseInt(share);
                var diff = 100 - parseInt(data);

                if (data == 'Y') {
                    return true;
                } else {
                    if (total > 100) {
                        alert('Please enter value less than ' + diff);
                        $(".share_percentage").val(null);
                        return false;
                    }
                }
                return false;
            });
        });
        if ($(".maincast_id").val()) {
            var maincast_id = $(".maincast_id").val();
            $.post('<?php echo $this->webroot; ?>Citizenentry/check_permission_case', {maincast_id: maincast_id, csrftoken: csrftoken}, function (data)
            {
                if (data == 'Y') {
                    $('.permission_case_number').show();
                } else {
                    $('.permission_case_number').hide();
                }
            });

        }
        $(".maincast_id").on('change', function () {
            var maincast_id = $(".maincast_id").val();
             var subcast_id = $(".cast_id").val();
          
             if(subcast_id === undefined){
                 return false;
             }else{
            
            cast_change_event(maincast_id);
        }


        });

<?php if ($guardian_name == 'Y') { ?>
            $(".age").blur(function () {
                var age = $(".age").val();
                var guardian = $('.guardian_full_name_en').val();
                if (age < 18) {
                    if (guardian != 'undefined') {
                        $('.guardian_full_name_en').show();
                    }

                } else {
                    $('.guardian_full_name_en').hide();
                }
            });
<?php } ?>

    });


    function state_change_event(state) {
        var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
        $.post("<?php echo $this->webroot; ?>Citizenentry/state_change_event", {state: state, csrftoken: csrftoken}, function (data)
        {

            var sc = '<option>--select--</option>';
            $.each(data.dist, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $(".district_id").prop("disabled", false);
            $(".district_id option").remove();
            $(".district_id").append(sc);
            sortSelect('.district_id', 'text', 'asc');
        }, 'json');

    }
    function dist_change_event(dist_id) {
        var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
        $.post("<?php echo $this->webroot; ?>Citizenentry/district_change_event", {dist: dist_id, csrftoken: csrftoken}, function (data)
        {
            var sc = '<option>--select--</option>';
            $.each(data.taluka, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $(".taluka_id").prop("disabled", false);
            $(".taluka_id option").remove();
            $(".taluka_id").append(sc);
            sortSelect('.taluka_id', 'text', 'asc');
        }, 'json');
    }

    function taluka_change_event(tal)
    {
        var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
        $.post('<?php echo $this->webroot; ?>Citizenentry/taluka_change_event', {tal: tal, csrftoken: csrftoken}, function (data)
        {

            var sc = "<option value=" + 0 + ">--select--</option>";

            $.each(data.village, function (index, val) {

                sc += "<option value=" + index + ">" + val + "</option>";
            });

            $(".village_id option").remove();
            $(".village_id").append(sc);
            sortSelect('.village_id', 'text', 'asc');
        }, 'json');
    }

    function village_change_event(village) {
        var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
        $.post('<?php echo $this->webroot; ?>Citizenentry/behavioral_patterns', {village_id: village, ref_id: 2, behavioral_id: 2, csrftoken: csrftoken}, function (data)
        {

            $('.partyaddress').html(data);
            $(document).trigger('_page_ready');
            show_error_messages();

        });
    }



    function assign_dist_tal_village(data, ref, party_id) {
        var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
        var res = data.split(",");
        var dist = res[0].trim();
        var tal = res[1].trim();
        var village = res[2].trim();
        var state = res[3].trim();
        $(".district_id").val(dist);

        if (res) {
            if ($(".party_state_id").val() != 'undefined') {

                $(".party_state_id").val(state);

                $.post("<?php echo $this->webroot; ?>Citizenentry/state_change_event", {state: state, csrftoken: csrftoken}, function (data)
                {

                    var sc1 = '';
                    $.each(data.dist, function (index, val) {
                        if (index == dist) {
                            sc1 += "<option value=" + index + " selected>" + val + "</option>";
                        }
                    });
                    $(".district_id").prop("disabled", false);
                    $(".district_id option").remove();
                    $(".district_id").append(sc1);

                    sortSelect('.district_id', 'text', 'asc');
                }, 'json');
            }
            $.post("<?php echo $this->webroot; ?>Citizenentry/district_change_event", {dist: dist, csrftoken: csrftoken}, function (data1)
            {
                var sc2 = '<option>--select--</option>';
                $.each(data1.taluka, function (index, val) {

                    if (index == tal) {
                        sc2 += "<option value=" + index + " selected>" + val + "</option>";
                    }

                });
                $(".taluka_id").prop("disabled", false);
                $(".taluka_id option").remove();
                $(".taluka_id").append(sc2);


            }, 'json');

            $.post('<?php echo $this->webroot; ?>Citizenentry/taluka_change_event', {tal: tal, csrftoken: csrftoken}, function (data2)
            {

                var sc3 = '<option>--select--</option>';
                $.each(data2.village, function (index, val) {
                    if (index == village) {

                        sc3 += "<option value=" + index + " selected>" + val + "</option>";
                    } else {
                        sc3 += "<option value=" + index + " >" + val + "</option>";
                    }
                });

                $(".village_id option").remove();
                $(".village_id").append(sc3);
            }, 'json');
            if (ref == 1) {
                village_change_event(village);
            }
            if (ref == 2) {
                if (village.length && village != '') {

                    $.post(host + 'Citizenentry/behavioral_patterns', {ref_id: 2, behavioral_id: 2, village_id: village, ref_val: party_id, csrftoken: csrftoken}, function (data1)
                    {

                        $('.partyaddress').html(data1);
                    });
                }
            }


        } else {
            return true;
        }
    }


    function  cast_change_event(maincast_id)
    {
        var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
        
        
         var subcast_id = $(".cast_id").val();
          
             if(subcast_id === undefined){
                 return true;
             }else{
        
        $.post('<?php echo $this->webroot; ?>Citizenentry/get_sub_cast', {maincast_id: maincast_id, csrftoken: csrftoken}, function (data)
        {

            var sc = '<option>--select--</option>';
            $.each(data.cast, function (index, val) {

                sc += "<option value=" + index + ">" + val + "</option>";
            });

            $(".cast_id option").remove();
            $(".cast_id").append(sc);
            sortSelect('.cast_id', 'text', 'asc');
        }, 'json');
    }

<?php if ($permission_applicable == 'Y') { ?>
            $.post('<?php echo $this->webroot; ?>Citizenentry/check_permission_case', {maincast_id: maincast_id, csrftoken: csrftoken}, function (data)
            {
                if (data == 'Y') {
                    $('.permission_case_number').show();
                } else {
                    $('.permission_case_number').hide();
                }
            });
<?php } ?>
    }


</script>


<?php
$this->Form->create('party_entry', array('autocomplete' => 'off'));
$doc_lang = $this->Session->read('doc_lang');

if ($doc_lang == 'en') {
    //if ($marathi_template['conf_reg_bool_info']['conf_bool_value'] == 'N') {
    $info = NULL;
    // }
} else {
    $info = '[ENGLISH]';
}
?>
<div class="row">
    <div class="col-sm-12">
        <div class="box box-primary">
            <div class="box-body">
                <?php
// ngdrstab_mst_party_category_fields
                if (isset($partyfields) && !empty($partyfields)) {
                    if ($doc_lang == 'en') {
                        // if ($marathi_template['conf_reg_bool_info']['conf_bool_value'] == 'N') {
                        $info = NULL;
                        //  }
//                        else{
//                            $info = '[ENGLISH]';
//                        }
                    } else {
                        $info = '[ENGLISH]';
                    }
                    ?>
                    <div class="col-md-12">
                        <?php
                        $upadteflag = 0;

                        foreach ($partyfields as $field) {
                            $field = $field['party_category_fields'];

                            if (isset($party) && !empty($party)) {

                                $upadteflag = 1;
                                @$party1[$field['field_id_name_en']] = $party[0]['party_entry'][$field['field_id_name_en']];
                                if (isset($field['field_id_name_en'])) {
                                    if ($field['field_id_name_en'] == 'poa_id') {
                                        $party1[$field['field_id_name_en']] = $party[0]['party_entry']['power_attoney_party_id'];
                                    }
                                }

                                if ($field['field_id_name_ll']) {
                                    $party1[$field['field_id_name_ll']] = $party[0]['party_entry'][$field['field_id_name_ll']];
                                }
                                $party1['category_id'] = $party[0]['party_entry']['party_catg_id'];
                            } else {
                                $party1[$field['field_id_name_en']] = '';
                                $party1[$field['field_id_name_ll']] = '';
                                $party1['category_id'] = $field['category_id'];
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

                                                <?php
                                           
                                                
                                                   if(isset($fname) && $fname!='' && $field['field_id_name_ll']=='party_fname_ll'){
                                                       //echo 'in if';
                                                         $dispnamef=$fname;
                                                         //echo $dispnamef;
                                                     }
                                                     else if(isset($mname) && $mname!='' && $field['field_id_name_ll']=='party_mname_ll'){
                                                         $dispnamef=$mname;
                                                     }
                                                     else if(isset($lname) && $lname!='' && $field['field_id_name_ll']=='party_lname_ll'){
                                                         $dispnamef=$lname;
                                                     }
                                                     else{
                                                         //echo 'in else';
                                                         $dispnamef=$party1[$field['field_id_name_ll']];
                                                     }
                                                ?>   
                                            

                                                <?php if ($field['is_mask'] == 'Y') { ?>
                                                    <?php echo $this->Form->input($field['field_id_name_ll'], array('label' => false, 'id' => $field['field_id_name_ll'] . $field['category_id'], 'class' => 'form-control input-sm ' . $field['field_id_name_ll'], 'type' => 'password', 'default' => $party1[$field['field_id_name_ll']])) ?>
                                                <?php } else { ?>
                                                    <!--<?php echo $this->Form->input($field['field_id_name_ll'], array('label' => false, 'id' => $field['f<!--ield_id_name_ll'] . $field['category_id'], 'class' => 'form-control input-sm ' . $field['field_id_name_ll'], 'type' => 'text', 'default' => $party1[$field['field_id_name_ll']])) ?>-->
                                                    <?php echo $this->Form->input($field['field_id_name_ll'], array('label' => false, 'id' => $field['field_id_name_ll'], 'class' => 'form-control input-sm', 'type' => 'text','value'=>$dispnamef, 'default' => $party1[$field['field_id_name_ll']])) ?>
                                                <?php } ?>
                                                <span id="<?php echo $field['field_id_name_ll'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                    ?></span>
                                                  <!--<span id="party_fname_ll_error" class="form-error"><?php //echo //$errarr['party_fname_ll_error'];                     ?></span>-->
                                            </div>
                                            <?php
                                        }
                                    }
//                                  
                                    ?>

                                    <label  class="col-sm-3 control-label"> <?php
                                        if ($field['field_name_' . $doc_lang]) {
                                            echo $field['field_name_' . $doc_lang]
                                            ?> :-<?php echo $info; ?> <span style="color: #ff0000"><?php echo $field['is_required'] ?></span><?php } ?></label>
                                    <div class="col-sm-3">
                                        <?php
                                        switch ($field['field_id_name_en']) {
                                            case 'bank_id': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field['field_id_name_en'], 'label' => false, 'options' => $bank_master, 'default' => $party1[$field['field_id_name_en']]));
                                                ?>

                                                <span id="<?php echo $field['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                        ?></span>
                                                       <!--<span id="bank_id_error" class="form-error"><?php //echo $errarr['bank_id_error'];                            ?></span>-->
                                                <?php
                                                break;
                                            case 'country_id': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field['field_id_name_en'], 'label' => false, 'options' => $Country, 'empty' => '--Select--', 'default' => $party1[$field['field_id_name_en']]));
                                                ?>
                                                       <!--<span id="maincast_id_error" class="form-error"><?php //echo $errarr['is_executer_error'];                            ?></span>--> 
                                                <span id="<?php echo $field['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                        ?></span>
                                               <?php
                                                break;
                                            case 'maincast_id': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field['field_id_name_en'], 'label' => false, 'options' => $maincast, 'empty' => '--Select--', 'default' => $party1[$field['field_id_name_en']]));
                                                ?>
                                                       <!--<span id="maincast_id_error" class="form-error"><?php //echo $errarr['is_executer_error'];                            ?></span>--> 
                                                <span id="<?php echo $field['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                        ?></span>


 <?php
                                                break;
                                            case 'is_executer': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field['field_id_name_en'], 'label' => false, 'options' => $executer, 'default' => $party1[$field['field_id_name_en']], 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'By Selecting "No" for this party ,Photo capture details gets Disabled at SRO'));
                                                ?>
                                                <span id="<?php echo $field['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                        ?></span>
                                                                                              <!--<span id="is_executer_error" class="form-error"><?php //echo $errarr['is_executer_error'];                            ?></span>-->
                                                <?php
                                                break;
                                            case 'pay_flag': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field['field_id_name_en'], 'label' => false, 'options' => $executer, 'default' => $party1[$field['field_id_name_en']]));
                                                ?>
                                                                                              <!--<span id="is_executer_error" class="form-error"><?php //echo $errarr['is_executer_error'];                            ?></span>-->
                                                <span id="<?php echo $field['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                        ?></span>
                                                <?php
                                                break;
                                            case 'is_stamp_purchaser': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field['field_id_name_en'], 'label' => false, 'options' => $stamp_purchaser, 'default' => $party1[$field['field_id_name_en']]));
                                                ?>
                                                                                               <!--<span id="is_executer_error" class="form-error"><?php //echo $errarr['is_executer_error'];                            ?></span>-->
                                                <span id="<?php echo $field['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                        ?></span>
                                                <?php
                                                break;
                                            case 'presenty_require': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field['field_id_name_en'], 'label' => false, 'options' => $executer, 'default' => $party1[$field['field_id_name_en']]));
                                                ?>
                                                 <!--<span id="presenty_require_error" class="form-error"><?php //echo $errarr['is_executer_error'];                            ?></span>-->
                                                <span id="<?php echo $field['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                        ?></span>
                                                <?php
                                                break;
                                            case 'power_attoney_party_id': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field['field_id_name_en'], 'label' => false, 'options' => $allparty, 'empty' => '--Select--', 'default' => $party1[$field['field_id_name_en']]));
                                                ?>
                                                                                               <!--<span id="power_attoney_party_id_error" class="form-error"><?php //echo $errarr['is_executer_error'];                            ?></span>-->
                                                <span id="<?php echo $field['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                        ?></span>
                                                <?php
                                                break;
                                            case 'poa_id':
                                                ?>
                                                <span style="color:#CD5C5C; font-weight: bold;">This is for Repeat Party of Power of Attroney</span>        
                                                <?php echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'], 'class' => $field['field_id_name_en'] . $field['category_id'], 'type' => 'select', 'label' => false, 'options' => $allparty, 'multiple' => 'checkbox', 'default' => $party1[$field['field_id_name_en']]));
                                                ?>
                                                <!--<span id="poa_id_error" class="form-error"><?php //echo $errarr['is_executer_error'];              ?></span>-->
                                                <span id="<?php echo $field['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                        ?></span>

                                                <?php
                                                break;
                                            case 'home_visit_flag': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field['field_id_name_en'], 'label' => false, 'options' => $homevisit, 'default' => $party1[$field['field_id_name_en']]));
                                                ?>
                                                                                                                                                                                                                <!--<span id="home_visit_error" class="form-error"><?php //echo $errarr['is_executer_error'];                            ?></span>-->
                                                <span id="<?php echo $field['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                        ?></span>  
                                                <?php
                                                break;
                                            case 'salutation_id':echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field['field_id_name_en'], 'label' => false, 'options' => $salutation, 'default' => $party1[$field['field_id_name_en']]));
                                                ?>
                                                  <!--<span id="salutation_id_error" class="form-error"><?php // echo $errarr['salutation_id_error'];                            ?></span>-->
                                                <span id="<?php echo $field['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                        ?></span>
                                                <?php
                                                break;
                                            case 'gender_id': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field['field_id_name_en'], 'label' => false, 'options' => $gender, 'default' => $party1[$field['field_id_name_en']]));
                                                ?>
                                               <!--<span id="gender_id_error" class="form-error"><?php ///echo $errarr['gender_id_error'];                            ?></span>-->
                                                <span id="<?php echo $field['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                        ?></span>
                                                <?php
                                                break;
                                            case 'marital_status': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field['field_id_name_en'], 'label' => false, 'options' => $marital_status, 'default' => $party1[$field['field_id_name_en']]));
                                                ?>
                                                                                               <!--<span id="marital_status_error" class="form-error"><?php ///echo $errarr['gender_id_error'];             ?></span>-->
                                                <span id="<?php echo $field['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                        ?></span>  
                                                <?php
                                                break;
                                            case 'nationality': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field['field_id_name_en'], 'label' => false, 'options' => $nationality, 'default' => $party1[$field['field_id_name_en']]));
                                                ?>
                                                  <!--<span id="nationality_error" class="form-error"><?php ///echo $errarr['gender_id_error'];             ?></span>-->
                                                <span id="<?php echo $field['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                        ?></span>    
                                                <?php
                                                break;
                                            case 'exmption_trible': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field['field_id_name_en'], 'label' => false, 'options' => $industrial, 'default' => $party1[$field['field_id_name_en']]));
                                                ?>
                                                    <!--<span id="nationality_error" class="form-error"><?php ///echo $errarr['gender_id_error'];             ?></span>-->
                                                <span id="<?php echo $field['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                        ?></span>
                                                <?php
                                                break;
                                            case 'cast_id': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field['field_id_name_en'], 'label' => false, 'options' => $category, 'default' => $party1[$field['field_id_name_en']]));
                                                ?>
                                                                                               <!--<span id="cast_id_error" class="form-error"><?php ///echo $errarr['gender_id_error'];                               ?></span>-->
                                                <span id="<?php echo $field['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                        ?></span>
                                                <?php
                                                break;
                                            case 'occupation_id': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field['field_id_name_en'], 'label' => false, 'options' => $occupation, 'default' => $party1[$field['field_id_name_en']]));
                                                ?>
                                                                                               <!--<span id="occupation_id_error" class="form-error"><?php //echo $errarr['occupation_id_error'];                            ?></span>-->
                                                <span id="<?php echo $field['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                        ?></span> 
                                                <?php
                                                break;
                                            case 'identificationtype_id': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field['field_id_name_en'], 'label' => false, 'options' => $identificatontype, 'empty' => '--Select--', 'default' => $party1[$field['field_id_name_en']]));
                                                ?>
                                                <!--<span id="identificationtype_id_error" class="form-error"><?php //echo $errarr['identificationtype_id_error'];                            ?></span>-->
                                                <span id="<?php echo $field['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                        ?></span>    
                                                <?php
                                                break;
                                            case 'pan_form_list': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field['field_id_name_en'], 'label' => false, 'options' => $panlist, 'empty' => '--Select--', 'default' => $party1[$field['field_id_name_en']]));
                                                ?>
                                                <span id="<?php echo $field['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                        ?></span>
                                                   <!--<span id="pan_form_list_error" class="form-error"><?php //echo $errarr['identificationtype_id_error'];                            ?></span>-->  
                                                <?php
                                                break;
                                            case 'exemption_id': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field['field_id_name_en'], 'label' => false, 'options' => $exemption, 'empty' => '--Not Applicable--', 'default' => $party1[$field['field_id_name_en']]));
                                                ?>
                                                <span id="<?php echo $field['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                        ?></span>
                                                  <!--<span id="exemption_id_error" class="form-error"><?php //echo $errarr['exemption_id_error'];                            ?></span>-->
                                                <?php
                                                break;
                                            case 'repete_add': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field['field_id_name_en'], 'label' => false, 'options' => $allparty1, 'empty' => '--Select--', 'default' => $party1[$field['field_id_name_en']]));
                                                ?>
                                                  <!--<span id="repete_add_error" class="form-error"><?php //echo $errarr['exemption_id_error'];            ?></span>-->
                                                <span id="<?php echo $field['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                        ?></span> 
                                                <?php
                                                break;
                                            case 'party_state_id': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field['field_id_name_en'], 'label' => false, 'options' => $State, 'empty' => '--Select--', 'default' => $party1[$field['field_id_name_en']]));
                                                ?>
                                                 <!--<span id="party_state_id_error" class="form-error"><?php //echo $errarr['district_id_error'];                             ?></span>-->
                                                <span id="<?php echo $field['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                        ?></span>
                                                <?php
                                                break;
                                            case 'district_id': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field['field_id_name_en'], 'label' => false, 'options' => $districtdata, 'empty' => '--Select--', 'default' => $party1[$field['field_id_name_en']]));
                                                ?>
                                                                                               <!--<span id="district_id_error" class="form-error"><?php //echo $errarr['district_id_error'];                            ?></span>-->
                                                <span id="<?php echo $field['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                        ?></span>
                                                <?php
                                                break;
                                            case 'taluka_id': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field['field_id_name_en'], 'label' => false, 'options' => $taluka, 'selected' => $party1[$field['field_id_name_en']]));
                                                ?>
                                                <span id="<?php echo $field['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                        ?></span>
                                               <!--<span id="taluka_id_error" class="form-error"><?php //echo $errarr['taluka_id_error'];                            ?></span>-->
                                                <?php
                                                break;
                                            case 'village_id': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field['field_id_name_en'], 'label' => false, 'options' => $villagelist, 'default' => $party1[$field['field_id_name_en']]));
                                                ?>
                                                <span id="<?php echo $field['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                        ?></span>
                                              <!--<span id="village_id_error" class="form-error"><?php //echo $errarr['village_id_error'];                               ?></span>-->

                                                <?php
                                                break;
                                            case 'is_uid_consent': echo $this->Form->input($field['field_id_name_en'], array('id' => $field['field_id_name_en'] . $field['category_id'], 'class' => $field['field_id_name_en'], 'type' => 'select', 'class' => 'form-control input-sm chosen-select ' . $field['field_id_name_en'], 'label' => false, 'options' => $homevisit, 'default' => $party1[$field['field_id_name_en']], 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'I hereby state that I have no objection in authenticating myself with Aadhaar based authentication system and consent to provide my Aadhaar Number, Biometric for Aadhaar based know your customer. I also give my explicit consent for accessing the mobile number and address from Aadhaar System.'));
                                                ?>
                                                <span id="<?php echo $field['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                        ?></span>
                                             <!--<span id="is_uid_consent_error" class="form-error"><?php //echo $errarr['village_id_error'];                               ?></span>-->
                                                <?php
                                                break;
                                            default:
                                                if ($field['is_mask'] == 'Y') {
                                                    echo $this->Form->input($field['field_id_name_en'], array('label' => false, 'id' => $field['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm ' . $field['field_id_name_en'], 'type' => 'password', 'value' => $party1[$field['field_id_name_en']]));
                                                } else {


                                                    if ($rejected == 'Y' && isset($party_type) && $party_type == 1) {

                                                        if ($field['field_id_name_en'] == 'party_lname_en') {

                                                            echo $this->Form->input($field['field_id_name_en'], array('label' => false, 'id' => $field['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm ' . $field['field_id_name_en'], 'type' => 'text', 'placeholder' => $field['placeholder_' . $doc_lang], 'value' => $party1[$field['field_id_name_en']], 'readonly' => 'readonly'));
                                                        } else {
                                                            echo $this->Form->input($field['field_id_name_en'], array('label' => false, 'id' => $field['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm ' . $field['field_id_name_en'], 'type' => 'text', 'placeholder' => $field['placeholder_' . $doc_lang], 'value' => $party1[$field['field_id_name_en']]));
                                                        }
                                                    } else {

                                                        if ($field['field_id_name_en'] == 'identificationtype_desc_en') {
                                                            echo $this->Form->input($field['field_id_name_en'], array('label' => false, 'id' => $field['field_id_name_en'], 'class' => 'form-control input-sm ' . $field['field_id_name_en'], 'type' => 'text', 'placeholder' => $field['placeholder_' . $doc_lang], 'value' => $party1[$field['field_id_name_en']]));
                                                            ?>
                                                            <span id="<?php echo $field['field_id_name_en']; ?>_error" class="form-error"><?php //echo $errarr['party_fname_en_error'];                                                                    ?></span>
                                                            <?php
                                                        } else {
                                                            //echo $this->Form->input($field['field_id_name_en'], array('label' => false, 'id' => $field['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm ' . $field['field_id_name_en'], 'type' => 'text', 'placeholder' => $field['placeholder_' . $doc_lang], 'value' => $party1[$field['field_id_name_en']]));
                                                            echo $this->Form->input($field['field_id_name_en'], array('label' => false, 'id' => $field['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm ' . $field['field_id_name_en'], 'type' => 'text', 'placeholder' => $field['placeholder_' . $doc_lang], 'value' => $party1[$field['field_id_name_en']]));
                                                        }
                                                        ?>
                                                        <span id="<?php echo $field['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php //echo $errarr['party_fname_en_error'];                                                                   ?></span>
                                                        <?php
                                                        //   echo $this->Form->input($field['field_id_name_en'], array('label' => false, 'id' => $field['field_id_name_en'], 'class' => 'form-control input-sm ' . $field['field_id_name_en'], 'type' => 'text', 'placeholder' => $field['placeholder_' . $doc_lang], 'value' => $party1[$field['field_id_name_en']]));
                                                    }
                                                }
                                                ?>
                <!--<span id="<?php //echo $field['field_id_name_en'];       ?>_error" class="form-error"><?php
                                                //echo $errarr['party_fname_en_error']; 
                                                //                                              
                                                ?></span>-->
                                                <span id="<?php echo $field['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                    ?></span>
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
                <?php
                if (isset($auth_sign)&& $auth_sign == 'Y') {
                    if (!empty($signatory) && isset($signatory)) {
                        ?>

                        <div class="col-sm-12">

                            <div class="box-header with-border">
                                <h3 class="box-title headbolder"><?php echo __('lblautosigndetails'); ?></h3>
                            </div>
                            <div  class="rowht">&nbsp;</div>
                            <?php
                            $upadteflag = 0;
                            foreach ($signatory as $field1) {
                                //   if($field['field_id_name'])
                                $field1 = $field1['party_category_fields'];
                                if (isset($party) && !empty($party)) {

                                    $upadteflag = 1;
                                    $party1[$field1['field_id_name_en']] = $party[0]['party_entry'][$field1['field_id_name_en']];
                                    if ($field1['field_id_name_ll']) {
                                        $party1[$field1['field_id_name_ll']] = $party[0]['party_entry'][$field1['field_id_name_ll']];
                                    }
                                    $party1['category_id'] = $party[0]['party_entry']['party_catg_id'];
                                } else {
                                    $party1[$field1['field_id_name_en']] = '';
                                    $party1[$field1['field_id_name_ll']] = '';
                                    $party1['category_id'] = $field1['category_id'];
                                }
                                ?>
                                <div  class="rowht">&nbsp;</div>
                                <div class="row">
                                    <div class="form-group">
                                        <?php
                                        if (!empty($doc_lang) and $doc_lang != 'en') {
                                            if ($field1['field_id_name_ll'] != '') {
                                                ?>
                                                <label  class="col-sm-3 control-label"> <?php echo $field1['field_name_' . $doc_lang] ?> <span style="color: #ff0000"><?php echo $field1['is_required'] ?></span></label>
                                                <div class="col-sm-3">
                                                    <?php if ($field1['is_mask'] == 'Y') { ?>
                                                        <?php echo $this->Form->input($field1['field_id_name_ll'], array('label' => false, 'id' => $field1['field_id_name_ll'] . $field['category_id'], 'class' => 'form-control input-sm ' . $field1['field_id_name_ll'], 'type' => 'password', 'default' => $party1[$field1['field_id_name_ll']])) ?>
                                                    <?php } else { ?>
                                                        <?php echo $this->Form->input($field1['field_id_name_ll'], array('label' => false, 'id' => $field1['field_id_name_ll'] . $field['category_id'], 'class' => 'form-control input-sm ' . $field1['field_id_name_ll'], 'type' => 'text', 'default' => $party1[$field1['field_id_name_ll']])) ?>
                                                    <?php } ?>
                                                    <!--<span id="<?php //echo $field1['field_id_name_ll'];       ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                   ?></span>-->
                                                    <span id="<?php echo $field1['field_id_name_ll'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                     ?></span>
                                                     <!--<span id="party_fname_ll_error" class="form-error"><?php //echo //$errarr['party_fname_ll_error'];                    ?></span>-->
                                                </div>
                                                <?php
                                            }
                                        }
//                                  
                                        ?>

                                        <label  class="col-sm-3 control-label"> <?php
                                            if ($field1['field_name_' . $doc_lang]) {
                                                echo $field1['field_name_' . $doc_lang]
                                                ?> :-<?php echo $info; ?> <span style="color: #ff0000"><?php echo $field1['is_required'] ?></span><?php } ?></label>
                                        <div class="col-sm-3">
                                            <?php
                                            switch ($field1['field_id_name_en']) {
                                                case 'bank_id': echo $this->Form->input($field1['field_id_name_en'], array('id' => $field1['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field1['field_id_name_en'], 'label' => false, 'options' => $bank_master, 'default' => $party1[$field1['field_id_name_en']]));
                                                    ?>
                                                    <span id="<?php echo $field1['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                      ?></span>
                                                           <!--<span id="bank_id_error" class="form-error"><?php //echo $errarr['bank_id_error'];                          ?></span>-->
                                                    <?php
                                                    break;
                                                case 'maincast_id': echo $this->Form->input($field1['field_id_name_en'], array('id' => $field1['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field1['field_id_name_en'], 'label' => false, 'options' => $maincast, 'empty' => '--Select--', 'default' => $party1[$field1['field_id_name_en']]));
                                                    ?>
                                                           <!--<span id="maincast_id_error" class="form-error"><?php //echo $errarr['is_executer_error'];                           ?></span>--> 
                                                    <span id="<?php echo $field1['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                     ?></span>
                                                    <?php
                                                    break;
                                                case 'is_executer': echo $this->Form->input($field1['field_id_name_en'], array('id' => $field1['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field1['field_id_name_en'], 'label' => false, 'options' => $executer, 'default' => $party1[$field1['field_id_name_en']]));
                                                    ?>
                                                                                                        <!--<span id="is_executer_error" class="form-error"><?php //echo $errarr['is_executer_error'];                         ?></span>-->
                                                    <span id="<?php echo $field1['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                     ?></span>   
                                                    <?php
                                                    break;
                                                case 'pay_flag': echo $this->Form->input($field1['field_id_name_en'], array('id' => $field1['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field1['field_id_name_en'], 'label' => false, 'options' => $executer, 'default' => $party1[$field1['field_id_name_en']]));
                                                    ?>
                                                        <!--<span id="is_executer_error" class="form-error"><?php //echo $errarr['is_executer_error'];                           ?></span>-->
                                                    <span id="<?php echo $field1['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                     ?></span>
                                                    <?php
                                                    break;
                                                case 'is_stamp_purchaser': echo $this->Form->input($field1['field_id_name_en'], array('id' => $field1['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field1['field_id_name_en'], 'label' => false, 'options' => $stamp_purchaser, 'default' => $party1[$field1['field_id_name_en']]));
                                                    ?>
                                                                                                        <!--<span id="is_executer_error" class="form-error"><?php //echo $errarr['is_executer_error'];                         ?></span>-->
                                                    <span id="<?php echo $field1['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                     ?></span>
                                                    <?php
                                                    break;
                                                case 'presenty_require': echo $this->Form->input($field1['field_id_name_en'], array('id' => $field1['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field1['field_id_name_en'], 'label' => false, 'options' => $executer, 'default' => $party1[$field1['field_id_name_en']]));
                                                    ?>
                                                      <!--<span id="presenty_require_error" class="form-error"><?php //echo $errarr['is_executer_error'];                           ?></span>-->
                                                    <span id="<?php echo $field1['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                     ?></span>
                                                    <?php
                                                    break;
                                                case 'power_attoney_party_id': echo $this->Form->input($field1['field_id_name_en'], array('id' => $field1['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field1['field_id_name_en'], 'label' => false, 'options' => $allparty, 'empty' => '--Select--', 'default' => $party1[$field1['field_id_name_en']]));
                                                    ?>
                                                                                                        <!--<span id="power_attoney_party_id_error" class="form-error"><?php //echo $errarr['is_executer_error'];                           ?></span>-->
                                                    <span id="<?php echo $field1['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                     ?></span>
                                                    <?php
                                                    break;
                                                case 'poa_id':
                                                    ?>
                                                    <span style="color:#CD5C5C; font-weight: bold;">This is for Repeat Party of Power of Attroney</span>        
                                                    <?php echo $this->Form->input($field1['field_id_name_en'], array('id' => $field1['field_id_name_en'] . $field['category_id'], 'class' => $field1['field_id_name_en'], 'type' => 'select', 'label' => false, 'options' => $allparty, 'multiple' => 'checkbox', 'default' => $party1[$field1['field_id_name_en']]));
                                                    ?>
                                                    <span id="<?php echo $field1['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                     ?></span>
                                                    <!--<span id="poa_id_error" class="form-error"><?php //echo $errarr['is_executer_error'];             ?></span>-->


                                                    <?php
                                                    break;
                                                case 'home_visit': echo $this->Form->input($field1['field_id_name_en'], array('id' => $field1['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field1['field_id_name_en'], 'label' => false, 'options' => $executer, 'default' => $party1[$field1['field_id_name_en']]));
                                                    ?>
                                                                                                                                                                            <!--<span id="home_visit_error" class="form-error"><?php //echo $errarr['is_executer_error'];                         ?></span>-->
                                                    <span id="<?php echo $field1['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                     ?></span>
                                                    <?php
                                                    break;
                                                case 'salutation_id':echo $this->Form->input($field1['field_id_name_en'], array('id' => $field1['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field1['field_id_name_en'], 'label' => false, 'options' => $salutation, 'default' => $party1[$field1['field_id_name_en']]));
                                                    ?>
                                                       <!--<span id="salutation_id_error" class="form-error"><?php // echo $errarr['salutation_id_error'];                         ?></span>-->
                                                    <span id="<?php echo $field1['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                     ?></span> 
                                                    <?php
                                                    break;
                                                case 'gender_id': echo $this->Form->input($field1['field_id_name_en'], array('id' => $field1['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field1['field_id_name_en'], 'label' => false, 'options' => $gender, 'default' => $party1[$field1['field_id_name_en']]));
                                                    ?>
                                                        <!--<span id="gender_id_error" class="form-error"><?php ///echo $errarr['gender_id_error'];                           ?></span>-->
                                                    <span id="<?php echo $field1['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                     ?></span>
                                                    <?php
                                                    break;
                                                case 'marital_status': echo $this->Form->input($field1['field_id_name_en'], array('id' => $field1['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field1['field_id_name_en'], 'label' => false, 'options' => $marital_status, 'default' => $party1[$field1['field_id_name_en']]));
                                                    ?>
                                                                                                        <!--<span id="marital_status_error" class="form-error"><?php ///echo $errarr['gender_id_error'];          ?></span>-->
                                                    <span id="<?php echo $field1['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                     ?></span>
                                                    <?php
                                                    break;
                                                case 'nationality': echo $this->Form->input($field1['field_id_name_en'], array('id' => $field1['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field1['field_id_name_en'], 'label' => false, 'options' => $nationality, 'default' => $party1[$field1['field_id_name_en']]));
                                                    ?>
                                                    <span id="<?php echo $field1['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                     ?></span>
                                                     <!--<span id="nationality_error" class="form-error"><?php ///echo $errarr['gender_id_error'];          ?></span>-->
                                                    <?php
                                                    break;
                                                case 'exmption_trible': echo $this->Form->input($field1['field_id_name_en'], array('id' => $field1['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field1['field_id_name_en'], 'label' => false, 'options' => $industrial, 'default' => $party1[$field1['field_id_name_en']]));
                                                    ?>
                                                    <span id="<?php echo $field1['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                     ?></span>
                                                     <!--<span id="nationality_error" class="form-error"><?php ///echo $errarr['gender_id_error'];            ?></span>-->

                                                    <?php
                                                    break;
                                                case 'cast_id': echo $this->Form->input($field1['field_id_name_en'], array('id' => $field1['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field1['field_id_name_en'], 'label' => false, 'options' => $category, 'default' => $party1[$field1['field_id_name_en']]));
                                                    ?>
                                                    <span id="<?php echo $field1['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                     ?></span>
                                                     <!--<span id="cast_id_error" class="form-error"><?php ///echo $errarr['gender_id_error'];                              ?></span>-->

                                                    <?php
                                                    break;
                                                case 'occupation_id': echo $this->Form->input($field1['field_id_name_en'], array('id' => $field1['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field1['field_id_name_en'], 'label' => false, 'options' => $occupation, 'default' => $party1[$field1['field_id_name_en']]));
                                                    ?>
                                                    <span id="<?php echo $field1['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                     ?></span>
                                                     <!--<span id="occupation_id_error" class="form-error"><?php //echo $errarr['occupation_id_error'];                         ?></span>-->
                                                    <?php
                                                    break;
                                                case 'identificationtype_id': echo $this->Form->input($field1['field_id_name_en'], array('id' => $field1['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field1['field_id_name_en'], 'label' => false, 'options' => $identificatontype, 'empty' => '--Select--', 'default' => $party1[$field1['field_id_name_en']]));
                                                    ?>
                                                    <span id="<?php echo $field1['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                     ?></span>
                                                     <!--<span id="identificationtype_id_error" class="form-error"><?php //echo $errarr['identificationtype_id_error'];                         ?></span>-->
                                                    <?php
                                                    break;
                                                case 'pan_form_list': echo $this->Form->input($field1['field_id_name_en'], array('id' => $field1['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field1['field_id_name_en'], 'label' => false, 'options' => $panlist, 'empty' => '--Select--', 'default' => $party1[$field1['field_id_name_en']]));
                                                    ?>
                                                    <span id="<?php echo $field1['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                     ?></span>
                                                    <!--<span id="pan_form_list_error" class="form-error"><?php //echo $errarr['identificationtype_id_error'];                         ?></span>-->  
                                                    <?php
                                                    break;
                                                case 'exemption_id': echo $this->Form->input($field1['field_id_name_en'], array('id' => $field1['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field1['field_id_name_en'], 'label' => false, 'options' => $exemption, 'empty' => '--Not Applicable--', 'default' => $party1[$field1['field_id_name_en']]));
                                                    ?>
                                                    <span id="<?php echo $field1['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                    ?></span>
                                                    <!--<span id="exemption_id_error" class="form-error"><?php //echo $errarr['exemption_id_error'];                         ?></span>-->
                                                    <?php
                                                    break;
                                                case 'repete_add': echo $this->Form->input($field1['field_id_name_en'], array('id' => $field1['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field1['field_id_name_en'], 'label' => false, 'options' => $allparty1, 'empty' => '--Select--', 'default' => $party1[$field1['field_id_name_en']]));
                                                    ?>
                                                    <span id="<?php echo $field1['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                    ?></span>
                                                    <!--<span id="repete_add_error" class="form-error"><?php //echo $errarr['exemption_id_error'];         ?></span>-->
                                                    <?php
                                                    break;
                                                case 'party_state_id': echo $this->Form->input($field1['field_id_name_en'], array('id' => $field1['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field1['field_id_name_en'], 'label' => false, 'options' => $State, 'empty' => '--Select--', 'default' => $party1[$field1['field_id_name_en']]));
                                                    ?>
                                                    <span id="<?php echo $field1['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                    ?></span>
                                                    <!--<span id="party_state_id_error" class="form-error"><?php //echo $errarr['district_id_error'];                            ?></span>-->

                                                    <?php
                                                    break;
                                                case 'district_id': echo $this->Form->input($field1['field_id_name_en'], array('id' => $field1['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field1['field_id_name_en'], 'label' => false, 'options' => $districtdata, 'empty' => '--Select--', 'default' => $party1[$field1['field_id_name_en']]));
                                                    ?>
                                                    <span id="<?php echo $field1['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                    ?></span>
                                                    <!--<span id="district_id_error" class="form-error"><?php //echo $errarr['district_id_error'];                         ?></span>-->
                                                    <?php
                                                    break;
                                                case 'taluka_id': echo $this->Form->input($field1['field_id_name_en'], array('id' => $field1['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field1['field_id_name_en'], 'label' => false, 'options' => $taluka, 'selected' => $party1[$field1['field_id_name_en']]));
                                                    ?>
                                                    <span id="<?php echo $field1['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                    ?></span>
                                                    <!--<span id="taluka_id_error" class="form-error"><?php //echo $errarr['taluka_id_error'];                         ?></span>-->
                                                    <?php
                                                    break;
                                                case 'village_id': echo $this->Form->input($field1['field_id_name_en'], array('id' => $field1['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm chosen-select ' . $field1['field_id_name_en'], 'label' => false, 'options' => $villagelist, 'default' => $party1[$field1['field_id_name_en']]));
                                                    ?>
                                                    <span id="<?php echo $field1['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php // echo $errarr['party_fname_ll_error'];                                    ?></span>
                                                    <!--<span id="village_id_error" class="form-error"><?php //echo $errarr['village_id_error'];                              ?></span>-->

                                                    <?php
                                                    break;
                                                default:
                                                    if ($field1['is_mask'] == 'Y') {
                                                        echo $this->Form->input($field1['field_id_name_en'], array('label' => false, 'id' => $field1['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm ' . $field1['field_id_name_en'], 'type' => 'password', 'value' => $party1[$field1['field_id_name_en']]));
                                                    } else {


                                                        if ($rejected == 'Y' && isset($party_type) && $party_type == 1) {

                                                            if ($field1['field_id_name_en'] == 'party_lname_en') {

                                                                echo $this->Form->input($field1['field_id_name_en'], array('label' => false, 'id' => $field1['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm ' . $field1['field_id_name_en'], 'type' => 'text', 'placeholder' => $field1['placeholder_' . $doc_lang], 'value' => $party1[$field1['field_id_name_en']], 'readonly' => 'readonly'));
                                                            } else {
                                                                echo $this->Form->input($field1['field_id_name_en'], array('label' => false, 'id' => $field1['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm ' . $field1['field_id_name_en'], 'type' => 'text', 'placeholder' => $field1['placeholder_' . $doc_lang], 'value' => $party1[$field1['field_id_name_en']]));
                                                            }
                                                        } else {

                                                            if ($field1['field_id_name_en'] == 'identificationtype_desc_en') {
                                                                echo $this->Form->input($field1['field_id_name_en'], array('label' => false, 'id' => $field1['field_id_name_en'], 'class' => 'form-control input-sm ' . $field1['field_id_name_en'], 'type' => 'text', 'placeholder' => $field1['placeholder_' . $doc_lang], 'value' => $party1[$field1['field_id_name_en']]));
                                                                ?>
                                                                <span id="<?php echo $field1['field_id_name_en']; ?>_error" class="form-error"><?php //echo $errarr['party_fname_en_error'];                                                                   ?></span>
                                                                <?php
                                                            } else {
                                                                //echo $this->Form->input($field['field_id_name_en'], array('label' => false, 'id' => $field['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm ' . $field['field_id_name_en'], 'type' => 'text', 'placeholder' => $field['placeholder_' . $doc_lang], 'value' => $party1[$field['field_id_name_en']]));
                                                                echo $this->Form->input($field1['field_id_name_en'], array('label' => false, 'id' => $field1['field_id_name_en'] . $field1['category_id'], 'class' => 'form-control input-sm ' . $field1['field_id_name_en'], 'type' => 'text', 'placeholder' => $field1['placeholder_' . $doc_lang], 'value' => $party1[$field1['field_id_name_en']]));
                                                            }
                                                            ?>
                                                            <span id="<?php echo $field1['field_id_name_en'] . $field1['category_id']; ?>_error" class="form-error"><?php //echo $errarr['party_fname_en_error'];                                                                  ?></span>
                                                            <?php
                                                            //  echo $this->Form->input($field1['field_id_name_en'], array('label' => false, 'id' => $field1['field_id_name_en'] . $field['category_id'], 'class' => 'form-control input-sm ' . $field1['field_id_name_en'], 'type' => 'text', 'placeholder' => $field1['placeholder_' . $doc_lang], 'value' => $party1[$field1['field_id_name_en']]));
                                                        }
                                                    }
                                                    ?>
                                                    <span id="<?php echo $field1['field_id_name_en'] . $field['category_id']; ?>_error" class="form-error"><?php //echo $errarr['party_fname_en_error'];                                                    ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?> 
                        </div> 
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="box box-primary">
            <div class="box-body">
                <div class="box-body partyaddress" ></div>
            </div>
        </div>
    </div>
</div>

<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>

<!--<script type="text/javascript" src="www.facebook.com"></script>

<script type="text/javascript">
google.load("elements", "1", {packages: "transliteration"});
</script> 


<script>
 function OnLoad() {                
  //   alert('onload');
   //  var variable=document.getElementById('party_fname_ll').value;
    // alert(variable);
    var options = {
        sourceLanguage:
        google.elements.transliteration.LanguageCode.ENGLISH,
        destinationLanguage:
        [google.elements.transliteration.LanguageCode.MARATHI],
        shortcutKey: 'ctrl+g',
        transliterationEnabled: true
    };

    var control = new google.elements.transliteration.TransliterationControl(options);
    var ids = ["party_fname_ll","party_mname_ll"];
    control.makeTransliteratable(ids);
   // control.makeTransliteratable(["party_fname_ll"]);
    //control.makeTransliteratable(["party_mname_ll"]);
    //control.makeTransliteratable(["party_lname_ll"]);
 //   var keyVal = 32; // Space key
    } //end onLoad function

google.setOnLoadCallback(OnLoad);
</script> -->