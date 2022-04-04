<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>
<script>
    function PopIt() {
        return "Are you sure you want to leave?";
    }
    function UnPopIt() { /* nothing to return */
    }

    $(document).ready(function () {
        window.onbeforeunload = PopIt;
        $("a").click(function () {
            window.onbeforeunload = UnPopIt;
        });
    });
    
  

</script>

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


</script>
<script type="text/javascript">
    function Save() {
    document.getElementById("actiontype").value = '1';
    $("#biometric_registration").submit();
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
    var uri = "http://localhost:8000/SGIFPCapture";
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
    }
</script>
<?php
echo $this->Html->css('jquery.dataTables.min');
echo $this->Html->script('jquery.dataTables.min');
?>
<script type="text/javascript">

    $(document).ready(function () {
        if (!navigator.onLine)
        {
            // document.body.innerHTML = 'Loading...';
            window.location = '../cterror.html';
        }
        function disableBack() {
            window.history.forward()
        }

        window.onload = disableBack();
        window.onpageshow = function (evt) {
            if (evt.persisted)
                disableBack()
        }
        $('#myTable').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, -1], [5, 10, "All"]]
        });
    });

    function activate(id)
    {
         $("#dialog").dialog({
            modal: true,
            autoOpen: false,
            title: "Biometric Capture",
            width: 300,
            height: 550
        });
        
         $('#dialog').dialog('open');
       $('#hfid').val(id);
//        alert(id);
        //$this->redirect(array('action' => 'biomerticlogin'));
//      window.location =<?php // echo $html->link('yourlinkdescription', '#', array('onclick'=>"var openWin = window.open('".$html->url(array('action'=>'youraction')."', '_blank', 'toolbar=0,scrollbars=1,location=0,status=1,menubar=0,resizable=1,width=500,height=500');  return false;")); ?>
//        window.location = ("<?php echo $this->webroot; ?>Users/biometriclogin/" + id);
    }




</script>
<?php echo $this->Form->create('biometric_registration', array('id' => 'biometric_registration', 'autocomplete' => 'off')); ?>
<div class="well">
    <div class="panel panel-default">
        <div class="panel-heading" style="text-align: center;"><?php echo __('lblbioreg'); ?></div>
        <div id="dialog" style="display: none" align = "center">
    <?php // echo ("This is a jQuery Dialog");?>
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
                            <span class="glyphicon glyphicon-plus"></span>&nbsp;<?php echo __('lblbioreg'); ?></button>
                        </div>
                    </div>

                </div>
            </div>
</div>
        <div class="panel-body">
            <div class="table-responsive">
                <table id="myTable" class="table table-striped table-bordered">  
                    <thead>  
                        <tr> 
<!--                            <th>Select</th>  -->
                            <th><?php echo __('lblofficename'); ?></th> 
                            <th><?php echo __('lbladmdistrict'); ?></th> 

                            <th><?php echo __('lbladmtaluka'); ?></th>
                            <th><?php echo __('lblaction'); ?></th>
                            <th><?php echo __('lblreset'); ?></th>
                        </tr>  
                    </thead>  
                    <?php for ($i = 0; $i < count($officedetails); $i++) {
                        ?>
                        <tr>
    <!--                            <td><b> <button id="active" class="btn btn-primary" onClick="activate(<?php //echo $usrdata[$i][0]['id']  ?>);">Select</button></b></td>-->

                            <td><b><?php echo $officedetails[$i][0]['office_name_en'] ?></b></td>
                            <td><?php echo $officedetails[$i][0]['district_name_en'] ?></td>
                            <td><?php echo $officedetails[$i][0]['taluka_name_en'] ?></td>


                            <td>
                                <!--<a  href='activate/<?php //echo base64_encode($usrdata[$i][0]['user_id'])          ?> '><input type="button" id="view" value="view" style="width:61px"></a>-->
                                <button id="active" class="btn btn-primary" onClick="activate(<?php echo $officedetails[$i][0]['id'] ?>);"><?php echo __('lblauthenticatebiometric'); ?></button>
                            </td>
                            <td>
                                <button id="active" class="btn btn-primary" onClick="reset(<?php //echo $usrdata[$i][0]['id']  ?>);"><?php echo __('lblreset'); ?></button>
                            </td>
                        </tr>
                    <?php } ?>
                </table> 
                <br><br>
            </div>
        </div>
    </div>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php echo $actiontype; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $cap; ?>' name='cap' id='cap'/>
</div>


<script language="JavaScript" type="text/javascript">
    var message = "Right Click Not Allowed";
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

