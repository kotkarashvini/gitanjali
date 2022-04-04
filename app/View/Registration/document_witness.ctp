<?php echo $this->Html->script('Device/webcam.min.js'); ?>

<script type="text/javascript">
    function Save() {
        document.getElementById("actiontype").value = '1';
    }

    function Verify() {
        document.getElementById("actiontype").value = '2';
    }
</script> 


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
                document.getElementById("btnsavebio").disabled = false;
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
            var uri = "https://localhost:8000/SGIFPCapture";
            var secugen_lic = "NTommEhS08t44kdRsZsKLRrHxuLlFDkfD84Sb8zyAlo=";
            var params = "licstr=" + encodeURIComponent(secugen_lic);
        } else {
            var uri = "https://localhost:8000/SGIFPCapture";
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
        xmlhttp.send(params);
//        xmlhttp.send();
    }
</script>

<script type="text/javascript">

    function formphoto(id) {
        document.getElementById('my_result').innerHTML = '';
            Webcam.set({
                width: 490,
                height: 390,
                image_format: 'jpeg',
                jpeg_quality: 90
            });

            Webcam.attach('#my_camera');
            $('#photomodal').modal('show');
            $('#hfid').val(id);
            document.getElementById("btnsavephoto").disabled = true;
        return false;
    }

    function Savepic() {
        document.getElementById("actiontype").value = '3';
        $('#witness').submit();
    }

    function formsave(id) {
        $("#modal").dialog({
            modal: true,
            autoOpen: false,
            title: "Biometric Capture",
            width: 300,
            height: 550
        });
        $('#modal').dialog('open');
        $('#hfid').val(id);
        document.getElementById("btnsavebio").disabled = true;
    }
    function formreset(id) {
        document.getElementById("actiontype").value = '2';
        $('#hfid').val(id);
        $('#witness').submit();
    }
    function Save() {
        document.getElementById("actiontype").value = '1';
        $('#witness').submit();
    }
