
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
<?php
echo $this->element("Registration/main_menu");
?> 

<br>

<style type="text/css">
    .style2
    {
        width: 11px;
    }
    .style3
    {
        width: 993px;
    }
    .auto-style1 {
    }
    .auto-style2 {
        width: 540px;
    }
</style>
<script>
    $(document).ready(function () {
        
         $('#btnotp').on('click', function () {
            $('#btnotp').text('Please Wait');
            $.post('<?php echo $this->webroot; ?>Registration/sendotp', {}, function (data)
            {
                $('#btnotp').text('<?php echo __('lblresendotp'); ?>');
            });
        });
        
        
        $('#final_stamp_pending').on('change', function () {
           // alert();
            if ($('#final_stamp_pending').is(':checked')) {
                $('#divfinal_stamp_pending_remark').show();
            } else {
                $('#divfinal_stamp_pending_remark').hide();
            }
        });

    });


    function captureFP() {

        CallSGIFPGetData(SuccessFunc, ErrorFunc);
    }
    /* 
     This functions is called if the service sucessfully returns some data in JSON object
     */
    function SuccessFunc(result) {
        if (result.ErrorCode == 0) {
            /* 	Display BMP data in image tag
             BMP data is in base 64 format 
             */
            if (result != null && result.BMPBase64.length > 0) {
                document.getElementById("FPImage1").src = "data:image/bmp;base64," + result.BMPBase64;
                $('#hfimg').val(result.BMPBase64);
//    var a ="data:image/bmp;base64," + result.BMPBase64;
//    alert(a);
            }
//                alert(result.TemplateBase64);
            var tbl = "<table border=1>";
            tbl += "<tr>";
            tbl += "<td> Serial Number of device </td>";
            tbl += "<td> <b>" + result.SerialNumber + "</b> </td>";
            tbl += "</tr>";
            tbl += "<tr>";
            tbl += "<td> Image Height</td>";
            tbl += "<td> <b>" + result.ImageHeight + "</b> </td>";
            tbl += "</tr>";
            tbl += "<tr>";
            tbl += "<td> Image Width</td>";
            tbl += "<td> <b>" + result.ImageWidth + "</b> </td>";
            tbl += "</tr>";
            tbl += "<tr>";
            tbl += "<td> Image Resolution</td>";
            tbl += "<td> <b>" + result.ImageDPI + "</b> </td>";
            tbl += "</tr>";
            tbl += "<tr>";
            tbl += "<td> Image Quality (1-100)</td>";
            tbl += "<td> <b>" + result.ImageQuality + "</b> </td>";
            tbl += "</tr>";
            tbl += "<tr>";
            tbl += "<td> NFIQ (1-5)</td>";
            tbl += "<td> <b>" + result.NFIQ + "</b> </td>";
            tbl += "</tr>";
            tbl += "<tr>";
            tbl += "<td> Template(base64)</td>";
            tbl += "<td> <b> <textarea rows=8 cols=50>" + result.TemplateBase64 + "</textarea></b> </td>";
            tbl += "</tr>";
            tbl += "</table>";
//            document.getElementById('result').innerHTML = tbl;
            $('#fingerdata').val(result.TemplateBase64);
            $('#cap').val(result.TemplateBase64);
//                 $('#fingerdatatemplet').val(result.TemplateBase64);
        } else {
            alert("Fingerprint Capture ErrorCode " + result.ErrorCode)
        }
    }

    function ErrorFunc(status) {

        /* 	
         If you reach here, user is probabaly not running the 
         service. Redirect the user to a page where he can download the
         executable and install it. 
         */
        alert("Check if SGIBIOSRV is running ");
    }

    function CallSGIFPGetData(successCall, failCall) {
        var hfserver = $("#biometserverflag ").val();
        if (hfserver == 'Y') {
            var uri = "https://SGIWEBSRV:8000/SGIFPCapture";
//            var secugen_lic = "NTommEhS08t44kdRsZsKLRrHxuLlFDkfD84Sb8zyAlo=";
//               var params =  "licstr=" + encodeURIComponent(secugen_lic);
        } else {
            var uri = "http://localhost:8000/SGIFPCapture";
        }
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                fpobject = JSON.parse(xmlhttp.responseText);
                successCall(fpobject);
            } else if (xmlhttp.status == 404) {
                failCall(xmlhttp.status)
            }
        }
        xmlhttp.onerror = function () {
            failCall(xmlhttp.status);
        }
        xmlhttp.open("POST", uri, true);
