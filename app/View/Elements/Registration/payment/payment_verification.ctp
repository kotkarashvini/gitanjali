  
<div class="col-md-12">
     <div class="col-md-6">
        <div class="col-md-10" >
            <label for="" class="control-label"><?php echo __('lblselectpaymode'); ?><span style="color: #ff0000">*</span></label>    
            <?php echo $this->Form->input('paymentmode_id', array('label' => false, 'id' => 'paymentmode_selection1', 'class' => 'form-control input-sm paymentmode_id', 'type' => 'select', 'options' => array('empty' => '--Select--', $payment_mode_online))) ?>                         
        </div> 
         <div class="col-md-2">
            <a  href="<?php echo $this->webroot; ?>helpfiles/Payment/payment_varification_<?php echo $lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
        </div>
        <div class="col-md-10" id="paydetails1"> 

        </div>        

    </div>
</div>