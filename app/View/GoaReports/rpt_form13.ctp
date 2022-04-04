<script>
    $(document).ready(function () {

//        $("#date").hide();
        $('.date').datepicker({
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            calendarWeeks: true,
            //orientation: "top left",
            autoclose: true,
            todayHighlight: true
        });
        
          $('#memo1').on('click', function () {
            $.print("#memo");
        });

        $('#district_id').change(function () {
            var district = $("#district_id option:selected").val();
            $("#hfdist").val($("#district_id option:selected").text());
            var token = $("#token").val();
            $.post("<?php echo $this->webroot; ?>Reports/get_taluka_name", {district: district, token: token}, function (data)
            {
                var sc = '<option value="">--Select--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#taluka_id option").remove();
                $("#taluka_id").append(sc);
            }, 'json');
        })

        $('#taluka_id').change(function () {
            var taluka = $("#taluka_id option:selected").val();
            $("#hftal").val($("#taluka_id option:selected").text());
            var token = $("#token").val();
            $.post("<?php echo $this->webroot; ?>Reports/get_village_name", {taluka: taluka, token: token}, function (data)
            {
                var sc = '<option value="">Select Village</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#village_id").prop("disabled", false);
                $("#village_id option").remove();
                $("#village_id").append(sc);
            }, 'json');
        })
    });


</script>

<script>

    $(function () {
        $('#go').click(function () {
            $('form').submit();
        });
    });
</script>


<?php echo $this->Form->create('rpt_form13', array('id' => 'rpt_form13')); ?>

<div class = "box box-primary">
    <div class = "box-header with-border">
        <center><h3 class = "box-title headbolder">Form XIII</h3></center>
    </div>
    <div class="box-body">
        
        <div class="row">
            <label for="district_id " class="col-sm-2 control-label"><?php echo __('lbladmdistrict'); ?><span style="color: #ff0000">*</span></label>    
            <div class="col-sm-2">
                <?php echo $this->Form->input('district_id', array('label' => false, 'id' => 'district_id', 'class' => 'form-control input-sm', 'options' => array($District), 'empty' => '--Select--')); ?>
                  <span id="district_id_error" class="form-error"><?php echo $errarr['district_id_error']; ?></span>
            </div>
            <label for="taluka_id " class="col-sm-2 control-label"><?php echo __('lbladmtaluka'); ?><span style="color: #ff0000">*</span></label>    
            <div class="col-sm-2">
                <?php echo $this->Form->input('taluka_id', array('label' => false, 'id' => 'taluka_id', 'class' => 'form-control input-sm', 'options' => array($taluka), 'empty' => '--Select--')); ?>
                 <span id="taluka_id_error" class="form-error"><?php echo $errarr['taluka_id_error']; ?></span>
            </div>
            <label for="village_id " class="col-sm-2 control-label"><?php echo __('lbladmvillage'); ?><span style="color: #ff0000">*</span></label>    
            <div class="col-sm-2">
                <?php echo $this->Form->input('village_id', array('label' => false, 'id' => 'village_id', 'class' => 'form-control input-sm', 'options' => array($village), 'empty' => '--Select--')); ?>
                 <span id="village_id_error" class="form-error"><?php echo $errarr['village_id_error']; ?></span>
            </div>
        </div>
        
         <div  class="rowht">&nbsp;</div>  <div  class="rowht">&nbsp;</div> <div  class="rowht">&nbsp;</div>  <div  class="rowht">&nbsp;</div>
        <div class="row" id="divDate">
            <div class="form-group">
                <div class="col-sm-2"></div>
                <label for="Valuation No" class="control-label col-sm-2"> <?php echo __('lblgetrecordby'); ?> </label>            
                <div class="col-sm-2"><?php echo $this->Form->input("from", array('id' => 'from', 'legend' => false, 'class' => 'date form-control','placeholder'=>'from date', 'label' => false)); ?></div>
                <div class="col-sm-2"><?php echo $this->Form->input("to", array('id' => 'to', 'legend' => false, 'class' => 'date form-control', 'placeholder'=>'to date','label' => false)); ?></div>
                 <button id="go" class="btn btn-primary" type="submit"> Submit </button>
            </div>
        </div>
          
    </div> 
 

</div>    
