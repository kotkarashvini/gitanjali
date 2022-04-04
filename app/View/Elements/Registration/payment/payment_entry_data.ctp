<table id="" class="table table-striped table-bordered table-hover" >
    <thead >  
        <tr>  
            <th class="center"><?php echo __('lblpaymode'); ?></th>
            <th class="center"><?php echo __('lblpaymenthead'); ?></th>
            <th class="center"><?php echo __('lblpayername'); ?></th>
            <th class="center"><?php echo __('lbldepamt'); ?> </th>
            <th class="center"><?php echo __('lblotherdetails'); ?> </th>

    </thead>
    <tbody>
                            <?php
                            foreach ($citizen_payment_entry as $paydetails) {
                                $paydetails = $paydetails[0];
                                ?>
        <tr>
            <td class="tblbigdata"><?php echo $paydetails['payment_mode_desc_en']; ?></td>
            <td class="tblbigdata"><?php
                                        if (isset($accounthead[$paydetails['fee_item_id']])) {
                                            echo $accounthead[$paydetails['fee_item_id']];
                                        };
                                        ?></td>                                
            <td class="tblbigdata"><?php echo $paydetails['payee_fname_en'] . " " . $paydetails['payee_mname_en'] . " " . $paydetails['payee_lname_en']; ?></td>
            <td class="tblbigdata"><?php echo $paydetails['pamount']; ?></td>
            <td class="tblbigdata"><?php
                                        foreach ($paymentfields_trn as $tranfield) {
                                            if ($tranfield['PaymentFields']['is_transaction_flag'] == 'Y' and $tranfield['PaymentFields']['payment_mode_id'] == $paydetails['payment_mode_id']) {
                                                echo $tranfield['PaymentFields']['field_name_desc_en'] . " : " . $paydetails[$tranfield['PaymentFields']['field_name']] . "<br>";
                                            }
                                        }
                                        ?></td>


                                <?php }
                                ?>

    </tbody>
</table> 
