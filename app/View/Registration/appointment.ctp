<?php
echo $this->element("Registration/main_menu");
?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblsearchbydate'); ?></h3></center>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="office" class="col-sm-2 control-label"><?php echo __('lblfromdate'); ?>:<span style="color: #ff0000">*</span></label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('from', array('label' => false, 'id' => 'from', 'class' => 'form-control input-sm')); ?>
                        </div>
                        <label for="office" class="col-sm-2 control-label"><?php echo __('lbltodate'); ?>:<span style="color: #ff0000">*</span></label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('to', array('label' => false, 'id' => 'to', 'class' => 'form-control input-sm')); ?>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div> <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group center">
                        <button type="button" class="btn btn-info" id="cmdSubmit" name="cmdSubmit">
                            <?php echo __('lblsearch'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div id="apptable">

        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lbltodaysappointment'); ?></h3></center>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table id="Doclist" class="table table-striped table-bordered table-hover">  
                        <thead >  
                            <tr>
                                <th><?php echo __('lblsrno'); ?></th> 
                                <th><?php echo __('lbltokenno'); ?></th>
                                <th><?php echo __('lblappointmentdt'); ?></th>
                                <th><?php echo __('lblappontmentquota'); ?></th>
                                <th><?php echo __('lblscheduledtime'); ?> </th>
                                <th><?php echo __('lblslotno'); ?> </th>
                                <th><?php echo __('lblaction'); ?> </th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $counter = 0;
                            foreach ($appointment as $app) {
                                ?>
                                <tr>
                                    <th scope="row"><?php echo ++$counter; ?></th>
                                    <td><?php echo $app['appointment']['token_no']; ?></td>
                                    <td><?php echo $app['appointment']['appointment_date']; ?></td>
                                    <td><?php
                                        if ($app['appointment']['flag'] == 'N') {
                                            echo 'Normal';
                                        } else if ($app['appointment']['flag'] == 'T') {
                                            echo 'Tatkal';
                                        }
                                        ?></td>
                                    <td><?php echo $app['appointment']['sheduled_time']; ?></td>
                                    <td><?php echo $app['appointment']['slot_no']; ?></td>
                                    <td><a href="<?php echo $this->webroot; ?>Registration/reschedule_appointment/<?php echo $app['appointment']['token_no']; ?>/<?php echo $app['appointment']['appointment_date']; ?>" class="btn btn-primary"><?php echo __('Reschedule'); ?></a></td>

                                </tr> 
                            <?php } ?>
                        </tbody>
                    </table> 

                </div>
            </div>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('Next 10 Days'); ?></h3></center>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table id="Doclist" class="table table-striped table-bordered table-hover">  
                        <thead >  
                            <tr>
                                <th><?php echo __('lblsrno'); ?></th> 
                                <th><?php echo __('lbltokenno'); ?></th>
                                <th><?php echo __('lblappointmentdt'); ?></th>
                                <th><?php echo __('lblappontmentquota'); ?></th>
                                <th><?php echo __('lblscheduledtime'); ?> </th>
                                <th><?php echo __('lblslotno'); ?> </th>
                                <th><?php echo __('lblaction'); ?> </th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $counter = 0;
                            foreach ($app_10days as $app) {
                                ?>
                                <tr>
                                    <th scope="row"><?php echo ++$counter; ?></th>
                                    <td><?php echo $app['appointment']['token_no']; ?></td>
                                    <td><?php echo $app['appointment']['appointment_date']; ?></td>
                                    <td><?php
                                        if ($app['appointment']['flag'] == 'N') {
                                            echo 'Normal';
                                        } else if ($app['appointment']['flag'] == 'T') {
                                            echo 'Tatkal';
                                        }
                                        ?></td>
                                    <td><?php echo $app['appointment']['sheduled_time']; ?></td>
                                    <td><?php echo $app['appointment']['slot_no']; ?></td>
                                    <td><a href="<?php echo $this->webroot; ?>Registration/reschedule_appointment/<?php echo $app['appointment']['token_no']; ?>/<?php echo $app['appointment']['appointment_date']; ?>" class="btn btn-primary"><?php echo __('Reschedule'); ?></a></td>

                                </tr> 
                            <?php } ?>
                        </tbody>
                    </table> 

                </div>
            </div>
        </div>
    </div>
</div>



<script>
    $(document).ready(function () {
        $('#from,#to').datepicker({
            todayBtn: "linked",
            language: "it",
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy"
        });

        $('#Doclist').DataTable();


        $("#cmdSubmit").click(function () {
            var from = $('#from').val();
            var to = $('#to').val();
            $.post('<?php echo $this->webroot; ?>Registration/get_available_appointment', {from: from, to: to}, function (data1)
            {
                if (data1 == 'e') {
                    alert('Please select From and to date');
                    return false;
                }
                $('#apptable').html(data1);
            });

        });
    });

</script>