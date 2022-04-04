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
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title" style="font-weight: bolder"><?php echo __('lblappointmentavailability'); ?></h3>
                </div>

                <div class="box-body">

                    <table class="table table-striped table-bordered table-hover" id="name_list_tbl">
                        <thead >
                            <tr class="table_title_red_brown">
                                <th>
                                    <?php echo __('lblappointmentdt'); ?>
                                </th>

                                <th>
                                    <?php echo __('lblofficename'); ?>
                                </th>
                                <th>
                                    <?php echo __('lblapplicationtype'); ?>
                                </th>
                                <th>
                                    <?php echo __('lblappontmentquota'); ?>
                                </th>
                                
                                 <th>
                                    <?php echo __('lblreserveappointment'); ?>
                                </th>
                                <th>
                                    <?php echo __('lblappointmentavailability'); ?>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($appointment as $app) { ?>
                                <tr>
                                    <td><?php echo $app['appointment']['appointment_date']; ?></td>
                                     <td><?php echo $office['office']['office_name_en']; ?></td>
                                      <td>Citizen Appointment</td>
                                       <td><?php if($app['appointment']['flag']=='N'){echo 'Normal';}else if($app['appointment']['flag']=='T'){echo 'Tatkal'; }?></td>
                                    
                                    <td><?php echo $app[0]['reserved']; ?></td>
                                    <?php if($app['appointment']['flag']=='N') {?>
                                    <td><?php echo ($app['appointment']['totalslot']-$app[0]['reserved']); ?></td>
                                    <?php }else if($app['appointment']['flag']=='T') { ?>
                                     <td><?php echo ($app['appointment']['tatkal_totalslot']-$app[0]['reserved']); ?></td>
                                    <?php } ?>

                             
                                </tr>
<?php }} ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div> 
    </div>
   

