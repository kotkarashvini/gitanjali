<div class="col-md-12">
    <div class="col-md-6">
        <div class="col-md-10" id="paymentmode_selection_div">
            <label for="" class="control-label"><?php echo __('lblselectpaymode'); ?><span style="color: #ff0000">*</span></label>    
            <?php echo $this->Form->input('paymentmode_id', array('label' => false, 'id' => 'paymentmode_selection', 'class' => 'form-control input-sm', 'type' => 'select', 'options' => array('empty' => '--Select--', $payment_mode_counter))) ?>                         
        </div> 
        <div class="col-md-2">
            <a  href="<?php echo $this->webroot; ?>helpfiles/Payment/new_payment_entry_<?php echo $lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
        </div> 
        <div class="col-md-10" id="paydetails"> 

        </div> 
    </div>
    <div class="col-md-6">
        <table  class="table table-bordered table-condensed " >

            <thead>  

                <tr class="bg-warning">  
                    <th><?php echo __('lblpaymenthead'); ?></th>
                    <th><?php echo __('lblamttobepaid'); ?></th>
                    <th><?php echo __('lblpaidamt'); ?></th>
                    <th><?php echo __('lblbalamt'); ?></th>
                    <th><?php //echo __('lblpaymode');            ?></th> 
                </tr>  
            </thead>
            <tbody>


                <?php
//                                    pr($payment);exit;
                $verifyed = 'Y';
                $sdamount = 0;
                $paidamount = 0;
//                                    pr($feedetails);exit;
                //$test = 0;
                $preference = 0;
                foreach ($feedetails as $fee):
                    $sdamount += $fee[0]['totalsd'];
                    $amount = 0;
                    if (is_numeric($fee[0]['fee_preference'])) {
                        $preference = $fee[0]['fee_preference'];
                    } else {
                        $preference++;
                    }

                    foreach ($payment as $paydetails):
                        $paydetails = $paydetails[0];

                        if ($fee[0]['account_head_code'] == $paydetails['account_head_code']) {
                            $amount += $paydetails['pamount'];
                            $paidamount += $paydetails['pamount'];
                        }
                    endforeach;
                    ?>
                    <tr class="bg-info">
                        <td> <?php echo $fee[0]['fee_item_desc_' . $lang]; ?> </td>
                        <td id="sdtotal<?php echo $preference; ?>"> <?php echo $fee[0]['totalsd']; ?>  </td>
                        <td id="paidsd<?php echo $preference; ?>"> 
                            <?php
                            echo $amount;
                            ?>
                        </td>
                        <td class="bg-danger"  id="balance<?php echo $preference ?>"> 
                            <?php
                            echo $fee[0]['totalsd'] - $amount;
                            ?>
                        </td> 

                        <td  id="currentpay<?php echo $preference; ?>"></td>     

                    </tr>

                    <?php
                endforeach;
                ?>           

                <tr class="bg-warning">
                    <td><?php echo __('lbltotal'); ?></td>
                    <td><?php echo $sdamount; ?></td>
                    <td> <?php echo $paidamount; ?> </td>
                    <td class="bg-danger" id="totalbalance"><?php echo $sdamount - $paidamount; ?></td>
                    <td></td>

                </tr>

            </tbody>
        </table> 
    </div>
</div>
