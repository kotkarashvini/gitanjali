<div class="container-fluid">

<div class="col-sm-12">
    <form action="<?php echo $action; ?>" method="post" name="payuForm"  autocomplete="off" >

        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title"><h5>New eGRAS  Payment </h5></div>    
            </div>
            <div class="panel-body"> 

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Token Number <span class="star">*</span></label>
                            <div>
                                <input type="text" name="token_no" id="token_no" class="form-control" value="<?php echo (empty($posted['token_no'])) ? '' : $posted['token_no']; ?>" />
                            </div>
                            <span class="form-error" id="token_no_error"></span>
                        </div>
                    </div> 
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Depositor  Name <span class="star">*</span> </label>
                            <div>
                                <input type="text" name="Fullname" id="Fullname" class="form-control" value="<?php echo (empty($posted['Fullname'])) ? '' : $posted['Fullname']; ?>" />
                            </div>
                            <span class="form-error" id="Fullname_error"></span>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>City <span class="star"></span> </label>
                            <div>
                                <input name="Cityname" id="Cityname"  class="form-control" value="<?php echo (empty($posted['Cityname'])) ? '' : $posted['Cityname']; ?>" />                    
                            </div>
                            <span class="form-error" id="Cityname_error"></span>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Address <span class="star"></span> </label>
                            <div>
                                <input name="Address" id="Address"  class="form-control" value="<?php echo (empty($posted['Address'])) ? '' : $posted['Address']; ?>" />                    
                            </div>
                            <span class="form-error" id="Address_error"></span>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Mobile Number <span class="star"></span> </label>
                            <div>
                                <input name="Securityphone" id="Securityphone"  class="form-control" value="<?php echo (empty($posted['Securityphone'])) ? '' : $posted['Securityphone']; ?>" />                    
                            </div>
                            <span class="form-error" id="Securityphone_error"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Registration Fee <span class="star"></span> </label>
                            <div>
                                <input name="registration_fee_amount" id="registration_fee_amount"  class="form-control amtentry" value="<?php echo (empty($posted['registration_fee_amount'])) ? '0' : $posted['registration_fee_amount']; ?>" />                    
                            </div>
                            <span class="form-error" id="registration_fee_amount_error"></span>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Processing Fee<span class="star"></span> </label>
                            <div>
                                <input name="processing_fee_amount" id="processing_fee_amount"  class="form-control amtentry" value="<?php echo (empty($posted['processing_fee_amount'])) ? '0' : $posted['processing_fee_amount']; ?>" />                    
                            </div>
                            <span class="form-error" id="processing_fee_amount_error"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Total Amount <span class="star">*</span></label>
                            <div>
                                <input type="text" name="TotalAmount" id="TotalAmount" class="form-control"  readonly="readonly" value="<?php echo (empty($posted['TotalAmount'])) ? NULL : $posted['TotalAmount'] ?>" />
                            </div>
                            <span class="form-error" id="TotalAmount_error"></span>
                        </div>    
                    </div>
                </div>
            </div>
            <div class="panel-footer">   

                <?php if (!$hash) { ?> 
                    <input type="submit" class="btn btn-primary pull-left" value="Pay Now" /> 
                <?php } else { ?> 
                    <input type="hidden" name="hash" id="hash" class="form-control"  value="<?php echo (empty($posted['hash'])) ? NULL : $posted['hash'] ?>" />
                    <input type="hidden" name="UURL" id="UURL" class="form-control"  value="<?php echo (empty($posted['UURL'])) ? NULL : $posted['UURL'] ?>" />
                    <input type="hidden" name="DTO" id="DTO" class="form-control"  value="<?php echo (empty($posted['DTO'])) ? NULL : $posted['DTO'] ?>" />
                    <input type="hidden" name="STO" id="STO" class="form-control"  value="<?php echo (empty($posted['STO'])) ? NULL : $posted['STO'] ?>" />
                    <input type="hidden" name="DDO" id="DDO" class="form-control"  value="<?php echo (empty($posted['DDO'])) ? NULL : $posted['DDO'] ?>" />
                    <input type="hidden" name="Deptcode" id="Deptcode" class="form-control"  value="<?php echo (empty($posted['Deptcode'])) ? NULL : $posted['Deptcode'] ?>" />
                    <input type="hidden" name="UserID" id="UserID" class="form-control"  value="<?php echo (empty($posted['UserID'])) ? NULL : $posted['UserID'] ?>" />
                    <input type="hidden" name="Applicationnumber" id="Applicationnumber" class="form-control"  value="<?php echo (empty($posted['Applicationnumber'])) ? NULL : $posted['Applicationnumber'] ?>" />
                    <input type="hidden" name="Officename" id="Officename" class="form-control"  value="<?php echo (empty($posted['Officename'])) ? NULL : $posted['Officename'] ?>" />
                    <input type="hidden" name="ChallanYear" id="ChallanYear" class="form-control"  value="<?php echo (empty($posted['ChallanYear'])) ? NULL : $posted['ChallanYear'] ?>" />
                    <input type="hidden" name="Bank" id="Bank" class="form-control"  value="<?php echo (empty($posted['Bank'])) ? NULL : $posted['Bank'] ?>" />
                    <input type="hidden" name="Remarks" id="Remarks" class="form-control"  value="<?php echo (empty($posted['Remarks'])) ? NULL : $posted['Remarks'] ?>" />
                    <input type="hidden" name="ptype" id="ptype" class="form-control"  value="<?php echo (empty($posted['ptype'])) ? NULL : $posted['ptype'] ?>" />
                    <input type="hidden" name="SCHEMECOUNT" id="SCHEMECOUNT" class="form-control"  value="<?php echo (empty($posted['SCHEMECOUNT'])) ? NULL : $posted['SCHEMECOUNT'] ?>" />
                    <?php for ($i = 1; $i <= $posted['SCHEMECOUNT']; $i++) { ?>
                        <input type="hidden" name="SCHEMENAME<?php echo $i; ?>" id="SCHEMENAME<?php echo $i; ?>" class="form-control"  value="<?php echo (empty($posted['SCHEMENAME' . $i])) ? NULL : $posted['SCHEMENAME' . $i] ?>" />

                        <input type="hidden" name="FEEAMOUNT<?php echo $i; ?>" id="FEEAMOUNT<?php echo $i; ?>" class="form-control"  value="<?php echo (empty($posted['FEEAMOUNT' . $i])) ? NULL : $posted['FEEAMOUNT' . $i] ?>" />
                    <?php } ?>

                <?php } ?>     
                <br>  <br>  
            </div>
        </div> 
    </form>
