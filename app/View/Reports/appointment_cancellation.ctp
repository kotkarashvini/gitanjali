<script>
    $(document).ready(function () {

//        $("#date").hide();
        $('.date').datepicker({
           format: "yyyy-mm-dd",
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


<?php echo $this->Form->create('appointment_cancellation', array('id' => 'appointment_cancellation')); ?>

<div class = "box box-primary">
    <div class = "box-header with-border">
        <center><h3 class = "box-title headbolder">Appointment Cancellation</h3></center>
    </div>
    <div class="box-body">
          <div  class="rowht"></div>  <div  class="rowht"></div> 
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="" class="col-sm-2 control-label"><?php echo __('lblselofc'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('office_id', array('options' => array($officedata), 'empty' => '--select--', 'id' => 'office_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                        <span id="office_id_error" class="form-error"><?php echo $errarr['office_id_error']; ?></span>
                        </div>
                    </div>
                </div>
          <div  class="rowht"></div>  <div  class="rowht"></div> <div  class="rowht"></div>  <div  class="rowht"></div> 
        <div class="row" id="divDate">
            <div class="form-group">
                <div class="col-sm-2"></div>
                <label for="Valuation No" class="control-label col-sm-2">Select Date </label>            
                <div class="col-sm-2">
                    <?php echo $this->Form->input("from", array('id' => 'from', 'legend' => false,'Placeholder'=>'From Date','class' => 'date form-control', 'label' => false)); ?>
                 <span id="from_error" class="form-error"><?php echo $errarr['from_error']; ?></span>
                </div>
                <div class="col-sm-2">
                    <?php echo $this->Form->input("to", array('id' => 'to', 'legend' => false,'Placeholder'=>'To Date', 'class' => 'date form-control', 'label' => false)); ?>
                 <span id="to_error" class="form-error"><?php echo $errarr['to_error']; ?></span>
                </div>
            </div>
        </div>
   
    </div> 
         <div  class="rowht">&nbsp;</div>     <div  class="rowht">&nbsp;</div>
      <div class="row">
            <div class="form-group">
                <div class="col-sm-2"></div>
                <label for="Filter Record By" class="control-label col-sm-2">Order By :- </label>            
                <div class="col-sm-7"> 
                    <?php echo $this->Form->input('filterby', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Deed Writer&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;Date'), 'value' => 'Y', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'filterby')); ?>
                    <?php //echo $this->Form->input('filterby', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Deed Writer &nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;Date &nbsp;&nbsp;&nbsp;'), 'legend' => false, 'div' => false, 'id' => 'filterby')); ?>
                   <span id="filterby_error" class="form-error"><?php echo $errarr['filterby_error']; ?></span>
                </div>                            
            </div>
        </div>
    <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
    <div class="row center">
        <div class="form-group">
            <div class="col-sm-1"></div>
            <button id="go" class="btn btn-primary" type="submit"> Go </button>
            <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
            <input type="hidden" id="actiontype" name="hfaction" class="btn btn-primary">
        </div>
    </div>


</div>    