//        xmlhttp.send(params);
        xmlhttp.send();
    }
</script>

<?php //pr($errarr); ?>          <div class="row">
    <div class="col-md-12">
        <?php echo $this->Form->create('final_stamp', array('id' => 'final_stamp')); ?>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <?php echo __('lbltokenno'); ?> : <?php echo $documents[0][0]['token_no']; ?>
                <div class="pull-right action-buttons">
                    <div class="btn-group pull-right"> 
                        <?php echo __('lbldocrno'); ?> : <?php echo $documents[0][0]['doc_reg_no']; ?>                      
                    </div>
                </div>
            </div>
            <div class="box-heading">
                <center><h3 class="text-uppercase text-muted" style="font-weight: bolder"><?php echo __('lblfinalstamp'); ?></h3></center>
            </div>
            <div class="panel-body">

                <table class="table table-condensed">
                    <thead>
                        <tr>
                            <th><?php echo __('lblsrno'); ?></th>
                            <th><?php echo __('lblOverrides'); ?></th>
                            <td><b><?php echo __('lblstatus'); ?>  </b></td>
                            <td><b><?php echo __('lblaction'); ?></b></td>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $c = 0;
                        $btnaccept = 1;
                        foreach ($SroAcceptance as $single) {
                            $c++;
                            ?>
                        <tr>
                            <th width="10%"><?php echo $c; ?></th>
                            <th width="40%">
                                    <?php echo $single[0]['acceptance_desc_' . $lang] ?></th>
                            <td width="30%"> <?php
                                    if ($single[0]['acceptance_flag'] == 'A') {
                                        echo "<i class='label label-success'><span class='glyphicon glyphicon-check'></span>" . __('lblaccepted') . "</i>";
                                    } elseif ($single[0]['acceptance_flag'] == 'R') {
                                        echo "<i class='label label-danger'><span class='glyphicon glyphicon-remove glyphicon-ring'></span> " . __('lblrejected') . "</i>";
                                    }
                                    ?> </td>
                            <td>
                                    <?php
                                    if ($single[0]['remark_flag'] == 'Y') {
                                        if ($single[0]['old_data_flag'] == 'N' && $single[0]['acceptance_flag'] == 'A' && $single[0]['second_remark_flag'] == 'N') {
                                            $btnaccept = 0;
                                            ?>
                                <button type="button" class="btn btn-info icon-btn" data-toggle="modal" data-target="#Modal_accept<?php echo $single[0]['id'] ?>"><span class="glyphicon glyphicon-check img-circle text-success"></span>  <?php echo __('lblaccept'); ?></button>  
                                <!--<button type="button" class="btn btn-info icon-btn" data-toggle="modal" data-target="#Modal_reject<?php //echo $single[0]['id']      ?>"><span class="glyphicon glyphicon-remove img-circle text-danger"></span> <?php //echo __('lblreject');      ?></button>-->  

                                            <?php
                                        } else {
                                            ?>
                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal<?php echo $single[0]['id'] ?>"> <?php echo __('lblremark'); ?></button> 

                                            <?php
                                        }
                                        ?>
                                        <?php
                                    } else {
                                        $btnaccept = 0;
                                        ?>
                                <button type="button" class="btn btn-info icon-btn" data-toggle="modal" data-target="#Modal_accept<?php echo $single[0]['id'] ?>"><span class="glyphicon glyphicon-check img-circle text-success"></span>  <?php echo __('lblaccept'); ?></button>  
                                <!--<button type="button" class="btn btn-info icon-btn" data-toggle="modal" data-target="#Modal_reject<?php echo $single[0]['id'] ?>"><span class="glyphicon glyphicon-remove img-circle text-danger"></span> <?php echo __('lblreject'); ?></button>-->  

                                    <?php }
                                    ?>
                            </td> 
                        </tr>
                            <?php
                        }

                        if ($c == 0) {
                            ?>
                        <tr>
                            <td colspan="4"><?php echo __('lblrecordnotfound'); ?></td>
                        </tr>   
                            <?php
                        }
                        ?>

                    </tbody>
                </table> 

                <div class="pull-right ">
                    <button type="button" class="btn btn-warning " data-toggle="modal" data-target="#modelRegSummary1"><span class="glyphicon glyphicon-print img-circle"></span> <?php echo __('lblviewsummer1'); ?></button>
                     <?php  if ($btnhide[0][0]['final_stamp_flag'] == 'N') { ?>
                    <button type="button" class="btn btn-warning " data-toggle="modal" data-target="#modelRegSummary2partial"><span class="glyphicon glyphicon-print img-circle"></span> <?php echo __('lblsummary2partialview'); ?></button>
                      <?php } ?>
                       <?php  if ($btnhide[0][0]['final_stamp_flag'] == 'Y'  && $btnhide[0][0]['final_stamp_pending'] == 'N') { ?>
                    <button type="button" class="btn btn-warning " data-toggle="modal" data-target="#modelRegSummary2"><span class="glyphicon glyphicon-print img-circle"></span> <?php echo __('lblsummary2fullview'); ?></button>
                     <?php } ?>
                    <?php if ($documents[0][0][$funflag] == 'Y') { ?>
                    <button type="button" class="btn btn-warning " data-toggle="modal" data-target="#indexreport"><span class="glyphicon glyphicon-list img-circle"></span> <?php echo __('lblindexreports'); ?></button>
                    <?php } ?>

                        <?php  if ($i>0) { ?>       
                    <button type="button" class="btn btn-warning " data-toggle="modal" data-target="#modelhomevisit"><span class="glyphicon glyphicon-print img-circle"></span> <?php echo __('Home Visit'); ?></button>
                    <?php } ?>          
                    <?php
                    if ($documents[0][0][$funflag] == 'Y') {
                        echo $this->Html->link(
                                __('lbldocumentdownload'), array(
                            'disabled' => TRUE,
                            'controller' => 'Registration', // controller name
                            'action' => 'downloadfile', //action name
                            'full_base' => true, $token . '_final_document.pdf', 'Report'), array('class' => 'btn btn-warning', 'target' => '_blank')
                        );
                    }
                    ?>


                </div>

                <div class="pull-left">
                    <?php if ($documents[0][0]['final_stamp_pending'] == 'N' && $documents[0][0]['final_stamp_flag'] == 'N') { ?>
                    <button type="button" class="btn btn-warning " data-toggle="modal" data-target="#documentdisposal"> <?php echo __('lbldocumentdisposal'); ?></button>
                    <?php } else { ?> 
                    <button type="button" class="btn btn-warning smartbtn-disabled " > <?php echo __('lbldocumentdisposal'); ?></button>

                    <?php } ?>
                </div>
            </div>
            <div class="panel-footer">
                <div class=" col-xs-12">
                    <div class="checkbox">
                        <div class="form-group">
                        <label>
                            <?php 
                             $remarkstyle='style="display: none;"';
                            if ($documents[0][0]['final_stamp_pending'] == 'N') { ?>
                            <input id="final_stamp_pending" name="final_stamp_pending" value="1" type="checkbox">Final Stamp Pending
                            <?php } else{ ?> 
                            <span class="fa fa-arrow-right"></span> <?php echo __('lblstatus'); ?> : Final Stamp Pending  
                                <?php } ?>
                        </label>
                            </div>
                        <div class="clearfix"></div>
                          <div class="form-group col-sm-4" id="divfinal_stamp_pending_remark" <?php echo @$remarkstyle;?>>   
                            <label><?php echo __('lblfinal_stamp_pending_remark');?></label><br>
                            <div>
                                <textarea class="form-control" name="data[final_stamp][final_stamp_pending_remark]" id="final_stamp_pending_remark"></textarea>
                            </div> 
                            <span id="final_stamp_pending_remark_error" class="form-error"></span>

                        </div>
                        
                           <?php  if ($btnhide[0][0]['final_stamp_pending'] == 'Y') { ?>
                          <button type="button" class="btn btn-warning " data-toggle="modal" data-target="#modelpendingdoc"><span class="glyphicon glyphicon-print img-circle"></span> Pending Document </button> 
                         <?php } ?>
                    </div>
                </div>
                <div class="center">
                    <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>

                    <?php
                    if ($documents[0][0][$funflag] == 'N' && $btnaccept == 1 && $documents[0][0]['final_stamp_pending'] == 'N') {
                           if (!empty($regconfbiometric)) {
                             if($regconfbiometric[0]['regconfig']['info_value']==1){
                              echo $this->Form->button(__($btnaccept_label), array('type' => 'button', 'label' => FALSE, 'class' => 'smartbtn smartbtn-success', 'data-toggle' => 'modal', 'data-target' => '#modelbiometric'));
                             }else {
                              echo $this->Form->button(__($btnaccept_label), array('type' => 'button', 'label' => FALSE, 'class' => 'smartbtn smartbtn-success', 'data-toggle' => 'modal', 'data-target' => '#modelotp'));
                             }                            
                            
                        } else {
                            echo $this->Form->button(__($btnaccept_label), array('type' => 'submit', 'label' => FALSE, 'class' => 'smartbtn smartbtn-success'));
                        }  
                    } else {
                        echo $this->Form->button(__($btnaccept_label), array('type' => 'button', 'label' => FALSE, 'class' => 'smartbtn smartbtn-disabled'));
                    }
                    ?>

                </div>
            </div>
        </div>




        <div id="modelbiometric" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><?php echo __('lblbioreg'); ?> </h4>
                    </div>
                    <div class="modal-body center" >
                        <div class="row">
                            <div class="form-group">
                                <center>
                                    <img border="2" id="FPImage1" alt="Fingerprint Image" height=250 width=180 src="" > <br><br>
                                    <input type="button" value="<?php echo __('lblcapturefingurprint') ?>" onclick="captureFP();"> <br>
                                    <br>
                                    <div style=" color:black;                                                     padding:20px;">
                                        <p id="result"> </p>
                                    </div>
                                </center>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class=" col-xs-12">
                            <div class="checkbox">
                                <label>
                                    <?php if ($documents[0][0][$funflag] == 'Y') { ?>    
                                    <input type="checkbox" id="device_not_working" name="device_not_working" value="1" disabled="disabled">Device is Not Working
                                    <?php } else { ?>
                                    <input type="checkbox" id="device_not_working" name="device_not_working" value="1">Device is Not Working
                                    <?php } ?>
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-default pull-left" name="done"><?php echo __('lbldone'); ?></button>
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
                    </div>
                </div>
            </div>
