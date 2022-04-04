<?php
echo $this->Html->script('Device/webcam.js');
echo $this->Html->script('jquery.dataTables');
echo $this->Html->script('dataTables.bootstrap');
echo $this->Html->script('JS');
?>


<script type="text/javascript">
    function take_pending_remark(id) {

        if ($('#admission_pending_flag' + id).is(':checked')) {
            $('#divadmission_pending_remark' + id).show();
        } else {
            $('#divadmission_pending_remark' + id).hide();
        }
    }
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
            var uri = "https://SGIWEBSRV:8000/SGIFPCapture";
//             var secugen_lic = "NTommEhS08t44kdRsZsKLRrHxuLlFDkfD84Sb8zyAlo=";
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

<script>
    var params = "";
    function Info()
    {
        var uri = "https://127.0.0.1:11100";
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function ()
        {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
            {
                SucessInfo(xmlhttp.responseText);
                $("#block ").val(xmlhttp.responseText);
            }
            else if (xmlhttp.status == 404)
            {
                failCall(xmlhttp.status)
            }
            else if (xmlhttp.status == 503)
            {
                failCall(xmlhttp.status)
            }
        }
        xmlhttp.onerror = function ()
        {
            failCall(xmlhttp.status);
        }
        xmlhttp.onabort = function ()
        {
            alert("Aborted");
        }
        xmlhttp.open("RDSERVICE", uri, true);
        xmlhttp.send();

    }
    function Capture( )
    {

        var uri = "https://localhost:11100/rd/capture";
        // params  += "&PidXML=" + encodeURIComponent(enc_pid_b64);
//		params = '<PidOptions ver="1.0"> <Opts fCount="1"  format="0" pidVer="2.0" timeout="20000" env="S" posh="LEFT_INDEX" />';
//		params += '</PidOptions>';
        var params = '<PidOptions ver="1.0"> <Opts fCount="1" format="0" pidVer="2.0" timeout="20000" env="P" posh="LEFT_INDEX" wadh="';
        params += "<?php echo $wadh; ?>";
        params += '" />';
        params += '</PidOptions>';

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function ()
        {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
            {
                SucessInfo(xmlhttp.responseText);
                $("#block ").val(xmlhttp.responseText);
//                                var data = xmlhttp.responseText;
//                            var oString = XMLtoString(data); 
//                             alert(oString);return false;
                $("#hfxml").val(xmlhttp.responseText);
//                            $("#hfxml").val(oString);
//                            alert(oString);

            }
            else if (xmlhttp.status == 404)
            {
                failCall(xmlhttp.status)
            }
            else if (xmlhttp.status == 503)
            {
                alert("server Unavailable");
            }
        }
        xmlhttp.onerror = function ()
        {
            failCall(xmlhttp.status);
        }
        xmlhttp.onabort = function ()
        {
            alert("Aborted");
        }
        xmlhttp.open("CAPTURE", uri, true);
        //xmlhttp.send(encodeURIComponent(params));
        xmlhttp.send(params);
    }
    function DriverInfo( )
    {
        var uri = "https://localhost:11100/rd/info";
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function ()
        {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
            {
                SucessInfo(xmlhttp.responseText);
            }
            else if (xmlhttp.status == 404)
            {
                failCall(xmlhttp.status)
            }
            else if (xmlhttp.status == 503)
            {
                failCall(xmlhttp.status)
            }
        }
        xmlhttp.onerror = function ()
        {
            failCall(xmlhttp.status);
        }
        xmlhttp.onabort = function ()
        {
            alert("Aborted");
        }
        xmlhttp.open("DEVICEINFO", uri, true);
        xmlhttp.send();
    }
    function	SucessInfo(result)
    {
        alert(result);
        $("#btnauth").prop('disabled', false);
    }

    function	failCall(status)
    {

        /* 	
         If you reach here, user is probabaly not running the 
         service. Redirect the user to a page where he can download the
         executable and install it. 
         */
        alert("Check if RDSERVICE is running ");

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
        document.getElementById("btnsavephoto").disabled = true;
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
        document.getElementById("btnsavebio").disabled = true;
    }
    function formreset(id) {
//        alert(id);
        document.getElementById("actiontype").value = '2';
        $('#hfid').val(id);

        $('#party').submit();
    }
    function Save() {
        document.getElementById("actiontype").value = '1';

        $('#party').submit();
    }
    function formPan(party_id) {
        $.post("<?php echo $this->webroot; ?>Registration/party_pan_verification", {party_id: party_id}, function (response, status) {
            $('#pandetails').html(response);
            $('#panmodal').modal('show');
        });
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
?>
<br>

<div class="modal"  id="panmodal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Pan  Details</div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="pandetails">
                <p>Please wait..</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>                
            </div>
        </div>
    </div>
</div>


<?php echo $this->Form->create('party', array('id' => 'party', 'autocomplete' => 'off')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="row center" > <p id="displayResult" style="color: red; font-weight: bold; font-size: 14pt"></p></div>
            <div class="row left" >
                <div class="col-sm-12">
                    <?php
                    if ($documents[0][0][$funflag] == 'N') {
                        ?>
                        <a href="<?php echo $this->webroot; ?>Citizenentry/party_entry/<?php echo $this->Session->read('csrftoken'); ?>" class="btn btn-info ">
                            <button type="button" style="width: 200px;"   class="btn btn-info" value="Add Power Of Attorney" >
                                <?php echo __('Add Power Of Attorney'); ?>
                            </button>
                        </a>
                    <?php } ?>
                </div>
            </div>
            <div class="box-header with-border">
                <?php echo __('lbltokenno'); ?> : <?php echo $documents[0][0]['token_no']; ?>
                <div class="pull-right action-buttons">
                    <div class="btn-group pull-right"> 
                        <?php echo __('lbldocrno'); ?>: <?php echo $documents[0][0]['doc_reg_no']; ?>                      
                    </div>
                </div>
            </div>
            <div class="box-header with-border">
                <h3 class="box-title headbolder"></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal">Party Info</button>
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

                            <th style="text-align: center;"><?php echo __('lblage'); ?></th>
                            <th class="center"><?php echo __('lblpartytypeshow'); ?></th>
                            <th style="text-align: center;"><?php echo __('lblpartycategoryshow'); ?> </th>
                            <th style="text-align: center;"><?php echo __('lblphoto'); ?></th>
                            <?php if ($fivefinger == 'Y') { ?>
                                <th style="text-align: center;"><?php echo __('Thumb'); ?></th>
                                <th style="text-align: center;"><?php echo __('Index Finge'); ?></th>
                                <th style="text-align: center;"><?php echo __('Middle Finger'); ?></th>
                                <th style="text-align: center;"><?php echo __('Ring Finger'); ?></th>
                                <th style="text-align: center;"><?php echo __('baby finger'); ?></th>

                            <?php } else { ?>
                                <th style="text-align: center;"><?php echo __('Thumb'); ?></th>
                            <?php } ?>
                            <th style="text-align: center;"><?php echo __('lblaction'); ?></th>

                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $counter = 0;
                        $btnaccept = 1;

                        foreach ($partylist as $party) {
//                          pr($party);exit;
                            $lockflag = 1;
                            if ($party[0]['home_visit_flag'] == 'N') {
                                if ($party[0]['is_executer'] == 'Y' || $party[0]['presenty_require'] == 'Y') {
                                    ?>
                                    <tr >
                                        <td scope="row" style="text-align: center; font-weight:bold;"><?php echo ++$counter; ?></td>
                                        <td style="text-align: center;"><?php
                                            if (empty($party[0]['representive_full_name_en'])) {
                                                echo $party[0]['party_full_name_' . $language];
                                            } else {
                                                echo $party[0]['representive_full_name_' . $language];
                                            }
                                            ?></td>
                                        <td style="text-align: center;"><?php echo $party[0]['gender_desc_' . $language]; ?></td>

                                        <td style="text-align: center;"><?php echo $party[0]['age']; ?></td>
                                        <td style="text-align: center;"><?php echo $party[0]['party_type_desc_' . $language]; ?></td>

                                        <td style="text-align: center;"><?php echo $party[0]['category_name_' . $language]; ?></td>


                                        <td style="text-align: center;">  

                                            <?php
                                            $imagedata = $path['file_config']['filepath'] . $party[0]['photo_img'];
                                            if ($party[0]['photo_img'] != null && file_exists($imagedata)) {
                                                $image = file_get_contents($imagedata);
                                                $image_codes = base64_encode($image);
                                            } else if ($party[0]['camera_working_flag'] == 'N') {
                                                $image1 = file_get_contents('img/camera_cross.png', true);
                                                $image_codes = base64_encode($image1);
                                            } else if ($party[0]['admission_pending_flag'] == 'Y') {
                                                $image1 = file_get_contents('img/pending.jpg', true);
                                                $image_codes = base64_encode($image1);
                                                $btnaccept = 0;
                                            } else {
                                                $image_codes = null;
                                                $btnaccept = 0;
                                                $lockflag = 0;
                                            }
                                            ?>   

                                            <image src="data:image/jpg;charset=utf-8;base64,<?php echo $image_codes; ?>" height="70" width="70" />

                                        </td>
                                        <?php if ($fivefinger == 'Y') { ?>
                                            <td style="text-align: center;">

                                                <?php
                                                $imagedata1 = $path['file_config']['filepath'] . $party[0]['biometric_img'];

                                                if ($party[0]['biometric_img'] != null && file_exists($imagedata1)) {
                                                    $image1 = file_get_contents($imagedata1);
                                                    $image_codes1 = base64_encode($image1);
                                                } else if ($party[0]['biodevice_working_flag'] == 'N') {
                                                    $image1 = file_get_contents('img/fingerprint-cross.png', true);
                                                    $image_codes1 = base64_encode($image1);
                                                } else if ($party[0]['admission_pending_flag'] == 'Y') {
                                                    $image1 = file_get_contents('img/pending.jpg', true);
                                                    $image_codes1 = base64_encode($image1);
                                                    $btnaccept = 0;
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
                                                $imagedata2 = $path['file_config']['filepath'] . $party[0]['biometric_img2'];
                                                if ($party[0]['biometric_img2'] != null && file_exists($imagedata2)) {
                                                    $image2 = file_get_contents($imagedata2);
                                                    $image_codes2 = base64_encode($image2);
                                                } else if ($party[0]['biodevice_working_flag2'] == 'N') {
                                                    $image2 = file_get_contents('img/fingerprint-cross.png', true);
                                                    $image_codes2 = base64_encode($image2);
                                                } else if ($party[0]['admission_pending_flag'] == 'Y') {
                                                    $image2 = file_get_contents('img/pending.jpg', true);
                                                    $image_codes2 = base64_encode($image2);
                                                    $btnaccept = 0;
                                                } else {
                                                    $image_codes2 = null;
                                                    $btnaccept = 0;
                                                    $lockflag = 0;
                                                }
                                                ?>                                  
                                                <image src="data:image/jpg;charset=utf-8;base64,<?php echo $image_codes2; ?>" height="70" width="70" />

                                            </td>

                                            <td style="text-align: center;">

                                                <?php
                                                $imagedata3 = $path['file_config']['filepath'] . $party[0]['biometric_img3'];
                                                if ($party[0]['biometric_img3'] != null && file_exists($imagedata3)) {
                                                    $image3 = file_get_contents($imagedata3);
                                                    $image_codes3 = base64_encode($image3);
                                                } else if ($party[0]['biodevice_working_flag3'] == 'N') {
                                                    $image3 = file_get_contents('img/fingerprint-cross.png', true);
                                                    $image_codes3 = base64_encode($image3);
                                                } else if ($party[0]['admission_pending_flag'] == 'Y') {
                                                    $image3 = file_get_contents('img/pending.jpg', true);
                                                    $image_codes3 = base64_encode($image3);
                                                    $btnaccept = 0;
                                                } else {
                                                    $image_codes3 = null;
                                                    $btnaccept = 0;
                                                    $lockflag = 0;
                                                }
                                                ?>                                  
                                                <image src="data:image/jpg;charset=utf-8;base64,<?php echo $image_codes3; ?>" height="70" width="70" />

                                            </td>

                                            <td style="text-align: center;">

                                                <?php
                                                $imagedata4 = $path['file_config']['filepath'] . $party[0]['biometric_img4'];
                                                if ($party[0]['biometric_img4'] != null && file_exists($imagedata4)) {
                                                    $image4 = file_get_contents($imagedata4);
                                                    $image_codes4 = base64_encode($image4);
                                                } else if ($party[0]['biodevice_working_flag4'] == 'N') {
                                                    $image4 = file_get_contents('img/fingerprint-cross.png', true);
                                                    $image_codes4 = base64_encode($image4);
                                                } else if ($party[0]['admission_pending_flag'] == 'Y') {
                                                    $image4 = file_get_contents('img/pending.jpg', true);
                                                    $image_codes4 = base64_encode($image4);
                                                    $btnaccept = 0;
                                                } else {
                                                    $image_codes4 = null;
                                                    $btnaccept = 0;
                                                    $lockflag = 0;
                                                }
                                                ?>                                  
                                                <image src="data:image/jpg;charset=utf-8;base64,<?php echo $image_codes4; ?>" height="70" width="70" />

                                            </td>

                                            <td style="text-align: center;">

                                                <?php
                                                $imagedata5 = $path['file_config']['filepath'] . $party[0]['biometric_img5'];
                                                if ($party[0]['biometric_img5'] != null && file_exists($imagedata5)) {
                                                    $image5 = file_get_contents($imagedata5);
                                                    $image_codes5 = base64_encode($image5);
                                                } else if ($party[0]['biodevice_working_flag5'] == 'N') {
                                                    $image5 = file_get_contents('img/fingerprint-cross.png', true);
                                                    $image_codes5 = base64_encode($image5);
                                                } else if ($party[0]['admission_pending_flag'] == 'Y') {
                                                    $image5 = file_get_contents('img/pending.jpg', true);
                                                    $image_codes5 = base64_encode($image5);
                                                    $btnaccept = 0;
                                                } else {
                                                    $image_codes5 = null;
                                                    $btnaccept = 0;
                                                    $lockflag = 0;
                                                }
                                                ?>                                  
                                                <image src="data:image/jpg;charset=utf-8;base64,<?php echo $image_codes5; ?>" height="70" width="70" />

                                            </td>
                                        <?php } else { ?>


                                            <td style="text-align: center;">

                                                <?php
                                                $imagedata1 = $path['file_config']['filepath'] . $party[0]['biometric_img'];

                                                if ($party[0]['biometric_img'] != null && file_exists($imagedata1)) {
                                                    $image1 = file_get_contents($imagedata1);
                                                    $image_codes1 = base64_encode($image1);
                                                } else if ($party[0]['biodevice_working_flag'] == 'N') {
                                                    $image1 = file_get_contents('img/fingerprint-cross.png', true);
                                                    $image_codes1 = base64_encode($image1);
                                                } else if ($party[0]['admission_pending_flag'] == 'Y') {
                                                    $image1 = file_get_contents('img/pending.jpg', true);
                                                    $image_codes1 = base64_encode($image1);
                                                    $btnaccept = 0;
                                                } else {
                                                    $image_codes1 = null;
                                                    $btnaccept = 0;
                                                    $lockflag = 0;
                                                }
                                                ?>                                  
                                                <image src="data:image/jpg;charset=utf-8;base64,<?php echo $image_codes1; ?>" height="70" width="70" />

                                            </td>
                                        <?php } ?>



                                        <td style="text-align: center;">

                                            <?php
                                            if ($party[0]['record_lock'] == 'Y') {
                                                echo __('lbllocked');
                                            } else {
                                                ?>

                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#otheroptions<?php echo $party[0]['party_id']; ?>">
                                                    <?php echo __('lbloptions'); ?>
                                                </button>
                                                <?php if ($party[0]['photo_img'] != '') {
                                                    ?>    <button type="button"  id='btncap' class="btn btn-primary disabled"><?php echo __('lblphotocapture'); ?></button>
                                                <?php } else {
                                                    ?>
                                                    <button type="button"  id='btncap' class="btn btn-primary" onclick="javascript: return formphoto(('<?php echo $party[0]['id']; ?>'));"><?php echo __('lblphotocapture'); ?></button>
                                                <?php } ?>

                                                <?php if ($fivefinger == 'Y') { ?>
                                                    <?php if ($party[0]['biometric_img'] != '' && $party[0]['biometric_img2'] != '' && $party[0]['biometric_img3'] != '' && $party[0]['biometric_img4'] != '' && $party[0]['biometric_img5'] != '') {
                                                        ?>    <button type="button"  id='btncap' class="btn btn-warning disabled"><?php echo __('lblfingercapture'); ?></button>
                                                    <?php } else {
                                                        ?>
                                                        <button type="button"  id='btncap' class="btn btn-warning" onclick="javascript: return formsave(('<?php echo $party[0]['id']; ?>'));"><?php echo __('lblfingercapture'); ?></button>
                                                    <?php } ?>
                                                <?php } else { ?>

                                                    <?php if ($party[0]['biometric_img'] != '') {
                                                        ?>    <button type="button"  id='btncap' class="btn btn-warning disabled"><?php echo __('lblfingercapture'); ?></button>
                                                    <?php } else {
                                                        ?>
                                                        <button type="button"  id='btncap' class="btn btn-warning" onclick="javascript: return formsave(('<?php echo $party[0]['id']; ?>'));"><?php echo __('lblfingercapture'); ?></button>
                                                    <?php } ?>
                                                <?php } ?>


                                                <?php if ($fivefinger == 'Y') { ?>
                                                    <?php if ($party[0]['biometric_img'] != '' || $party[0]['biometric_img2'] != '' || $party[0]['biometric_img3'] != '' || $party[0]['biometric_img4'] != '' || $party[0]['biometric_img5'] != '' || $party[0]['photo_img'] != '') {
                                                        ?>    <button type="button"  id='btncap' class="btn btn-success" onclick="javascript: return formreset(('<?php echo $party[0]['id']; ?>'));"><?php echo __('lblreset'); ?></button>
                                                    <?php } else {
                                                        ?>
                                                        <button type="button"  id='btncap' class="btn btn-success disabled"><?php echo __('lblreset'); ?></button>
                                                        <?php
                                                    }
                                                } else {
                                                    if ($party[0]['biometric_img'] != '' || $party[0]['photo_img'] != '') {
                                                        ?>    <button type="button"  id='btncap' class="btn btn-success" onclick="javascript: return formreset(('<?php echo $party[0]['id']; ?>'));"><?php echo __('lblreset'); ?></button>
                                                    <?php } else {
                                                        ?>
                                                        <button type="button"  id='btncap' class="btn btn-success disabled"><?php echo __('lblreset'); ?></button>
                                                        <?php
                                                    }
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
                                            <?php
                                            $uid = $party[0]['uid'];
                                            $variables = $this->requestAction('/App/dec/' . $uid);
                                            //$uid= $this->request->($party[0]['uid']);
//                              echo $variables;
//                               exit;
                                            ?>
                                            <!--<button type="button"  id='btncap' class="btn btn-info" onclick="javascript: return formEkyc('<?php //echo $party[0]['party_id']; ?>', '<?php //echo $variables; ?>');"><?php //echo 'EKYC(Biometric)'; ?></button>-->
<!--                                            <?php //if ($_SESSION['Auth']['User']['user_id'] == '6') { ?>
                                                <button type="button"  id='btncap' class="btn btn-info" onclick="javascript: return otpEkyc('<?php //echo $party[0]['id']; ?>', '<?php //echo $variables; ?>');"><?php //echo 'EKYC(OTP)'; ?></button>
                                            <?php // } ?>   -->
                                           <div class="dropdown">
                                               <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                   EKYC  
                                               </button> 
                                               <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                   <li><a href="#" onclick="javascript: return formEkyc('<?php echo $party[0]['party_id']; ?>', '<?php echo $variables; ?>');"> Biomatric</a></li> 
                                                 <li>  <a  href="#" onclick="javascript: return otpEkyc('<?php echo $party[0]['party_id']; ?>', '<?php echo $variables; ?>');">OTP</a> </li>
                                                 <!--<li>  <a  href="#">Something else here</a></li>-->
                                               </ul>
                                           </div>

                                            <?php if ($btnpan && $party[0]['pan_verified'] == 'N' && !is_null($party[0]['pan_no'])) { ?>
                                                <button type="button"  id='verifypan' class="btn btn-info" onclick="javascript: return formPan('<?php echo $party[0]['party_id']; ?>')"><?php echo __('lblverifypan'); ?></button>

                                            <?php } elseif ($btnpan && $party[0]['pan_verified'] == 'Y' && !is_null($party[0]['pan_no'])) { ?>
                                                <button type="button"  id='verifypan' class="btn btn-success" ><?php echo __('lblpanverified'); ?></button>
                                            <?php } ?>         



                                        </td>
                                    </tr> 
                                    <?php
                                }
                            }
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
                <label for="fingerdescription_id" class="col-sm-4 control-label"><?php echo __('Select finger'); ?><span style="color: #ff0000">*</span></label> 
                <div class="col-sm-8">
                    <?php echo $this->Form->input('fingerdescription_id', array('label' => false, 'id' => 'fingerdescription_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $fingerdescription))); ?>
                    <span id="fingerdescription_id_error" class="form-error"><?php //echo $errarr['designation_id_error'];                   ?></span>
                </div>
                <div class="box-body">
                    <div class="form-group">

                        <div class="row">
                            <center>
                                <img border="2" id="FPImage1" alt="Fingerprint Image" height=250 width=180 src="" > <br><br>
                                <input type="button" value="Capture Finger Print" onclick="captureFP()"> <br>
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
            <div id="ekycmodal" class="modal fade MyModel100" role="dialog">
                <div class="modal-dialog modal-lg MyModel40">
                    <!-- Modal content-->
                    <div class="modal-content MyModel100">
                        <div class="modal-header MyModel40">
                            <button type="button" class="close Margin" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">EKYC Verification</h4>                
                            <!--<h5 class="modal-title" style="color: red">Best View in Mozilla Firefox only...!!!</h5>-->
                        </div>
                        <div class="modal-body MyModel100" id="divcap">
                            <div class="row" style="text-align: center">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label for="name" class="control-label"><b>Uid No : </b></label>
                                        <?php echo $this->Form->input('uidno', array('label' => false, 'type' => 'text', 'placeholder' => 'UID NO', 'id' => 'uidno')); ?>
                                        <div class="col-sm-12">&nbsp;</div>
                                        <label><input type="checkbox" name="uidathentication"  value="Y" checked >&nbsp;&nbsp;<?php echo 'Do You Agree with Terms and Condition of UID Authentication'; ?>
                                            <input type="hidden" name="uidathentication"  value="N" checked >
                                        </label> <br>
                                    </div>                    
                                    <div class="col-sm-12">&nbsp;</div>
                                    <div class="col-sm-12">&nbsp;</div>
                                    <div class="col-sm-12">
                                        <input class="control-label" type="button" value="<?php echo __('lblcapturefingurprint'); ?>" onclick="Capture()"> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="row" style="text-align: center">
                                <div class="form-group">
                                    <div class="col-sm-12 tdadd">
                                        <button type="submit" id="btnauth" name="btnauth" class="btn btn-info" style="text-align: center;" onclick="javascript: return authenticate();" disabled>
                                            <span class="glyphicon glyphicon-plus"></span>&nbsp;Authenticate</button>
                                            <!--<br> <center> <button type="button" onclick="Info()"> RDSERVICE INFO </button> </center> </br>-->
                                            <!--<br> <center> <button type="button" onclick="Capture()"> FP CAPTURE </button> </center> </br>-->
                                            <!--<br> <center> <button type="button" onclick="DriverInfo()"> DRIVER INFO </button> </center> </br>-->
                                        <?php // echo $this->Form->input('block', array('label' => false, 'type' => 'textarea', 'placeholder' => 'block', 'id' => 'block', 'class' => ' form-control input-xlarge'));   ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="otpekycmodal" class="modal fade MyModel100" role="dialog">
                <div class="modal-dialog modal-lg MyModel40">
                    <!-- Modal content-->
                    <div class="modal-content MyModel100">
                        <div class="modal-header MyModel40">
                            <button type="button" class="close Margin" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">EKYC Verification</h4>                
                            <!--<h5 class="modal-title" style="color: red">Best View in Mozilla Firefox only...!!!</h5>-->
                        </div>
                        <div class="modal-body MyModel100" id="divcap">
                            <div class="row" style="text-align: center">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label for="name" class="control-label"><b>Uid No : </b></label>
                                        <?php echo $this->Form->input('uidno', array('label' => false, 'type' => 'text', 'placeholder' => 'UID NO', 'id' => 'uidno1')); ?>
                                        <div class="col-sm-12">&nbsp;</div>
                                        <?php echo $this->Form->input('otp', array('label' => false, 'type' => 'text', 'placeholder' => 'OTP', 'style' => 'display:none', 'id' => 'otp')); ?> <br>
                                    </div>                    
                                    <!--                                    <div class="col-sm-12">&nbsp;</div>
                                                                        <div class="col-sm-12">&nbsp;</div>-->
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="row" style="text-align: center">
                                <div class="form-group">
                                    <div class="col-sm-12 tdadd">
                                        <button type="button" id="btnotp" name="btnotp" class="btn btn-info" style="text-align: center;" onclick="javascript: return forotp();">
                                            <span class="glyphicon glyphicon-plus"></span>&nbsp;Request OTP</button>
                                        <button type="submit" id="btnauthotp" name="btnauthotp" class="btn btn-info" style="text-align: center; display:none;" onclick="javascript: return otpauth();">
                                            <span class="glyphicon glyphicon-plus"></span>&nbsp;Authenticate</button>
                                    </div>
                                </div>
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
                                            document.getElementById("btnsavephoto").disabled = false;
                                        });

                                    }
                                </script>
                            </center>
                        </div>
                    </div>
                    <div class="row" style="text-align: center">
                        <div class="form-group">
                            <div class="col-sm-12 tdadd">

                                <button id="btnsavephoto" name="btnsave" class="btn btn-info " style="text-align: center;" onclick="javascript: return Savepic();">
                                    <span class="glyphicon glyphicon-plus"></span><?php echo __('btnsave'); ?></button>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div id="ekymyModal" class="modal fade MyModel100" role="dialog">
                <div class="modal-dialog modal-lg MyModel80">
                    <!-- Modal content-->
                    <div class="modal-content MyModel100">
                        <div class="modal-header MyModel80">
                            <button type="button" class="close Margin" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">EKYC Verification</h4>                
                            <!--<h5 class="modal-title" style="color: red">Best View in Mozilla Firefox only...!!!</h5>-->
                        </div>
                        <div class="modal-body MyModel100" id="divekyc">
                            <p>Data Loading...!!!!</p>
                        </div>
                        <div class="modal-footer">
                            <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
                            <div class="col-sm-12">
                                <div class="row" style="text-align: center">
                                    <div class="col-sm-2" style="height: 5px;">&nbsp;</div>
                                    <label for="name" class="col-sm-8 control-label"><b>Are you satisfied with this information.. ???? </b></label>  
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="row" style="text-align: center">
                                    <button type="button" id="btnyes" name="btnyes" value="Y" class="btn btn-info " style="text-align: center;">
                                        <span class="glyphicon glyphicon-thumbs-up"></span>&nbsp;Yes</button> &nbsp;&nbsp;&nbsp;&nbsp;
                                    <button type="button" id="btnno" name="btnyes" value="N" class="btn btn-info " style="text-align: center;">
                                        <span class="glyphicon glyphicon-thumbs-down"></span>&nbsp;No</button>
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
        <input type='hidden' value='<?php echo $hffinger; ?>' name='hffinger' id='hffinger'/>
        <input type='hidden' value='<?php echo $hfxml; ?>' name='hfxml' id='hfxml'/>
        <input type='hidden' value='<?php echo $txn; ?>' name='txn' id='txn'/>

        <input type='hidden' value='<?php echo $biometserverflag; ?>' name='biometserverflag' id='biometserverflag'/>
    </div>
    <?php echo $this->Form->end(); ?>
    <style>
        .MyModel100{    
            width:100%;
        }

        .MyModel80{    
            width:50%;
            /*width:100%;*/
        }

        .MyModel40{    
            width:40%;
            /*width:100%;*/
        }
        .Margin{
            margin-right:-320px;
        }
    </style>
    <script>
        $(document).ready(function () {
//            $(".modal").on("hidden.bs.modal", function () {
//                $(".modal-body").html("Data Loading...!!!");
//            });
            $("#btnyes,#btnno").click(function () {
                var id = $('#hfverificationid').val();
                var text = $(this).val();
                $.post('<?php echo $this->webroot; ?>Registration/sroaction', {text: text, id: id}, function (data)
                {

                });
                $('#ekymyModal').modal('toggle');
                return false;
            });
        });

        function formEkyc(id, uid) {
            $('#ekycmodal').modal('show');
            $('#hfid').val(id);
            $('#uidno').val(uid);
            return false;
        }


        function authenticate() {
            var flag = "B";
            var id = $('#hfid').val();
            var uid = $('#uidno').val();
            var capturexml = $('#hfxml').val();
            var consent;
            if ($('input[name=uidathentication]:checked').prop('checked') == true) {
                consent = $('input[name=uidathentication]:checked').val();
            } else {
                consent = "N";
            }
            //        var consent = $('input[name=uidathentication]:checked').val();
            $.post('<?php echo $this->webroot; ?>Registration/ekycverification', {flag: flag, id: id, uid: uid, capturexml: capturexml, consent: consent}, function (data)
            {
                if (data.indexOf("message") > -1) {
                    $('#ekycmodal').modal('toggle');
//                    $('#ekycmodal').modal('hide');
                    var obj = JSON.parse(data);
                    $('#displayResult').empty();
                    $('#displayResult').append(obj['message']);
                } else {
                    $('#ekycmodal').modal('toggle');
                    $('#ekymyModal').modal('show');
                    $("#divekyc").html('');
                    $("#divekyc").html(data);
                }

            });
//            if(modeldata != ''){
//            $('#myModal').modal('show');
//            }
            return false;
        }

        //Ekyc OTP

        function otpEkyc(id, uid) {
            $('#otpekycmodal').modal('show');
            $('#hfid').val(id);
            $('#uidno1').val(uid);
            return false;
        }
        function forotp() {
            var uid = $('#uidno1').val();
            $.post('<?php echo $this->webroot; ?>Registration/otpekyc', {uid: uid}, function (data)
            {
                var data = $.parseJSON(data);
                if (data['status'] == 'y') {
                    var txn = data['txn'];
                    var message = data['message'];
                    $('#txn').val(txn);
                    $("#otp").show();
                    $("#btnotp").hide();
                    $("#btnauthotp").show();
                    alert(message);
                } else {
                    var message = data['message'];
                    alert(message);
                }

            });
            return false;
        }

        function otpauth() {
            var flag = "O";
            var id = $('#hfid').val();
            var uid = $('#uidno1').val();
            var txn = $('#txn').val();
            var otp = $('#otp').val();

            $.post('<?php echo $this->webroot; ?>Registration/ekycverification', {flag: flag, id: id, uid: uid, txn: txn, otp: otp}, function (data)
            {
                if (data.indexOf("message") > -1) {
                    $('#otpekycmodal').modal('toggle');
//                    $('#ekycmodal').modal('hide');
                    var obj = JSON.parse(data);
                    $("#otp").val('');
                    $("#otp").hide();
                    $("#btnotp").show();
                    $("#btnauthotp").hide();
                    $('#displayResult').empty();
                    $('#displayResult').append(obj['message']);
                } else {
                    $("#otp").val('');
                    $("#otp").hide();
                    $("#btnotp").show();
                    $("#btnauthotp").hide();
                    $('#otpekycmodal').modal('toggle');
                    $('#ekymyModal').modal('show');
                    $("#divekyc").html('');
                    $("#divekyc").html(data);
                }

            });
//            if(modeldata != ''){
//            $('#myModal').modal('show');
//            }
            return false;
        }

    </script>
    <script>
        $(document).ready(function () {
            $('#Doclist').DataTable();
            $('#device_not_working').click(function () {
                if ($(this).is(':checked')) {
                    $('#btnaccept').attr("type", "submit");
                    $('#btnaccept').removeClass("smartbtn-disabled");
                    $('#btnaccept').addClass("smartbtn-success");
                } else {
                    $('#btnaccept').attr("type", "button");
                    $('#btnaccept').removeClass("smartbtn-success");
                    $('#btnaccept').addClass("smartbtn-disabled");
                }
            });

            $('#fingerdescription_id').change(function () {
                var fingerdescription = $("#fingerdescription_id option:selected").val();
                $('#hffinger').val(fingerdescription);
                //           alert(fingerdescription);

            });
        });

        function changepresenter(id) {


            //alert(id);
            //   var val = $('input[name=q12_3]:checked').val();
            var is_presenter = $('input[name=ispresenter_' + id + ']:checked').val();

            $.post('<?php echo $this->webroot; ?>Registration/ispresenter', {is_presenter: is_presenter, id: id}, function (data)
            {

                if (is_presenter == 'Y')
                {
                    alert('Party Set as Presenter..!');
                    $('input[name=ispresenter_' + id + ']').val('Y');
                } else {
                    $('input[name=ispresenter_' + id + ']').val('N');
                }
                //alert(data);
            });
        }


        function changeexecuter(id) {


            //alert(id);
            //   var val = $('input[name=q12_3]:checked').val();
            var is_executer = $('input[name=isexecuter_' + id + ']:checked').val();

            $.post('<?php echo $this->webroot; ?>Registration/isexecuter', {is_executer: is_executer, id: id}, function (data)
            {

                if (is_executer == 'Y')
                {
                    alert('Party Set As Executer..!');
                    $('input[name=isexecuter_' + id + ']').val('Y');
                } else {
                    $('input[name=isexecuter_' + id + ']').val('N');
                }
                //alert(data);
            });
        }

        function Saveparty() {

            $('#party').submit();
        }





    </script>

    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Party Info</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><?php echo __('lblsrno'); ?></th>
                                <th><?php echo __('lblpartyfullname'); ?></th>
                                <th><?php echo __('lblpartytypeshow'); ?></th>
                                <th><?php echo __('lblispresenter'); ?></th>
                                <th><?php echo __('lblisexecuter'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $counter = 0;
                            $btnaccept = 1;
// pr($partylist);exit;

                            foreach ($partylist as $party) {
                                $partyid = $party[0]['party_id'];
                                // pr($party);exit;
                                $lockflag = 1;
//                            if ($party[0]['is_executer'] == 'Y') {
                                ?>
                                <tr >
                                    <td scope="row" style="text-align: center; font-weight:bold;"><?php echo ++$counter; ?></td>
                                    <td style="text-align: center;"><?php echo $party[0]['party_full_name_' . $language]; ?></td>
                                    <td style="text-align: center;"><?php echo $party[0]['party_type_desc_' . $language]; ?></td>
                                    <td>

                                        <?php echo $this->Form->input('ispresenter', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'legend' => false, 'div' => false, 'id' => 'ispresenter_' . $party[0]['party_id'], 'name' => 'ispresenter_' . $party[0]['party_id'], 'value' => $party[0]['is_presenter'], 'onclick' => "changepresenter($partyid)")); ?>
                                    </td>
                                    <td>

                                        <?php echo $this->Form->input('isexecuter', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'legend' => false, 'div' => false, 'id' => 'isexecuter_' . $party[0]['party_id'], 'name' => 'isexecuter_' . $party[0]['party_id'], 'value' => $party[0]['is_executer'], 'onclick' => "changeexecuter($partyid)")); ?>


                                    </td>


                                </tr> 
                                <?php
                            }
//                        }
                            ?>
                        </tbody>
                    </table>
                    <span class="form-error"> <?php
                        if (!empty($notice)) {
                            echo "Errors : " . $notice;
                        }
                        ?></span>
                </div>

                <div class="modal-footer">
                    <button id="btnsave" name="btnsave" class="btn btn-default " style="text-align: right;" onclick="javascript: return Saveparty();">
                        <span ></span><?php echo __('btnsave'); ?></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
                </div>
            </div>

        </div>
    </div>


    <?php
    foreach ($partylist as $party) {
//                         pr($party);exit;
        $lockflag = 1;
        if ($party[0]['home_visit_flag'] == 'N') {
            if ($party[0]['is_executer'] == 'Y' || $party[0]['presenty_require'] == 'Y') {
                ?>

                <div id="otheroptions<?php echo $party[0]['party_id']; ?>" class="my-popup modal fade in" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content row">
                            <div class="modal-header custom-modal-header">
                                <button type="button" class="close" data-dismiss="modal">×</button>
                                <h4 class="modal-title"><?php echo __('lblotheroptions'); ?></h4>
                            </div>
                            <div class="modal-body">
                                <?php echo $this->Form->create('other_options', array('url' => array('controller' => 'Registration', 'action' => 'party'), 'id' => 'other_options' . $party[0]['party_id'], 'class' => 'form-vertical')); ?>   
                                <div class="form-group col-sm-12">
                                    <?php
                                    echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken")));
                                    echo $this->Form->input('optionsid', array('type' => 'hidden', 'id' => 'optionsid', 'class' => 'form-control input-sm', 'label' => false, 'value' => $party[0]['party_id']));
                                    $checkedflag = '';
                                    if ($party[0]['camera_working_flag'] == 'N') {
                                        $checkedflag = "checked=checked";
                                    }
                                    ?> 
                                    <label><input type="checkbox" name="data[other_options][camera_working_flag]" value="1" <?php echo @$checkedflag; ?> ><?php echo __('lblcameranotworking'); ?></label> <br>

                                    <?php if ($fivefinger == 'Y') { ?>
                                        <?php
                                        $checkedflag = '';
                                        if ($party[0]['biodevice_working_flag'] == 'N') {
                                            $checkedflag = "checked=checked";
                                        }
                                        ?>


                                        <label><input type="checkbox" name="data[other_options][biodevice_working_flag]" value="1" <?php echo @$checkedflag; ?>><?php echo __('lblbiodivicenotworkingthumb'); ?></label> <br>
                                        <?php
                                        $checkedflag = '';
                                        if ($party[0]['biodevice_working_flag2'] == 'N') {
                                            $checkedflag = "checked=checked";
                                        }
                                        ?>
                                        <label><input type="checkbox" name="data[other_options][biodevice_working_flag2]" value="1" <?php echo @$checkedflag; ?>><?php echo __('lblbiodivicenotworkingindex'); ?></label> <br>

                                        <?php
                                        $checkedflag = '';
                                        if ($party[0]['biodevice_working_flag3'] == 'N') {
                                            $checkedflag = "checked=checked";
                                        }
                                        ?>
                                        <label><input type="checkbox" name="data[other_options][biodevice_working_flag3]" value="1" <?php echo @$checkedflag; ?>><?php echo __('lblbiodivicenotworkingmiddle'); ?></label> <br>

                                        <?php
                                        $checkedflag = '';
                                        if ($party[0]['biodevice_working_flag4'] == 'N') {
                                            $checkedflag = "checked=checked";
                                        }
                                        ?>
                                        <label><input type="checkbox" name="data[other_options][biodevice_working_flag4]" value="1" <?php echo @$checkedflag; ?>><?php echo __('lblbiodivicenotworkingring'); ?></label> <br>


                                        <?php
                                        $checkedflag = '';
                                        if ($party[0]['biodevice_working_flag5'] == 'N') {
                                            $checkedflag = "checked=checked";
                                        }
                                        ?>
                                        <label><input type="checkbox" name="data[other_options][biodevice_working_flag5]" value="1" <?php echo @$checkedflag; ?>><?php echo __('lblbiodivicenotworkingbaby'); ?></label> <br>
                                    <?php } else { ?>
                                        <?php
                                        $checkedflag = '';
                                        if ($party[0]['biodevice_working_flag'] == 'N') {
                                            $checkedflag = "checked=checked";
                                        }
                                        ?>


                                        <label><input type="checkbox" name="data[other_options][biodevice_working_flag]" value="1" <?php echo @$checkedflag; ?>><?php echo __('lblbiodivicenotworkingthumb'); ?></label> <br>
                                    <?php } ?>


                                    <?php
                                    $checkedflag = '';
                                    $remarkstyle = 'style="display: none;"';
                                    if ($party[0]['admission_pending_flag'] == 'Y') {
                                        $checkedflag = "checked=checked";
                                        $remarkstyle = 'style="display: block;"';
                                    }
                                    ?>

                                    <label><input type="checkbox"  id="admission_pending_flag<?php echo $party[0]['party_id']; ?>" name="data[other_options][admission_pending_flag]" value="1"  onclick="take_pending_remark('<?php echo $party[0]['party_id']; ?>');" <?php echo @$checkedflag; ?>>  <?php echo __('lbladminssionpending'); ?></label> <br>
                                    <div class="form-group col-sm-12" id="divadmission_pending_remark<?php echo $party[0]['party_id']; ?>" <?php echo @$remarkstyle; ?>>   
                                        <label><?php echo __('lbladmission_pending_remark'); ?></label><br>
                                        <div>
                                            <textarea class="form-control" name="data[other_options][admission_pending_remark]" id="admission_pending_remark_<?php echo $party[0]['party_id']; ?>"><?php echo $party[0]['admission_pending_remark']; ?></textarea>
                                        </div> 
                                        <span id="admission_pending_remark_<?php echo $party[0]['party_id']; ?>_error" class="form-error"></span>

                                    </div>
                                </div> 
                                <div class="form-group col-sm-12">
                                    <button type="submit" class="btn btn-default pull-right"><?php echo __('btnsubmit'); ?></button>
                                </div>
                                <?php echo $this->Form->end(); ?>   
                            </div>

                        </div>

                    </div>
                </div>

                <?php
            }
        }
    }
    ?>