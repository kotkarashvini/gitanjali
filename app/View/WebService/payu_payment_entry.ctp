 
<div class="col-sm-12">
    <form action="<?php echo $action; ?>" method="post" name="payuForm" >

        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title"><h5>New PayU Payment </h5></div>    
            </div>
            <div class="panel-body">


                <input type="hidden" name="key" value="<?php echo $MERCHANT_KEY ?>" />
                <input type="hidden" name="hash" value="<?php echo $hash ?>"/>
                <input type="hidden" name="txnid" value="<?php echo $txnid ?>" />
                 
                <input type="hidden" name="surl" value="<?php echo $this->Html->url('/WebService/payu_payment_responce', true );?>" />   <!--Please change this parameter value with your success page absolute url like http://mywebsite.com/response.php. -->
                <input type="hidden" name="furl" value="<?php echo $this->Html->url('/WebService/payu_payment_responce', true );?>" />      <!--Please change this parameter value with your failure page absolute url like http://mywebsite.com/response.php. -->
                <input type="hidden" name="curl"  value="<?php echo $this->Html->url('/WebService/payu_payment_responce', true );?>" />
                <input type="hidden" name="productinfo" id="productinfo"  class="form-control"  value="Document Registration Fee Collection"  readonly="readonly"/>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Token Number</label>
                            <div>
                                <input name="udf1" id="udf1" class="form-control" value="<?php echo (empty($posted['udf1'])) ? '' : $posted['udf1']; ?>" />

                            </div>
                            <span class="form-error" id="udf1_error"></span>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>First Name <span class="star"></span> </label>
                            <div>
                                <input name="firstname" id="firstname"  class="form-control" value="<?php echo (empty($posted['firstname'])) ? '' : $posted['firstname']; ?>" />                    

                            </div>
                            <span class="form-error" id="firstname_error"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Last Name <span class="star"></span> </label>
                            <div>
                                <input name="lastname" id="lastname"  class="form-control" value="<?php echo (empty($posted['lastname'])) ? '' : $posted['lastname']; ?>" />                    
                            </div>
                            <span class="form-error" id="lastname_error"></span>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Email <span class="star"></span></label>
                            <div>
                                <input name="email"  id="email"  class="form-control"  id="email" value="<?php echo (empty($posted['email'])) ? '' : $posted['email']; ?>" />
                            </div>
                            <span class="form-error" id="email_error"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Mobile <span class="star"></span></label>
                            <div>
                                <input name="phone" id="phone"  class="form-control"  value="<?php echo (empty($posted['phone'])) ? '' : $posted['phone']; ?>" />
                            </div>
                            <span class="form-error" id="phone_error"></span>
                        </div>
                    </div>                     
                </div>
           <!--    
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Address</label>
                            <div>
                                <input name="address1"  id="address1" class="form-control"  value="<?php echo (empty($posted['address1'])) ? '' : $posted['address1']; ?>" />                   
                            </div>
                            <span class="form-error" id="address1_error"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>City</label>
                            <div>
                                <input name="city"  id="city"  class="form-control"  value="<?php echo (empty($posted['city'])) ? '' : $posted['city']; ?>" />                  
                            </div>
                            <span class="form-error" id="city_error"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">                   
                        <div class="form-group">
                            <label>Zipcode</label>
                            <div>
                                <input name="zipcode" id="zipcode" class="form-control" value="<?php echo (empty($posted['zipcode'])) ? '' : $posted['zipcode']; ?>" />
                            </div>
                            <span class="form-error" id="zipcode_error"></span>
                        </div>
                    </div>
                </div>  
                <div class="row">    
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>State</label>
                            <div>
                                <input name="state" id="state" class="form-control"  value="<?php echo (empty($posted['state'])) ? '' : $posted['state']; ?>" />                
                            </div>
                            <span class="form-error" id="state_error"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Country</label>
                            <div>
                                <input name="country"  id="country" class="form-control" value="<?php echo (empty($posted['country'])) ? '' : $posted['country']; ?>" />
                            </div>
                            <span class="form-error" id="country_error"></span>
                        </div>
                    </div>

                </div>
