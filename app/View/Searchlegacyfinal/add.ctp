<?php
echo $this->Html->script('bootstrap-datepicker.min');
echo $this->Html->css('bootstrap-datepicker3.min');
?>


<script type="text/javascript">
$(document).ready(function(){
        $('#sid').change(function(){   
            var stateid=$('#sid').value();
             $.getJSON("getDist", {state_id: stateid}, function (data) {
                var sc='';    
            }
        })     
    });
</script>



<?php $doc_lang = $this->Session->read('doc_lang');?>

<?php echo $this->Form->create('searchlegacyfinal', array('id' => 'searchlegacyfinal', 'autocomplete' => 'off')); ?>


<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
               <center><h3 class="box-title " style="font-weight: bolder">
                    <?php echo __('Add NEW DISTRICT ANCHAL ENTRY'); ?>
                </h3> </center>

                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Citizenentry/leg_property_details_en<?php ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div>    
            </div>

           

            <div class="box-body">
              
              <div class="rowht"></div>
              <div class="hr1" style="border: 1px solid black;"></div>
              <div class="rowht"></div>


              <div class="row">
                  <div class="form-group">
                      <div class="col-sm-3">
                          <label for="" class="col-sm-3 control-label"><?php echo __('lbladmdistrict'); ?><span style="color: #ff0000">*</span></label> 
                          <?php echo $this->Form->input('district_id', array('label' => false, 'id' => 'district_id', 'class' => 'form-control input-sm', 'style' => 'cursor: not-allowed;', 'disabled', 'options' => array('empty' => '--Select--', $district))); ?> 
                          <span  id="district_id_error" class="form-error"><?php // echo $errarr['district_id_error']; ?></span>
                      </div>

                      <div class="col-sm-3" >
                          <label for="" class="col-sm-7 control-label"><?php echo __('Enter Anchal/Circle code'); ?><span style="color: #ff0000">*</span></label> 
                          <?php echo $this->Form->input('taluka_code', array('label' => false, 'id' => 'taluka_code', 'class' => 'form-control input-sm')); ?>                              
                          <span  id="taluka_code_error" class="form-error"><?php //echo $errarr['taluka_code']; ?></span>
                      </div>
  
                      
                      <div class="col-sm-3">
                          <label for="" class="col-sm-7 control-label" ><?php echo __('Enter Anchal/Circle Name'); ?><span style="color: #ff0000">*</span></label> 
                          <?php echo $this->Form->input('taluka_name_en', array('label' => false, 'id' => 'taluka_name_en', 'class' => 'form-control input-sm')); ?>    
                          <span  id="taluka_name_en_error" class="form-error"><?php //echo $errarr['taluka_name_en_error']; ?></span>

                      </div>

                      
                  </div>
              </div> 

              








                </div>
                </div>
                </div>
               

    <?php echo $this->Form->end(); ?>