</div> 

<script>
    $(document).ready(function () {
        $('#translist').dataTable();
        submitPayuForm();
        $('.amtentry').on('keyup', function () {
            var totalamt = 0;
            if ($.isNumeric($('#registration_fee_amount').val())) {
                totalamt = totalamt + parseInt($('#registration_fee_amount').val(), 10);
            }
            if ($.isNumeric($('#processing_fee_amount').val())) {
                totalamt = totalamt + parseInt($('#processing_fee_amount').val(), 10);
            }
            $('#TotalAmount').val(totalamt);

        });
    });
    var hash = '<?php echo $hash ?>';

    function submitPayuForm() {

        if (hash == '') {
            return;
        }
        var payuForm = document.forms.payuForm;
        payuForm.submit();
    }




</script>


    <div class="col-sm-12">



        <div class="panel panel-warning">
            <div class="panel-heading">
                <div class="panel-title"><h5>eGras Payment Transactions  </h5></div>    
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
                            <th>GRN </th>
                            <th>CIN </th>
                            <th>Status</th>
                            <th>Action</th>

                        </tr>  
                    </thead>
                    <tbody>
                        <?php
                        if (isset($result)) {
                            foreach ($result as $single) {
                                $single = $single['BankPayment'];
                                ?>
                                <tr>
                                    <td><?php echo $single['payee_fname_en'] . " " . $single['payee_lname_en']; ?></td>
                                    <td><?php echo $single['token_no']; ?></td>
                                    <td><?php echo $single['pdate']; ?></td>
                                    <td><?php echo $single['pamount']; ?></td>
                                    <td><?php echo $single['transaction_id']; ?></td> 
                                    <td><?php echo $single['gateway_trans_id']; ?></td> 
                                    <td><?php echo $single['bank_trn_ref_number']; ?></td> 
                                    <td><?php echo $single['payment_status']; ?></td>
                                    <td>
                                        <?php //if ($single['payment_status'] == 'CREATED') { ?>
                                            <a href="<?php echo $this->webroot; ?>TRWebService/grass_payment/<?php echo $single['transaction_id']; ?> " class="btn btn-info">Update Status</a>
                                        <?php //} ?>
                                    </td>
                                </tr> 
                            <?php }
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>   



    </div>

</div>