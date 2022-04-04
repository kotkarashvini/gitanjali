<script>
    $(document).ready(function () {

        $('#from').datepicker({
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            calendarWeeks: true,
            autoclose: true,
            todayHighlight: true
        });
        
         $('#from').change(function () {
            $("#hffrom").val($("#from option:selected").text());
        });
       
       
    });


</script>
<?php echo $this->Form->create('rpt_fee_book_reg', array('id' => 'rpt_fee_book_reg')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class = "box box-primary">
            <div class = "box-header with-border" style="color: #8B0000">
                <center><h3 class = "box-title headbolder">Day Book Report </h3></center>
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
                    <div class="row" id="divDate">
                        <div class="form-group">
                            <div class="col-sm-2"></div>
                            <label for="TAX No" class="control-label col-sm-2"><?php echo __('Get Record By Day'); ?></label>        
                            <div class="col-sm-2"><?php echo $this->Form->input("from", array('id' => 'from', 'legend' => false, 'class' => 'date form-control', 'label' => false)); ?>
                                <span id="from_error" class="form-error"><?php //echo $errarr['from_error'];    ?></span>
                            </div>
                            <div class="col-sm-2"><button id="go" class="btn btn-primary" type="submit"> <?php echo __('lblsearch'); ?> </button></div>
                        </div>     
                    </div>

                </div>
            </div>
        </div>

    </div> 
</div>

    <input type="hidden" id="hffrom" value='<?php echo $hffrom;  ?>' name="hffrom" class="btn btn-primary"><!--
<input type="hidden" id="hffinyr" value='<?php //echo $hffinyr; ?>' name="hffinyr" class="btn btn-primary">-->



<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>


