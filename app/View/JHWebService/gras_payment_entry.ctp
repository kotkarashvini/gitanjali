 <?php 
 //$response = explode("|", 'COMTAX|003900800020101|ABC CONSTRUCTION|1234567891234567891|2|DRID001|NA|NA|NA|NA|PRJ|PRJFIN001|SUCCESS|NA|0000004387|10000032016042066183|3623578311641|2016-04-20 13:22:33|2|https://59.145.222.36/jegras/frmdownloadchallan.aspx?PDetails=9t1jWclZrk/lp8ICyIZO8Y410KhvkoAOafXvJU//VUk=|NA|NA');
 //pr($response);
 //$response = explode("|", 'DEPTID|RECIEPTHEADCODE|DEPOSITERNAME|DEPTTRANID|AMOUNT|DEPOSITERID|PANNO|ADDINFO1|ADDINFO2|ADDINFO3|TREASCODE|IFMSOFFICECODE|STATUS|PAYMENTSTATUSMESSAGE|GRN|CIN|REF_NO|TXN_DATE|TXN_AMOUNT|CHALLAN_URL| ADDINFO4| ADDINFO5');
 //pr($response);
 ?>
<div class="col-sm-12">
    <form action="<?php echo $action; ?>" method="post" name="payuForm" >

        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title"><h5>New GRAS  Payment </h5></div>    
            </div>
            <div class="panel-body"> 
                <input type="hidden" name="RESPONSE_URL" value="<?php echo $this->Html->url('/JHWebservice/gras_payment_responce', true );?>" />   <!--Please change this parameter value with your success page absolute url like http://mywebsite.com/response.php. -->
                <input type="hidden" name="requestparam" id="requestparam" class="form-control" value="<?php echo (empty($hash)) ? '' : $hash; ?>" />

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Token Number</label>
                            <div>
                                <input type="text" name="DEPOSITERID" id="DEPOSITERID" class="form-control" value="<?php echo (empty($posted['DEPOSITERID'])) ? '' : $posted['DEPOSITERID']; ?>" />
                            </div>
                            <span class="form-error" id="DEPOSITERID_error"></span>
                        </div>
                    </div>


                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>DEPOSITER NAME <span class="star"></span> </label>
                            <div>
                                <input type="text" name="DEPOSITERNAME" id="DEPOSITERNAME" class="form-control" value="<?php echo (empty($posted['DEPOSITERNAME'])) ? '' : $posted['DEPOSITERNAME']; ?>" />
                            </div>
                            <span class="form-error" id="DEPOSITERNAME_error"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>PAN NO <span class="star"></span> </label>
                            <div>
                                <input name="PANNO" id="PANNO"  class="form-control" value="<?php echo (empty($posted['PANNO'])) ? '' : $posted['PANNO']; ?>" />                    
                            </div>
                            <span class="form-error" id="PANNO_error"></span>
                        </div>
                    </div>

                </div>


                    <?php if(isset($mapping)){
                        $i=0;
                        foreach ($mapping as $map){
                            $i++;
                        ?>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><?php echo $map[0]['fee_item_desc_en']?></label>
                            <div>
                                <input name="ADDINFO<?php echo $i ;?>" id="ADDINFO<?php echo $i ;?>" class="form-control amtentry" value="<?php echo (empty($posted['ADDINFO'.$i])) ? '' : $posted['ADDINFO'.$i]; ?>" />
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
                                <input type="text" name="AMOUNT" id="AMOUNT" class="form-control"  value="<?php echo (empty($posted['AMOUNT'])) ? 0 : $posted['AMOUNT'] ?>" readonly="readonly"/>
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
                <div class="panel-title"><h5>Gras Payment Transactions  </h5></div>    
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
                            <th>Download</th>

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
                            <td><?php echo $single['gateway_trans_id']; ?></td> 
                            <td><?php echo $single['bank_trn_ref_number']; ?></td> 
                            <td><?php echo $single['payment_status']; ?></td>
                            <td>
                                <?php if($single['payment_status']=='CREATED'){?>
                                <a href="<?php echo $this->webroot;?>JHWebService/gras_payment_entry/<?php  echo $single['transaction_id'];  ?> " class="btn btn-info">Update Status</a>
                                <?php } ?>
                            </td>
                            <td>
                                <?php if($single['payment_status']=='SUCCESS'){?>
                                <a href="<?php  echo $single['invoice_url'];  ?> " class="btn btn-info" target="_blank">Download</a>
                                <?php } ?>
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
<script>
    $(document).ready(function () {
        submitPayuForm();
        $('#translist').dataTable();
        $('.amtentry').on('keyup', function () {
            var totalamt = 0;

                     <?php 
                        $i=0;
                        foreach ($mapping as $map){
                        $i++;
                       ?>
            if ($.isNumeric($('#ADDINFO<?php echo $i; ?>').val())) {
                totalamt = totalamt + parseInt($('#ADDINFO<?php echo $i; ?>').val(), 10);

            }
                           <?php   }  ?>
            $('#AMOUNT').val(totalamt);

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


