<script>
    $(document).ready(function () {


        $('#from').datepicker({
//            daysOfWeekDisabled: [0,6],
            format: "yyyy-mm-dd",
            todayBtn: "linked",
            calendarWeeks: true,
            //orientation: "top left",
            autoclose: true,
            todayHighlight: true
        });
        $('#to').datepicker({
//            daysOfWeekDisabled: [0,6],
            format: "yyyy-mm-dd",
            todayBtn: "linked",
            calendarWeeks: true,
            //orientation: "top left",
            autoclose: true,
            todayHighlight: true

        });
     
//        $("input:radio[name='data[rpt_index_register][filterby]']").change(function () {
//            viewoptions();
//        });

    });
    
     function formprint() {
        document.getElementById("actiontype").value = '1';
    }
</script>
<?php echo $this->Form->create('income_tax', array('id' => 'income_tax')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class = "box box-primary">
            <div class = "box-header with-border" style="color: #8B0000">
                <center><h3 class = "box-title headbolder">Income Tax Report </h3></center>
            </div>
            <div class="box-body">


                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <center><h3 class = "box-title headbolder"><?php echo $officename; ?> </h3></center>

                        </div>
                    </div>
                </div> 
                  <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="article " class="col-sm-2 control-label">Select Office</label>  
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('office_id', array('label' => false, 'id' => 'office_id', 'type' => 'select', 'class' => 'form-control input-sm', 'empty' => '--Select ----', 'options' => $office)); ?>
                        </div>

                    </div>     
                </div>
                <div  class="rowht"></div>  <div  class="rowht"></div> 
                <div class="row">
                    <div class="col-sm-2"></div>
                    <label for="TAX No" class="control-label col-sm-2"><?php echo __('Get Record By Date'); ?></label>        
                        <div class="col-sm-2"><?php echo $this->Form->input("from", array('id' => 'from', 'legend' => false, 'class' => 'date form-control', 'label' => false,'placeholder' =>'From Date')); ?></div>
                        <div class="col-sm-2"><?php echo $this->Form->input("to", array('id' => 'to', 'legend' => false, 'class' => 'date form-control', 'label' => false,'placeholder' =>'To Date')); ?></div>
                   
                </div>
                <div  class="rowht"></div>  <div  class="rowht"></div> 
                <div class="row" id="divDate">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                         <label for="district_id " class="col-sm-2 control-label">Property Value Greater Than<span style="color: #ff0000">*</span></label>    
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('val_amount', array('label' => false, 'id' => 'val_amount', 'class' => 'form-control input-sm')); ?>
                    </div>
                       
                           
                       
                        
                    </div>  
                    </div>
                <div  class="rowht"></div><div  class="rowht"></div>
                <div class="row">
                    <div class="col-sm-2"></div>
                       
                    <div class="col-sm-6"> <?php echo $this->Form->input('report_flag', array('type' => 'hidden',  'value' => 'H', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'listflag')); ?></div>
                </div> 
                 <div  class="rowht"></div><div  class="rowht"></div>
                 <div class="row">
                    <div class="col-sm-2"></div>
                       
                    <div class="col-sm-2"><button id="go" class="btn btn-primary" type="submit"> <?php echo __('Submit'); ?> </button></div>
                </div> 
                               
         
                
                </div>
            </div>
            <br><br>
             <?php if(!empty($htmldesign)) {  ?>
            <div class="row center" style='overflow:auto; width:100%;height:400px;'>
                <div class="form-group" > 
                    <div class="col-sm-1"></div>                   
                    <div class="col-sm-10"><?php echo $htmldesign; ?></div>
                    <div class="col-sm-1"></div>          
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
<input type="hidden" id="hfname" value='<?php echo $hfname; ?>' name="hfname" class="btn btn-primary">
</div>

