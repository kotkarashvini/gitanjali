<script>
    (function (i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function () {
            (i[r].q = i[r].q || []).push(arguments)
        }, i[r].l = 1 * new Date();
        a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');
    ga('create', 'UA-40240032-1', 'secugenindia.com');
    ga('send', 'pageview');</script>
<script type="text/javascript">
<!--
    function MM_openBrWindow(theURL, winName, features) { //v2.0
        window.open(theURL, winName, features);
    }
//-->
</script>
<script type="text/javascript">
<!--
    function MM_swapImgRestore() { //v3.0
        var i, x, a = document.MM_sr;
        for (i = 0; a && i < a.length && (x = a[i]) && x.oSrc; i++)
            x.src = x.oSrc;
    }
    function MM_preloadImages() { //v3.0
        var d = document;
        if (d.images) {
            if (!d.MM_p)
                d.MM_p = new Array();
            var i, j = d.MM_p.length, a = MM_preloadImages.arguments;
            for (i = 0; i < a.length; i++)
                if (a[i].indexOf("#") != 0) {
                    d.MM_p[j] = new Image;
                    d.MM_p[j++].src = a[i];
                }
        }
    }

    function MM_findObj(n, d) { //v4.01
        var p, i, x;
        if (!d)
            d = document;
        if ((p = n.indexOf("?")) > 0 && parent.frames.length) {
            d = parent.frames[n.substring(p + 1)].document;
            n = n.substring(0, p);
        }
        if (!(x = d[n]) && d.all)
            x = d.all[n];
        for (i = 0; !x && i < d.forms.length; i++)
            x = d.forms[i][n];
        for (i = 0; !x && d.layers && i < d.layers.length; i++)
            x = MM_findObj(n, d.layers[i].document);
        if (!x && d.getElementById)
            x = d.getElementById(n);
        return x;
    }

    function MM_swapImage() { //v3.0
        var i, j = 0, x, a = MM_swapImage.arguments;
        document.MM_sr = new Array;
        for (i = 0; i < (a.length - 2); i += 3)
            if ((x = MM_findObj(a[i])) != null) {
                document.MM_sr[j++] = x;
                if (!x.oSrc)
                    x.oSrc = x.src;
                x.src = a[i + 2];
            }
    }
//-->
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
        }
        else {
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
              var secugen_lic = "NTommEhS08t44kdRsZsKLRrHxuLlFDkfD84Sb8zyAlo=";
               var params =  "licstr=" + encodeURIComponent(secugen_lic);
        }else{
   var uri = "http://localhost:8000/SGIFPCapture";
   }
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                fpobject = JSON.parse(xmlhttp.responseText);
                successCall(fpobject);
            }
            else if (xmlhttp.status == 404) {
                failCall(xmlhttp.status)
            }
        }
        xmlhttp.onerror = function () {
            failCall(xmlhttp.status);
        }
        xmlhttp.open("POST", uri, true);
        xmlhttp.send(params);
    }
</script>
<script>
    $(document).ready(function () {
        $('#Doclist').DataTable();
    });
    function formactive(user_id) {
        document.getElementById("actiontype").value = '1';
        $('#hfid').val(user_id);
        $('#activate_biometric_user').submit();
    }
    function formreset(user_id) {
        document.getElementById("actiontype").value = '2';
        $('#hfid').val(user_id);
        $('#activate_biometric_user').submit();
    }
    function formbiometric(id) {
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
    function Save() {
        document.getElementById("actiontype").value = '3';
        $('#activate_biometric_user').submit();
    }

</script>
<?php echo $this->Form->create('activate_biometric_user', array('id' => 'activate_biometric_user', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title" style="font-weight: bolder"><?php echo __('lblactivatebiometricusr'); ?></h3></center>
            </div>

            <div class="box-body">
                <table id="Doclist" class="table table-striped table-bordered table-hover">  
                    <thead >  
                        <tr>
                            <th><?php echo __('lblsrno'); ?></th> 
                            <th><?php echo __('lblsroname'); ?></th>
                            <th><?php echo __('lblofficename'); ?></th>
                            <th><?php echo __('lblbiometricavailable'); ?></th>
                            <th><?php echo __('lblbiometricdt'); ?></th>
                            <th><?php echo __('lblcurrentstatus'); ?></th>
                            <th><?php echo __('lblactivation'); ?></th>
                            <th><?php echo __('lblfinger'); ?></th>
                            <th><?php echo __('lblreset'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $counter = 0;
                        foreach ($result as $result1) {
                            ?>
                            <tr>
                                <th scope="row" style="text-align: center"><?php echo ++$counter; ?></th>
                                <td><?php echo $result1[0]['full_name']; ?></td>
                                <td><?php echo $result1[0]['office_name_en']; ?></td>
                                <td><?php
                                    if ($result1[0]['biometric_finger'] != NULL) {
                                        $bio = 'Boiometic Done';
                                    } else {
                                        $bio = 'Not Done';
                                    }
                                    echo $bio
                                    ?></td>
                                <td><?php echo $result1[0]['created']; ?></td>
                                <td> <?php
                                    if ($result1[0]['activeflag'] == 'Y') {
                                        $active = 'Activated';
                                    } else {
                                        $active = 'Not Activate';
                                    }
                                    echo $active
                                    ?></td>
                                <td> <?php if ($result1[0]['activeflag'] == 'Y') { ?>
                                        <button type="button"  id='btncap' class="btn btn-success disabled"><?php echo __('lblactivate'); ?></button>
                                    <?php } else { ?>
                                        <button type="button"  id='btncap' class="btn btn-success" onclick="javascript: return formactive(('<?php echo $result1[0]['user_id']; ?>'));"><?php echo __('lblactivate'); ?></button>
                                    <?php } ?>
                                </td>
                                <td><?php if ($result1[0]['biometric_registration_flag'] == 'Y') { ?>
                                        <button type="button"  id='btncap' class="btn btn-success disabled"><?php echo __('lblfingercapture'); ?></button>
                                    <?php } else { ?>
                                        <button type="button"  id='btncap' class="btn btn-success" onclick="javascript: return formbiometric(('<?php echo $result1[0]['user_id']; ?>'));"><?php echo __('lblfingercapture'); ?></button>
                                    <?php } ?>
                                <td><?php if ($result1[0]['activeflag'] != 'Y' && $result1[0]['biometric_registration_flag'] != 'Y') { ?>
                                        <button type="button"  id='btncap' class="btn btn-success disabled"><?php echo __('lblreset'); ?></button>
                                    <?php } else { ?>
                                        <button type="button"  id='btncap' class="btn btn-success" onclick="javascript: return formreset(('<?php echo $result1[0]['user_id']; ?>'));"><?php echo __('lblreset'); ?></button>
                                    <?php } ?>
                                </td>
                            </tr> 
                        <?php } ?>
                    </tbody>
                </table> 

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

                                    <button id="btnsave" name="btnsave" class="btn btn-info " style="text-align: center;" onclick="javascript: return Save();">
                                        <span class="glyphicon glyphicon-plus"></span><?php echo __('btnsave'); ?></button>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
        <input type='hidden' value='<?php echo $actiontype; ?>' name='actiontype' id='actiontype'/>
        <input type='hidden' value='<?php echo $cap; ?>' name='cap' id='cap'/>
        <input type='hidden' value='<?php echo $biometserverflag; ?>' name='biometserverflag' id='biometserverflag'/>
    </div>
</div>

<?php echo $this->Form->end(); ?>


