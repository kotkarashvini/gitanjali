<script>
    $(document).ready(function () {


        $('#from').datepicker({
//            daysOfWeekDisabled: [0,6],
             format: "dd-mm-yyyy",
            todayBtn: "linked",
            calendarWeeks: true,
            //orientation: "top left",
            autoclose: true,
            todayHighlight: true
        });
        $('#to').datepicker({
//            daysOfWeekDisabled: [0,6],
           format: "dd-mm-yyyy",
            todayBtn: "linked",
            calendarWeeks: true,
            //orientation: "top left",
            autoclose: true,
            todayHighlight: true

        });
        $("#office_id").change(function () {
            $("#hfofficename").val($("#office_id option:selected").text());
        });

//        $("input:radio[name='data[rpt_index_register][filterby]']").change(function () {
//            viewoptions();
//        });

    });
    
     function formprint() {
        document.getElementById("actiontype").value = '1';
    }
</script>
<?php echo $this->Form->create('online_payment_summary', array('id' => 'online_payment_summary')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class = "box box-primary">
            <div class = "box-header with-border" style="color: #8B0000">
                <center><h3 class = "box-title headbolder">Online Payment Summary </h3></center>
            </div>
            <div class="box-body">


                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <center><h3 class = "box-title headbolder"><?php echo $officename; ?> </h3></center>

                        </div>
                    </div>
                </div> 
                <div  class="rowht"></div>  <div  class="rowht"></div> 
                 <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
                <div  class="rowht"></div>  <div  class="rowht"></div> 
                <div class="row" id="divDate">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="TAX No" class="control-label col-sm-2"><?php echo __('Get Record By Date'); ?></label>        
                        <div class="col-sm-2"><?php echo $this->Form->input("from", array('id' => 'from', 'legend' => false, 'class' => 'date form-control', 'label' => false)); ?>
                        <span id="from_error" class="form-error"><?php //echo $errarr['from_error'];  ?></span>
                        </div>
                        <div class="col-sm-2"><?php echo $this->Form->input("to", array('id' => 'to', 'legend' => false, 'class' => 'date form-control', 'label' => false)); ?>
                        <span id="to_error" class="form-error"><?php //echo $errarr['to_error'];  ?></span>
                        
                        </div>
                        <div class="col-sm-2"><button id="go" class="btn btn-primary" type="submit"> <?php echo __('lblsearch'); ?> </button></div>
                    </div>     
                </div>
            </div>
            <br><br>
             <?php if(!empty($htmldesign)) {  ?>
            <div class="row center">
                <div class="form-group col-sm-12" > 
                    <!--<div class="col-sm-1"></div>-->                   
                    <div class="col-sm-12"><?php echo $htmldesign; ?></div>
                    <!--<div class="col-sm-1"></div>-->          
                </div>
            </div><br><br>
            <div class="row center">
                <div class="form-group" > 
                   <button id="btnadd" name="btnadd1" class="btn btn-info " onclick="javascript: return formprint();">
                            <?php echo __('Print'); ?></button> 
                   
                </div>
            </div>
             <?php } ?><br><br>

        </div> 
    </div> 
<input type="hidden" id="actiontype" value='<?php echo $actiontype; ?>' name="actiontype" class="btn btn-primary">
</div>

