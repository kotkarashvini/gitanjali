 
<div class="col-sm-12">
    <?php
    echo $this->element("Registration/main_menu");
    if ($this->Session->read('sroparty') == 'N') {
        echo $this->element("Citizenentry/property_menu");
    }
    ?>
    <form action="" method="post" name="payuForm" >

        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title"><h5>Create New E-Challan  </h5></div>    
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Token Number <span class="star">*</span></label>
                            <div>
                                <input name="token_no" id="token_no" class="form-control" value="<?php echo $this->Session->read("Selectedtoken"); ?>" readonly="readonly"/>

                            </div>
                            <span class="form-error" id="token_no_error"></span>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>First Name <span class="star">*</span> </label>
                            <div>
                                <input name="payee_fname_en" id="payee_fname_en"  class="form-control"  />                    

                            </div>
                            <span class="form-error" id="payee_fname_en_error"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Last Name <span class="star">*</span> </label>
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
                            <label>Email <span class="star">*</span></label>
                            <div>
                                <input name="email_id"  id="email_id"  class="form-control"    />
                            </div>
                            <span class="form-error" id="email_id_error"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Mobile <span class="star">*</span></label>
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
                            <label>Address <span class="star">*</span></label>
                            <div>
                                <input name="address"  id="address" class="form-control"   />                   
                            </div>
                            <span class="form-error" id="address_error"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Taluka Name <span class="star">*</span></label>
                            <div>
                                <input name="city"  id="city"  class="form-control"   />                  
                            </div>
                            <span class="form-error" id="city_error"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">                   
                        <div class="form-group">
                            <label>Pin code <span class="star">*</span></label>
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
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Fee Type <span class="star">*</span></label>
                            <div>
                                <select class="form-control" id="feetype" name="feetype">
                                    <option value="">-- Select --</option>
                                    <option value="1">Registration Fee and Processing Fee</option>
                                    <option value="2">Mutation Fee</option>

                                </select>
                            </div>
                            <span class="form-error" id="feetype_error"></span>
                        </div>    
                    </div>
 
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Rural Urban  Flag <span class="star">*</span></label>
                            <div>
                                <select class="form-control" id="feeflag" name="feeflag">
                                    <option value="">-- Select --</option>                                   
                                </select>
                            </div>
                            <span class="form-error" id="feeflag_error"></span>
                        </div>    
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Total Amount <span class="star">*</span></label>
                            <div>
                                <input type="text" name="pamount" id="pamount" class="form-control"   />
                            </div>
                        </div>    
                    </div>
                    
                     <div class="col-sm-4">
                        <?php
                        if (isset($fees)) {
                            $regfee = 0;
                            $mfee = 0;
                            foreach ($fees as $fee) {
                                if ($fee[0]['fee_item_id'] == 1 || $fee[0]['fee_item_id'] == 100) {
                                    $regfee+= $fee[0]['totalsd'];
                                } else if ($fee[0]['fee_item_id'] == 48) {
                                    $mfee+=$fee[0]['totalsd'];
                                }
                            }
                        }
                        ?>
                        <table class="table table-striped">
                            <tr>
                                <th colspan="2" class="text-danger" ><b>Fees To be paid</b></th>   
                            </tr>
                            <tr>
                                <td  class="text-danger">Registration and Processing Fee</td>  <td class="text-success1"><b><span class="fa fa-rupee"></span> <?php echo $regfee; ?> </b></td>
                            </tr>
                            <tr>
                                <td class="text-danger"> Mutation Fees </td>  <td class="text-success1"><b><span class="fa fa-rupee"></span> <?php echo $mfee; ?></b></td>
                            </tr>
                        </table>
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
                <div class="panel-title"><h5>E-challan List  </h5></div>    
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
                                    <td><?php echo $single['payment_status']; ?></td>
                                    <td><?php echo $single['gateway_trans_id']; ?></td>
                                    <td>
                                        <?php
                                        if (!empty($single['gateway_trans_id'])) {
//                                            echo $this->Html->link(
//                                                    __('lbldownload'), array(
//                                                'disabled' => TRUE,
//                                                'controller' => 'Registration', // controller name
//                                                'action' => 'any_download_file', //action name
//                                                'full_base' => true, 'webservice_files', $single['transaction_id'] . '_challan.pdf'), array('class' => 'btn btn-warning', 'target' => '_blank')
//                                            );
                                        }
                                        ?>
                                        
                                        <?php if($single['payment_status']=='CREATED'){ ?>
                                        <a href="https://egov.goa.nic.in/echallanpg/haveechallan.aspx" class="btn btn-primary" target="_blank">Pay Now</a>
                                        <a href="<?php echo  $this->webroot;?>GAWebService/gras_payment_entry_new/123/<?php echo $single['gateway_trans_id'];?>" class="btn btn-primary" >Update Status</a>
                                       <?php }else{ ?>
                                        <a href="<?php echo  $this->webroot;?>GAWebService/EchallanReceipt/<?php echo $single['gateway_trans_id'];?>" class="btn btn-primary" target="_blank">Receipt</a>
                                       
                                     <?php  } ?>
                                        
                                         </td>


                                </tr> 
                                <?php
                            }
                        }
                        ?>
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
        $('#feetype').on('change', function () {
           // alert($('#feetype').val());
            if ($('#feetype').val() == 1) {
            //    alert("in");
                var options =
                        {1: "NOTARY"};
            } else {
                var options = {2: "URBAN", 3: "RURALNORTH", 4: "RURALSOUTH"};
            }
            //$('#feeflag') .option.remove();
            $('#feeflag option[value!=""]').remove();
            $.each(options, function (key, value) {
                // $.each(value, function (key1, value1) {
                //  alert(key + " " + value);
                $('#feeflag').append($("<option></option>")
                        .attr("value", key)
                        .text(value));

                // });
            });
        });

    });
    var hash = '<?php echo @$hash ?>';
    function submitPayuForm() {
        if (hash == '') {
            return;
        }
        var payuForm = document.forms.payuForm;
        payuForm.submit();
    }
</script>


