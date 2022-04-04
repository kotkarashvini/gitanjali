 
<div class="col-sm-12">
    <form action="" method="post" name="payuForm" >

        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title"><h5>New GRAS Challan Payment </h5></div>    
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Token Number</label>
                            <div>
                                <input name="token_no" id="token_no" class="form-control"  />

                            </div>
                            <span class="form-error" id="token_no_error"></span>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>First Name <span class="star"></span> </label>
                            <div>
                                <input name="payee_fname_en" id="payee_fname_en"  class="form-control"  />                    

                            </div>
                            <span class="form-error" id="payee_fname_en_error"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Last Name <span class="star"></span> </label>
                            <div>
                                <input name="payee_lname_en" id="payee_lname_en"  class="form-control"  />                    
                            </div>
                            <span class="form-error" id="payee_lname_en_error"></span>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Email <span class="star"></span></label>
                            <div>
                                <input name="email_id"  id="email_id"  class="form-control"    />
                            </div>
                            <span class="form-error" id="email_id_error"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Mobile <span class="star"></span></label>
                            <div>
                                <input name="mobile" id="mobile"  class="form-control"   />
                            </div>
                            <span class="form-error" id="mobile_error"></span>
                        </div>
                    </div>                     
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Address</label>
                            <div>
                                <input name="address"  id="address" class="form-control"   />                   
                            </div>
                            <span class="form-error" id="address_error"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>City</label>
                            <div>
                                <input name="city"  id="city"  class="form-control"   />                  
                            </div>
                            <span class="form-error" id="city_error"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">                   
                        <div class="form-group">
                            <label>Pin code</label>
                            <div>
                                <input name="pincode" id="pincode" class="form-control"  />
                            </div>
                            <span class="form-error" id="pincode_error"></span>
                        </div>
                    </div>
                </div>  
                <!--                     <div class="row">    
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
                    <?php 
//pr($mapping);
if(isset($mapping)){
                        $i=0;
                        foreach ($mapping as $map){
                            $i++;
                        ?>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><?php echo $map[0]['fee_item_desc_en']?></label>
                            <div>
                                <input name="map<?php echo $i ;?>" id="map<?php echo $i ;?>" class="form-control amtentry"  />
                            </div>
                            <span class="form-error" id="map<?php echo $i ;?>_error"></span>
                        </div>
                    </div>
                </div>
                    <?php }  }?> 
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Total Amount <span class="star"></span></label>
                            <div>
                                <input type="text" name="amount" id="amount" class="form-control"   readonly="readonly"/>
                            </div>
                        </div>    
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <div class="panel-title">

                    <input type="submit" class="btn btn-info" value="Create Challan" />

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
<th>Challan No</th>
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
 <td><?php echo $single['gateway_trans_id']; ?></td>
                            <td>
                               <?php 
if(!empty($single['gateway_trans_id'])){
 echo $this->Html->link(
                                __('lbldownload'), array(
                            'disabled' => TRUE,
                            'controller' => 'Registration', // controller name
                            'action' => 'any_download_file', //action name
                            'full_base' => true, 'webservice_files',$single['transaction_id'] . '_challan.pdf'), array('class' => 'btn btn-warning', 'target' => '_blank')
                        );
}



?>
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
            if ($.isNumeric($('#map<?php echo $i; ?>').val())) {
                totalamt = totalamt + parseInt($('#map<?php echo $i; ?>').val(), 10);

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


