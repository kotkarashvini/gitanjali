<script type='text/javascript'>
    jQuery(function ($) {
        'use strict';
        $('#printbtn').on('click', function () {
            $.print("#printdiv");
        });

    });
</script>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel panel-heading">
                Payment Gate Way Response
            </div>
            <div class="panel panel-body" id="printdiv">
            <?php if(isset($rdata)){   ?>
                <?php if($rdata['status']=='success'){ ?>
                <div class="alert alert-success">
                    <strong>Transaction Success!</strong> Please Note Your Transaction Id.
                </div>
                <?php }else{ ?>
                <div class="alert alert-danger">
                    <strong>Transaction Failed!</strong> <small><?php echo @$rdata['error_Message']; ?></small> 
                </div>
                <?php } ?>
                <br>
                <table class="table table-bordred">   

                    <thead>
                        <tr> <td>Name</td><td> <?php echo @$rdata['firstname']; ?></td> </tr>
                    </thead>
                    <tbody>
                        <tr>   <td>Token No</td><td><?php echo @$rdata['udf1']; ?></td> </tr>
                        <tr>   <td>Amount</td><td><?php echo @$rdata['net_amount_debit']; ?></td> </tr>
                        <tr>   <td>Transaction ID</td><td><?php echo @$rdata['txnid']; ?></td> </tr>
                        <tr>   <td>Bank Reference Number</td><td><?php echo @$rdata['bank_ref_num']; ?></td> </tr>
                        <tr>   <td>Time</td><td><?php echo @$rdata['addedon']; ?></td>                </tr>
                    </tbody>
                    </thead>
                </table>
 

            <?php } ?>



            </div>
            <div class="panel-footer center">

                <button class="btn btn-info" id="printbtn">Print</button>
            </div>
        </div>

    </div>
</div>