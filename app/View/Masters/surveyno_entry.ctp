<script language="JavaScript" type="text/javascript">
    $(document).ready(function () {

        $('#tablesurvey').dataTable({
            "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        })

        $('#state_id').change(function () {
            var state = $("#state_id option:selected").val();


            var i;
            $.getJSON("<?php echo $this->webroot; ?>regdistrict", {state: state}, function (data)
            {
                var sc = '<option value="empty">--Select District--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#district_id option").remove();
                $("#district_id").append(sc);
            });
        })

        $('#district_id').change(function () {
            var district = $("#district_id option:selected").val();
            var token = $("#token").val();
            var i;
            $.getJSON("<?php echo $this->webroot; ?>regtaluka", {district: district, token: token}, function (data)
            {
                var sc = '<option value="empty">--Select Taluka--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#taluka_id option").remove();
                $("#taluka_id").append(sc);
            });
        })

        $('#taluka_id').change(function () {

            var taluka = $("#taluka_id option:selected").val();
            //var token = $("#token").val();
            //alert(taluka);exit;
            var i;
            $.getJSON("<?php echo $this->webroot; ?>get_village_survey", {taluka_id: taluka}, function (data)
            {
                var sc1 = '<option value="">--select--</option>';
                $.each(data, function (index1, val1) {

                    sc1 += "<option value=" + index1 + ">" + val1 + "</option>";
                });

                $("#village_id option").remove();
                $("#village_id").append(sc1);
            }, 'json');
        })

        $('#level1_id').change(function () {
            var level1_list = $("#level1_id option:selected").val();
            $.getJSON('<?php echo $this->webroot; ?>get_level1_list1', {level1_list: level1_list}, function (data)
            {
                var sc = '<option>select</option>';
                var sc1 = '<option>select</option>';
                $.each(data.data1, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#level1_list_id").prop("disabled", false);
                $("#level1_list_id option").remove();
                $("#level1_list_id").append(sc);
            });
        });


        $('#village_id').change(function () {
            village_change_event_new();
        })

        function village_change_event_new() {
            //var district = $("#district_id option:selected").val();
            var village = $("#village_id option:selected").val();
            //alert(village);
            $.post("surveyno_grid_update", {village: village}, function (data)
            {
                $("#divsurveynogrid").html(data);
            });
        }
    });
</script>
<?php echo $this->Form->create('surveyno_entry', array('id' => 'surveyno_entry', 'class' => 'form-vertical', 'autocomplete' => 'off')); ?>

<div class="row">
    <div class="col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('Survey No Entry'); ?></h3></center>
                <div  class="rowht">&nbsp;</div> <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="state" class="col-sm-3 control-label"><?php echo __('lblSelect'); ?>&nbsp;<?php echo __('lbladmstate'); ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('state_id', array('label' => false, 'id' => 'state_id', 'class' => 'form-control input-sm', 'empty' => '----select----', 'options' => array($State))); ?>
                            <span id="state_id_error" class="form-error"><?php //echo $errarr['state_id_error'];      ?></span>
                        </div>

                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="district" class="col-sm-3 control-label"><?php echo __('lblSelect'); ?>&nbsp;<?php echo __('lbladmdistrict'); ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('district_id', array('label' => false, 'id' => 'district_id', 'class' => 'form-control input-sm', 'empty' => '--Select District--', 'options' => array())); ?>
                            <span id="district_id_error" class="form-error"><?php //echo $errarr['district_id_error'];      ?></span>
                        </div>

                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="taluka" class="col-sm-3 control-label"><?php echo __('lblSelect'); ?>&nbsp;<?php echo __('lbladmtaluka'); ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('taluka_id', array('label' => false, 'id' => 'taluka_id', 'class' => 'form-control input-sm', 'empty' => '--Select Taluka--', 'options' => array())); ?>
                            <span id="taluka_id_error" class="form-error"><?php //echo $errarr['taluka_id_error'];      ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="village_id" class="col-sm-3 control-label"><?php echo __('lblSelect'); ?>&nbsp;<?php echo __('lbladmvillage'); ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('village_id', array('label' => false, 'id' => 'village_id', 'class' => 'form-control input-sm', 'empty' => '--Select Village--', 'options' => array())); ?>
                            <span id="village_id_error" class="form-error"><?php //echo $errarr['village_id_error'];      ?></span>
                        </div>
                    </div>
                </div>

                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="level1" class="col-sm-3 control-label"><?php echo __('lblSelect'); ?>&nbsp;<?php echo __('Government Body'); ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('level1_id', array('label' => false, 'id' => 'level1_id', 'class' => 'form-control input-sm', 'empty' => '--Select Level1--', 'options' => array($level1))); ?>
                        <span id="level1_id_error" class="form-error"><?php //echo $errarr['level1_id_error'];      ?></span>
                        </div>
                    </div>
                </div>

                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="level1_list" class="col-sm-3 control-label"><?php echo __('lblSelect'); ?>&nbsp;<?php echo __('Government Body List'); ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('level1_list_id', array('label' => false, 'id' => 'level1_list_id', 'class' => 'form-control input-sm', 'empty' => '--Select Level1 List--', 'options' => array())); ?>
                            <span id="level1_list_id_error" class="form-error"><?php //echo $errarr['level1_list_id_error'];      ?></span> 
                        </div>
                    </div>
                </div>

                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="survey_no" class="col-sm-3 control-label">&nbsp;<?php echo __('Enter Survey No.'); ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('survey_no', array('label' => false, 'id' => 'survey_no', 'class' => 'form-control input-sm', 'type' => 'text')); ?>
                            <span id="survey_no_error" class="form-error"><?php //echo $errarr['survey_no_error'];      ?></span> 
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row" style="text-align: center">
                    <button type="submit" class="btn btn-info" id="cmdSubmit" name="cmdSubmit">
                        <span class="glyphicon glyphicon-ok"></span> <?php echo __('btnsubmit'); ?>
                    </button>

                </div>

            </div>
        </div>

        <div id="divsurveynogrid" class="table-responsive"></div>

        <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
        <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>        </div>

</div>
</div>
<?php echo $this->Form->end(); ?>
