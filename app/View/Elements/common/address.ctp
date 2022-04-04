<script>
    $(document).ready(function () {
        var host = '<?php echo $this->webroot; ?>';
        $('#district_id').change(function () {
            var dist = $("#district_id option:selected").val();
            if (dist) {
                $.post(host + "districtchangeevent", {dist: dist}, function (data)
                {
                    var sc = '<option value="">--select--</option>';
                    $.each(data.taluka, function (index, val) {
                        sc += "<option value=" + index + ">" + val + "</option>";
                    });
                    $("#taluka_id").prop("disabled", false);
                    $("#taluka_id option").remove();
                    $("#taluka_id").append(sc);
                    getCorpListByDist(dist);
                },'json');
            } else {
                $('#taluka_id,#village_id,#corp_id').val('');
            }
        });
        // Circle
        $('#taluka_id').change(function () {
            var tal = $("#taluka_id option:selected").val();
            var dist = $("#district_id option:selected").val();
            var landtype = $("#developed_land_types_id option:selected").val();
            var corp = $("#corp_id option:selected").val();
            if (dist) {
                $.post('<?php echo $this->webroot; ?>Property/taluka_change_event', {tal: tal, dist: dist, landtype: landtype, corp: corp}, function (data)
                {
                    var sc = '<option value="">--select--</option>';
                    $.each(data.village, function (index, val) {

                        sc += "<option value=" + index + ">" + val + "</option>";
                    });

                    $("#village_id option").remove();
                    $("#village_id").append(sc);
                    $("#lblvillage_id").show();
                    $("#lblcitytown").hide();
                },'json');
                getCorpListByTal(tal);
            } else {
                $('#village_id,#corp_id').val('');
            }

        });
        $('#corp_id').change(function () {
            var corp = $("#corp_id option:selected").val();
            $.post(host + 'Property/corp_change_event', {corp: corp}, function (data)
            {
                var sc2 = '<option value="">--select--</option>';
                $.each(data.village, function (index, val) {
                    sc2 += "<option value=" + index + ">" + val + "</option>";
                });
                $("#village_id").prop("disabled", false);
                $("#village_id option").remove();
                $("#village_id").append(sc2);
                $("#lblvillage_id").hide();
                $("#lblcitytown").show();
            },'json');
        });
    });
//----------------------------------------------------------------------------------------------------------------------
    var host = '<?php echo $this->webroot; ?>';
    function getCorpListByDist(dist) {
        $.post(host + 'Property/get_corp_list', {district: dist}, function (data)
        {
            var sc = '<option value="">--select--</option>';
            $.each(data.corp, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $("#corp_id option").remove();
            $("#corp_id").append(sc);
        },'json');
    }
//----------------------------------------------------------------------------------------------------------------
    function getCorpListByTal(tal) {
        $.post('<?php echo $this->webroot; ?>Property/get_corp_list', {taluka: tal}, function (data)
        {
            var sc = '<option value="">--select--</option>';
            $.each(data.corp, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $("#corp_id option").remove();
            $("#corp_id").append(sc);
        },'json');
    }
    //----------------------------------------------------------------------------------------------------------------
    function getVillage(tal) {
        $.post('<?php echo $this->webroot; ?>Property/taluka_change_event', {tal: tal}, function (data)
        {
            var sc = '<option value="">--select--</option>';
            $.each(data.village, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $("#village_id option").remove();
            $("#village_id").append(sc);
            $("#lblvillage_id").show();
            $("#lblcitytown").hide();
            getCorpListByTal(tal);
        },'json');
    }

</script>
<div class="rowht"></div>
<div class="row">
    <div class="form-group">
        <div class='col-sm-2'></div>
        <label for='district' class="col-sm-2 control-label"><?php echo __('lbladmdistrict'); ?><span style="color: #ff0000">*</span></label>
        <div class='col-sm-2'>
            <?php echo $this->Form->input('district_id', array('label' => false, 'id' => 'district_id', 'class' => 'form-control input-sm', 'empty' => '--Select District--', 'options' => $dist_list, 'required')); ?>
       <span id="district_id_error" class="form-error"><?php //echo $errarr['district_id_error'];           ?></span>
        
        
        </div>  
        <div class="col-sm-2" >
            <label for="developed_land_types_id" class="control-label" ><?php echo __('lbldellandtype'); ?></label>                              
        </div> 
        <div class="col-sm-2" >
            <?php echo $this->Form->input('developed_land_types_id', array('options' => $landtype, 'empty' => '--select--', 'id' => 'developed_land_types_id', 'class' => 'form-control input-sm chosen-select', 'label' => false)); ?>
        <span id="developed_land_types_id_error" class="form-error"><?php //echo $errarr['developed_land_types_id_error'];           ?></span>
        </div>
    </div>
</div>
<div class="rowht"></div>
<div class="row">
    <div class="form-group">
        <div class='col-sm-2'></div>
        <div class='col-sm-2' >
            <label for="corp_id" class="control-label" ><?php echo __('lblcorporation'); ?></label>                              
        </div> 
        <div class='col-sm-2' >
            <?php echo $this->Form->input('corp_id', array('empty' => '--select--', 'id' => 'corp_id', 'class' => 'form-control input-sm chosen-select', 'label' => false)); ?>
        <span id="corp_id_error" class="form-error"><?php //echo $errarr['corp_id_error'];           ?></span>
        </div>
        <label for="taluka" class="col-sm-2 control-label"><?php echo __('lbladmtaluka'); ?><span style="color: #ff0000">*</span></label>
        <div class='col-sm-2'>
            <?php echo $this->Form->input('taluka_id', array('label' => false, 'id' => 'taluka_id', 'class' => 'form-control input-sm chosen-select')); ?>
       <span id="taluka_id_error" class="form-error"><?php //echo $errarr['taluka_id_error'];           ?></span>
        </div>
    </div>
</div>
<div class="rowht"></div>
<div class="row">
    <div class="form-group">
        <div class='col-sm-2'></div>
        <label for="village" class="col-sm-2 control-label"><?php echo __('lblcityvillage'); ?><span style="color: #ff0000">*</span></label>
        <div class='col-sm-2'>
            <?php echo $this->Form->input('village_id', array('label' => false, 'id' => 'village_id', 'class' => 'form-control input-sm chosen-select', 'required')); ?>          
        <span id="village_id_error" class="form-error"><?php //echo $errarr['village_id_error'];           ?></span>
        </div>                
    </div>
</div>
<div class="rowht"></div>