<!--            <input type='hidden' value='<?php // echo $cap; ?>' name='cap' id='cap'/>
            <input type='hidden' value='<?php // echo $biometserverflag; ?>' name='biometserverflag' id='biometserverflag'/>-->
        </div>


        <div id="modelotp" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><?php echo __('lblotpverification'); ?> </h4>
                        <div class="box-tools pull-right">
                            <button class="btn btn-sm btn-info" type="button" id="btnotp"><span class="fa fa-mobile" ></span> <?php echo __('lblsendotp'); ?>  </button>
                        </div>
                    </div>
                    <div class="modal-body" >
                        <div class="row">
                            <div class="col-sm-3">
                                <label><?php echo __('lblenterotp'); ?></label>
                            </div>
                            <div class="col-sm-3">
                                  <?php echo $this->Form->input('otp', array('label' => false, 'id' => 'otp', 'type' => 'text', 'class'=>'form-control')); ?>
                            </div>
                            <div class="col-sm-3">
                                <button type="submit" class="btn btn-info pull-left" name="done"><?php echo __('btnsubmit'); ?></button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">

                            </div>
                            <div class="col-sm-3">
                                <span id="otp_error" class="form-error"></span>
                            </div>
                            <div class="col-sm-3">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">                        
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
                    </div>
                </div>
            </div>
            <input type='hidden' value='<?php echo $cap; ?>' name='cap' id='cap'/>
            <input type='hidden' value='<?php echo $biometserverflag; ?>' name='biometserverflag' id='biometserverflag'/>
        </div>

        <?php echo $this->Form->end(); ?>

    </div>