-->
                    <?php if(isset($mapping)){
                        $i=1;
                        foreach ($mapping as $map){
                            $i++;
                        ?>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><?php echo $map[0]['fee_item_desc_en']?></label>
                            <div>
                                <input name="udf<?php echo $i ;?>" id="udf<?php echo $i ;?>" class="form-control amtentry" value="<?php echo (empty($posted['udf'.$i])) ? '' : $posted['udf'.$i]; ?>" />
                            </div>
                            <span class="form-error" id="udf<?php echo $i ;?>_error"></span>
                        </div>
                    </div>
                </div>
                    <?php }  }?> 
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Total Amount <span class="star"></span></label>
                            <div>
                                <input type="text" name="amount" id="amount" class="form-control"  value="<?php echo (empty($posted['amount'])) ? 0 : $posted['amount'] ?>" readonly="readonly"/>
                            </div>
                        </div>    
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <div class="panel-title">
                 <?php if(!$hash) { ?>
                    <input type="submit" class="btn btn-info" value="Pay" />
            <?php } ?>
                </div>    
            </div>
        </div>



    </form>
</div>
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
                                <a href="<?php echo $this->webroot;?>WebService/payu_payment_entry/<?php  echo $single['transaction_id'];  ?> " class="btn btn-info">Update Status</a>
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
        submitPayuForm();
        $('#translist').dataTable();
        $('.amtentry').on('keyup', function () {
            var totalamt = 0;

                     <?php 
                        $i=1;
                        foreach ($mapping as $map){
                        $i++;
                       ?>
            if ($.isNumeric($('#udf<?php echo $i; ?>').val())) {
                totalamt = totalamt +  parseInt($('#udf<?php echo $i; ?>').val(), 10);
                
            }
                           <?php   }  ?>
            $('#amount').val(totalamt);

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
    <form action="<?php echo $action; ?>" method="post" name="payuForm" >

        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title"><h5>New PayU Payment </h5></div>    
            </div>
            <div class="panel-body">


                <input type="hidden" name="key" value="<?php echo $MERCHANT_KEY ?>" />
                <input type="hidden" name="hash" value="<?php echo $hash ?>"/>
                <input type="hidden" name="txnid" value="<?php echo $txnid ?>" />
                 
                <input type="hidden" name="surl" value="<?php echo $this->Html->url('/WebService/payu_payment_responce', true );?>" />   <!--Please change this parameter value with your success page absolute url like http://mywebsite.com/response.php. -->
                <input type="hidden" name="furl" value="<?php echo $this->Html->url('/WebService/payu_payment_responce', true );?>" />      <!--Please change this parameter value with your failure page absolute url like http://mywebsite.com/response.php. -->
                <input type="hidden" name="curl"  value="<?php echo $this->Html->url('/WebService/payu_payment_responce', true );?>" />
                <input type="hidden" name="productinfo" id="productinfo"  class="form-control"  value="Document Registration Fee Collection"  readonly="readonly"/>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Token Number</label>
                            <div>
                                <input name="udf1" id="udf1" class="form-control" value="<?php echo (empty($posted['udf1'])) ? '' : $posted['udf1']; ?>" />

                            </div>
                            <span class="form-error" id="udf1_error"></span>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>First Name <span class="star"></span> </label>
                            <div>
                                <input name="firstname" id="firstname"  class="form-control" value="<?php echo (empty($posted['firstname'])) ? '' : $posted['firstname']; ?>" />                    

                            </div>
                            <span class="form-error" id="firstname_error"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Last Name <span class="star"></span> </label>
                            <div>
                                <input name="lastname" id="lastname"  class="form-control" value="<?php echo (empty($posted['lastname'])) ? '' : $posted['lastname']; ?>" />                    
                            </div>
                            <span class="form-error" id="lastname_error"></span>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Email <span class="star"></span></label>
                            <div>
                                <input name="email"  id="email"  class="form-control"  id="email" value="<?php echo (empty($posted['email'])) ? '' : $posted['email']; ?>" />
                            </div>
                            <span class="form-error" id="email_error"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Mobile <span class="star"></span></label>
                            <div>
                                <input name="phone" id="phone"  class="form-control"  value="<?php echo (empty($posted['phone'])) ? '' : $posted['phone']; ?>" />
                            </div>
                            <span class="form-error" id="phone_error"></span>
                        </div>
                    </div>                     
                </div>
           <!--    
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Address</label>
                            <div>
                                <input name="address1"  id="address1" class="form-control"  value="<?php echo (empty($posted['address1'])) ? '' : $posted['address1']; ?>" />                   
                            </div>
                            <span class="form-error" id="address1_error"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>City</label>
                            <div>
                                <input name="city"  id="city"  class="form-control"  value="<?php echo (empty($posted['city'])) ? '' : $posted['city']; ?>" />                  
                            </div>
                            <span class="form-error" id="city_error"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">                   
                        <div class="form-group">
                            <label>Zipcode</label>
                            <div>
                                <input name="zipcode" id="zipcode" class="form-control" value="<?php echo (empty($posted['zipcode'])) ? '' : $posted['zipcode']; ?>" />
                            </div>
                            <span class="form-error" id="zipcode_error"></span>
                        </div>
                    </div>
                </div>  
                <div class="row">    
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>State</label>
                            <div>
                                <input name="state" id="state" class="form-control"  value="<?php echo (empty($posted['state'])) ? '' : $posted['state']; ?>" />                
                            </div>
                            <span class="form-error" id="state_error"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Country</label>
                            <div>
                                <input name="country"  id="country" class="form-control" value="<?php echo (empty($posted['country'])) ? '' : $posted['country']; ?>" />
                            </div>
                            <span class="form-error" id="country_error"></span>
                        </div>
                    </div>

                </div>
-->
                    <?php if(isset($mapping)){
                        $i=1;
                        foreach ($mapping as $map){
                            $i++;
                        ?>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><?php echo $map[0]['fee_item_desc_en']?></label>
                            <div>
                                <input name="udf<?php echo $i ;?>" id="udf<?php echo $i ;?>" class="form-control amtentry" value="<?php echo (empty($posted['udf'.$i])) ? '' : $posted['udf'.$i]; ?>" />
                            </div>
                            <span class="form-error" id="udf<?php echo $i ;?>_error"></span>
                        </div>
                    </div>
                </div>
                    <?php }  }?> 
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Total Amount <span class="star"></span></label>
                            <div>
                                <input type="text" name="amount" id="amount" class="form-control"  value="<?php echo (empty($posted['amount'])) ? 0 : $posted['amount'] ?>" readonly="readonly"/>
                            </div>
                        </div>    
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <div class="panel-title">
                 <?php if(!$hash) { ?>
                    <input type="submit" class="btn btn-info" value="Pay" />
            <?php } ?>
                </div>    
            </div>
        </div>



    </form>
</div>
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
                                <a href="<?php echo $this->webroot;?>WebService/payu_payment_entry/<?php  echo $single['transaction_id'];  ?> " class="btn btn-info">Update Status</a>
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
        submitPayuForm();
        $('#translist').dataTable();
        $('.amtentry').on('keyup', function () {
            var totalamt = 0;

                     <?php 
                        $i=1;
                        foreach ($mapping as $map){
                        $i++;
                       ?>
            if ($.isNumeric($('#udf<?php echo $i; ?>').val())) {
                totalamt = totalamt +  parseInt($('#udf<?php echo $i; ?>').val(), 10);
                
            }
                           <?php   }  ?>
            $('#amount').val(totalamt);

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


