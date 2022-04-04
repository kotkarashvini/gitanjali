<?php echo $this->Form->create('payment_head', array('url' => array('controller' => 'Registration', 'action' => 'payment_verification'),'id' => 'payment_head', 'class' => 'form-vertical')); ?>   
<div class="col-md-12">
    <div class="col-md-6">
         <div class="form-group">
        <label><?php echo __('lblamttobepaid'); ?></label>
        <?php         
            echo $this->Form->input("final_value", array('id' => 'final_value', 'class' => 'form-control input-sm', 'label' => false));
         ?>
        <span class="form-error" id="final_value_error"></span>
       </div>
         <div class="form-group">
        <label><?php echo __('lblpaymenthead'); ?></label>
        <?php         
            echo $this->Form->input("account_head_id", array('id' => 'account_head_id', 'class' => 'form-control input-sm', 'label' => false, 'options' => $accounthead, 'empty' => '--Select--'));
            echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); 

            ?>
        <span class="form-error" id="account_head_id_error"></span>
       </div>
        <div class="form-group">
        
        <?php         
            echo $this->Form->button(__('btnsave'), array('type'=>'submit','id' => 'account_head_id', 'class' => 'btn btn-primary input-sm', 'label' => false));
         ?>
       
       </div>
    </div>
</div>
 <?php echo $this->Form->end(); ?>   