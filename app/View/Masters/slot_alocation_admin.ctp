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
<script>
    $("input:radio").each(function () {
        $(this).find('input:radio').prop('disabled', true);

        $(this).on('click', function () {
            $("#time").val($(this).parent().attr('id'));
        });
    });

</script>



<div class="row">
    <div class="col-sm-12">
        <div class="col-sm-12">
            <hr style="border: red solid thin">
            <b><span style="color: red;">Note 1 : Lunch time from <?php echo $lunch_from . ' To ' . $lunch_to; ?></span></b><br>
            <?php if($tatkal=='Y'){?>
            <b><span style="color: red;">Note 2 : Tatkal time from <?php echo $tatkal_from . ' To ' . $tatkal_to; ?></span></b><?php } ?></div>
        
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
      
        <?php
        $i = 1;
        $sum = 0;
        $curr_date = date('d-m-Y');
   
      for($i=1;$i<=count($a); $i++){
          
           if (strtotime($curr_date) == strtotime($app_dt)) {
                      $curr_time = date("H:i", strtotime(date('h:i A')));
                           $s = explode('-', $a[$i]);
                         // if (!(strtotime($s[0]) <= strtotime($curr_time))) {?>
          <div class="col-sm-2">
                <br>
                <label > <?php echo $a[$i]; ?> </label><br>
                <?php
                $timeslot = (explode("-", $a[$i]));
                $slot1minut = get_total_minutes($timeslot[0]);
                $slot2minut = get_total_minutes($timeslot[1]);
                $totminute = abs($slot1minut - $slot2minut);
                $totslot = $totminute / $slot;


                for ($j = 1; $j <= $totslot; $j++) {
                    $sum = $sum + 1;
                    ?>
                    <span id="<?php echo $a[$i]; ?>">
                        <input type="radio" name="slot" value="<?php echo $i . '_' . $j; ?>"  />

                    </span>
                <?php } ?>
            </div>
                              
         <?php //}
                           
               
           }else{
          
          
            ?>
            <div class="col-sm-2">
                <br>
                <label > <?php echo $a[$i]; ?> </label><br>
                <?php
                $timeslot = (explode("-", $a[$i]));
                $slot1minut = get_total_minutes($timeslot[0]);
                $slot2minut = get_total_minutes($timeslot[1]);
                $totminute = abs($slot1minut - $slot2minut);
                $totslot = $totminute / $slot;


                for ($j = 1; $j <= $totslot; $j++) {
                    $sum = $sum + 1;
                    ?>
                    <span id="<?php echo $a[$i]; ?>">
                        <input type="radio" name="slot" value="<?php echo $i . '_' . $j; ?>"    />

                    </span>
                <?php } ?>
            </div>
            <?php
           }
        }
        ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="col-sm-12">
            <hr style="border: red solid thin">
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">

        </div>
        <input type="hidden" name="time" id="time" >
        <input type="hidden" name="totalslot" id="totalslot" value="<?php echo $sum; ?>">
    </div>
