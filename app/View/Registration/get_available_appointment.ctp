<?php
echo $this->Html->script('bootstrap-datepicker.min');
echo $this->Html->css('bootstrap-datepicker3.min');
?>

<script type="text/javascript">

    $(document).ready(function () {
        $('#name_list_tbl').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
    });
</script>
<?php
if (!empty($appointment)) {
    ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <center><h3 class="box-title" style="font-weight: bolder"><?php echo __('lblreserveappointment'); ?></h3></center>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="name_list_tbl" class="table table-striped table-bordered table-hover">  
                            <thead >  
                                <tr>
                                    <th><?php echo __('lblsrno'); ?></th> 
                                    <th><?php echo __('lbltokenno'); ?></th>
                                    <th><?php echo __('lblappointmentdt'); ?></th>
                                    <th><?php echo __('lblappontmentquota'); ?></th>
                                    <th><?php echo __('lblscheduledtime'); ?></th>
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
        </div>
    </div>
<?php } ?>



