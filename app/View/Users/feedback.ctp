

<?php echo $this->Form->create('feedback'); ?>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-info">

                <div class="panel-heading">

                    <label><?php echo __('Feedback'); ?></label>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label> *  <?php echo __('Sender Name'); ?></label>
                        <?php echo $this->Form->input('applicantname', array('label' => false, 'type' => 'text', 'id' => 'applicantname', 'class' => 'form-control')); ?>
                        <span id="applicantname_error" class="form-error"><?php echo $errarr['applicantname_error']; ?></span>
                    </div>

                    <div class="form-group">
                        <label>*<?php echo __('Sender Email'); ?></label>
                        <?php echo $this->Form->input('email_id', array('label' => false, 'id' => 'email_id', 'type' => 'text', 'class' => 'form-control')); ?>
                        <span id="email_id_error" class="form-error"><?php echo $errarr['email_id_error']; ?></span>
                        <?php //echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>
                    </div>
                    <div class="form-group">
                        <label>*<?php echo __('Mobile no.'); ?></label>
                        <?php echo $this->Form->input('mobile_no', array('label' => false, 'type' => 'text', 'id' => 'mobile_no', 'class' => 'form-control')); ?>
                        <span id="mobile_no_error" class="form-error"><?php echo $errarr['mobile_no_error']; ?></span>
                    </div>
                    <div class="form-group">
                        <label>*<?php echo __('Feedback'); ?></label>
                        <?php echo $this->Form->input('message', array('label' => false, 'type' => 'textarea', 'id' => 'message', 'class' => 'form-control')); ?>
                        <span id="message_error" class="form-error"><?php echo $errarr['message_error']; ?></span>
                    </div>


                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"><?php echo __('submit'); ?></button> 
                        <button type="reset"  value="reset" class="btn btn-primary"><?php echo __('cancel'); ?></button>
                    </div>
                    <!--</form>-->


                </div>

            </div>

        </div>
    </div>
</div>

<?php echo $this->form->end(); ?>

