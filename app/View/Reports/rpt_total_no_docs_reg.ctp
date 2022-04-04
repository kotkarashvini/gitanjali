<script>
    $(document).ready(function () {

        $('#district_id').change(function () {
            $("#hfdist").val($("#district_id option:selected").text());
        });
         $('#finyear_desc').change(function () {
            $("#hffinyr").val($("#finyear_desc option:selected").text());
        });

    });


</script>
<?php echo $this->Form->create('rpt_total_no_docs_reg', array('id' => 'rpt_total_no_docs_reg')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class = "box box-primary">
            <div class = "box-header with-border" style="color: #8B0000">
                <center><h3 class = "box-title headbolder">Article-wise Documents Registered</h3></center>
            </div>
            <div class="box-body">


<!--                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="col-sm-2"></div>
                            <label for="district_id " class="col-sm-2 control-label"><?php //echo __('lbladmdistrict'); ?><span style="color: #ff0000">*</span></label>    
                            <div class="col-sm-2">
                                <?php //echo $this->Form->input('district_id', array('label' => false, 'id' => 'district_id', 'class' => 'form-control input-sm', 'options' => array($District), 'empty' => '--Select--')); ?>
                                <span id="district_id_error" class="form-error"><?php //echo $errarr['district_id_error']; ?></span>
                            </div>
                        </div>
                    </div>
                </div> -->
                <div  class="rowht"></div>  <div  class="rowht"></div> 
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="col-sm-2"></div>
                            <label for="month" class="control-label col-sm-2"><?php echo __('Select Financial Year'); ?></label>     
                            <div class="col-sm-2">
                                <?php echo $this->Form->input('finyear_desc', array('label' => false, 'id' => 'finyear_desc', 'class' => 'form-control input-sm', 'options' => array($finyear), 'empty' => '--Select--')); ?>
				<span id="finyear_desc_error" class="form-error"><?php //echo $errarr['finyear_desc_error']; ?></span>				
                            </div>
                            <div class="col-sm-2"><button id="go" class="btn btn-primary" type="submit"> <?php echo __('lblsearch'); ?> </button></div>
                        </div>
                    </div>
                </div>
                
            </div> 
        </div>

    </div>
    <input type="hidden" id="hfdist" value='<?php echo $hfdist; ?>' name="hfdist" class="btn btn-primary">
    <input type="hidden" id="hffinyr" value='<?php echo $hffinyr; ?>' name="hffinyr" class="btn btn-primary">
</div> 
</div> 

<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>