<script>
    $(document).ready(function () {


        $('#from').datepicker({
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            calendarWeeks: true,
            autoclose: true,
            todayHighlight: true
        });

        $('#to').datepicker({
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            calendarWeeks: true,
            autoclose: true,
            todayHighlight: true
        });


        $('#tableparty').dataTable({
            "bPaginate": false,
            "ordering": false
        });

    });

    function pay_details(token_no, payment_status) {
//        alert(payment_status);
        $.post('<?php echo $this->webroot; ?>Reports/get_all_payment_status', {token_no: token_no, payment_status: payment_status}, function (data)
        {

            $('#statuschk').html(data);
            $('#paymentstatus').modal('show');
        });
    }

    function notpayment() {
        alert("Payment not fount for this token...!!!!");
        return false;
    }

</script>
<style>
    .table-responsive
    {
        overflow-y:auto;
        height:300px;
    }
</style>
<?php echo $this->Form->create('payment_status_details', array('id' => 'payment_status_details')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title" style="font-weight: bolder">Payment Details Search</h3></center>
            </div>

            <div class="box-body"><BR>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-1"></div>
                        <label for="district_id" class="col-sm-2 control-label"><?php echo __('Token No.:'); ?><span style="color: #ff0000"></span></label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('token_no', array('label' => false, 'id' => 'token_no', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span  id="token_no_error" class="form-error"><?php //echo $errarr['token_no_error'];            ?></span>
                        </div>
                        <label for="grn_no" class="col-sm-2 control-label"><?php echo __('GRN No.'); ?><span style="color: #ff0000"></span></label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('grn_no', array('label' => false, 'id' => 'grn_no', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span  id="grn_no_error" class="form-error"><?php //echo $errarr['grn_no_error'];            ?></span>

                        </div>
                        <div class="form-group">
                            <div class="col-sm-1"></div>
                            <button id="go" class="btn btn-primary" type="submit"> Search </button>
                        </div>
                    </div>
                </div><br>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-body">
                <?php
                if (!empty($paygrid)) {
                    //pr($paygrid);exit;
                    ?>
                    <div id="selectdocument" class="table-responsive">
                        <table id="tableparty" class="table table-striped table-bordered table-hover table-responsive">  
                            <thead class="center">  
                                <tr>
                                    <th class="width10 center"><?php echo __('Token Number'); ?></th>
                                    <th class="width10 center"><?php echo __('Payee Name'); ?></th>
                                    <th class="width10 center"><?php echo __('CIN No.'); ?></th>
                                    <th class="width10 center"><?php echo __('GRN No.'); ?></th>
                                    <th class="width10 center"><?php echo __('Payment Date'); ?></th>
                                    <th class="width10 center"><?php echo __('Office'); ?></th>
                                    <th class="width10 center"><?php echo __('Amount'); ?></th>
                                    <th class="width10 center"><?php echo __('Transaction ID'); ?></th>
                                    <th class="width10 center"><?php echo __('Payment Status'); ?></th>
                                    <th class="width10 center"><?php echo __('Action'); ?></th>
                                </tr>  
                            </thead>
                            <tbody>
                                <?php foreach ($paygrid as $rec): ?>
                                    <tr>
                                        <td ><?php echo $rec[0]['token_no']; ?></td>
                                        <td ><?php echo $rec[0]['payee_fname_en']; ?></td>
                                        <td ><?php echo $rec[0]['cin_no']; ?></td>
                                        <td ><?php echo $rec[0]['grn_no']; ?></td>
                                        <td ><?php
                                            if ($rec[0]['pdate'] == null) {
                                                echo '';
                                            } else {
                                                echo date('d-M-Y', strtotime($rec[0]['pdate']));
                                            }
                                            ?></td>
                                        <td ><?php echo $rec[0]['office_name_en']; ?></td>
                                        <td ><?php echo $rec[0]['pamount']; ?></td>
                                        <td ><?php echo $rec[0]['transaction_id']; ?></td>
                                        <td ><?php echo $rec[0]['payment_status']; ?></td>
                                        <td >
                                            <button type="button" data-target="#paymentstatus" class="btn btn-info"  onclick="javascript: return pay_details('<?php echo $rec[0]['token_no']; ?>', '<?php echo $rec[0]['payment_status']; ?>');">View</button>
                                        </td>

    <?php endforeach; ?>
                            </tbody>
                        </table> 
<?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="paymentstatus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">Payment Status</h4>
            </div>
            <div class="modal-body" id="statuschk">
                <p>Data Loading...!!!!</p>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12">

                        <div class="pull-right">
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
                        </div></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type='text/javascript'>
    jQuery(function ($) {
        'use strict';
        $('#printpartydetails').on('click', function () {
            $.print("#rptdetails");
        });
    });
</script>

<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>