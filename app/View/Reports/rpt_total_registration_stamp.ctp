<style>
    .ui-datepicker-calendar {
        display: none;
    }
    </style>
<script>
    $(document).ready(function () {

            $('.date-picker').datepicker( {
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: 'MM yy',
            onClose: function(dateText, inst) { 
                $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
            }
            });
       

        $('#district_id').change(function () {
            $("#hfname").val($("#district_id option:selected").text());
        });

    });
    
    
</script>
<?php echo $this->Form->create('rpt_total_registration_stamp', array('id' => 'rpt_total_registration_stamp')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class = "box box-primary">
            <div class = "box-header with-border" style="color: #8B0000">
                <center><h3 class = "box-title headbolder">MONTHLY REPORT ON COLLECTION FOR REGISTRATION FEE AND STAMP DUTY </h3></center>
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
                    <div class="col-sm-2"></div>
                    <label for="month" class="control-label col-sm-2" required><?php echo __('Select Month'); ?></label>     
                        <div class="col-sm-2"><?php echo $this->Form->input("startDate", array('id' => 'startDate','legend' => false, 'class' => 'date-picker form-control', 'label' => false,'placeholder' =>'Select Month')); ?>
                        <span id="startDate_error" class="form-error"><?php //echo $errarr['startDate_error']; ?></span>
                        </div>
                    <div class="col-sm-2"><button id="go" class="btn btn-primary" type="submit" required> <?php echo __('lblsearch'); ?> </button></div>
                </div> 
                </div>

            </div>
            <input type="hidden" id="hfname" value='<?php echo $hfname; ?>' name="hfname" class="btn btn-primary">

        </div> 
    </div> 

<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>


