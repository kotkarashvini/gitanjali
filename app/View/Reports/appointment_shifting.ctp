<script>
    $(document).ready(function () {
        $('#tablearticlescreen').dataTable({
            "bPaginate": false,
            "ordering": false
        });

        $('.date').datepicker({
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            calendarWeeks: true,
            autoclose: true,
            todayHighlight: true
        });

    });

    function formupdate() {

        var result = confirm("Do you Really want to shift this date appointemnt ??????");
        if (result) {
            var result1 = confirm("Are you Sure???? Shift this date appointment ??????");
            if (result1) {
                $('#appointment_shifting').submit();
            } else {
                return false;
            }
        } else {
            return false;
        }

    }

</script>   

<?php echo $this->Form->create('appointment_shifting', array('id' => 'appointment_shifting', 'class' => 'form-vertical')); ?>
<div class="row">
    <div class="col-lg-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('Appointment Shifting'); ?></h3></center>

            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-3"></div>
                    <div class="form-group">
                        <!--<label for="appointment_type" ><?php // echo __('Appointment Type');   ?></label>--> 
                        <label for="appointment_type" class="col-sm-2 control-label"><?php echo __('Appointment Type'); ?><span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-2">
                            <?php //echo $this->Form->input('appointment_type', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Tatkal Slot&nbsp;&nbsp;', 'N' => '&nbsp;Normal Slot'), 'value' => '', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'appointment_type')); ?>
                            <?php //echo $this->Form->input('appointment_type', array('label' => false, 'options' => $appointment_type, 'legend' => false, 'div' => false, 'empty' => '--Select--', 'class' => 'select', 'id' => 'appointment_type' )); ?>
                            <?php echo $this->Form->input('appointment_type', array('label' => false, 'id' => 'appointment_type', 'class' => 'form-control input-sm', 'value' => '', 'options' => array(' ' => 'select', $appointment_type))); ?>
                            <span id="appointment_type_error" class="form-error"><?php //echo $errarr['appointment_type_error'];                ?></span>
                        </div><br><br>
                    </div>  



                    <div class="row center">
                        <div class="col-sm-3"></div>
                        <div class="form-group">
                            <label for="office_id" class="col-sm-2 control-label"><?php echo __('Select Office'); ?><span style="color: #ff0000">*</span></label> 
                            <div class="col-sm-2">
                                <?php echo $this->Form->input('office_id', array('label' => false, 'id' => 'office_id', 'class' => 'form-control input-sm', 'options' => array('empty' => 'ALL Offices', $officelist))); ?>
                                <span id="office_id_error" class="form-error"><?php //echo $errarr['article_id_error'];                ?></span>
                            </div><br><br>
                        </div>
                    </div>


                    <div class="row center">

                        <div class="col-sm-1"></div>
                        <label for="TAX No" class="control-label col-sm-2"><?php echo __('Select Date'); ?><span style="color: #ff0000">*</span></label>        
                        <div class="col-sm-2"><?php echo $this->Form->input("from", array('id' => 'from', 'legend' => false, 'class' => 'date form-control', 'label' => false, 'placeholder' => 'From Date')); ?>
                            <span id="from_error" class="form-error"><?php //echo $errarr['from_error'];                                ?></span>
                        </div>
                        <label for="TAX No" class="control-label col-sm-2"><?php echo __('Select Transfer Date'); ?><span style="color: #ff0000">*</span></label>      
                        <div class="col-sm-2"><?php echo $this->Form->input("to", array('id' => 'to', 'legend' => false, 'class' => 'date form-control', 'label' => false, 'placeholder' => 'To Date')); ?>
                            <span id="to_error" class="form-error"><?php //echo $errarr['to_error'];                                      ?></span>
                        </div>


                    </div>

                    <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>

                    <div class="row center">
                        <div class="form-group">
                            <div class="col-sm-12 tdselect">
                                <button id="btnadd" type="button" name="btnadd" class="btn btn-info " onclick="javascript: return formupdate();">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;<?php echo __('Submit'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>   

            </div>
        </div>
    </div>
</div>


