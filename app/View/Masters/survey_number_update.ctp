<?php

echo $this->element("Helper/jqueryhelper");
?><script>
    $(document).ready(function () {
        $('#district_id').change(function () {
            $.postJSON("<?php echo $this->webroot; ?>districtchangeevent", {dist: $('#district_id').val()}, function (data)
            {
                var sc = '<option>--select--</option>';
                $.each(data.taluka, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });

                $("#taluka_id").prop("disabled", false);
                $("#taluka_id option").remove();
                $("#taluka_id").append(sc);
                $.postJSON('<?php echo $this->webroot; ?>Property/get_corp_list', {district: $("#district_id").val(), taluka: $("#taluka_id").val()}, function (data)
                {
                    var sc = '<option value="">--select--</option>';
                    var flag = 0;
                    $.each(data.corp, function (index, val) {
                        sc += "<option value=" + index + ">" + val + "</option>";
                    });

                    $("#corp_id option").remove();
                    $("#corp_id").append(sc);
                });
            });

        });
        $('#taluka_id').change(function () {
         var finyear = $("#finyear_id option:selected").val();
            $.postJSON('<?php echo $this->webroot; ?>Property/taluka_change_event', {tal: $("#taluka_id").val(), dist: $('#district_id').val(), landtype: $('#developed_land_types_id').val(), finyear: finyear}, function (data)
            {
                var sc = '<option value="">--select--</option>';
                $.each(data.village, function (index, val) {

                    sc += "<option value=" + index + ">" + val + "</option>";
                });

                $("#village_id option").remove();
                $("#village_id").append(sc);

                $.postJSON('<?php echo $this->webroot; ?>Property/get_corp_list', {district: $("#district_id").val(), taluka: $("#taluka_id").val()}, function (data)
                {
                    var sc = '<option value="">--select--</option>';
                    var flag = 0;
                    $.each(data.corp, function (index, val) {
                        sc += "<option value=" + index + ">" + val + "</option>";
                    });

                    $("#corp_id option").remove();
                    $("#corp_id").append(sc);
                });

            });
        });

        $('#village_id').change(function () {

            $.postJSON('<?php echo $this->webroot; ?>Property/village_change_event', {village_id: $('#village_id').val()}, function (data)
            {
                var sc1 = '<option value="">--select--</option>';

                $.each(data.data2, function (index, val) {
                    sc1 += "<option value=" + index + ">" + val + "</option>";

                });

                $("#level1_id option").remove();
                $("#level1_id").append(sc1);

            });
        });

        $('#level1_id').change(function () {
            var village_id = $("#village_id option:selected").val();
            $.postJSON('<?php echo $this->webroot; ?>Property/Level1_change_event', {level1list: $('#level1_id').val(), village_id: $('#village_id').val()}, function (data)
            {
                if (data['level1listflag'].toString() === '1') {
                    var sc = '<option>--select--</option>';
                    $.each(data.data1, function (index, val) {
                        sc += "<option value=" + index + ">" + val + "</option>";
                    });
                    $("#level1_list_id option").remove();
                    $("#level1_list_id").append(sc);
                }
            });

        });


        $('#level1_list_id').change(function () {
            $.post("<?php echo $this->webroot; ?>Masters/getsurveynumbers",
                    {
                        district: $("#district_id").val(),
                        landtype: $("#Developedland").val(),
                        taluka: $("#taluka_id").val(),
                        village: $("#village_id").val(),
                        lavel1: $("#level1_id").val(),
                        lavel1_list: $("#level1_list_id").val(),
                        attribute: $("#attribute").val(),
                        csrftoken: '<?php echo $this->Session->read("csrftoken"); ?>'
                    },
                    function (data, status) {
                        $('#survey_number').val(data);
                    });

        });


        $('#corp_id').change(function () {
            var corp = $("#corp_id option:selected").val();
            var dist = $("#district_id option:selected").val();
            var landtype = $("#developed_land_types_id option:selected").val();
            var tal = $("#taluka_id option:selected").val();
             var finyear = $("#finyear_id option:selected").val();
            $.postJSON('<?php echo $this->webroot; ?>Property/corp_change_event', {tal: tal, finyear: finyear, corp: corp, dist: dist, landtype: landtype}, function (data)
            {
                var sc2 = '<option value="">--select--</option>';
                $.each(data.village, function (index, val) {
                    sc2 += "<option value=" + index + ">" + val + "</option>";
                });

                $("#village_id option").remove();
                $("#village_id").append(sc2);




            });
        });

    });

</script>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="panel-title">Update Property Attributes</div>
            </div>
            <div class="panel-body">


<?php 