</div>

<?php if ($documents[0][0]['disposal_flag'] == 'N') { ?>
<div id="documentdisposal" class="modal fade" role="dialog">
        <?php echo $this->Form->create('final_stamp', array('id' => 'disposal')); ?>
        <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>

    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo __('lbldisposalmethod'); ?> </h4>

            </div>
            <div class="modal-body" >
                <div class="form-group">
                        <?php
                        echo $this->Form->input('disposal_id', array('id' => 'disposal_id', 'class' => 'form-control input-sm chosen-select', 'label' => false, 'options' => $DocumentDisposal, 'empty' => '--Select--'));
                        ?>      
                    <span class="form-error" id="disposal_id_error"></span>
                </div>  
                <div class="form-group">
                    <label><?php echo __('lblreason'); ?></label>
                        <?php
                        echo $this->Form->input('reason_id', array('id' => 'reason_id', 'class' => 'form-control input-sm', 'label' => false, 'options' => $DocumentDisposalReasons, 'empty' => '--Select--'));
                        ?>      
                    <span class="form-error" id="reason_id_error"></span>
                </div>
                <div class="form-group">
                    <label><?php echo __('lblremark'); ?></label>
                        <?php echo $this->Form->input('disposal_remark', array('label' => false, 'id' => 'disposal_remark', 'type' => 'textarea', 'class' => 'form-control')); ?>
                    <span class="form-error" id="disposal_remark_error"></span>
                </div>
                <div class="form-group">
                    <label><?php echo __('lblforwartouser'); ?></label>
                        <?php
                        echo $this->Form->input('forward_user_id', array('id' => 'forward_user_id', 'class' => 'form-control input-sm chosen-select', 'label' => false, 'options' => $userlist, 'empty' => '--Select--'));
                        ?>      
                    <span class="form-error" id="user_id_error"></span>
                </div>

            </div>
            <div class="modal-footer">                 
                <button type="submit" class="btn btn-default pull-left" name="dispose"><?php echo __('lbldispose'); ?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
            </div>
        </div>
    </div>
        <?php echo $this->Form->end(); ?>
</div>
<?php }if ($documents[0][0]['disposal_flag'] == 'Y') { ?>
<div id="documentdisposal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo __('lbldisposalmethod'); ?> </h4>

            </div>
            <div class="modal-body" >
                <table class="table table-responsive table-bordered">
                    <tbody>
                            <?php
                            foreach ($DocumentDisposalEntry as $DisposalEntry) {
                                ?>
                        <tr class="bg-danger">
                            <th width="10%"><?php echo __('lbldisposal'); ?></th>   <th> <?php echo $DisposalEntry['DocumentDisposal']['disposal_desc_' . $lang]; ?>  </th>
                        </tr> 
                        <tr>
                            <th width="10%"><?php echo __('lblreason'); ?></th>   <th> <?php echo $DisposalEntry['DocumentDisposalReasons']['reason_desc_' . $lang]; ?>  </th>
                        </tr>
                        <tr>
                            <th> <?php echo __('lblremark'); ?></th> <th> <?php echo $DisposalEntry['DocumentDisposalEntry']['disposal_remark']; ?>  </th>
                        </tr> 
                        <tr>
                            <th> <?php echo __('lblforwartouser'); ?></th> <th> <?php echo $DisposalEntry['User']['full_name']; ?>  </th>
                        </tr>  

                            <?php } ?> 
                    </tbody>
                </table> 
            </div>
            <div class="modal-footer">    
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
            </div>
        </div>
    </div>

</div> 
<?php } ?>

