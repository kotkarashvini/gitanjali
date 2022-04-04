<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>
<?php
echo $this->Html->script('jquery.dataTables');
echo $this->Html->script('dataTables.bootstrap');
?>

<script type="text/javascript">
    $(document).ready(function () {

        $('#district_id').change(function () {
            var district = $("#district_id option:selected").val();
            $("#hfdist").val($("#district_id option:selected").text());
            var token = $("#token").val();
            $.post("get_taluka_name", {district: district, token: token}, function (data)
            {
                var sc = '<option value="">--Select--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#taluka_id option").remove();
                $("#taluka_id").append(sc);
            },'json');
        })

        $('#taluka_id').change(function () {
            var taluka = $("#taluka_id option:selected").val();
            $("#hftal").val($("#taluka_id option:selected").text());
            var token = $("#token").val();
            $.post("get_village_name", {taluka: taluka, token: token}, function (data)
            {
                var sc = '<option value="">All Villages</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#village_id").prop("disabled", false);
                $("#village_id option").remove();
                $("#village_id").append(sc);
            },'json');
        })

        $("#village_id").change(function () {
            $("#hfvil").val($("#village_id option:selected").text());
        });

    });

    function formprint(r) {
        document.getElementById("actiontype").value = '1';
        document.getElementById("report").value = r;
    }

    function forcancel() {
        document.getElementById("actiontype").value = '2';
    }
</script>

<?php echo $this->Form->create('ratereportchange', array('id' => 'ratereportchange', 'autocomplete' => 'off')); ?>

<div class="row">
    <div class="col-lg-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblratereport'); ?></h3></center>
            </div>
            <div class="box-body">
                
                 <div class="row">
                    <label for="district_id " class="col-sm-2 control-label">Select Financial Year<span style="color: #ff0000">*</span></label>    
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('finyear_id', array('label' => false, 'id' => 'finyear_id', 'class' => 'form-control input-sm', 'options' => array($finyear), 'empty' => '--Select--')); ?>
                        <span id="finyear_id_error" class="form-error"><?php echo $errarr['finyear_id_error']; ?></span>
                    </div>
                 </div>
                   <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
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
                <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
                <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
                <div class="row center">
                    <div class="form-group" >
                        <button id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript: return formprint('p');">
                            <?php echo __('PDF'); ?></button> 
<!--                           <button id="btnadd" name="btnadd1" class="btn btn-info " onclick="javascript: return formprint('e');">
                            <?php// echo __('Excel'); ?></button> -->
                        <button id="btnadd" name="btncancel" class="btn btn-info " onclick="javascript: return forcancel();">
                            <span class="glyphicon glyphicon-floppy-remove"></span>&nbsp;&nbsp; <?php echo __('btncancel'); ?></button>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfdist; ?>' name='hfdist' id='hfdist'/>
    <input type='hidden' value='<?php echo $hftal; ?>' name='hftal' id='hftal'/>
    <input type='hidden' value='<?php echo $hfvil; ?>' name='hfvil' id='hfvil'/>
     <input type='hidden' value='' name='report' id='report'/>
</div>

<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>




