<?php
foreach ($stampconfig as $stamprec) {
    if (isset($stamprec['functions'])) {
        foreach ($stamprec['functions'] as $funrec) {
            if ($funrec['action'] == $this->request->params['action']) {
                $btnaccept_label = $funrec['btnaccept'];
                $stampflag = $stamprec['stamp_flag'];
                $funflag = $funrec['function_flag'];
            }
        }
    }
}
?>
<table  class="table table-bordered table-condensed " >

    <thead>  

        <tr class="bg-warning">  
            <th><?php echo __('lblpaymenthead'); ?></th>
            <th><?php echo __('lblamttobepaid'); ?></th>
            <th><?php echo __('lblpaidamt'); ?></th>
            <th><?php echo __('lblbalamt'); ?></th>
            <th><?php echo __('lblpaymode'); ?></th> 
            <th><?php echo __('lblpayername'); ?></th>
            <th><?php echo __('lblReferenceNo'); ?>  </th>
            <th><?php echo __('lbldepamt'); ?> </th>

            <th><?php echo __('lblaction'); ?></th>
        </tr>  
    </thead>
    <tbody>
        <tr>
            <td colspan="9" class="">

            </td>
        </tr>
        <?php
//                                    pr($payment);exit;
        $verifyed = 'Y';
        $sdamount = 0;
        $paidamount = 0;
//                                    pr($feedetails);exit;
        //$test = 0;
        $headwise_tally_flag = 1;
        foreach ($feedetails as $fee):
            $sdamount += $fee[0]['totalsd'];
            $amount = 0;

            foreach ($payment as $paydetails):
                $paydetails = $paydetails[0];

                if ($fee[0]['account_head_code'] == $paydetails['account_head_code']) {
                    $amount += $paydetails['pamount'];
                    $paidamount += $paydetails['pamount'];
                    //$test++;
                }
            endforeach;
            $balance = $fee[0]['totalsd'] - $amount;
            if ($balance > 0) {
                $headwise_tally_flag = 0;
            }
            ?>
            <tr class="bg-info">
                <td> <?php echo $fee[0]['fee_item_desc_' . $lang]; ?> </td>
                <td> <?php echo $fee[0]['totalsd']; ?>  </td>
                <td> 
                    <?php
                    echo $amount;
                    ?>
                </td>
                <td class="bg-danger"> 
                    <?php echo $fee[0]['totalsd'] - $amount; ?>
                </td> 
                <?php
                //pr($paymentfields);
                $extrarow = 0;
                foreach ($payment as $paydetails):
                    $paydetails = $paydetails[0];
                    if ($fee[0]['account_head_code'] == $paydetails['account_head_code']) {
                        $extrarow++;
                        if ($extrarow > 1) {
                            echo "</tr><tr><td colspan='4'></td>";
                        }
                        if ($fee[0]['account_head_code'] == $paydetails['account_head_code']) {
                            ?>

                            <td class="bg-success"><?php echo $paydetails['payment_mode_desc_' . $lang]; ?></td>
                            <td class="bg-success"> <?php echo $paydetails['payee_fname_en'] . " " . $paydetails['payee_mname_en'] . " " . $paydetails['payee_lname_en']; ?></td>
                            <td class="bg-success"> <ul class="list-inline no-padding">                                        
                                    <?php
                                    $editflag = 'Y';
                                    $receipt_flag = 'N';
                                    //   pr($paydetails);
                                    //  exit;
                                    foreach ($paymentfields_trn as $key => $singletrnfield) {
                                        if ($singletrnfield['PaymentFields']['payment_mode_id'] == $paydetails['payment_mode_id']) {
                                            echo "<li>" . $singletrnfield['PaymentFields']['field_name_desc_' . $lang] . " : <span class='text-primary'>" . $paydetails[$singletrnfield['PaymentFields']['field_name']] . " </span></li>";
                                            if ($paydetails['verification_flag'] == 'Y') {
                                                $editflag = 'N';
                                            }
                                            if ($paydetails['receipt_flag'] == 'Y') {
                                                $receipt_flag = 'Y';
                                            }
                                        }
                                    }
                                    ?></ul></td>
                            <td class="bg-success"><span class="fa fa-rupee"></span> <?php echo $paydetails['pamount']; ?></td>
                            <td width="15%" class="bg-info">    
                                <div class="btn-group">                                         
                                    <?php
                                    $water_mark = 'NGDRS DEMO';
                                    if ($documents[0][0][$stampflag] == 'N') {
                                        $water_mark = 'Test Recept';
                                    } else if ($paydetails['verification_flag'] == 'Y' and $documents[0][0][$funflag] == 'Y') {
                                        $water_mark = 'Defaced';
                                    }
                                    //  echo $this->Html->link('PDF', array('controller' => 'Reports', 'action' => 'doc_payment_receipt', $paydetails['payment_id'], $water_mark), array('class' => 'btn btn-danger', 'escape' => false));

                                    if ($editflag == 'Y' and $documents[0][0][$funflag] == 'N') {
                                        ?>
                                        <input type="button" class="btn btn-danger" value="Edit"    onclick="edit_payment('<?php echo $paydetails['payment_mode_id']; ?>', '<?php echo $paydetails['payment_id']; ?>');"> 
                                        <a   class="btn btn-danger" href="<?php echo $this->webroot; ?>Registration/payment_remove/<?php echo $paydetails['payment_id']; ?>/<?php echo $this->Session->read("csrftoken"); ?>">Delete</a>
                                    <?php
                                    } else if ($receipt_flag == 'Y' and $documents[0][0][$funflag] == 'Y') {
                                        echo $this->Html->link('PDF', array('controller' => 'Registration', 'action' => 'payment_receipt', $paydetails['payment_id']), array('class' => 'btn btn-danger', 'escape' => false, 'target' => '_blank'));
                                    }
                                    ?>
                                </div>   
                            </td>       
                            <?php
                        }
                    }
                endforeach;
                ?> 
            </tr>
            <tr>
                <td colspan="9" class="">

                </td>
            </tr>
            <?php
        endforeach;
        ?>           

        <tr class="bg-warning">
            <td><?php echo __('lbltotal'); ?></td>
            <td><?php echo $sdamount; ?></td>
            <td> <?php echo $paidamount; ?> </td>
            <td class="bg-danger"><?php echo $sdamount - $paidamount; ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>


    </tbody>
