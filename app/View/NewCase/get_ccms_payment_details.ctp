
<?php
if (isset($paymentfields) && !empty($paymentfields)) {
    ?>
    <?php echo $this->Form->create('payment', array('url' => array('controller' => 'NewCase', 'action' => 'payment')), array('id' => 'payment', 'class' => 'form-vertical')); ?>   
    <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>
               
    <?php
    $upadteflag = 0;
    foreach ($paymentfields as $field) {
        $field = $field['PaymentFields'];
        if (isset($payment) && !empty($payment)) {
            $upadteflag = 1;
            $payment1[$field['field_name']] = $payment[0]['payment'][$field['field_name']];
            $payment1['payment_mode_id'] = $payment[0]['payment']['payment_mode_id'];
        } else {
            $payment1[$field['field_name']] = '';
            $payment1['payment_mode_id'] = $field['payment_mode_id'];
        }


        $lblbtnsave = 'Save';
        if ($field['payment_mode_id'] == 1) {
            $lblbtnsave = 'Verify And Save';
        }
        ?>
<div class="form-group">
        <label> <?php echo $field['field_name_desc_' . $lang] ?> </label>
        
        <?php
        if ($field['field_name'] == 'account_head_code') {
            echo $this->Form->input($field['field_name'], array('id' => $field['field_name'], 'class' => 'form-control input-sm', 'label' => false, 'options' => $accounthead, 'empty' => '--Select--', 'default' => $payment1[$field['field_name']]));
        } else if ($field['field_name'] == 'bank_id') {
            echo $this->Form->input($field['field_name'], array('id' => $field['field_name'], 'class' => 'form-control input-sm chosen-select', 'label' => false, 'options' => $bank_master, 'empty' => '--Select--', 'default' => $payment1[$field['field_name']]));
        } else if ($field['field_name'] == 'branch_id') {
            echo $this->Form->input($field['field_name'], array('id' => $field['field_name'], 'class' => 'form-control input-sm chosen-select', 'label' => false, 'options' => $branch_master, 'empty' => '--Select--', 'default' => $payment1[$field['field_name']]));
        } else if ($field['field_name'] == 'cos_id') {
            echo $this->Form->input($field['field_name'], array('id' => $field['field_name'], 'class' => 'form-control input-sm chosen-select', 'label' => false, 'options' => $office, 'empty' => '--Select--', 'default' => $payment1[$field['field_name']]));
        } else {
            echo $this->Form->input($field['field_name'], array('label' => false, 'id' => $field['field_name'], 'class' => 'form-control input-sm', 'type' => 'text', 'value' => $payment1[$field['field_name']]));
        }
        ?>
        <span class="form-error" id="<?php echo $field['field_name']; ?>_error"></span>
       </div> 
        <!--<div class="clearfix"></div>-->
    <?php }
    ?>
    <?php
    echo $this->Form->input('payment_mode_id', array('label' => false, 'id' => 'payment_mode_id', 'class' => 'form-control input-sm', 'type' => 'hidden', 'value' => $payment1['payment_mode_id']));
    if ($upadteflag == 1) {
        echo $this->Form->input('payment_id', array('label' => false, 'id' => 'id', 'class' => 'form-control input-sm', 'type' => 'hidden', 'value' => $payment[0]['payment']['payment_id']));
    }
    ?>

    <br>

    <div class="row center">
        <div class="col-lg-12">
            <div class="form-group">
                <button id="btnadd" type="submit" name="btnadd" class="btn btn-info ">
                    <?php if ($upadteflag == 1) {
                        ?>
                        <span class="glyphicon glyphicon-plus"></span> <?php echo __('btnupdate'); ?>
                    <?php } else { ?>
                        <span class="glyphicon glyphicon-plus"></span> <?php echo $lblbtnsave; ?>
                    <?php } ?>
                </button>
                <button id="btncancel" name="btncancel" class="btn btn-info " type="reset">
                    <span class="glyphicon glyphicon-reset"><?php ?></span><?php echo __('lblreset'); ?>
                </button>
                <?php echo $this->Html->link('New Entry', array('controller' => 'Registration', 'action' => 'payment_verification'), array('class' => 'btn btn-danger', 'escape' => false));
                ?>
            </div>
        </div>
    </div>       
    <?php echo $this->Form->end(); ?>   
    
<?php } else { ?>

    <div class="alert alert-warning">
        No Data Found !
    </div>
<?php } ?>