<?php
$lc = 0;
foreach ($SroAcceptance as $single) {
    $lc = $single[0]['acceptance_id'];
    $html = $this->requestAction(array('controller' => 'Registration', 'action' => 'details_sro_acceptance', $lc));
    if ($single[0]['remark_flag'] == 'Y') {
        if ($single[0]['old_data_flag'] == 'N' && $single[0]['acceptance_flag'] == 'A' && $single[0]['second_remark_flag'] == 'N') {
            ?>
<!-- Modal -->
<div id="Modal_reject<?php echo $single[0]['id'] ?>" class="modal fade" role="dialog">
    <div class="modal-dialog">
                    <?php echo $this->Form->create('final_stamp', array('id' => 'formrj' . $lc)); ?>
                    <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $single[0]['acceptance_desc_' . $lang] ?>  <?php echo __('lblreject') . " " . __('lblremark') . " 2"; ?></h4>
            </div>
            <div class="modal-body">
                            <?php echo $html; ?>
                <input type="hidden" name="acceptance_flag" value="R">
                <input type="hidden" name="acceptance_id"  value="<?php echo $single[0]['acceptance_id']; ?>">
                <textarea class="form-control" name="acceptance_remark2" id="<?php echo 'formrj' . $lc . '_acceptance_remark2'; ?>"></textarea> 
                <span class="form-error" id="<?php echo 'formrj' . $lc . '_acceptance_remark2'; ?>_error"></span>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-default pull-left" name="sroreject1"><?php echo __('lblreject'); ?></button>               
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
            </div>
        </div>

                    <?php echo $this->Form->end(); ?>
    </div>
</div>

<!-- Modal -->
<div id="Modal_accept<?php echo $single[0]['id'] ?>" class="modal fade" role="dialog">
    <div class="modal-dialog">
                    <?php echo $this->Form->create('final_stamp', array('id' => 'formac' . $lc)); ?>
                    <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $single[0]['acceptance_desc_' . $lang] ?>   <?php echo __('lblaccept') . " " . __('lblremark') . " 2"; ?></h4>
            </div>
            <div class="modal-body">
                            <?php echo $html; ?>

                <input type="hidden" name="acceptance_flag" value="A">
                <input type="hidden" name="acceptance_id" value="<?php echo $single[0]['acceptance_id']; ?>">
                <textarea class="form-control" id="<?php echo 'formac' . $lc . '_acceptance_remark2'; ?>" name="acceptance_remark2"></textarea> 
                <span class="form-error" id="<?php echo 'formac' . $lc . '_acceptance_remark2'; ?>_error"></span>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-default pull-left" name="sroaccept1"><?php echo __('lblaccepted'); ?></button>               
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
            </div>
        </div>
                    <?php echo $this->Form->end(); ?>
    </div>
</div>
            <?php
        } else {
            ?>
<!-- Modal -->
<div id="myModal<?php echo $single[0]['id'] ?>" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $single[0]['acceptance_desc_' . $lang] ?>  <?php echo __('lblremark'); ?> </h4>
            </div>
            <div class="modal-body">
                            <?php echo $html; ?>

                            <?php
                            $remarkcount = "";
                            if ($single[0]['old_data_flag'] == 'N') {
                                $remarkcount = 1;
                            }
                            ?>
                <table class="table table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th><?php echo __('lblremark') . " " . $remarkcount; ?></th> 
                        </tr> 
                        <tr>
                            <th><?php echo $single[0]['acceptance_remark']; ?></th> 
                        </tr> 
                    </thead> 
                    </thead>                                       
                </table>  
                            <?php
                            if ($single[0]['old_data_flag'] == 'N') {
                                $remarkcount = 2;
                                ?>
                <table class="table table-bordered table-condensed">
                    <thead> 
                        <tr>
                            <th><?php echo __('lblremark') . " " . $remarkcount; ?></th> 
                        </tr> 
                        <tr>
                            <th><?php echo $single[0]['acceptance_remark2']; ?></th> 
                        </tr> 
                    </thead>                                       
                </table>
                            <?php } ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
            </div>
        </div>

    </div>
</div>
            <?php
        }
    } else {
        ?>
<!-- Modal -->
<div id="Modal_reject<?php echo $single[0]['id'] ?>" class="modal fade" role="dialog">
    <div class="modal-dialog">
                <?php echo $this->Form->create('final_stamp', array('id' => 'formrj' . $lc)); ?>
                <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $single[0]['acceptance_desc_' . $lang] ?>  <?php echo __('lblreject') . " " . __('lblremark'); ?></h4>
            </div>
            <div class="modal-body">
                        <?php echo $html; ?>

                <input type="hidden" name="acceptance_flag" value="R">
                <input type="hidden" name="acceptance_id" value="<?php echo $single[0]['acceptance_id']; ?>">
                <textarea class="form-control" id="<?php echo 'formrj' . $lc . '_acceptance_remark'; ?>" name="acceptance_remark"></textarea> 
                <span class="form-error" id="<?php echo 'formrj' . $lc . '_acceptance_remark'; ?>_error"></span>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-default pull-left" name="sroreject"><?php echo __('lblreject'); ?></button>               
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
            </div>
        </div>
                <?php echo $this->Form->end(); ?>
    </div>
</div>

<!-- Modal -->
<div id="Modal_accept<?php echo $single[0]['id'] ?>" class="modal fade" role="dialog">
    <div class="modal-dialog">
                <?php echo $this->Form->create('final_stamp', array('id' => 'formac' . $lc)); ?>
                <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $single[0]['acceptance_desc_' . $lang] ?>   <?php echo __('lblaccept') . " " . __('lblremark'); ?></h4>
            </div>
            <div class="modal-body">
                        <?php echo $html; ?>

                <input type="hidden" name="acceptance_flag" value="A">
                <input type="hidden" name="acceptance_id" value="<?php echo $single[0]['acceptance_id']; ?>">
                <textarea class="form-control" id="<?php echo 'formac' . $lc . '_acceptance_remark'; ?>" name="acceptance_remark" id="acceptance_remark"></textarea> 
                <span class="form-error" id="<?php echo 'formac' . $lc . '_acceptance_remark'; ?>_error"></span>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-default pull-left" name="sroaccept"><?php echo __('lblaccepted'); ?></button>               
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
            </div>
        </div>
                <?php echo $this->Form->end(); ?>
    </div>
</div>


        <?php
    }
}
?>

