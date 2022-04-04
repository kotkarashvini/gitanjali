
<script type='text/javascript'>
    jQuery(function ($) {
        'use strict';
        $('#printbtn').on('click', function () {
            $.print("#printdiv");
        });

    });
</script>

<?php //pr($result);exit; ?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel panel-heading">
                <div class="panel-title"><h1>eGras   Response</h1></div>
            </div>
            <div class="panel panel-body" id="printdiv">

                <?php if ($result['BankPayment']['payment_status'] == 'SUCCESS') { ?>
                    <div class="alert alert-success">
                        <strong>Transaction Success!</strong> Please Note Your Transaction Id.
                    </div>
                <?php } else { ?>
                    <div class="alert alert-danger">
                        <strong>Transaction Unsuccessful!</strong> Please Note Your Transaction Id.
                    </div>
                <?php } ?>


                <table class="table table-bordred">   

                    <thead>
                        <tr> <td>Name</td><td> <?php echo $result['BankPayment']['payee_fname_en']; ?></td> </tr>
                    </thead>
                    <tbody>
                        <tr>   <td>Token No</td><td><?php echo $result['BankPayment']['token_no']; ?></td> </tr>
                        <tr>   <td>Amount</td><td><?php echo $result['BankPayment']['pamount']; ?></td> </tr>
                        <tr>   <td>NGDRS Receipt  ID</td><td><?php echo $result['BankPayment']['transaction_id']; ?></td> </tr>
                        <tr>   <td>Payment Status</td><td><?php echo $result['BankPayment']['payment_status']; ?></td> </tr>
                        <tr>   <td>GRN</td><td><?php echo $result['BankPayment']['gateway_trans_id']; ?></td> </tr> 
                        <tr>   <td>CIN</td><td><?php echo $result['BankPayment']['bank_trn_ref_number']; ?></td> </tr> 
                    </tbody> 
                </table>    
            </div>
            <div class="panel-footer center">

                <button class="btn btn-info" id="printbtn">Print</button>
            </div>
        </div>

    </div>
</div>