<div class="col-md-12" id="rptreceipt">

</div>
<div class="col-md-12 center">
    <div class="btn-group">
        <a type="button" href="<?php echo $this->webroot; ?>Reports/receipt_report/<?php echo base64_encode($documents[0][0]['token_no']); ?>/D" class="btn btn-warning"> <span class="fa fa-download"></span> <?php echo __('lbldownload'); ?></a>
        <button type="button" class="btn btn-warning" id="receiptprint"> <span class="fa fa-print"></span> <?php echo __('lblprint'); ?></button>
    </div>
</div>