echo $this->Form->create('survey_number_update', array('url' => array('controller' => 'Masters', 'action' => 'survey_number_update'),  'id' => 'survey_number_update', 'class' => 'form-inline')); ?>
               <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>
                
                
                  <div class="form-group col-md-2">
                    <label for="finyear_id" class="control-label"><?php echo __('lblfineyer'); ?></label>
                       <?php echo $this->Form->input('finyear_id', array('options' => $finyearList, 'id' => 'finyear_id', 'class' => 'form-control input-sm chosen-select', 'label' => false)); ?>
                    <span class="form-error" id="finyear_id_error"></span>
                </div>
                <div class="form-group col-md-2">
                    <label for="attribute" class="control-label"><?php echo __('Property Attribute'); ?></label>
                     <?php echo $this->Form->input('attribute', array('options' =>$attribute,'empty' => '--select--', 'id' => 'attribute', 'label' => false,'div' => false, 'class' => 'form-control input-sm chosen-select' )); ?>
                    <span class="form-error" id="attribute_error"></span>
                </div>
                <div class="clearfix"></div>          
                <div class="form-group col-md-2">
                    <label for="district_id" class="control-label"><?php echo __('lbladmdistrict'); ?> <span class="star">*</span></label>
                                        <?php echo $this->Form->input('district_id', array('options' => $districtdata, 'empty' => '--select--','id' => 'district_id', 'class' => 'form-control input-sm chosen-select', 'label' => false,'div' => false)); ?>
                    <span class="form-error" id="district_id_error"></span>
                </div>

                <div class="form-group col-md-2">
                    <label for="taluka_id" class="control-label"><?php echo __('lbladmtaluka'); ?> <span class="star">*</span></label>
                <?php echo $this->Form->input('taluka_id', array('options' => $taluka, 'empty' => '--select--', 'id' => 'taluka_id', 'class' => 'form-control input-sm chosen-select', 'label' => false,'div' => false)); ?>
                    <span class="form-error" id="taluka_id_error"></span>
                </div>

                <div class="form-group col-md-2">
                    <label for="developed_land_types_id" class="control-label" ><?php echo __('lbldellandtype'); ?> <span class="star">*</span></label>
                                    <?php echo $this->Form->input('developed_land_types_id', array('options' => $landtype, 'empty' => '--select--', 'id' => 'developed_land_types_id', 'class' => 'form-control input-sm chosen-select', 'label' => false,'div' => false)); ?>
                    <span class="form-error" id="developed_land_types_id_error"></span>
                </div>
                <div class="form-group col-md-2">
                    <label for="corp_id" class="control-label" ><?php echo __('corp_id'); ?> </label>
                                    <?php echo $this->Form->input('corp_id', array('options' => $corp, 'empty' => '--select--', 'id' => 'corp_id', 'class' => 'form-control input-sm chosen-select', 'label' => false,'div' => false)); ?>
                    <span class="form-error" id="corp_id_error"></span>
                </div>
                <div class="form-group col-md-2">
                    <label for="village_id" class="control-label" id="lblvillage_id"><?php echo __('lblcityvillage'); ?> <span class="star">*</span></label>
                    <label for="village_id" class="control-label" hidden="true" id="lblcitytown"><?php echo __('lblcityarea'); ?></label>
                                    <?php echo $this->Form->input('village_id', array('options' => $villagenname, 'empty' => '--select--', 'id' => 'village_id', 'class' => 'form-control input-sm chosen-select', 'label' => false,'div' => false)); ?>
                    <span class="form-error" id="village_id_error"></span>
                </div>

                <div class="form-group col-md-2">
                    <label for="lblLevel1" class="control-label"><?php echo __('Location Type'); ?> <span class="star">*</span></label>
             <?php echo $this->Form->input('level1_id', array( 'options' => $level1propertydata,'empty' => '--select--', 'id' => 'level1_id', 'label' => false,'div' => false, 'class' => 'form-control input-sm chosen-select')); ?>
                    <span class="form-error" id="level1_id_error"></span>
                </div>
                <div class="form-group col-md-2">
                    <label for="level1_list_id" class="control-label"><?php echo __('Location List'); ?> <span class="star">*</span></label>
                     <?php echo $this->Form->input('level1_list_id', array('options' =>$level1propertylist,'empty' => '--select--', 'id' => 'level1_list_id', 'label' => false,'div' => false, 'class' => 'form-control input-sm chosen-select' )); ?>
                    <span class="form-error" id="level1_list_id_error"></span>
                </div>


                <div class="clearfix"></div>
                <div class="form-group col-md-12">
                    <label><?php echo __('List Of Attributes');?></label>
                      <?php
                    echo $this->Form->input('survey_number', array('id' => 'survey_number','type' => 'textarea', 'class' => 'col-md-12', 'label' => false,'div' => false,'rows'=>4 ));
                    ?>
                    <span class="form-error" id="survey_number_error"></span>
                </div>
                <div class="form-group"> 
                    <button type="submit" class="btn btn-primary pull-right"><?php echo __('btnsubmit');?></button>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>


        </div>

    </div>

</div>