<!--home Visit-->   

<div id="modelhomevisit" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo __('Home Visit'); ?> </h4>
            </div>
            <div class="modal-body center" id="rpthomevisit">
                <?php
                if (isset($regconf) && !empty($regconf)) {
                    if ($regconf[0]['regconfig']['info_value'] == 'QR') {
                        echo $this->Html->image(array('controller' => 'Registration', 'action' => 'document_qr_bar_code', 'QR'), array('id' => 'QRcode', 'width' => '50', 'height' => '50', 'class' => 'pull-left'));
                    } elseif ($regconf[0]['regconfig']['info_value'] == 'BAR') {
                        echo $this->Html->image(array('controller' => 'Registration', 'action' => 'document_qr_bar_code', 'BAR'), array('id' => 'QRcode', 'class' => 'img-responsive pull-left'));
                    }
                }
                ?>
                <?php echo $homevisit; ?>
            </div>
            <div class="modal-footer">
                <a type="button" href="<?php echo $this->webroot; ?>Reports/party_home_visit/<?php echo base64_encode($documents[0][0]['token_no']); ?>/D" class="btn btn-warning btn-xs pull-left"><?php echo __('lbldownload'); ?></a>
                <button type="button" class="btn btn-default" id="homevisitprint"><?php echo __('lblprint'); ?></button>

                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
            </div>
        </div>
    </div>
