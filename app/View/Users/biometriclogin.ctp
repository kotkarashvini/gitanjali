<!--<script type="text/javascript" id="myscript">
</script>-->
<!--<script type="text/javascript">
    $(document).ready(function () {

        $('#bio_list').change(function () {
            $('#FPImage1').attr('src','');
            var bio_list = $("#bio_list option:selected").val();
            if (bio_list == '1') {
                $.post('<?php //echo $this->webroot; ?>Biometric/secugen_api', {}, function (data)
                {
                    $('#myscript').html('');
                    $('#myscript').html(data);
                    $("#myscript").find("script").each(function () {
                        eval($(this).text());
                    });
                });
            } else if (bio_list == '3') {
                $.post('<?php //echo $this->webroot; ?>Biometric/startek_api', {}, function (data)
                {
                    $('#myscript').html('');
                    $('#myscript').html(data);
                    $("#myscript").find("script").each(function () {
                        eval($(this).text());
                    });
                });
            } else {
                alert("Please Select Device ...!!!!");
            }



        });

    });


    function Save() {
        document.getElementById("actiontype").value = '1';
    }

    function Verify() {
        document.getElementById("actiontype").value = '2';
    }
</script>-->

<?php include 'csrf-magic.php'; ?>
<?php
echo $this->Html->script('jquery.dataTables');
echo $this->Html->script('dataTables.bootstrap');
echo $this->Html->script('JS');
?>

<script language="javascript" type="text/javascript">
    $(document).ready(function () {
    if (!navigator.onLine)
    {
    // document.body.innerHTML = 'Loading...';
    //window.location = '../cterror.html';
    }
    function disableBack() {
    window.history.forward()
    }

    window.onload = disableBack();
            window.onpageshow = function (evt) {
            if (evt.persisted)
                    disableBack()
            }
    function formSuccess() {
    alert('Success!');
    }


    function formFailure() {
    alert('Failure!');
    }
    });


</script>
<script type="text/javascript">
    function Save() {
    document.getElementById("actiontype").value = '1';
    }

    function Verify() {
    document.getElementById("actiontype").value = '2';
    }
</script>


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
            var capdata = result.TemplateBase64;
              if(capdata!=""){
                 $('#btnsave').prop('disabled', false);
                  $('#btnverify').prop('disabled', false);
            }else{
                $('#btnsave').prop('disabled', true);
                  $('#btnverify').prop('disabled', true);
            }
            var SALT = "<?php echo $this->Session->read("salt"); ?>";
             $('#cap').val(encrypt(capdata, SALT));
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
    debugger;
         var hfserver = $("#biometserverflag ").val();
		 if(hfserver=='Y'){
             var uri = "https://localhost:8000/SGIFPCapture";
               var secugen_lic = "NTommEhS08t44kdRsZsKLRrHxuLlFDkfD84Sb8zyAlo=";
               var params =  "licstr=" + encodeURIComponent(secugen_lic);
        }else{
   var uri = "https://localhost:8000/SGIFPCapture";
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
    xmlhttp.send();
            <!--xmlhttp.send(params);-->
    }
</script>

<?php echo $this->Form->create('biometriclogin', array('id' => 'biometriclogin', 'autocomplete' => 'off')); ?>
<?php if ($checkflag != 1) { ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="box box-primary"> 

                <div class="box-header with-border">
                    <?php if ($userdata == 0) { ?>
                        <center><h3 class="box-title"><?php echo __('lblbioreg1'); ?></h3></center>
                    <?php } else { ?>
                        <center><h3 class="box-title"><?php echo __('lblbiologin'); ?></h3></center>
                    <?php } ?>
                </div>
                <div class="box-body"><br>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-3"></div>
                            <label for="district_id" class="col-sm-2 control-label"><?php echo __('Select Device'); ?><span style="color: #ff0000">*</span></label>
                            <div class="col-sm-3">
                                <?php echo $this->Form->input('bio_list', array('options' => array($bio_list), 'empty' => '--select--', 'id' => 'bio_list', 'label' => false, 'class' => 'form-control input-sm')); ?>
                            </div>
                        </div>
                    </div><br>
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
                                <?php if ($userdata == 0) { ?>
                                    <button id="btnsave" name="btnsave" class="btn btn-info " style="text-align: center;" onclick="javascript: return Save();" disabled>
                                        <span class="glyphicon glyphicon-plus"></span>&nbsp;<?php echo __('btnsave'); ?></button>
                                <?php } else { ?>
                                    <button id="btnverify" name="btnverify" class="btn btn-info " style="text-align: center;" onclick="javascript: return Verify();" disabled>
                                        <?php echo __('btnnext'); ?></button>
                                <?php } ?>
                            </div>
                        </div>

                    </div>
                    <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>
                </div>

            </div>
        </div>
        <input type='hidden' value='<?php echo $actiontype; ?>' name='actiontype' id='actiontype'/>
        <input type='hidden' value='<?php echo $cap; ?>' name='cap' id='cap'/>
        <input type='hidden' value='<?php echo $biometcount; ?>' name='biometcount' id='biometcount'/>
        <input type='hidden' value='<?php echo $biometserverflag; ?>' name='biometserverflag' id='biometserverflag'/>
        <?php // echo $this->Form->input('biometcount', array('type' => 'text', 'id' => 'biometcount', 'value' => $biometcount)); ?>
    </div>
<?php } ?>
<?php echo $this->Form->end(); ?>




<script language="JavaScript" type="text/javascript">
    var message = "Not Allowed Right Click";
    function rtclickcheck(keyp)
    {
        if (navigator.appName == "Netscape" && keyp.which == 3)
        {
            alert(message);
            return false;
        }
        if (navigator.appVersion.indexOf("MSIE") != -1 && event.button == 2)
        {
            alert(message);
            return false;
        }
    }
    document.onmousedown = rtclickcheck;
</script>
<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>