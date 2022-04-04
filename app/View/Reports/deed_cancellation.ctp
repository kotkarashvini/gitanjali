<script>
    $(document).ready(function () {

//        $("#date").hide();
        $('.date').datepicker({
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            calendarWeeks: true,
            //orientation: "top left",
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


<?php echo $this->Form->create('deed_cancellation', array('id' => 'deed_cancellation')); ?>

<div class = "box box-primary">
    <div class = "box-header with-border">
        <center><h3 class = "box-title headbolder">Appointment Cancellation</h3></center>
    </div>
    <div class="box-body">
        <div class="row" id="divDate">
            <div class="form-group">
                <div class="col-sm-2"></div>
                <label for="Valuation No" class="control-label col-sm-2">Select Date </label>            
                <div class="col-sm-2"><?php echo $this->Form->input("from", array('id' => 'from', 'legend' => false,'Placeholder'=>'From Date','class' => 'date form-control', 'label' => false)); ?></div>
                <div class="col-sm-2"><?php echo $this->Form->input("to", array('id' => 'to', 'legend' => false,'Placeholder'=>'To Date', 'class' => 'date form-control', 'label' => false)); ?></div>
            </div>
        </div>
     <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
    </div> 
         <div  class="rowht">&nbsp;</div>     <div  class="rowht">&nbsp;</div>
      <div class="row">
            <div class="form-group">
                <div class="col-sm-2"></div>
                <label for="Filter Record By" class="control-label col-sm-2">Order By :- </label>            
                <div class="col-sm-7"> <?php echo $this->Form->input('filterby', array('type' => 'radio', 'options' => array('DR' => '&nbsp;Deed Writer &nbsp;&nbsp;&nbsp;&nbsp;', 'DT' => '&nbsp;Date &nbsp;&nbsp;&nbsp;'), 'legend' => false, 'div' => false, 'id' => 'fltrBy')); ?></div>                            
            </div>
        </div>
    <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
    <div class="row center">
        <div class="form-group">
            <div class="col-sm-1"></div>
            <button id="go" class="btn btn-primary" type="submit"> Go </button>
            
            <input type="hidden" id="actiontype" name="hfaction" class="btn btn-primary">
        </div>
    </div>


</div>    

