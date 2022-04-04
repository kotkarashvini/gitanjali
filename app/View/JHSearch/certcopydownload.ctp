
<?php echo $this->Form->create('certcopydownload', array('id' => 'certcopydownload')); ?>
<div class="box box-primary">
    <div class="box-header with-border">
        <center><h3 class="box-title" style="font-weight: bolder"><?php echo __('Download Certified Copy'); ?></h3></center>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="form-group">
                <div class="col-sm-2"></div>
                <label for="district_id" class="col-sm-2 control-label"><?php echo __('Registration Number'); ?><span style="color: #ff0000">*</span></label>
                <div class="col-sm-3">
                    <?php echo $this->Form->input('final_doc_reg_no', array('type' => 'text', 'name' => 'final_doc_reg_no', 'id' => 'final_doc_reg_no', 'label' => FALSE, 'class' => 'form-control')); ?>
                    <span id="final_doc_reg_no_error" class="form-error"><?php echo $errarr['final_doc_reg_no_error'];    ?></span>
                </div>
            </div>
        </div><br>
        <div class="row">
            <div class="form-group">
                <div class="col-sm-2"></div>
                <label for="district_id" class="col-sm-2 control-label"><?php echo __('Application Number'); ?><span style="color: #ff0000">*</span></label>
                <div class="col-sm-3">
                    <?php echo $this->Form->input('application_id', array('type' => 'text', 'name' => 'application_id', 'id' => 'application_id', 'label' => FALSE, 'class' => 'form-control')); ?>
                    <span id="application_id_error" class="form-error"><?php echo $errarr['application_id_error'];    ?></span>
                </div>
            </div>
        </div><br>
        <div class="row" style="text-align: center">
            <div class="col-sm-12">
                <div class="form-group">
                    <button id="btndownload" name="btndownload" class="btn btn-info" style="text-align: center;" type="submit">
                        Download </button>&nbsp;&nbsp;&nbsp;&nbsp;
                </div>
            </div>
        </div>
    </div>  
</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>