</table> 
<div class="panel-footer"> 

    <?php
    // pr($headwise_tally_flag);
    echo $this->Form->create('payment_verification', array('id' => 'payment_verification', 'class' => 'form-inline'));
    echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken")));
    ?>
<?php if (!empty($regconf_amount_infront_of_sro)) { ?>
        <div class="col-md-12">
            <label for="email"><?php echo __('lblpaid_amount_infront_of_sro'); ?></label> 
            <div class="form-group">

                <?php
                if ($infront_of_sro) {
                    echo " : " . @$infront_of_sro;
                } else {
                    echo $this->Form->input("paid_amount_infront_of_sro", array('type' => 'text', 'id' => 'paid_amount_infront_of_sro', 'class' => 'form-control', 'label' => FALSE, 'div' => FALSE, 'value' => @$infront_of_sro));
                }
                ?></div> 
            <span class="form-error" id="paid_amount_infront_of_sro_error"></span>

        </div>
    <?php } ?>
    <?php
    echo $this->Form->input("status", array('type' => 'hidden', 'label' => FALSE, 'value' => $verifyed));
    echo $this->Form->input("totalpaid", array('type' => 'hidden', 'label' => FALSE, 'value' => $paidamount));
    echo $this->Form->input("tobepaid", array('type' => 'hidden', 'label' => FALSE, 'value' => $sdamount));
    ?>
    <div class="center">


        <?php
        if (!empty($regconf_amount_tally) && $headwise_tally_flag == 1 && $documents[0][0][$funflag] == 'N') {
            echo $this->Form->input(__($btnaccept_label), array('type' => 'submit', 'name' => 'btnaccept', 'label' => FALSE, 'class' => 'btn btn-success smartbtn smartbtn-success'));
        } else if (empty($regconf_amount_tally) && $documents[0][0][$funflag] == 'N' && $sdamount <= $paidamount) {
            echo $this->Form->input(__($btnaccept_label), array('type' => 'submit', 'name' => 'btnaccept', 'label' => FALSE, 'class' => 'btn btn-success smartbtn smartbtn-success'));
        } else {
            echo $this->Form->button(__($btnaccept_label), array('type' => 'button', 'label' => FALSE, 'class' => 'smartbtn smartbtn-disabled btn btn-disabled'));
        }
        ?></div>
    <?php
    echo $this->Form->end();
    ?>

</div>