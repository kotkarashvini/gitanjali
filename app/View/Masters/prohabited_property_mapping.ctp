<script type="text/javascript">
    $(document).ready(function () {

        
        $('#tabledoc').dataTable({
            "pagination": 10,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });

        $('#district_id').change(function () {
            var district = $("#district_id option:selected").val();
            $.getJSON("get_taluka_name", {district: district}, function (data)
            {
                var sc = '<option value="">--Select--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#taluka_id").prop("disabled", this.checked);
                $("#taluka_id option").remove();
                $("#taluka_id").append(sc);
            });
        })

        $('#taluka_id').change(function () {
            var taluka = $("#taluka_id option:selected").val();
            $.getJSON("get_village_name", {taluka: taluka}, function (data)
            {
                var sc = '<option value="">--Select--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#village_id").prop("disabled", this.checked);
                $("#village_id option").remove();
                $("#village_id").append(sc);
            });
        })
        
        if($("#actiontype").val() == 1){
            $('#khata_no').val('');
        }

    });

    function formsearch() {
        document.getElementById("actiontype").value = '1';
    }

    function forcancel() {
        document.getElementById("actiontype").value = '2';
    }
</script>

<?php echo $this->Form->create('prohabited_property_mapping', array('id' => 'prohabited_property_mapping', 'autocomplete' => 'off')); ?>
<?php //echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>

<div class="row">
    <div class="col-lg-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('Prohibited Property Entry'); ?></h3></center>
            </div>
            <div class="box-body">
                <div class="row">
                    <label for="district_id " class="col-sm-2 control-label"><?php echo __('Select District'); ?><span style="color: #ff0000">*</span></label>    
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('district_id', array('label' => false, 'id' => 'district_id', 'class' => 'form-control input-sm', 'options' => array($District), 'empty' => '--Select--')); ?>
                        <span id="district_id_error" class="form-error"><?php //echo $errarr[district_id_error'];          ?></span>
                    </div>
                    <label for="taluka_id " class="col-sm-2 control-label"><?php echo __('Select Taluka'); ?><span style="color: #ff0000">*</span></label>    
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('taluka_id', array('label' => false, 'id' => 'taluka_id', 'class' => 'form-control input-sm', 'options' => array($taluka), 'empty' => '--Select--')); ?>
                        <span id="taluka_id_error" class="form-error"><?php //echo $errarr[taluka_id_error'];          ?></span>
                    </div>
                    <label for="village_id " class="col-sm-2 control-label"><?php echo __('Select Village'); ?><span style="color: #ff0000">*</span></label>    
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('village_id', array('label' => false, 'id' => 'village_id', 'class' => 'form-control input-sm', 'options' => array($village), 'empty' => '--Select--')); ?>
                        <span id="village_id_error" class="form-error"><?php //echo $errarr[village_id_error'];          ?></span>
                    </div>
                </div>
 <div class="rowht"></div><div class="rowht"></div><div class="rowht"></div>
                <div class="row">
                    <div class="col-sm-1"></div>
                    <label for="khata_no" class="col-sm-2 control-label"><?php echo __('Khata No.'); ?></label>
<!--                    <div class="col-sm-2">
                        <?php //echo $this->Form->input('khata_no', array('label' => false, 'id' => 'khata_no', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                        <span  id="khata_no_error" class="form-error"><?php //echo $errarr['khata_no_error'];       ?></span>
                    </div>-->

                     <div class="col-sm-2">
                        <textarea class="form-control" name="data[prohabited_property_mapping][khata_no]" id="khata_no"></textarea>
                        <span  id="khata_no_error" class="form-error"><?php //echo $errarr['khata_no_error'];       ?></span>
                    </div>
                    

                    <label for="survey_no" class="col-sm-2 control-label"><?php echo __('Plot No.'); ?></label>
                    <div class="col-sm-2">
                        <textarea class="form-control" name="data[prohabited_property_mapping][survey_no]" id="survey_no"></textarea>
                        <input type="hidden" value="Y" id="hfhidden1"/>
                        <span  id="survey_no_error" class="form-error"><?php //echo $errarr['survey_no_error'];       ?></span>
                    </div>

                </div>  


                <div class="rowht"></div><div class="rowht"></div><div class="rowht"></div>
                <div class="row center">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <button id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript: return formsearch();">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp; <?php echo __('Submit'); ?></button> 
                            <button id="btnadd" name="btncancel" class="btn btn-info "  onclick="javascript: return forcancel();">
                                <span class="glyphicon glyphicon-floppy-remove"></span>&nbsp;&nbsp; <?php echo __('Cancel'); ?></button>
                        </div>
                    </div>
                </div>
                <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
                <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
            </div>
        </div>
    </div>
</div>

<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>
