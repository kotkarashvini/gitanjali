<?php
echo $this->Html->script('Device/webcam.js');
?>


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
		 if(hfserver=='Y'){
             var uri = "https://SGIWEBSRV:8000/SGIFPCapture";
        }else{
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
        xmlhttp.send();
    }
</script>

<script type="text/javascript">
    function formphoto(id) {
        $("#photo").dialog({
            modal: true,
            autoOpen: false,
            title: "Photo Capture",
            width: 350,
            height: 550
        });
        $('#photo').dialog('open');

        $('#hfid').val(id);
        Webcam.attach('#my_camera');
        Webcam.set({
            width: 200,
            height: 160,
            dest_width: 200,
            dest_height: 160,
            image_format: 'jpeg',
            jpeg_quality: 90,
            force_flash: false
        });
    }

    function Savepic() {
        document.getElementById("actiontype").value = '3';
        $('#party').submit();
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
    }
    function formreset(id) {
        document.getElementById("actiontype").value = '2';
        $('#hfid').val(id);
        $('#party').submit();
    }
    function Save() {
        document.getElementById("actiontype").value = '1';
        $('#party').submit();
    }
</script>

<?php
echo $this->element("Registration/main_menu");
?>
<br>
<?php echo $this->Form->create('party', array('id' => 'party', 'autocomplete' => 'off')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <?php echo __('lbltokenno'); ?> : <?php if(isset($documents[0][0]['token_no'])){ echo $documents[0][0]['token_no'];} ?>
                <div class="pull-right action-buttons">
                    <div class="btn-group pull-right"> 
                        <?php echo __('lbldocrno'); ?>: <?php if(isset($documents[0][0]['doc_reg_no'])){ echo $documents[0][0]['doc_reg_no'];} ?>                      
                    </div>
                </div>
            </div>
            <div class="box-heading">
                <center><h3 class="box-title headbolder"><?php echo __('lbladmissionlists'); ?></h3></center>
            </div>


            <div class="box-body">
                <table class="table table-striped table-bordered table-hover" id="Doclist">
                    <thead>
                        <tr>
                            <th style="text-align: center;"><?php echo __('lblsrno'); ?></th> 
                            <th style="text-align: center;"><?php echo __('lblpartyfullname'); ?></th>
                            <th style="text-align: center;"><?php echo __('lblgender'); ?></th>
                            <th style="text-align: center;"><?php echo __('lbldob'); ?></th>
                            <th style="text-align: center;"><?php echo __('lblage'); ?></th>
                           <!--<th style="text-align: center;">Is Executer(Yes/No)</th>-->
                            <th style="text-align: center;"><?php echo __('lblphoto'); ?></th>
                            <th style="text-align: center;"><?php echo __('lblfinger'); ?></th>
                            <th style="text-align: center;"><?php echo __('lblaction'); ?></th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $counter = 0;
                        $btnaccept = 1;

                        foreach ($partylist as $party) {
                            // pr($party);exit;
                            $lockflag = 1;
                            if ($party[0]['is_executer'] == 'Y') {
                                ?>
                                <tr >
                                    <td scope="row" style="text-align: center; font-weight:bold;"><?php echo ++$counter; ?></td>
                                    <td style="text-align: center;"><?php echo $party[0]['party_full_name_' . $language]; ?></td>
                                    <td style="text-align: center;"><?php echo $party[0]['gender_desc_' . $language]; ?></td>
                                    <td style="text-align: center;"><?php
                                        if (!empty($party[0]['dob'])) {
                                            $date = date_create($party[0]['dob']);
                                            echo date_format($date, 'd M Y');
                                        }
                                        ?></td>
                                    <td style="text-align: center;"><?php echo $party[0]['age']; ?></td>

                                    <td style="text-align: center;">  

                                        <?php
                                        $imagedata = $path['file_config']['filepath'] . $party[0]['photo_img'];
                                        if ($party[0]['photo_img'] != null && file_exists($imagedata)) {
                                            $image = file_get_contents($imagedata);
                                            $image_codes = base64_encode($image);
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
                                        $imagedata1 = $path['file_config']['filepath'] . $party[0]['biometric_img'];
                                        if ($party[0]['biometric_img'] != null && file_exists($imagedata1)) {
                                            $image1 = file_get_contents($imagedata1);
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

                                        <?php if ($party[0]['record_lock'] == 'Y') {
                                            echo __('lbllocked');
                                        } else { ?>


                                            <?php if ($party[0]['photo_img'] != '') {
                                                ?>    <button type="button"  id='btncap' class="btn btn-primary disabled"><?php echo __('lblphotocapture'); ?></button>
                                            <?php } else {
                                                ?>
                                                <button type="button"  id='btncap' class="btn btn-primary" onclick="javascript: return formphoto(('<?php echo $party[0]['id']; ?>'));"><?php echo __('lblphotocapture'); ?></button>
                                            <?php } ?>
                                            <?php if ($party[0]['biometric_img'] != '') {
                                                ?>    <button type="button"  id='btncap' class="btn btn-warning disabled"><?php echo __('lblfingercapture'); ?></button>
                                            <?php } else {
                                                ?>
                                                <button type="button"  id='btncap' class="btn btn-warning" onclick="javascript: return formsave(('<?php echo $party[0]['id']; ?>'));"><?php echo __('lblfingercapture'); ?></button>
                                            <?php } ?>
                                            <?php if ($party[0]['biometric_img'] != '' || $party[0]['photo_img'] != '') {
                                                ?>    <button type="button"  id='btncap' class="btn btn-success" onclick="javascript: return formreset(('<?php echo $party[0]['id']; ?>'));"><?php echo __('lblreset'); ?></button>
                                            <?php } else {
                                                ?>
                                                <button type="button"  id='btncap' class="btn btn-success disabled"><?php echo __('lblreset'); ?></button>
                                            <?php
                                            }
                                            // lock button
                                            if ($lockflag == 1) {
                                                ?>
                                                <a href="<?php echo $this->webroot; ?>Registration/party/<?php echo $party[0]['party_id'] . "/" . $this->Session->read("csrftoken"); ?>" class="btn btn-primary"><?php echo __('lbllock'); ?></a>
                                                <?php } else {
                                                ?>
                                                <a href="" class="btn btn-primary disabled"><?php echo __('lbllock'); ?></a> 
                                            <?php } ?>


        <?php } // lock  ?>  
                                   

                                    </td>
                                </tr> 
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
                <hr style='border:1px #3c8dbc solid;'> 

  <div class="row">

                    <div class="form-group">

                         <label for="" class="col-sm-3 control-label"><?php echo __('Remark'); ?><span style="color: #ff0000">*</span></label>    
                         <div class="col-sm-3" ><?php echo $this->Form->input('remark', array('label' => false, 'id' => 'remark', 'class' => 'form-control input-sm', 'type' => 'text')); ?></div>
                        <label for="" class="col-sm-3 control-label"><?php echo __('Home Visit Date'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3" ><?php echo $this->Form->input('visit_date', array('label' => false, 'id' => 'visit_date', 'class' => 'form-control input-sm', 'type' => 'text')); ?>
                           
                        </div>

                    </div>
                </div>
            </div>
            
           
            <div class="panel panel-footer center" >
                <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>
               
                <?php
               
                    echo $this->Form->input(__('Save'), array('type' => 'submit', 'name' => 'btnaccept', 'label' => FALSE, 'class' => 'smartbtn smartbtn-success'));
               ?>
            </div> 

            <div id="modal" style="display: none;" align = "center">
                <div class="box-body">
                    <div class="row">
                        <div class="form-group">
                            <center>
                                <img border="2" id="FPImage1" alt="Fingerpint Image" height=250 width=180 src="" > <br><br>
                                <input type="button" value="Capture Fingure Print" onclick="captureFP()"> <br>
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

                                <button id="btnsave" name="btnsave" class="btn btn-info " style="text-align: center;" onclick="javascript: return Save();">
                                    <span class="glyphicon glyphicon-plus"></span><?php echo __('btnsave'); ?></button>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div id="photo" style="display: none;" align = "center">
                <div class="box-body">
                    <div class="row">
                        <div class="form-group">
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
                                        });

                                    }
                                </script>
                            </center>
                        </div>
                    </div>
                    <div class="row" style="text-align: center">
                        <div class="form-group">
                            <div class="col-sm-12 tdadd">

                                <button id="btnsave" name="btnsave" class="btn btn-info " style="text-align: center;" onclick="javascript: return Savepic();">
                                    <span class="glyphicon glyphicon-plus"></span><?php echo __('btnsave'); ?></button>

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
        
        $('#visit_date').datepicker({
            todayBtn: "linked",
            language: "it",
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy"
        });
        
        
        $('#Doclist').DataTable();
    });

</script>