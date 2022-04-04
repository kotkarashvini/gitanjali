
<script>
    $(document).ready(function () {
     
         $("#date").hide();
          $('.date').datepicker({
            format: "yyyy-mm-dd",
            todayBtn: "linked",
            calendarWeeks: true,
            //orientation: "top left",
            autoclose: true,
            todayHighlight: true
        });
       
    $("input:radio[name='data[inspection_report][filterby]']").change(function () {
            viewoptions();
        });

    });

    function viewoptions(){
           var fltflag = $("input:radio[name='data[inspection_report][filterby]']:checked").val();
         //  alert(fltflag);
        if (fltflag == 'SIA') {
              $("#date").hide();
            $("#date").slideDown(1000);
        }
        else  if (fltflag == 'IWVDL') {
            //alert(fltflag);
            $("#date").hide();
            $("#date").fadeIn("slow");
        }
        else  if (fltflag == 'VA') {
               $("#date").hide();
            $("#date").slideDown(100);
        }
        else  if (fltflag == 'VSRL') {
//              alert(fltflag);
                $("#date").hide();
            $("#date").show();
        }
        else  if (fltflag == 'SIB') {
              $("#date").hide();
            $("#date").slideDown(1000);
        }
        else{
            $("#date").hide();
        }
    }
    
    
    
   
</script>




<?php echo $this->Form->create('inspection_report', array('id' => 'inspection_report')); ?>

<div class = "box box-primary">
    <div class = "box-header with-border">
        <center><h3 class = "box-title headbolder"><?php echo __('lblinspectionrpt'); ?></h3></center>
    </div>
    <div class="box-body">

        <div class="row">
            <div class="form-group">
                 <div class="col-sm-12"> <?php echo $this->Form->input('filterby', array('type' => 'radio', 'options' => array('SIA' => '&nbsp;' . __('Site Inspection Allowtment') . '&nbsp;&nbsp;&nbsp;&nbsp;', 'IWVDL' => '&nbsp;' . __('Inspector wise visited documents list') . '&nbsp;&nbsp;&nbsp;', 'VA' => '&nbsp;' . __('Visit not Done for the Allotment') . '&nbsp;&nbsp;&nbsp;', 'VSRL' => '&nbsp;' . __('Visit Report of Sub Register Lottery Documents') . '&nbsp;&nbsp;&nbsp;', 'SIB' => '&nbsp;' . __('Site Inspection Book') . '&nbsp;&nbsp;&nbsp;'), 'value' => 'V', 'legend' => false, 'div' => false, 'id' => 'fltrBy')); ?></div>                            
            </div>
        </div>
        <div class="rowht">&nbsp;</div>
        <div id="date">
            <div class="row">
                <div class="form-group">
                    <div class="col-sm-3"></div>
                    <label for="From_Date" class="col-sm-2"><?php echo __('lblfromdate'); ?></label>   
                    <div class="input-group date col-sm-2">
                        <?php echo $this->Form->input('from', array('label' => false, 'id' => 'from', 'type' => 'text', 'class' => 'form-control input-sm', 'readonly' => 'readonly', 'value' => date('Y-m-d'))); ?>
                        <span class="input-group-addon glyphicon glyphicon-calendar"></span>
                    </div>
                </div> 
            </div>
            <div  class="rowht">&nbsp;</div>
            <div class="row" >
                <div class="form-group">
                    <div class="col-sm-3"></div>
                    <label for="TO Date" class="col-sm-2" ><?php echo __('lbltodate'); ?></label> 
                    <div class="input-group date col-sm-2">
                        <?php echo $this->Form->input('to', array('label' => false, 'id' => 'to', 'type' => 'text', 'class' => 'form-control input-sm', 'readonly' => 'readonly', 'value' => date('Y-m-d'))); ?>
                        <span class="input-group-addon glyphicon glyphicon-calendar"></span>
                    </div>
                </div>
            </div>
        </div>  
        <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
        <div class="row center">
            <div class="form-group">
                <input type="submit" id="go" value="Go" class="btn btn-info">
<!--                        <input type="hidden" id="actiontype" name="hdnaction" class="btn btn-primary">
                <input type="hidden" id="val_id" name="valid" class="btn btn-primary">-->
            </div>
        </div>




    </div>
</div>


