<script>
    $(document).ready(function () {

        $('.date').datepicker({
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            calendarWeeks: true,
            autoclose: true,
            todayHighlight: true
        });
    });


</script>

<script>

    $(function () {
        $('#go').click(function () {
            $('form').submit();
        });
    });
</script>


<?php echo $this->Form->create('rpt_franking_mh', array('id' => 'rpt_franking_mh')); ?>

<div class = "box box-primary">
    <div class = "box-header with-border">
        <center><h3 class = "box-title headbolder">FRANKING Book</h3></center>
    </div>
    <div class="box-body">

        <div class="row" id="divDate">
            <div class="form-group">
                <div class="col-sm-2"></div>
                <label for="Valuation No" class="control-label col-sm-2"> <?php echo __('lblgetrecordby'); ?> </label>            
                <div class="col-sm-2"><?php echo $this->Form->input("from", array('id' => 'from', 'legend' => false, 'class' => 'date form-control', 'label' => false)); ?>
                    <span id="from_error" class="form-error"><?php //echo $errarr['from_error'];  ?></span>
                </div>
                <button id="go" class="btn btn-primary" type="submit"> Submit </button>
            </div>
            
        </div>
    </div> 

</div>    

