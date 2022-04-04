<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>
<style>
    .tb {
        background-color : #FFFFBB !important;
        border: 3px double #008000;
        font-size:50px;
        width: 250px;
        height: 80px;
        color: red;
        text-align: center;
    }
    .pd {
        background-color : #FFDDBB !important;
        /*border: 3px double #008000;*/
        font-size:20px;
        width: 250px;
        height: 30px;
        color: blue;
        text-align: center;
        font-weight: bold;
    }
</style>
<script>
    $(document).ready(function () {

        $('.date').datepicker({
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            calendarWeeks: true,
            //orientation: "top left",
            autoclose: true,
            todayHighlight: true
        });

        $('#btnSearch').click(function () {
            var from = $("#from").val();
            var to = $("#to").val();
            $.post('<?php echo $this->webroot; ?>Masters/activity_count', {from: from, to: to, flag: 'S'}, function (data) {
                $("#count").val('');
                $("#count").val(data);
                $("#pdata").html('');
                $("#pdata").html("Session Count");
            });
            return false;
        });
        $('#btnSearch1').click(function () {
            $.post('<?php echo $this->webroot; ?>Masters/activity_count', {flag: 'P'}, function (data) {
                $("#count").val('');
                $("#count").val(data);
                $("#pdata").html('');
                $("#pdata").html("Activity Count");
            });
            return false;
        });

    });
</script>


<?php echo $this->Form->create('activity', array('id' => 'activity', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('Logs Count'); ?></h3></center>

            </div>
            <div class="box-body">
                <div  class="rowht"></div>
                <div  class="rowht"></div>
                <div class="col-sm-9">

                    <div class="row">
                        <div class="form-group">
                            <label for="Select Date" class="control-label col-sm-2"> <?php echo __('Select Date'); ?> </label>    

                            <div class="col-sm-3"><?php echo $this->Form->input("from", array('id' => 'from', 'legend' => false, 'class' => 'date form-control', 'label' => false)); ?></div>
                            <div class="col-sm-3"><?php echo $this->Form->input("to", array('id' => 'to', 'legend' => false, 'class' => 'date form-control', 'label' => false)); ?></div>
                            <div class="col-sm-3">
                                <button id="btnSearch" name="btnSearch" class="btn btn-info " style="text-align: center;" >
                                    <span class="glyphicon glyphicon-plus">
                                    </span>&nbsp;
                                    <?php echo __('Session Count'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div  class="rowht"></div>
                    <div  class="rowht"></div>
                    <div  class="rowht"></div>
                    <div  class="rowht"></div><div  class="rowht" style="border: 3px solid #0073b7"></div><div  class="rowht"></div>
                    <div  class="rowht"></div>
                    <div  class="rowht"></div>
                    <div  class="rowht"></div>
                    <div class="row">
                        <div class="form-group">
                            <!--<div class="col-sm-2"></div>-->
                            <label for="Select Date" class="control-label col-sm-3"> <?php echo __('Postgres Statistics'); ?> </label>    
                            <div class="col-sm-3 tdselect">

                                <button id="btnSearch1" name="btnSearch1" class="btn btn-info " >
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('Activity Count'); ?>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
                
                <div class="col-sm-3">
                   
                    <div class="row">
                        <div class="form-group">
                            <p id="pdata" class="control-label col-sm-12 pd"> <?php echo __('Count'); ?> </p>    
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <?php echo $this->Form->input('count', array('label' => false, 'id' => 'count', 'class' => 'form-control tb', 'type' => 'text', 'readonly' => 'readonly')) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>

