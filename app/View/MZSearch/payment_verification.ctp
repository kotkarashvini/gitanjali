<script>
    $(document).ready(function () {
        
                        $('#paymentmode_selection1').change(function (e) {
            var mode = $('#paymentmode_selection1').val();
            var reff_no = $('#reff_no').val();
            if (mode === '')
            {
                alert("Please Select Payment Mode");
                e.preventDefault();
                retun;
            } else {
                $.post('<?php echo $this->webroot; ?>MZSearch/get_payment_details', {mode: mode, csrftoken:<?php echo $this->Session->read('csrftoken'); ?>}, function (data)
                {
                    $(document).unbind('_pay_chart');
                    $(document).unbind('_pay_event');
                    $('#paydetails1').html('');
                    $('#paydetails1').html(data);

                    $(document).trigger('_page_ready');
                    $(document).trigger('_pay_chart');
                    $(document).trigger('_pay_event');
                });

            }
        });


    });
</script>
<?php echo $this->Form->create('payment_verification', array('id' => 'payment_verification')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title" style="font-weight: bolder">Application for Search</h3></center>
            </div>
            <div class="box-body"><BR>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="col-md-2"></div>
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
                    </div>        
                </div><br>
            </div>
        </div>        
    </div>
</div>


<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>