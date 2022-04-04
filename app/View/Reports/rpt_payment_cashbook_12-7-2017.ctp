<script>

    $(function () {
        var host = '<?php echo $this->webroot; ?>';
        $('.date').datepicker({
            autoclose: true,
            format: "dd-mm-yyyy"
        });
        $('#go').click(function () {
            $('form').submit();
        });
    });
</script>
<?php echo $this->Form->create('rptPaymentRecord', array('id' => 'frmRpt')); ?>
<div class = "box box-primary">
    <div class = "box-header with-border">
        <center><h3 class = "box-title headbolder"><?php echo __('lbl_payment_cashbook'); ?> </h3></center>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="form-group">
                <div class="col-sm-3"></div>
                <label for="Filter Record By" class="control-label col-sm-2"><?php echo __('lblgetrebordby'); ?></label>            
                <div class="col-sm-7"> <?php echo $this->Form->input('filterby', array('type' => 'radio', 'options' => array('B' => '&nbsp;Both (Online,Counter)&nbsp;&nbsp;&nbsp;&nbsp;', 'O' => '&nbsp;Online&nbsp;&nbsp;&nbsp;', 'C' => '&nbsp;Counter&nbsp;&nbsp;&nbsp;'), 'value' => 'B', 'legend' => false, 'div' => false, 'id' => 'fltrBy')); ?></div>                            
            </div>
        </div>

        <div  class="rowht">&nbsp;</div>

        <div class="row" id="divDate">
            <div class="form-group">
                <div class="col-sm-3"></div>
                <label for="Date Between" class="control-label col-sm-2"><?php echo __('lbldate'); ?></label>            
                <div class="col-sm-2"><?php echo $this->Form->input("fromDate", array('id' => 'fromDate', 'legend' => false, 'placeholder' => 'From Date', 'class' => 'date  form-control', 'label' => false, 'value' => date('d-m-Y'), 'readOnly' => TRUE)); ?></div>
                <div class="col-sm-2"><?php echo $this->Form->input("toDate", array('id' => 'toDate', 'legend' => false, 'placeholder' => 'To Date', 'class' => 'date form-control', 'label' => false, 'value' => date('d-m-Y'), 'readOnly' => TRUE)); ?></div>
            </div>

        </div>
        <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
        <div class="row center">
            <div class="form-group">
                <div class="col-sm-1"></div>
                <button id="go" class="btn btn-primary" type="submit"> Go </button>
                <input type="hidden" id="actiontype" name="hdnaction" class="btn btn-primary">
            </div>
        </div>           

    </div>    
</div>
<?php echo $this->form->end(); ?>
<?php if ($pdf_flag) { ?>
    <iframe src="<?php echo $this->webroot ?>payment_cashbook_pdf" width="100%" height="500px">
    <?php
}
?>