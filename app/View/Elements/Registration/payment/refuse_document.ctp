<?php

if(@$documents[0][0]['doc_refuse_flag']=='N'  && @$documents[0][0]['final_stamp_flag']=='N'){?>
<div class="col-md-12"> 
    <div class="col-md-6">
       <?php echo $this->Form->create('refuse', array('url' => array('controller' => 'Registration', 'action' => 'payment_verification'),'id' => 'refuse','class' => 'form-vertical')); ?>   
    <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>

        <div class="form-group">

            <label>Document Refuse Remark</label>
 <?php echo $this->Form->input('doc_refuse_remark', array('label' => false, 'id' => 'doc_refuse_remark', 'class' => 'form-control input-sm', 'type' => 'textarea', 'rows' => 3)); ?>
            <span class="form-error" id="doc_refuse_remark_error"></span>
        </div>
        <div class="form-group">
       <?php echo $this->Form->input('submit', array('label' => false, 'id' => 'refusesubmit', 'class' => 'btn btn-primary input-sm', 'type' => 'submit', 'value' => 'Submit')); ?>
        </div>
        <?php echo $this->Form->end(); ?>  
    </div>
</div>
<?php }else if(@$documents[0][0]['doc_refuse_flag']=='Y') { ?> 
<div class="col-md-12" id="divprintrefuse">  
    <?php
    
$result = $this->requestAction(array('controller' => 'Reports', 'action' => 'pre_registration_docket', base64_encode($token), 'V'));
echo "<ul style='color:red;'>"
        . "<li><b> This document is refused on ".date('d-m-Y h:s:i a',strtotime(@$documents[0][0]['doc_refuse_date']))." </b></li><li> <b>Remark : ".@$documents[0][0]['doc_refuse_remark']." </b></li></ul>";
echo $result;
?>
   </div>  
<div class="col-md-12 center">
   <button type="button" class="btn btn-warning" id="btnrefuseprint"> <span class="fa fa-print"></span> <?php echo __('lblprint'); ?></button> 
    
</div>
    <?php
 }else{
     echo 'Document Is Registered.';
 }?>

 
