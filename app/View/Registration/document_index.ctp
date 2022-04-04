<script>
    $(document).ready(function () {


        $('#from').datepicker({
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            calendarWeeks: true,
            autoclose: true,
            todayHighlight: true
        });
    });
</script>
<?php
echo $this->element("Registration/main_menu");
?> 
<?php echo $this->Form->create('doc_index', array('id' => 'income_tax')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class = "box box-primary">
            <div class = "box-header with-border" style="color: #8B0000">
                <center><h3 class = "box-title headbolder">Click On Display Record Button </h3></center>
            </div>
            <div class="box-body">


              
               
                <div  class="rowht"></div>  <div  class="rowht"></div> 
                <div class="row">
                    <div class="col-sm-2"></div>
                    <label for="TAX No" class="control-label col-sm-2"><?php echo __('Get Record By Date'); ?></label>        
                    <div class="col-sm-2"><?php echo $this->Form->input("from", array('id' => 'from', 'legend' => false, 'class' => 'date form-control', 'label' => false, 'placeholder' => 'From Date', 'value'=>  date('d-m-Y'))); ?></div>
                     <div class="col-sm-2"><button id="go" class="btn btn-primary" type="submit" value="Display Record"> <?php echo __('Display Record'); ?> </button></div>  </div>

                </div>
               
                <div  class="rowht"></div> 
                
            <br><br>
           

        </div> 
    </div> 
    
</div>

