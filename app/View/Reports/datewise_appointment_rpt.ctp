<?php

echo $this->Html->script('jquery.dataTables');
echo $this->Html->script('dataTables.bootstrap');
?>
<style>
    th, td {
        padding: 5px;
    }
</style>
<script type="text/javascript">
    $(document).ready(function () {

        $('#from').datepicker({
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            calendarWeeks: true,
            autoclose: true,
            todayHighlight: true
        });
        $("#captcha ").val('');
        $('#tableparty').dataTable({
            "iDisplayLength": 10,
            "ordering": false,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });


    });
</script>
<script type="text/javascript">
    $(document).ready(function ()
    {
        $('#reload').click(function ()
        {
            var captcha = $("#captcha_image");
            captcha.attr('src', captcha.attr('src') + '?' + Math.random());
            return false;
        });
    });
</script>
<?php
echo $this->Form->create('datewise_appointment_rpt', array('id' => 'datewise_appointment_rpt', 'autocomplete' => 'off'));
?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>


<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary" id="formselect">
            <div class="box-header with-border">
                <center><h1 class="box-title headbolder">Appointment Details</h1></center>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-3">
                        <label for="" class=" control-label"><?php echo __('Select Office'); ?> :-</label>  
                    </div>
                    <div class="col-sm-2">                   
                        <div class="form-group">
                     <?php echo $this->Form->input('office_id', array('type' => 'select', 'empty' => '--All Offices--', 'options' => $officelist, 'label' => false, 'multiple' => false, 'id' => 'office_id', 'class' => 'form-control input-sm')); ?>
                            <span id="office_id_error" class="form-error"><?php //echo $errarr['office_id_error'];                  ?></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <label for="TAX No" class="control-label"><?php echo __('Select Appointment Date'); ?></label> 
                    </div>
                    <div class="col-sm-2">                   
                        <div class="form-group">
                      <?php echo $this->Form->input("from", array('id' => 'from', 'legend' => false, 'class' => 'date form-control', 'label' => false)); ?>

                            <span id="from_error" class="form-error"><?php //echo $errarr['from_error'];  ?></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-2">

                        <div class="form-group has-feedback">
            <?php echo $this->Form->input('captcha', array('label' => false, 'class' => 'form-control', 'id' => 'captcha', 'class' => 'form-control', 'placeholder' => 'Enter Captcha')); ?>
                        </div>
                        <span id="from_error" class="form-error"><?php //echo $errarr['from_error'];  ?></span>
                    </div>
                </div>


                <div class="row" >
                    <div class="form-group">
                        <div class="col-sm-3"></div>
                        <div class="form-group has-feedback">                
            <?php echo $this->Html->image(array('controller' => 'users', 'action' => 'get_captcha'), array('id' => 'captcha_image', 'class' => 'img-rounded img-thumbnail')); ?>
                            <button type="button" id="reload" class="btn btn-default btn-reload btn-lrg ajax">
                                <i class="fa fa-spin fa-refresh"></i>
                            </button>
                        </div>
                    </div>
                </div>


                <div class="row" >
                    <div class="col-sm-3"></div>                     
                    <div class="form-group">
                        <button id="go" name="go" class="btn btn-info" style="text-align: center;" type="submit"><?php echo __('lblsearch'); ?>  </button>
                    </div>
                </div>   
            </div>
        </div>
    </div>
</div>

<?php
if (!empty($searchgrid)) {
    $SrNo = 1;
    ?>
<div class="row">

    <div class="box box-primary">
        <div class="col-sm-12">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"> Status for <?php echo $offname; ?></center> 
                <center><h4 class="box-title headbolder"> Appointment Date: <?php echo date('d-M-Y', strtotime($fromdate)); ?></h4></center>
            </div>
            <br>
            <table id="tableparty" class="table table-striped table-bordered table-hover table-responsive">  
                <thead >  
                    <tr>
                        <th class="width10 center"><?php echo __('Sr. No'); ?></th>
                        <th class="center"><?php echo __('District'); ?></th>
                        <th class="width10 center"><?php echo __('SRO Name'); ?></th>
                        <th class="width10 center"><?php echo __('Total Slots'); ?></th>
                        <th class="width10 center"><?php echo __('Slots Taken'); ?></th>
                        <th class="width10 center"><?php echo __('Slot Available'); ?></th>
                    </tr>  
                </thead>
                <tbody>
             <?php
            foreach ($searchgrid as $searchgrid1):
                $h = explode(":", $searchgrid1[0]['officetime']);
                $offmin = ($h[0]*60)+$h[1];
                $m = explode(":", $searchgrid1[0]['lunchtime']);
                $offlun = ($m[0]*60)+$m[1];
                $totalslots = ($offmin-$offlun)/$searchgrid1[0]['slot_time_minute'];
                $taken_slots = $takenslots[0][0]['taken_slots'];
                $available = $totalslots - $taken_slots;
                ?>
                    <tr>
                        <td><?php echo $SrNo++; ?></td>
                        <td><?php echo $searchgrid1[0]['district_name_en']; ?></td>
                        <td><?php echo $searchgrid1[0]['office_name_en']; ?></td>
                        <td><?php echo $totalslots; ?></td>
                        <td><?php echo $taken_slots; ?></td>
                        <td><?php echo $available; ?></td>
                    </tr>
            <?php endforeach; ?>
                </tbody>
            </table> 

        </div>

    </div>

</div>
<?php } ?>

<?php echo $this->Form->end(); ?>