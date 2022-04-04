<?php
//echo 'payment for'.$docregno;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="col-sm-12">
        <form action="" method="post" name="payuForm" >

            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title"><h5>Create New E-Challan  </h5></div>    
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Document Registration Number <span class="star">*</span></label>
                                <div>
                                    <input name="doc_regno" id="doc_regno" class="form-control" value="<?php echo $docregno; ?>" readonly="readonly"/>

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
                    
                     <!--<div class="col-sm-4">
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
                    </div>-->
                    
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
<script>
     $(document).ready(function () {
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
</script>
