<?php
if (isset($arr_new) && !empty($arr_new)) {
    ?>

    <table class="table table-bordered" id="ratetbl">
        <thead>
            <tr>
                <th><?php echo 'Srno'; ?></th>
                <th><?php echo 'Holding Number'; ?></th>
                <th><?php echo 'Ward Number'; ?></th>
                <th><?php echo 'Mouja Name'; ?></th> 
                <th><?php echo 'Entry Type'; ?></th>
                <th><?php echo 'Plot No'; ?></th>           
                <th><?php echo 'Khata No'; ?></th>              
                <th><?php echo 'Total Area'; ?></th>

            </tr>
        </thead>
        <tbody>
    <?php
    $i = 1;
    ?>

        <td>
            <?php echo $i; ?>  
        </td>


        <td>
            <?php if (isset($arr_new['HoldingNo'])) {
                echo $arr_new['HoldingNo'];
            } ?>  
        </td>
       <td>
            <?php if (isset($arr_new['WardNo'])) {
                echo $arr_new['WardNo'];
            } ?>  
        </td>
        <td>
            <?php if (isset($arr_new['Mouja'])) {
                echo $arr_new['Mouja'];
            } ?>  
        </td>
       <td>
            <?php if (isset($arr_new['EntryType'])) {
                echo $arr_new['EntryType'];
            } ?>  
        </td>		
        <td>
            <?php if (isset($arr_new['plot_no'])) {
                echo $arr_new['plot_no'];
            } ?>  
        </td>
        <td>
            <?php if (isset($arr_new['khata_no'])) {
                echo $arr_new['khata_no'];
            } ?>  
        </td>
        <td>
            <?php if (isset($arr_new['TotalArea'])) {
                echo $arr_new['TotalArea'];
            } ?>  
        </td>
     




    </tr>



    </tbody>
    </table>

    <h4>Owner Details</h4>
    
   

    
    <table class="table table-bordered" id="ratetbl">
        <thead>
            <tr>
                <th><?php echo 'Srno'; ?></th>
                <th><?php echo 'Owner Name'; ?></th>
                <th><?php echo 'Owner Mobile Number'; ?></th>
                <th><?php echo 'Owner Adhar Card'; ?></th> 
              

            </tr>
        </thead>
        <tbody>
    <?php
    $i = 1;
    ?>
             <?php if(isset($arr_new['OwnerDetails']['OwnerDetail']) && !empty($arr_new['OwnerDetails']['OwnerDetail'])){ 
        
        $owner=$arr_new['OwnerDetails']['OwnerDetail'];
        ?>
            <tr>
             <td>
            <?php echo $i; ?>  
        </td>
        <td>
            <?php echo $owner['OwnerName']; ?>  
        </td>
        <td>
            <?php echo $owner['OwnerMobileNo']; ?>  
        </td>
         <td>
            <?php echo $owner['OwnerAadharNo']; ?>  
        </td>
    </tr>
             <?php } ?>
          </tbody>
    </table>
            
    
    <h4>SAF Transaction Details </h4>
    
   

    
    <table class="table table-bordered" id="ratetbl">
        <thead>
            <tr>
                <th><?php echo 'Srno'; ?></th>
                <th><?php echo 'Transaction Date'; ?></th>
                <th><?php echo 'Transaction Amount'; ?></th>
                <th><?php echo 'Transaction Number'; ?></th> 
                  <th><?php echo 'Payment From'; ?></th> 
                    <th><?php echo 'Payment To'; ?></th> 
              

            </tr>
        </thead>
        <tbody>
    <?php
    $i = 1;
    ?>
    <?php if(isset($arr_new['SAFTransactionDetails']['TransactionDetail']) && !empty($arr_new['SAFTransactionDetails']['TransactionDetail'])){ 
        
        $trans=$arr_new['SAFTransactionDetails']['TransactionDetail'];
        ?>
            <tr>
             <td>
            <?php echo $i; ?>  
        </td>
         <td>
            <?php echo $trans['TransactionDate']; ?>  
        </td>
         <td>
            <?php echo $trans['TransactionAmount']; ?> 
        </td>
         <td>
             <?php echo $trans['TransactionNo']; ?> 
        </td>
         <td>
            <?php echo $trans['PaymentFrom']; ?> 
        </td> <td>
             <?php echo $trans['PaymentUpto']; ?> 
        </td>
        
        
         </tr>
             <?php } ?>
          </tbody>
    </table>

    <?php
    }?>