</script>
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
?><br>
<?php echo $this->Form->create('witness', array('id' => 'witness', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <?php echo __('lbltokenno'); ?> : <?php echo $documents[0][0]['token_no']; ?>
                <div class="pull-right action-buttons">
                    <div class="btn-group pull-right"> 
                        <?php echo __('lbldocrno'); ?>: <?php echo $documents[0][0]['doc_reg_no']; ?>                      
                    </div>
                </div>
            </div>
            <div class="box-header with-border">
                <center><h3 class="box-title" style="font-weight: bolder"><?php echo __('lblwitnesslists'); ?></h3></center>
            </div>
            <div class="box-body">

                <table class="table table-striped table-bordered table-hover" id="Doclist">
                    <thead>
                        <tr>
                            <th style="text-align: center;"><?php echo __('lblsrno'); ?></th> 
                            <th style="text-align: center;"><?php echo __('lblwitnessfullname'); ?></th>
                            <th style="text-align: center;"><?php echo __('lblwitnesstype'); ?></th>

                            <th style="text-align: center;"><?php echo __('lblgender'); ?></th>
                            <th style="text-align: center;"><?php echo __('lbldob'); ?></th>
                            <th style="text-align: center;"><?php echo __('lblage'); ?></th>
                            <th style="text-align: center;"><?php echo __('lblphoto'); ?></th>
                            <th style="text-align: center;"><?php echo __('lblfinger'); ?></th>
                            <th style="text-align: center;"><?php echo __('lblaction'); ?></th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $counter = 0;
                        $btnaccept = 1;

                        foreach ($witness as $witnessrow) {
                            //pr($witnessrow);exit;
                            $lockflag = 1;
                            ?>
                            <tr >
                                <td scope="row" style="text-align: center; font-weight:bold;"><?php echo ++$counter; ?></td>
                                <td style="text-align: center;"><?php echo $witnessrow['witness']['witness_full_name_' . $doc_lang]; ?></td>
                                <td style="text-align: center;"><?php echo $witnessrow['witness_type']['witness_type_desc_' . $language]; ?></td>                               

                                <td style="text-align: center;"><?php echo $witnessrow['gender']['gender_desc_' . $language]; ?></td>
                                <td style="text-align: center;"><?php
                                    if (!empty($witnessrow['witness']['dob'])) {
                                        $date = date_create($witnessrow['witness']['dob']);
                                        echo date_format($date, 'd M Y');
                                    }
                                    ?></td>
                                <td style="text-align: center;"><?php echo $witnessrow['witness']['age']; ?></td>

                                <td style="text-align: center;">  

                                    <?php
                                  //  pr($witnessrow);
                                    $imagedata = $path['file_config']['filepath'] . $witnessrow['witness']['photo_img'];
                                    if ($witnessrow['witness']['photo_img'] != null && file_exists($imagedata)) {
                                        $image = file_get_contents($imagedata);
                                        $image_codes = base64_encode($image);
                                    } else if ($witnessrow['witness']['camera_working_flag'] == 'N') {
                                        $image1 = file_get_contents('img/camera_cross.png', true);
                                        $image_codes = base64_encode($image1);
                                    } else {
                                        $image_codes = null;
                                        $btnaccept = 0;
                                        $lockflag = 0;
                                    }
                                    ?>   

                                    <image src="data:image/jpg;charset=utf-8;base64,<?php echo $image_codes; ?>" height="70" width="70" />

                                </td>
                                <td style="text-align: center;">

                                    <?php
                                    $imagedata1 = $path['file_config']['filepath'] . $witnessrow['witness']['biometric_img'];
                                    if ($witnessrow['witness']['biometric_img'] != null && file_exists($imagedata1)) {
                                        $image1 = file_get_contents($imagedata1);
                                        $image_codes1 = base64_encode($image1);
                                    } else if ($witnessrow['witness']['biodevice_working_flag'] == 'N') {
                                        $image1 = file_get_contents('img/fingerprint-cross.png', true);
                                        $image_codes1 = base64_encode($image1);
                                    } else {
                                        $image_codes1 = null;
                                        $btnaccept = 0;
                                        $lockflag = 0;
                                    }
                                    ?>                                  
                                    <image src="data:image/jpg;charset=utf-8;base64,<?php echo $image_codes1; ?>" height="70" width="70" />

                                </td>
                                <td style="text-align: center;">


                                    <?php
                                    if ($witnessrow['witness']['record_lock'] == 'Y') {
                                        echo __('lbllocked');
                                    } else {
                                        ?>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#otheroptions<?php echo $witnessrow['witness']['witness_id']; ?>">
                                            <?php echo __('lbloptions'); ?>
                                        </button>

                                        <?php if ($witnessrow['witness']['photo_img'] != '') {
                                            ?>    <button type="button"  id='btncap' class="btn btn-primary disabled"> <span class="fa fa-camera"></span> <?php echo __('lblphotocapture'); ?></button>
                                        <?php } else {
                                            ?>
                                            <button type="button"  id='btncap' class="btn btn-primary" onclick="javascript: return formphoto(('<?php echo $witnessrow['witness']['id']; ?>'));"> <span class="fa fa-camera"> </span> <?php echo __('lblphotocapture'); ?></button>
                                        <?php } ?>
                                        <?php if ($witnessrow['witness']['biometric_img'] != '') {
                                            ?>    <button type="button"  id='btncap' class="btn btn-warning disabled"> <?php echo __('lblfingercapture'); ?></button>
                                        <?php } else {
                                            ?>
                                            <button type="button"  id='btncap' class="btn btn-warning" onclick="javascript: return formsave(('<?php echo $witnessrow['witness']['id']; ?>'));"><?php echo __('lblfingercapture'); ?></button>
                                        <?php } ?>
                                        <?php if ($witnessrow['witness']['biometric_img'] != '' || $witnessrow['witness']['photo_img'] != '') {
                                            ?>    <button type="button"  id='btncap' class="btn btn-success" onclick="javascript: return formreset(('<?php echo $witnessrow['witness']['id']; ?>'));"><?php echo __('lblreset'); ?></button>
                                        <?php } else {
                                            ?>
                                            <button type="button"  id='btncap' class="btn btn-success disabled"><?php echo __('lblreset'); ?></button>
                                            <?php
                                        }
                                        // lock button
                                        if ($lockflag == 1) {
                                            ?>
                                            <a href="<?php echo $this->webroot; ?>Registration/document_witness/<?php echo $witnessrow['witness']['witness_id'] . "/" . $this->Session->read("csrftoken"); ?>" class="btn btn-primary"><?php echo __('lbllock'); ?></a>
                                        <?php } else {
                                            ?>
                                            <a href="" class="btn btn-primary disabled"><?php echo __('lbllock'); ?></a> 
                                        <?php } ?>


                                    <?php } // lock  ?>  

                                </td>
                            </tr> 
                            <?php
                        }
                        ?>
                    </tbody>

                </table>
            </div>
            <div class="panel panel-footer " > 
                <div class="center">     
                    <?php
                    echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken")));
                    ?>

                    <?php
                    if ($documents[0][0][$funflag] == 'N' && $btnaccept == 1) {
                        echo $this->Form->input(__($btnaccept_label), array('type' => 'submit', 'name' => 'btnaccept', 'id' => 'btnaccept', 'label' => FALSE, 'class' => 'smartbtn smartbtn-success'));
                    } else {
                        echo $this->Form->button(__($btnaccept_label), array('type' => 'button', 'name' => 'btnaccept', 'id' => 'btnaccept', 'label' => FALSE, 'class' => 'smartbtn smartbtn-disabled'));
                    }
                    ?>
                </div>
            </div> 
            <div id="modal" style="display: none;" align = "center">
                <div class="box-body">
                    <div class="row">
                        <div class="form-group">
                            <center>
                                <img border="2" id="FPImage1" alt="Fingerpint Image" height=250 width=180 src="" > <br><br>
                                <input type="button" value="<?php echo __('lblcapturefingurprint'); ?>" onclick="captureFP()"> <br>
                                <br>
                                <div style=" color:black; padding:20px;">
                                    <p id="result"> </p>
                                </div>
                            </center>
                        </div>
                    </div>
                    <div class="row" style="text-align: center">
                        <div class="form-group">
                            <div class="col-sm-12 tdadd">
                                <button id="btnsavebio" name="btnsave" class="btn btn-info " style="text-align: center;" onclick="javascript: return Save();">
                                    <span class="glyphicon glyphicon-plus"></span><?php echo __('btnsave'); ?></button>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div id="photomodal" class="modal fade MyModel100" role="dialog">
                <div class="modal-dialog modal-lg MyModel40">
                    <!-- Modal content-->
                    <div class="modal-content MyModel100">
                        <div class="modal-header MyModel40">
                            <button type="button" class="close Margin" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Photo Capture</h4>                
                            <!--<h5 class="modal-title" style="color: red">Best View in Mozilla Firefox only...!!!</h5>-->
                        </div>
                        <div class="modal-body MyModel100" id="divcap">
                            <div class="row" style="text-align: center">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <center>
                                            <div id="my_camera" style="width:300px; height:220px;"></div><br>
                                            <input type="button" value="Take Snapshot" onclick="take_snapshot()"> 
                                            <br><br>
                                            <div id="my_result"></div>
                                            <script language="JavaScript">

                                                function take_snapshot() {
                                                    
                                                    Webcam.snap(function (data_uri) {
                                                        document.getElementById('my_result').innerHTML = '<img src="' + data_uri + '"/>';
                                                        $('#pic').val(data_uri);
                                                        document.getElementById("btnsavephoto").disabled = false;
                                                    });

                                                }
                                            </script>
                                        </center>
                                    </div>
                                    <div class="col-sm-12">&nbsp;</div>
                                    <div class="col-sm-12">&nbsp;</div>
                                    <div class="col-sm-12">
                                        <button id="btnsavephoto" name="btnsavephoto" class="btn btn-info" style="text-align: center;" onclick="javascript: return Savepic();">
                                            <span class="glyphicon glyphicon-plus"></span><?php echo __('btnsave'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="row" style="text-align: center">
                                <div class="form-group">
                                    <div class="col-sm-12 tdadd">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>  
    <input type='hidden' value='<?php echo $actiontype; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $cap; ?>' name='cap' id='cap'/>
    <input type='hidden' value='<?php echo $pic; ?>' name='pic' id='pic'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php echo $hfimg; ?>' name='hfimg' id='hfimg'/>
    <input type='hidden' value='<?php echo $biometserverflag; ?>' name='biometserverflag' id='biometserverflag'/>
</div>

<?php echo $this->Form->end(); ?>

<script>
    $(document).ready(function () {
        $('#Doclist').DataTable();
    });

</script>
<?php
foreach ($witness as $witnessrow) {

    if ($witnessrow['witness']['record_lock'] == 'N') {
        ?> 


        <div id="otheroptions<?php echo $witnessrow['witness']['witness_id']; ?>" class="my-popup modal fade in" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content row">
                    <div class="modal-header custom-modal-header">
                        <button type="button" class="close" data-dismiss="modal">×</button>
                        <h4 class="modal-title"><?php echo __('lblotheroptions'); ?></h4>
                    </div>
                    <div class="modal-body">
                        <?php echo $this->Form->create('other_options', array('url' => array('controller' => 'Registration', 'action' => 'document_witness'), 'id' => 'other_options', 'class' => 'form-vertical')); ?>   
                        <div class="form-group col-sm-12">
                            <?php
                            echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken")));
                            echo $this->Form->input('optionsid', array('type' => 'hidden', 'id' => 'optionsid', 'class' => 'form-control input-sm', 'label' => false, 'value' => $witnessrow['witness']['witness_id']));
                            $checkedflag = '';
                            if ($witnessrow['witness']['camera_working_flag'] == 'N') {
                                $checkedflag = "checked=checked";
                            }
                            ?> 
                            <label><input type="checkbox" name="data[other_options][camera_working_flag]" value="1" <?php echo @$checkedflag; ?> ><?php echo __('lblcameranotworking'); ?></label> <br>
                            <?php
                            $checkedflag = '';
                            if ($witnessrow['witness']['biodevice_working_flag'] == 'N') {
                                $checkedflag = "checked=checked";
                            }
                            ?>
                            <label><input type="checkbox" name="data[other_options][biodevice_working_flag]" value="1" <?php echo @$checkedflag; ?>><?php echo __('lblbiodevicenotworking'); ?></label> <br>

                        </div> 
                        <div class="form-group col-sm-12">
                            <button type="submit" class="btn btn-default pull-right"><?php echo __('btnsubmit'); ?></button>
                        </div>
        <?php echo $this->Form->end(); ?>   
                    </div>

                </div>

            </div>
        </div>


    <?php }
} ?>