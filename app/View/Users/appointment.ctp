<?php

function get_total_minutes($hours = NULL) {

    if (strstr($hours, ':')) {
        # Split hours and minutes.
        $separatedData = split(':', $hours);

        $minutesInHours = $separatedData[0] * 60;
        $minutesInDecimals = $separatedData[1];

        $totalMinutes = $minutesInHours + $minutesInDecimals;
    } else {
        $totalMinutes = $hours * 60;
    }

    return $totalMinutes;
}
?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title" style="font-weight: bolder"><?php echo $app_date; echo __('lblappointment'); ?></h3></center>
                <div class="col-sm-7"></div>
                 <div class="right col-sm-2">
                    <?php echo $this->Form->input('quota_id', array('label' => false, 'id' => 'quota_id', 'class' => 'form-control input-sm', 'selected'=>$quota,'options' => array('empty' => '--Select Quota--', $quota_list))); ?>
                </div>
                <div class="right col-sm-2">
                    <?php echo $this->Form->input('shift_id', array('label' => false, 'id' => 'shift_id', 'class' => 'form-control input-sm', 'selected'=>$shift,'empty' => '--Select Shift--','options' => array($officeshift))); ?>
                </div>
                <!--                 <div class="right col-sm-3">
                                    
                                 <a href='<?php $this->webroot ?>normalappointment'><button type="button" class="btn btn-info" id="cmdSubmit" name="cmdSubmit">
                                         Appointment Availability By Date
                                     </button></a>
                                 </div>-->
                <div class="row">
                    <div class="form-group">
                        <label for="select_date" class="col-sm-2 control-label"><?php echo __('lblseldate'); ?>:-</label>  
                        <div class="col-sm-3">
                            <input type="text" name="select_date" id="select_date" value="<?php echo $app_date;?>"/>
                        </div>
                    </div>
                </div>

            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table id="Doclist" class="table table-striped table-bordered table-hover">  
                        <thead >  
                            <tr>
                                <th>Office/Shift</th> 
                                <?php for ($m = 1; $m <= count($a); $m++) { ?>
                                    <th><?php echo $a[$m]; ?></th>
                                <?php } ?>


                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            for ($l = 0; $l < count($office); $l++) {

                                $i = 1;
                                $sum = 0;
                                ?>
                                <tr>
                                    <td><?php echo $office[$l]['office']['office_name_en']; ?></td>

                                    <?php
                                    for ($p = 1; $p <= count($a); $p++) {
                                        $timeslot = (explode("-", $a[$p]));
                                        $slot1minut = get_total_minutes($timeslot[0]);
                                        $slot2minut = get_total_minutes($timeslot[1]);
                                        $totminute = abs($slot1minut - $slot2minut);
                                        $totslot = $totminute / $office[$l]['slot']['slot_time_minute'];
                                        ?>



                                        <td><?php
                                            for ($j = 1; $j <= $totslot; $j++) {
                                                $sum = $sum + 1;
                                                ?><span id="<?php echo $a[$p]; ?>"> 

                                                    <input type="checkbox" name="slot"  disabled="true" value="<?php echo $i . '_' . $j; ?>"   <?php
                                                    for ($k = 0; $k < count($appointment); $k++) {
                                                        echo 1;

                                                        if ($office[$l]['office']['office_id'] == $appointment[$k]['appointment']['office_id']) {

                                                            if ($i . '_' . $j == $appointment[$k]['appointment']['interval_id'] . '_' . $appointment[$k]['appointment']['slot_no']) {
                                                                ?>  checked="checked"  class="rdodisable" style="background-color: red;"
                                                                       <?php
                                                                   }
                                                               }
                                                           }
                                                       }
                                                       ?> /></span>

                                        </td>
                                        <?php
                                        $i++;
                                    }
                                    ?>

                                </tr>  <?php } ?>    

                        </tbody>
                    </table> 

                </div>
            </div>
        </div>
    </div>
</div>



<script>
    $(document).ready(function () {


        $('#Doclist').DataTable();

        $('#shift_id').change(function () {
            var shift_id = $("#shift_id option:selected").val();
              var select_date   = $('#select_date').val();
              var quota_id   = $('#quota_id').val();
            window.location = "<?php echo $this->webroot; ?>Users/appointment/" + shift_id+"/"+select_date+"/"+quota_id; 
            //$.post({url : '<?php //echo $this->webroot;  ?>Users/appointment/'+shift_id});

        });
         $('#quota_id').change(function () {
            var shift_id = $("#shift_id option:selected").val();
              var select_date   = $('#select_date').val();
              var quota_id   = $('#quota_id').val();
            window.location = "<?php echo $this->webroot; ?>Users/appointment/" + shift_id+"/"+select_date+"/"+quota_id; 
            //$.post({url : '<?php //echo $this->webroot;  ?>Users/appointment/'+shift_id});

        });
        
        $('#select_date').datepicker({
            todayBtn: "linked",
            language: "it",
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy"
        }).on('changeDate', function () {
            var shift_id = $("#shift_id option:selected").val();
            var select_date   = $('#select_date').val(); 
            var quota_id   = $('#quota_id').val();
            
            window.location = "<?php echo $this->webroot; ?>Users/appointment/" + shift_id+"/"+select_date+"/"+quota_id; 
        });





    });

</script>
<style>
    input:checked{
        height: 35px;
        /*width: 25px;*/
    }
</style>