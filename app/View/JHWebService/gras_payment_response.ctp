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
            <?php
//pr($response);
 if(isset($response)){   ?>
                <?php if(strtoupper(trim($response['STATUS']))=='SUCCESS'){ ?>
                <div class="alert alert-success">
                    <strong>Transaction Success!</strong> Please Note Your Transaction Id.
                </div>
                <?php }else{ ?>
                <div class="alert alert-danger">
                    <strong>Transaction Failed!</strong> <small><?php echo @$response['PAYMENTSTATUSMESSAGE']; ?></small> 
                </div>
                <?php } ?>
                <br>
                <table class="table table-bordred">   

                    <thead>
                        <tr> <td>Name</td><td> <?php echo @$response['DEPOSITERNAME']; ?></td> </tr>
                    </thead>
                    <tbody>
                        <tr>   <td>Token No</td><td><?php echo @$response['DEPOSITERID']; ?></td> </tr>
                        <tr>   <td>Amount</td><td><?php echo @$response['AMOUNT']; ?></td> </tr>
                        <tr>   <td>Transaction ID</td><td><?php echo @$response['DEPTTRANID']; ?></td> </tr>
                        <tr>   <td>GRN</td><td><?php echo @$response['GRN']; ?></td> </tr>
                        <tr>   <td>CIN</td><td><?php echo @$response['CIN']; ?></td> </tr>
                        <tr>   <td>Time</td><td><?php echo @$response['TXN_DATE']; ?></td>                  </tr>
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