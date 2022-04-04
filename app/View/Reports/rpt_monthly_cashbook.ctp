
<script>
    $(function () {
      
        $('.date').datepicker({
            autoclose: true,
          format: "dd-mm-yyyy",
            viewMode: "months",
            minViewMode: "months"
        });
        $('#go').click(function () {
            $('form').submit();
        });
    });
</script>

<?php echo $this->Form->create('rpt_monthly_cashbook', array('id' => 'frmRpt')); ?>
<div class = "box box-primary">
    <div class = "box-header with-border">
        <center><h3 class = "box-title headbolder"><?php echo __('lbl_monthly_cashbook'); ?> </h3></center>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="form-group">
                <div class="col-sm-5"></div>
                <div class="col-sm-7"> <?php echo $this->Form->input('report_type', array('type' => 'radio', 'options' => array('H' => '&nbsp;HTML &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 'P' => '&nbsp;PDF&nbsp;&nbsp;'), 'value' => 'H', 'legend' => false, 'div' => false, 'id' => 'fltrBy')); ?></div>                            
            </div>
        </div>

        <div  class="rowht">&nbsp;</div>

        <div class="row" id="divDate">
            <div class="form-group">
                <div class="col-sm-3"></div>
                <label for="Date Between" class="control-label col-sm-2"><?php echo __('lblMonth'); ?></label>            
                <div class="col-sm-2"><?php echo $this->Form->input("month_year", array('id' => 'fromDate', 'legend' => false, 'placeholder' => 'From Date', 'class' => 'date  form-control', 'label' => false, 'value' => date('m-Y'), 'readOnly' => TRUE)); ?></div>
            </div>

        </div>
        <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
        <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
        <div class="row center">
            <div class="form-group">
                <button id="go" class="btn btn-primary" type="submit"> Go </button>    
                <input type="hidden" id="actiontype" name="hdnaction" class="btn btn-primary">
            </div>
        </div>           

    </div>    
</div>
<?php echo $this->form->end(); ?>
<?php if ($pdf_flag) { ?>
    <iframe src="<?php echo $this->webroot ?>monthly_cashbook_report" width="100%" height="500px">
    <?php
}
?>