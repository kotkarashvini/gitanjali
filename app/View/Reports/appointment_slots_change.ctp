<script>
    $(document).ready(function () {
        $('#tablearticlescreen').dataTable({
            "bPaginate": false,
            "ordering": false
        });
    });

    function formadd() {
        var result = confirm("Do you Really want to change time slot for office ??????");
        if (result) {
            var result1 = confirm("Are you Sure???? Update this time slot  ??????");
            if (result1) {
                $('#appointment_slots_change').submit();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    function formupdate(office_id, slot_id) {
        $('#office_id').val(office_id);
        $('#slot_id').val(slot_id);
        $('#btnadd').html('Save');
        return false;
    }

</script>   
<style>
    .table-responsive
    {
        overflow-y:auto;
        height:350px;
    }
</style>
<?php echo $this->Form->create('appointment_slots_change', array('id' => 'appointment_slots_change', 'class' => 'form-vertical')); ?>
<div class="row">
    <div class="col-lg-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('Appointment Time Slots Change'); ?></h3></center>

            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-2"></div>
                    <div class="form-group">
                        <label for="office_id" class="col-sm-2 control-label"><?php echo __('Select Office'); ?><span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('office_id', array('label' => false, 'id' => 'office_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $officelist))); ?>
                            <span id="office_id_error" class="form-error"><?php //echo $errarr['article_id_error'];     ?></span>
                        </div>
                        <label for="slot_id" class="col-sm-2 control-label"><?php echo __('Select Time Slot'); ?><span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('slot_id', array('label' => false, 'id' => 'slot_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $timeslotid))); ?>
                            <span id="slot_id_error" class="form-error"><?php //echo $errarr['slot_id_error'];     ?></span>
                        </div> <br><br>
                    </div>
                     <div class="col-sm-2"></div>
                        <label for="is_required" class="col-sm-2 control-label" ><?php echo __('Select Appointment Type'); ?><span style="color: #ff0000">*</span></label>   
                        <div class="col-md-2">
                            <?php echo $this->Form->input('is_required', array('label' => false, 'id' => 'is_required', 'class' => 'form-control input-sm', 'value' => '', 'options' => array(' ' => 'select', $appointment_type))); ?>
                            <?php //echo $this->Form->input('is_required', array('type' => 'radio', 'options' => array('T' => '&nbsp;Tatkal Slot&nbsp;&nbsp;', 'N' => '&nbsp;Normal Slot'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'is_required')); ?>
                        </div>
                </div>
                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>

                <div class="row center">
                    <div class="form-group">
                        <div class="col-sm-12 tdselect">
                            <button id="btnadd"type="submit" name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;<?php echo __('Submit'); ?>
                            </button>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
<div class="box box-primary">
    <div class="box-body">
        <div id="selectdocument" class="table-responsive">
            <table id="tablearticlescreen" class="table table-striped table-bordered table-hover">  
                <thead >  
                    <tr>  
                        <th class="center"><?php echo __('Office'); ?></th>
                        <th class="center"><?php echo __('Normal Slot ID'); ?></th>
                        <th class="center"><?php echo __('Normal Slot Minutes'); ?></th>
                        <th class="center"><?php echo __('Tatkal Slot ID'); ?></th>
                        <th class="center"><?php echo __('Tatkal Slot Minutes'); ?></th>
<!--                        <th class="center"><?php //echo __('lblaction');   ?></th>-->
                    </tr>  
                </thead>
                <tbody>
                    <?php foreach ($slotsgrid as $slotsgrid1): ?>
                        <tr>
                            <td ><?php echo $slotsgrid1[0]['office_name_en']; ?></td>
                            <td ><?php echo $slotsgrid1[0]['normal_slot_id']; ?></td>
                            <td ><?php echo $slotsgrid1[0]['normal_slot_time_minute']; ?></td>
                            <td ><?php echo $slotsgrid1[0]['tatkal_slot_id']; ?></td>
                            <td ><?php echo $slotsgrid1[0]['tatkal_slot_time_minute']; ?></td>
    <!--                            <td >
                                <button id="btnupdate" name="btnupdate" type="button" data-toggle="tooltip" title="Edit" class="btn btn-default "  onclick="javascript: return formupdate(
                                            ('<?php //echo $slotsgrid1[0]['office_id'];   ?>'),('<?php //echo $slotsgrid1[0]['slot_id'];   ?>')
                                            );">
                                    <span class="glyphicon glyphicon-pencil"></span>
                                </button>
                            </td>-->
                        </tr>
                    <?php endforeach; ?>
                    <?php unset($slotsgrid1); ?>
                </tbody>
            </table> 
        </div>
    </div>
</div> 