</div>  

<!-- Registration Summary 1 -->
<div id="modelRegSummary1" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo __('lbldocregsumm1'); ?> </h4>
            </div>
            <div class="modal-body center" id="rptRegSummary1">
                <?php
                if (isset($regconf) && !empty($regconf)) {
                    if ($regconf[0]['regconfig']['info_value'] == 'QR') {
                        echo $this->Html->image(array('controller' => 'Registration', 'action' => 'document_qr_bar_code', 'QR'), array('id' => 'QRcode', 'width' => '50', 'height' => '50', 'class' => 'pull-left'));
                    } elseif ($regconf[0]['regconfig']['info_value'] == 'BAR') {
                        echo $this->Html->image(array('controller' => 'Registration', 'action' => 'document_qr_bar_code', 'BAR'), array('id' => 'QRcode', 'class' => 'img-responsive pull-left'));
                    }
                }
                ?>
                <?php echo $summary1; ?>
            </div>
            <div class="modal-footer">
                <a type="button" href="<?php echo $this->webroot; ?>Reports/summary1_report/<?php echo base64_encode($documents[0][0]['token_no']); ?>/D" class="btn btn-warning btn-xs pull-left"><?php echo __('lbldownload'); ?></a>
                <button type="button" class="btn btn-default" id="summary1print"><?php echo __('lblprint'); ?></button>

                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
            </div>
        </div>
    </div>
</div>


<div id="modelpendingdoc" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"> <?php echo __('lbldocregsumm2'); ?></h4>
            </div>
            <div class="modal-body center cont_size" id="rptpendingdoc">
                <?php
                if (isset($regconf) && !empty($regconf)) {
                    if ($regconf[0]['regconfig']['info_value'] == 'QR') {
                        echo $this->Html->image(array('controller' => 'Registration', 'action' => 'document_qr_bar_code', 'QR'), array('id' => 'QRcode', 'width' => '50', 'height' => '50', 'class' => 'pull-left'));
                    } elseif ($regconf[0]['regconfig']['info_value'] == 'BAR') {
                        echo $this->Html->image(array('controller' => 'Registration', 'action' => 'document_qr_bar_code', 'BAR'), array('id' => 'QRcode', 'class' => 'img-responsive pull-left'));
                    }
                }
                ?>
                <?php echo $pendingdoc; ?>
            </div>
            <div class="modal-footer">
              
                <button type="button" class="btn btn-default" id="pendingdoc"><?php echo __('lblprint'); ?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
            </div>
        </div>
    </div>
