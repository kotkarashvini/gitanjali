 

         
<div class="col-sm-12">

    <div class="row">

        <div class="panel panel-warning">
            <div class="panel-heading">
                <div class="panel-title"><h5>PayU Payment Transactions  </h5></div>    
            </div>
            <div class="panel-body" >
                <table class="table" id="translist">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Token</th>
                            <th>Payment Date</th>
                            <th>Amount</th>
                            <th>Transaction ID </th>
                            <th>Status</th>
                            <th>Action</th>

                        </tr>  
                    </thead>
                    <tbody>
                <?php if(isset($result)){
                   foreach ($result as $single){ 
                       $single=$single['BankPayment'];
                    ?>
                        <tr>
                            <td><?php echo $single['payee_fname_en']." ".$single['payee_lname_en']; ?></td>
                            <td><?php echo $single['token_no']; ?></td>
                            <td><?php echo $single['pdate']; ?></td>
                            <td><?php echo $single['pamount']; ?></td>
                            <td><?php echo $single['transaction_id']; ?></td> 
                            <td><?php echo $single['payment_status']; ?></td>
                            <td>
                                <?php if($single['payment_status']=='CREATED'){?>
                                <a href="<?php echo $this->webroot;?>Citizenentry/payu_payment_entry/<?php  echo $single['transaction_id'];  ?> " class="btn btn-info">Update Status</a>
                                <?php } ?>
                            </td>
                        </tr> 
                <?php }} ?>
                    </tbody>
                </table>
            </div>
        </div>   



    </div>
</div>
<script>
    $(document).ready(function () {
      
        $('#translist').dataTable();
       
    });
 
   
</script>