</div>
<!-- Registration Summary 2 -->
<div id="modelRegSummary2" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"> <?php echo __('lbldocregsumm2'); ?></h4>
            </div>
            <div class="modal-body center cont_size" id="rptRegSummary2full">
                <?php
                if (isset($regconf) && !empty($regconf)) {
                    if ($regconf[0]['regconfig']['info_value'] == 'QR') {
                        echo $this->Html->image(array('controller' => 'Registration', 'action' => 'document_qr_bar_code', 'QR'), array('id' => 'QRcode', 'width' => '50', 'height' => '50', 'class' => 'pull-left'));
                    } elseif ($regconf[0]['regconfig']['info_value'] == 'BAR') {
                        echo $this->Html->image(array('controller' => 'Registration', 'action' => 'document_qr_bar_code', 'BAR'), array('id' => 'QRcode', 'class' => 'img-responsive pull-left'));
                    }
                }
                ?>
                <?php echo $summary2full; ?>
            </div>
            <div class="modal-footer">
                <!--<a type="button" href="<?php //echo $this->webroot; ?>Reports/summary2_report/<?php //echo base64_encode($documents[0][0]['token_no']); ?>/D" class="btn btn-warning btn-xs pull-left"><?php //echo __('lblfulldownload'); ?></a>-->
                <button type="button" class="btn btn-default" id="summary2fullprint"><?php echo __('lblprint'); ?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
            </div>
        </div>
    </div>
</div>
<!-- Registration Summary 2 -->
<div id="modelRegSummary2partial" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"> <?php echo __('lbldocregsumm2'); ?></h4>
            </div>
            <div class="modal-body center" id="rptRegSummary2partial">
                <?php
                if (isset($regconf) && !empty($regconf)) {
                    if ($regconf[0]['regconfig']['info_value'] == 'QR') {
                        echo $this->Html->image(array('controller' => 'Registration', 'action' => 'document_qr_bar_code', 'QR'), array('id' => 'QRcode', 'width' => '50', 'height' => '50', 'class' => 'pull-left'));
                    } elseif ($regconf[0]['regconfig']['info_value'] == 'BAR') {
                        echo $this->Html->image(array('controller' => 'Registration', 'action' => 'document_qr_bar_code', 'BAR'), array('id' => 'QRcode', 'class' => 'img-responsive pull-left'));
                    }
                }
                ?>
                <?php echo $summary2partial; ?>
            </div>
            <div class="modal-footer">
                <!--<a type="button" href="<?php //echo $this->webroot; ?>viewRegSummary2/<?php// echo base64_encode($documents[0][0]['token_no']); ?>/D/P" class="btn btn-warning btn-xs pull-left"><?php// echo __('lblpartialdownload'); ?></a>-->
                <button type="button" class="btn btn-default" id="summary2partialprint"><?php echo __('lblprint'); ?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
            </div>
        </div>
    </div>
</div> 

<script type='text/javascript'>
    jQuery(function ($) {
        'use strict';
        $('#summary2partialprint').on('click', function () {
            $.print("#rptRegSummary2partial");
        });
        $('#summary2fullprint').on('click', function () {
            $.print("#rptRegSummary2full");
        });
         
       $('#pendingdoc').on('click', function () {
            $.print("#rptpendingdoc");
        });
        $('#summary1print').on('click', function () {
            $.print("#rptRegSummary1");
        });
    });
</script>

<div id="indexreport" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"> <?php echo __('lblindexreports'); ?></h4>
            </div>
            <div class="modal-body"> 
                <ul class="list-group">
                    <?php if ($article['index1_flag'] == 'Y') { ?> 
                    <li class="list-group-item"> <a  href="<?php echo $this->webroot; ?>Registration/indexreport/1/D"><?php echo __('lblindex1'); ?></a></li> 
                    <?php } ?>
                    <?php if ($article['index2_flag'] == 'Y') { ?> 
                    <li class="list-group-item"> <a  href="<?php echo $this->webroot; ?>Registration/indexreport/2/D"><?php echo __('lblindex2'); ?></a></li> 
                    <?php } ?>
                    <?php if ($article['index3_flag'] == 'Y' && $article['article_id'] != 63) { ?> 
                    <li class="list-group-item"> <a  href="<?php echo $this->webroot; ?>Registration/indexreport/3/D"><?php echo __('lblindex3'); ?></a></li> 
                    <?php } ?>
                    <?php if ($article['index4_flag'] == 'Y') { ?> 
                    <li class="list-group-item"> <a  href="<?php echo $this->webroot; ?>Registration/indexreport/4/D"><?php echo __('lblindex4'); ?></a></li> 
                    <?php } ?>
                </ul>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
            </div>
        </div>
    </div>
</div> 

