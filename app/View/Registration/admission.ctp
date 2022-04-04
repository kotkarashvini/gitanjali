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
    function captureFP1() {
        CallSGIFPGetData(SuccessFunc1, ErrorFunc);
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
    function SuccessFunc1(result) {
        if (result.ErrorCode == 0) {
            /* 	Display BMP data in image tag
             BMP data is in base 64 format 
             */
            if (result != null && result.BMPBase64.length > 0) {
                document.getElementById("FPImage2").src = "data:image/bmp;base64," + result.BMPBase64;
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
        $('#admission').submit();
    }

    function formsave(id, type) {
        $("#modal").dialog({
            modal: true,
            autoOpen: false,
            title: "Biometric Capture",
            width: 300,
            height: 550
        });
        $('#hfimg').val('');
        document.getElementById("FPImage1").src = "data:image/jpg;base64,/9j/4AAQSkZJRgABAQEAZABkAAD/4QAiRXhpZgAATU0AKgAAAAgAAQESAAMAAAABAAEAAAAAAAD/2wBDAAIBAQIBAQICAgICAgICAwUDAwMDAwYEBAMFBwYHBwcGBwcICQsJCAgKCAcHCg0KCgsMDAwMBwkODw0MDgsMDAz/2wBDAQICAgMDAwYDAwYMCAcIDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAz/wAARCAHAAYwDASIAAhEBAxEB/8QAHwAAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoL/8QAtRAAAgEDAwIEAwUFBAQAAAF9AQIDAAQRBRIhMUEGE1FhByJxFDKBkaEII0KxwRVS0fAkM2JyggkKFhcYGRolJicoKSo0NTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uHi4+Tl5ufo6erx8vP09fb3+Pn6/8QAHwEAAwEBAQEBAQEBAQAAAAAAAAECAwQFBgcICQoL/8QAtREAAgECBAQDBAcFBAQAAQJ3AAECAxEEBSExBhJBUQdhcRMiMoEIFEKRobHBCSMzUvAVYnLRChYkNOEl8RcYGRomJygpKjU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6goOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4uPk5ebn6Onq8vP09fb3+Pn6/9oADAMBAAIRAxEAPwD9/KKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiignAoACcCm76C24Ypjnyk3f1pWdyZSaH76BJn1qEXalsfz4p3nKnJ/nTco9xxlcl30pbiq7X0ahed27sCM0NdqVVh8qt0Y8Z/rUc1tWxk3mUofJqslwplCll56DI5qSSZYV3MflHft+fSkqil8OpOtrvQmoqvDfJKRtVue4+b+WakFyD0WT/AL4Naa9RxknsSUUUUDCiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooJwKbvoAdRTd9G+gBzNtGajaYEd6WR8oaqXkmy1kPl+YMY2khd/tn3peb2J1vZDpb4RjOyTHrgdc4xjOc9/f68V43+0D+3j8P8A9nw/ZdS1RtS1hztGmaaUluk9fMBYCPjP3yD7Zr50/b5/4KH3ukeJdS8C+A9QNrcWjrHrGt22PMhkxn7PFngjHys/UE8c818SW8m+6mmaPdNcSGWRyS7SOerMx+Zm/wBok/QV8tm2eSov2dFXZ9VlOQOuvaVXZdj7i8Qf8FddSvLxh4e8Hww2uTg6nfszv+CDj8Gqv4f/AOCtHiaK/X+1/CuiPa7vm+yXkyyY7YL5HXHavjyxuTaMvzKVbrntU73P9xd27ruXI/KvH/tfEOzloz1P7Bw8Xbc/Qrw5/wAFXfAuoN5eqab4i0ebaTzFFMjHGeCr55+grwz9oD/gqD4o8Y381j4PWfw7o+cC5dN95cgHPPIEf/AScjivmaG7kU7FjaEr950fGfp6U3+0hazvkyN5vDBjvz+PaqlnFeSs2VDJcPF3SO4m/aF8ZeIJjI3jbxF5khyyw6nMmT9M8VsaJ+1F8RfCFxFNa+OvEh8o5CS3kk6ntyr5B/EV5JPBHJd7oR5Un+z3qeGfULSIs11lR/CwGD+NYxxtS91JnRUwNJ6OKPqnwF/wVX8ZeG76P/hJtK0zxBp6HDTxD7JdAdOCDtJzjr1r6K8I/wDBTb4V+JtEjurjWNR0WZjte0vrOQSxn6gEEehBr8zJtckt2Hnxqynq6MD/AI9enTvUDavps53SPdW7f3FVnA/EnNeph85qxjaTPNrZJhpO60P3Hooor7I+LCiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKCcCgAopu+jfQA6im76N9ADqCcCkD5NJK+yMmlzJbgG+jfVf7YrdM8dSCOKcZ9v8Lt9FzS5m9kLTuSyShUqKScRpuwaJbhY4wzBlVupI+79e9eWfHf9sH4f/AHw7cXfiHxBYxSwsqrZwzJLdyksANkYbJ5I+nU4ANZ1K0aavJmtOjOr7tJNs9OXVYiV+9tY7Q4G5SfqM4/HFQax4p0/wAPWbXOoXVvY2sZw01xIsSA/ViK/OP49/8ABXXxR4mluLXwPpVp4dsXXZ9uvwLrUGHZgqkJH+bfh1Hyz4n+Inij4kSyXniTxDrmvLJjMd3ds8IOeW8rgfrx15xivExGfQpu0Y3PocHwvXqRvUlY/XLxj+3t8I/BYZbjxxotxMuQI7SX7QWI6jKZA/EivlH9rD/grU+v6PdaN8N7OSKOdfJm1S6TLxqSA3lpnB3DK5JBG7IyRivjSKwghCrGJFRRlFXbgfQhQR+tT2pVW2MvysDnJz2rxsRxBVqLlSsfQ4ThWnT9+TuUrKK4khRZHmuG+YvK772cs245Pt61etYnhmxj5R3PQ1e065t7XTlTKqq5+91NH2u3nso1EirtbNfPtybvJnvexjZKK2GbWhC7trPJnaB/WhC0nUsD/cXrVidIZrmMo3ygcY7URxqXYfu2kk65NCD2a6ohRZYY+Wfrzkjmq9yjl1kbhQeV/iq/9kjVwzIoYcjDZpl3PHcJvZW8zoQB0o0MKkbPQpzXMazZCSPu7Un2tRNtEI49fmA/CmLNtYsu3cOmarm+8q4dgrHcMHimpWFGN9y890kJEjCNj/CFTaD+P/1qdDFYyRK0/nea3LfvelZbSKIIzIr7VOVwOc1XuJo5JSz+Zu9qfMDprsfvRRRRX6wfkYUUUUAFFFFABRRRQAUUE4FMedY13NnH0oAfRUJvFH8MhXsyruB/LNSPIqLubgUAOoqNLtWdVztLfdBI+b6c1ITgUAFFIHyaWgAooooAKKKCcCgAopu+lD5NAA7bFzTBOD2anTyCKJmbdheeBk1XF7HIqkMcSfdJ4z7D1PB/KplKyuLVvcsPOsa7m+Ueppj3KiPceB+f8q8r+OX7ZPw4/Z806WTxN4osLC6UHZZrIGupiP4VQZOT74H0618qeMP+C7fhmGZk8PeC9avlwdst/fR2+8+mIy+PXnFcc8dTgrtnbRy/EVf4cWz78F2pbGJPxjYD88UG4XGQc+vbH51+Wur/APBbj4ia3Oy6X4P8H2axk4N289w/typX6dqzW/4K+fGHV4m8m08E2YPy5gsbhiG7j5psZHX0rjeeUex6lPhvFyV5Kx+rhulA7/iMZ/Pr+FYvin4m6D4ItxNrGrabpduf+Wt5dxW6f+PsK/Ijxt+2T8Vvi3Hs1XxprUdp/FBpATTIsejCMfN/wIk1wB8y6vPMnuJpnY5HnyyTOT3yXY/piuapxBFL3I3OqjwvUf8AElY/WfxF/wAFFvgz4cuXgk8c6bdTx9UsY5bw/h5SsD+FcpqX/BWL4P2pZY9Q1672kcxaPOB1/wBpR/KvzQGl2oUqzTW69WEbkK31GcfpUZtbaWNgir5g+6ADub8Rj+dcEs+qy1tY9KPCtBL3pn3x46/4LK+E7C78nQfC3iLWW7SXEq2a/lycfhXk3xA/4K5+PvEMTRaDonh/w6q9Zm3XzrzxkMFXPbp3r5fhstkoIaTzJOFUt0+uc/zqaTTnMLKuyNl5ZhyGrhq5xiJ6J2Oqjw/hKe/vHbfEP9s/4rfEjT7iPVvHerG3kjZXt7CNLNGUjlf3aq/T0ce+RkHx2+ha3gKwKLcxEKHABkYsMklsZbIyDuz1/GujMcir8z7gCMjb1GeartpLXA+fZ0OcdznjH4V58q1SXxSZ62Ho06P8OK+4xbLTJ3eMAxN6bV2sv/xX44rftrQxxRqYWG37zt0qTTNPjtuWO0qccds8VYe7ZTt+do9205HFZpa7nR7RlWaPyF2xqzY+7ULxCaFtyNu9K2o5952qi/KOtS2ul+d837tW7AnrWkqMXuCrW0OZt45iyxkdD0x8v51PEpRnHlpjtXS/2ORGzbBx3Bqjd2S20e7ru6AVn7NR0Qe1fQyLfc821ty5/u1bR5IyNqk89T1qZLJlkVgv4mnLtlfZuXd6DrWUr30LjJvViib/AEhgysdw+UAZ6c02MG48xl5P8Xt+HWpkguIAuGRt3QqxDL+lNdGyWKquOuFyT+NJeZFTuUXswCzYbd69qqJIiyEfxVuJaxmFt0m5dobPrzWZJpymfgH607mMZK9mUby9Dqsa9R3PSs65MjzEhkx9au3mmNMzbFYMDwD1NP8A7IIAyrdKiUn0OhRXQ/eIPk0tQR3KsV6/N93Ixmpg+TX64fjN7i0UUUAFFFFABRQTgUx5ti52s3sByaAFlbahNV5rlVjb5ioVd5P3QB6nPb1qj4u8a6b4N8M3mq6peW+n6fYpvnuZ2Hlxcjg4PX6V+Z/7Zf8AwUc1T9o++ufCfgf7ZpfhKGQi4vw5E2sYPQEHKRAgEYOTjBABqXe+guZLc+gf2nP+CquhfC7xVceHfBelx+MtcsTtvJvOe3sbU5/vLnee2B3x2rhPDn/BU7x9IyzXvhTwrJCxzsiuJoXX23bW/lXyv4K8E2/h+1jO2NgpLgCM/eIwTyff3rqIrnyl+VV44reMFbUiVTsfcPw2/wCCnfgvVJY4vFFjqHhW+kIU4H2u2b3DICw/FRX0L4T+JOg+P7NZ9E1bT9UiZQwNtcJIQPcA5H0IFfk8ZA9use7aB/cTbn645NPiHkvG8M1xG0Z3K8TtDID6gpt/XNP2aM5YhR0Z+ukdzG+SrbtvXHNCXaswG1wWOBxu/UZA/Gvzl8AftvfEXwBFb27atFrVjCQBHqsAnkVemFkXaR9Tmvffh/8A8FLPD+rapHb+I9F1Tw+AP3l0D9sgbjqAmHUZ/wBlsd8DJGco2KhXhLd2PqHfRvrl/h/8XfDHxUszceHtc0/Vo1+8LeUMyf7w6j8a3m1CJJFU7lZm2hTwx+g6n8Km63ua8y2uWt9Naddjei9T6Vm694t03wrpU19qV9aafZ2/Mk9zOscUY9SxOAPrXzX8dP8Agq/8L/hratb6HezeNL/djytI/wBSMEfMZ2whH/XMuc9RjJrnrYqnT3Z0UcLVqvlgj6gF7GYlkB3K3QqN2fpiqWveMNN8MWLXOpXlvp8C/wDLS6lWFeuOrED86/MH4uf8FQviZ8TL+4h0T7F4P0u4jwDBGLi+K+jTMAvtwg+tfPGs6ne+Jr6S61TULzUZ2YlnvH85mJ9DkBfwU14+Iz+EHy043Pfw/C9eWtSSR+n3xp/4KsfCT4YTXWn2+rXPibVrZSxttLg81Ay84aUlUHTqGP0PQ/EP7Rn/AAVo+InxmM9l4dT/AIQPS+kiWM/n31yG6b52UbR7KvtmvAb3Q2YMJJEWNOUijG1V/L734iudkWSHxDqEjBVSQoUz2HA/OvKrZrWq76H02X8O4WlrJczMi5uZtb1m6up5JJLuR/nn3tJdTOTyXdyd3c8ba09D0VrCz1LULqOPMcRGBHs3YGQfdiePxqx4b0y30jUJLy8Zd29mRR/Fwa1vt0muzLG8JS1ikSaUMPvLuHyj39jXnSqvqz6R04wVqaSI9J8PS2NoiACSa5J8x88IMA8e/P6Vp22lLBC3k7xCpUbh2ORyfc9PxqZJ1ezXzo9z4DFCcKzZPQ9cYx+VOgb5dyghlOVP938O9c/t+hnGTfxGqluulShX3qsy5EZ6sevOKWCJvP8AMaRPl5AHI+hPrVJdV+ZmXdGWxvZvmPHTH+elTWTxR8BWjjkO99vzb2/HpWMqrT0K06o2Gn2IJGVSjY4appL9mlOWA3j5IkUZ/GsaXWdquAu7H3B/jVSPV/KuMMrNJLnd/s454qlUbWpPLHsast9HJKY2Rppc5bZ7e9R3F1FaxvNtCySYDANkJz2rHl18NOv2XcjLneMDB49aYmofat0Kqx8z7xb25/pUkypp7G/a3cd0F8sM3rUscHmSKm1lReQT/WsOwmSwVf3m1W656itW3u1ltmZXkYj16UE+xFvFYLMw+ZnHIFUYXkEiMxfyyMEZHB96sPJvEina+OvPFV2hhg3Yj+WTphskd6ClSj1Naxv4TBt/5aL1z3pz6uolZWjZ2TovZqwXRFbzGWSTdwqr1H1qWWaRFCmRRjqF5JBqudk8qWxs2esATf6mReww3Bz+NXowrpmRGbZzxg/1rlbdtp27pG28hDwfxq5YauNOj3NGzN0LIS2M8VnKTuS4pnQKPPh2Jt+fpntjmoYIvKRj5KBgeT/EKhsdSgnAVRIxBycqQPzq7BqaymTYysudsgFEdTOXNF2iMiiZNzKp2t93PeiSbdMvAXHVW6sO+KW8vPNgROVWM/KR15p9vb/aHO7bvjUsD6gDJp2M5TezG20aogHl5hZiQO+KbLb2t5EwVZo2yOwx1+tWhFIkvytG0bMEwM5BIzQHjllaLcAQSD/OjlM9L3Mm5tleXylaNWXo3esm6t2gnZWaZmHdRxXTJBDOfL2fvO7VXWDzM7k5U4zjrUSp31NI1LaH6KfC39uqZZV/4SjTVmjHDXumKFLDB5kjZuf+An8D0r6D8F/FLQfiBp0d3o+pW99DIOkZ/eKfQofmH5V8UfEL9jT4jfDB45rS3h8ZacuWEtjJ5VxbqO5RgB+RNedaJ49Ona/I1tNeWWoRNtlDJJBc7h2Y8Mcewx+FfrB+N3cdD9OI9QjmkZU+fb1KkNj8uaes6s2Pm/EYr4n+Hf7anijwuPL1CeHxFDH0ju/3M8Q6cSADb/wIHPTvXtnw6/bk8I+JdsWsLdeH7rp/pCGSEn/rouR+eKCozT3PcAc+tBOBWfpHirT9fsFurG6hvIGGQ0LCTP5dPxqw1+oi3FWHpxu57dM0Gg+a5WMMMMWAztHU155+0J+0p4X/AGdfAdxrPiK8ECY221uG2zXjcDCLnJxnk8CuB/bQ/b68M/so6SbVVXXvFlwoaHSoidyd1eVv4Ez16ntivzN+IXjrxV+0p46k8U+LtRuby6bIt4WGIrCInJijHp/tEAmizbMp1OV2Or/aS/av8Yftn+KEW7kbRvClqxFppduxiV1z9+UDO5j6Hisjw74ah0aFBlXZRtDbNp29lwP50ul2MOmRKF/ddD8yZZweACe3PpUWveMbfSb6O1tFfUtTbgWtv95c/wB49AMfWuiMEtzLmvqbIPmMY1IMi/eQA5X/AD7VWvPENlpO5bi8tkZBkosokY/guTWp4Y+B3ij4i3UB1W+XRbQ8rZ25yyjHeTGfw/Cvf/hL+wN4WiTzpLO3abG+SaSAoGI5yWYjNXzRW5pTo1av8KLPmKz+IFvrKstnYatd7TgkW7Qr/wB9Nj9KuxT+JJSPs+gyLC3eeZv6A19+2/7Pnw/8EaJ9s1K+0y3W3UGRpX8zysjpjPB/x4rhNa+KXgyK4aPw74XTUolJSO8u28qKU9yqjJ6ZPzBayqYiENzop5Xian2T5His/FT/APMKsl9xIWx+Qqh4j8aXnw90wXWsW0VnCT8qLMwml/3eOlfRPjTxYtrY3F9dfY7W3TJ2QW4iVfbOfm9OcV8cfEfxBdfFPxzPqd5NI1rC3lWkQGVRR3I6V4+OzZU42jufTZPwq6kuavsbUH7Sl8bhbjRrFrUk/K7TNDIPf93gH8c11z/t/wDxfHhCTQ7PxXdWNpIQBIEWa4UZB+WVhuX8M8ZrzO08PmN96t5hYdCuAKbLZSQTqWVQrZr5CrmmIqPc+4w/DuDiv3cUzP8AGviDxH471fy9f8Q6xr+Rlvt+o3N0HPsJJCqj2C49qd4e0S3024XzAqsvDYH8HZQBhR+Cirf9mRi5WRmI252kVbAjjgX7jMOpNcnPOWsmdv1OFL3YxRJhZZN0YYxuxwcfdHvTb+xWRUVf+WnPHbHP9KliVU/1bfK2PlpdS2Qxlow2eMA/Xmpk5dBclmZkumtcqZuMLgAd6xdV08SM3mKpbdnA7jtXRi92kjqrenas3VIdpMn6VpSbtqaJtvU4C7s5jqX7zdJGsm8DuPT9cV0eixHT7V5GkZpJG3srfdFMmnjhvBuXlqimLTSMn3l4yV6VNZN7G0n0NRhJPGrKytvPftWgJFitgcjcvWsg3Qs8L83Tj2qE6ort8zcdxXDyu9jJxfQ25rmGKRONxPam3l5uT5Sy/XpWKL9Xk+Vvm7ZpZtS84bd1bezGo9zVm1DytrL2FZ51Typ/NkZVA4/PiqGpasFi2K3pWRqmvqrKq/M3oeho5WPlN+1uPs10zbhhuevWp7e/Lz/K3ft1rjYb14JmZpGZmHy+gos/ESwXC/ekRm+bHajll0GrLc7k6l5szxbfpvq/HqS29v8AKzbsdO1YNvqaz3CszbVx0Aovb1UVjG3PbNCjLqGnQ6O21BVst27luuamguhPEuP4DzmsDSbyO9s9rN8ynmtyFI47X+JVx1NPlZjUk09CxJLGnzKTtbp6g0x1hiszuyWPJPf8Ky5tQjtLf5mbdnitHTYFvpFaNlYqA2D3FHKzJu5Ja2UsoGMRrt3hn4d/amDUWmmWNY5FHT5TjP41feMtbSLI3OeCOoFY9tpbB9okk65rOWj1EaVpdLDGEV5CxfD/ALzd79KsrPGjusSyRszZORwfrVCzsZC6sqhfnLZ9RjvVyCIzSqWKlZGCkD73JxS5jOT1NLT2WXHmZYbipUfe4GQfpV6zulhkjZgxZXAx6gnH9ay7ZvszGb+JSFI/2SDj8eKtLKpWXDDcsZdT2J7Ae9VF3Oed7l9NVfULLbJGsPyMylPUOAM/hVS91VRCWMPltDGMsB94njP61HDqSyqpHyrJwAeq/Kcg/jVfUb3zFEMindgISOh5FUSvM0NDlVrDfMcy8lSOhqTVBLBeMka8KBk+pxn+tQ2EUdtFGrJK0cLfwrkn1q4mtwiJd+GbGSePU46+2KdyZK70P2iW1cHdlN3rt6VxfxT/AGdvCnxk09o/EWi6feSMABcbCs6YIPEi4JPHHp713lBGa/Uz8mkr7nxv8R/+CbWqaPdNc+DfEkVxZqfksdaY+ZFk4wlwqk456Mp9MjqPH/GXw48bfB7UZoNf8LarDbqMm7gja+tGH94snQfVR+FfpJcJmFsMU7ZB/wD11zvi7xbo/gXwhe6tqV9Dp+l6erNcz3AKoAOoIOMk/TnPfpRdbESgrH5+eAvijL4cuIb7RdYms2V8o1tcbYWPumSGHqK1fi5/wVj1/wAPeELrw/oMGmX3jRxsl1aJf9HhGevlZ+9jjIJwcHtXjf7X37U3hz41+Ont/hp4ds9G0rcXn1ZLY29xqTHhsr08vrg4U5IrzrwZ4Bt9GAmaNS0hLEZLbSepyetaxgramcZNaFXRfC114kvZtS1i6kvb66dpZJpXZ5XdjuZmYnkZ4C9B1rq7HT1sQvzZVOAOxqwI0jUBV21HdTrbWssjDKojE4+lXGKWxMrSd2c74q8UXf2+PR9N2rqF1/rZXHy2yk4wf9rHTj8a7LwP4W0/4W+EJtYvHjVY4DcXF3KMyycHg+nTtXnPgYSI1xql0Q1xfTGVmPXI+VfwwatftzeKbuf9jTX/AOyY7iS4jgQMkP8ArNgG1sY9yD9AaqctLo9LJMCsTjYUW7J7nyz+1L/wXm/4V1q11pPge2uI2t8q146KSxzjj5jXyP4m/wCCvvxD8e6pI+oa/qRW4ypEly7qgOecFgox16HpXzL472X3lsrFd2Vd5JS29856Y5rnE02RZd3yt5fzDcoKsRyAcnoTx+Nee4+1d2fW1sZPLK86dBJqL6q5+8v/AAR9+PPh/wDaC/ZwvZtU1aFfG+n6tI+qS3C7zNE65ttuMkYVTk4GCR1r6ovfH7afI0cDNOzEDfsK7wDnk456e1fgj/wS8/aoT9mP9oy3ur6W8j0TWYW0+7Cj5V3HdG7AE8owA46KT9K/bTQfipaeJtP8yKaSRJI1dCQdhU4wwJHQ9q8HGU8Rze4z9f4Vy+jnGC9rTj7/AOpJ8QvFWreL1kt5pEiswSRGCfm+vFcHc6GlhYxY2ct0Wug8QeIN1xtVt+7+6K5XxFqkiLuXO1MdxxzXm1KfN703ZnsR4PxFONo09TYgij061zL1YcEdB9azdanhmtuGX5fzrmNZ8dv9nKs2AMVz1944/cN+8/JT/hXK6KPJr5LiKTtyNHUT6qvmqoPy5xzUlrqQZ2+VTsOOehrzO/8AFkk0cm2TnscHj9K1PC/juGSBWDBnj+RyzADd+JrB0n0PJxFN017256ImsN5y/ugqjqw7Umo6is8J8vO/sT0rlIvFsd3PtaQbcchSDn8qg1TxdHZ/KX8vd3bgfnTjB3+E4pyS1vc2LnWDa5X161Uu/EIm+VW3Mew61x954/ijnLCaJtvYsOai0651TxRP/wASvT7y4z1dIiqj/gTYX8zXTToNuyizkqY3DQ1qSt5FrxRqiyBtrOkvbjvUXhfxl9uiaOXck0H3ht/nU1v8DvF2v3qtJJpNnG3X7RPuYcf3Vzn862NP/ZJ1G4ufOuNc06z9ZbeBmY/UEgV2xympJXOGpxBgo/bK73k0y/dbB6HaaqkeUGkbhh0U967W6/ZrurO0Uaf4jhupFHS4gMWfxUt/KqS/s5eLLuZV+36BycZa4kGP/IdYyyeaexnT4iwrWkrnJwawvngEbfrVi41eOQjy/mwOdvzN+Q5r2n4df8EyPGXxWO3S/Gnw3kuG/wCWLapcxSD8DAucex/OvVfDX/BC34mXk8cereLvBNnbt96S1W7umA9t6qrfnWkMnqNXCfEGGTu5WPiucGUSOzKAOhaRVH6kfrWbdWMkjpNGsjL6hSf5f0r9OvB//BBbQbZ0bWviRrlyvXbp2nRWB98Fi+frj/GvZPBH/BHn4G+ELZftnhmbxVdJgrca1eNM+e/C4X/x01pHI6jZ5+I4toR0pLmPxfkMty6xw4km6eUjBnP4A5rqvD37KPxb+IUNvdaD8NfG2oW+c+ZHpjxRt+Mm3d+Ga/dbwR+yn8OfhgyyeHfA/hTSJV6SW+mwrIOMffKFq7cWPkR/8s2XIG0ZCr+GSOPoK7KeRpL3jyq3F1V6wgfz1eKPDHiH4ca9/ZPiPR9V8P6nGdjW2oWz27A/VhtP4E1k3urTxKfmyQpLY6RnOME9M/TIr94P2l/hZ4B8V/DnV9Y8deGNF8QWfh+xuNRc3lqjSxxwxs7AScEZCkde9fzs+MvjbY6h4nvLhdJtdP065uJpooLRTuso2fMSKC3zKEwCTzkjg9a8/H5aqWsT2cnz2eJbU1Y9G8HeIvs93tuGY7iSNteg2fimO8tNucr6cV88aP490fXJEax1JfMGcxyAow+uRj9a6zTPEnlxqszuino2QV9uQTXnezifRy95KR6B4q1+K6iFujL5jHEbj7p559+ntXWeC9bt7TQ4GXazMmGbvwcV86+OfEl8sy3duyySQkb3jcfvR0wRnAP9a3fhZ45fWNHRo5j5MOUO44KsTkjHt7UeziRKLsfROmsuqyfL8uc/e6VHc2awltzbd3de1cHonjiSELtk+Vcgn146iuo0rxEurwxMGDdcg/SuetTiYc0luXLOKRHX55GjXOcd60IpVnaGZYZFa3YMQAPmGee9UkUBS3mbRjPy09WW3Me95Akgzhun44riloyW7lyF47jLJLG/Khhzx1B7e9OUbWWMlNqScMD+WfxxTROvmg7IZfL5BU7Tj6d6z5b2JrvzI2Yq0m4qe1HMXGKa1NRYY3Rvmx5mXJ9G6flTWs2udTXLI0flbQynq3aqtne5KruVF2sC31OatWMawK3ZV+ZHbs3b9aqLuTOKRpw3cgsdvO9RtO04IP1qkuy7G4RgbflO5dxJHvUyXhhuoWZWQTnLMRld547dqrx6jBa7vvfOxccetEpWMeY/cKkd/LQse1DvsXJrzr9p79oTS/2cPg9qfibUFDyW6lLS3c7ftE5ICJ9CSCcds1+rH5GTftB/tG+Ev2dPh/NrnirUobK3xiCBmxNdvkYSNerNkjkcDrkDmvyx/ae/a18ZftneJWW4+16T4RgfNppQlI+QHhpv77HHA5wcHtVWH4s3nx2+K83in4iXzalqEs2+ON28y205CCfJgjbG0BeCwGev1r6c8DeEPhn4/wBNSG2ksYWkG8E7VKtjnBJz+Yq4U4t8zM5y6HyZoXhyPTkDBZpW2hNzyb3CDovQcA4rVidkOGXavoetfSHjH9ihZo/tejTLJHLkxqJQS34DNeReKfgR4j8K3TNcWMzRrnDKCcV0XMTkFfcCfSotQiN3YzRqPmZCBV17KS0Zo5IXQ++B/XNRvHtIXDKSeKLjuYvhjwtJfaekMke0KCMdweop17DNp9jd2V1B9osrqFoZ4znEiMMOv/AlJGfeu18D6XHf30gRn8z0bADfTmuxv/hSuu6c37mQqw++Dtzjnjv+lJ2aszSnWnTlGpTdmnc/EH/goX/wTk1L4U6/N4t8C6Peax4QuC08tpBE00ujAn7u0DcUyfvDOBycDJHyXa+GodQVrq4u7WCELkKXB3HpgdmIPJAzgDPav6LvGPwCvIFkaKMtHIpVkYFgwPBBHGa+Lv2m/wDglP4V+KV3Je2unP4b1Zslp9PiCw3B6/PERtH1XB+vSsfZqOsT6H+1I1ryq/E0fmBo9j4b029hkh/tW4j8siYjYilyMHByeOeDivp/4A/ttX3wu0CPTnkuryxjAEUcrqZLfHAUMBnbt/Wszxh/wTP8TeABJts7zVIYmystnNh+vHyEf415T4+8Aan8N3ZU0fxZbXXSWW6gJhA9iqnP44ry8Vzxnzo/YPD7iP8As2HtYRvp8vuPtzQ/+CiOi6rFGDfpbyY+aOaVh+pGP1rQP7Yljft+41CwYP1xcqT+Wa/NT+05HmZmkm3fxZPI/DH86bLrsrvtFxPGMHkc/pj+tcMcVFu0oH69hfF6pRXNVoKXlY/RPXv2pbNFZpNStF9vOC9/WuT1/wDbV0O3/dtfGbsypKTn8cY/Wvivwp8O/EvxBaRrHzJoYxnzHO1B7EnjNZ9z4D8WWGr/ANmxadfT3MhwI4ojKWI54C59KVXDxqP3UePnXidVr0/aLBcsX1PqzWP22LO6uWh0uC8ZmJAZ5sLwM+tO+Fnxb8ZeJ/jvDoqlYY1lUSxRtktkZ+8CDyDWR+zJ+xX4gtPs/iDxFppjuYmWex06YBvNdTnEy/wocdsn2r3v4VfBqP4X+OrjXr6GM31w00z7Y8EPI4YBT6LjA9qvD5Xy+/I+A4jzajLLnJaVJar0Ppj4paP4U+HXwpsZpFum8RagymFft0nzICN0m0kjb1HPOSOO9ea6bf6fqi4mhkk3f89ZSyj8Ky/Fup6h8T9b+3XX+uxtSMH5IEUfdUn+E/ePuO9ZepT2PhOwWbU9U0zSIZDiKS/vI7XzcdSochmHuBXoezp9EfiFTGV7W5meqeG7uxsEUQ2dqrdnSMKw+h5/lXTaf4mkUrtkZh2B4x+XH5ivFfA/jrSvFMwj0fxDoOrOOqWWoRXEi8gfMiMWXrn5gOldzpF/IjxbmZt4bBUHqOCMdc9+nSt6dktI3PPl7Sp9pt+ep6JH4j3J82c+uB/hWnYeJGMW3qvfNcHb3+4gbhuP8Ofm/KtjT7ln+UhguQpbsMnA9+p7CtV6WM5Ri9GdlBru4/KAp9qvWetN5i5JPfGcf41x1rc7Y45h5m1wcHbjBBwQc9/atfSy98V2+vSnc55JRdkdxomuzySq6cMp/jfd+RABFe+/BP8Aau8RfDlYYP7UvJ7NT/q7pvPUD8TwPpXz5o9l5ESsTtx3NWrrxTDpUbBptpA6rzTuuo43bP02+CX7SuifGFoYY5obfUmGPJ37lmIBJ2E4I6E4x2r0yKYSdu5BHoa/DvV/2qdS+GWtpe6XqbW1zatvgK8BW/8Ar9Pxr9cP2Ov2lNN/am+CWi+LNPZme7Q294px+6uIhtkU4J6k7h7enSspNcx0OLSuj1YqCKbcoXtpFUqNykZPbNSE4FQ3TK9tKGO1dhyfQYpVJNNcpEeZp3Pjn/gt38bpvhL+wT4it7SRre88WXdvoS8gSeTIA8wHP/PNXU+7D3I/n38RSNe3mW3JsYudhwcnaAv+6NoNfp1/wcR/tGTeK/jf4Z+G9s2LHwtZDWLxQRk3VyCADg/woDnPQsOvWvzdOlLc3DSMvDelfK5vU569l0PvuHsPKFHnfU51Id/zSMzSNxlj8oH071V1PUNW8Nru028uIV6kGQsn5V2lr4ZXyXZlX2zWfq2hSQxl9qsuOB2ryZSl0PsKMklynFyftQ+J9AAW+s7HVIlGMpF9nbH4Zz9TS+DP25NG8M655l5oOsabHOfLnSG4F1Dg8F8NtIP0FUvGPhhbiDkbWb+7XjfjXwy2n3LtukXtuQcrnjI9xW2HtJ++dXutH6FeH/F9nrulWOr6XeJdabqUebORG+WZO59iDwQec/nXZeA/Fpju7iJmUNCM9ent9a/N34B/tP3vwEv2027aa68L3blp7RVDyWpwfnhLEYJOCRkCvub4PX9r8QrC31XTb9LrS7uMiN42zkKR8r+knOccjAPNGKwsk7rY8uUlKdj2rTfF8bzR+WWdWPzZ6Ct7S9bWRGZv3gydqsdpUV5S+pR22orFC4Cq+zA9fU1qQ+J5LaGOYNjzASQfY4/nXkzg1sbqiktT0WTUjchGVEGD8wPX86rRE3mo7mfcq/dJ4x9PWuPs/F7SQGZy6tJwR2xW1p2rbIldcsq/dXuc8Vim+onG3wnSaVNb3N80auG8r7x7VsSiWaSPyf4ew71z+ihom87y449/Lc9a0+buP/WFRkfdPvVcxlO/U1bS6SHUApPlqvzFV+bOOe9ULO0ee3VvtDntyopnm7p2QLIrY6YHzfjU8FrcNEDEvyHpzRzCjTTR+4lwMwsP73HX1r4k/wCCxXie3tfDHhC1vrhbXTIJrrWb6YoXWCCCILI7AA8BXzxnoT2r7Y1FmSxlZCqsq7gSM4I5r4T/AOC5vwL1T4jfsvaj4g0eS7j1DQdN1GzH2ZiN8Vxbg4ZQOhZMf8CFfrG+iPxuUrWZ+Gvxo/4Kf/Fjxb8RJrP4Q6Loll4ftUzvudPS8kuAW+V383ARiozgEnBHSvTPhD/wVd8S+FrjTbf4geAZo5pRtl1nQL070wM7ntpSy9uiSL9D90+C/CDR3tvA84mj8m4e/f7QWG4tKUUnI46bcAdACT7VrXNmqYXy4wrH5crw34V+g5Zw3hq2HjKcveavufE5hxFWo1nGMbpM/S79nH/gqX4f+IesyWXhH4gW+sXkYAex1AvZ3bnugikRdxHX92W6elfUXhb9tqyuW+x+KLBbWTbhkuI2jfB74Izz245r8Dde+Guk67JNJcWaeZMMM6kN+PzAkfgQR2IrrPhh8cPit8B9AfSvCfja8m0uP5oLHWIotUt4/VQtwrOoI4+WVcdvSuDHcL4mm+aj70TqwvEtCr7tT3WfvgnhP4bfF2zWSya0juepVgUZCfUkDFcb41/YouFWSfR5zMrDKqJAfyr8r/hD/wAFbrzwpYQx+PvA+qafd78f2h4XMlzaL/tm0ncumf8Apk7demK+z/2dP+CmmmePLiOHwj478O+IpGAJ015PJv4Sf4Wgk2yZ+gNfPyozpvlqppn0FGtCa5oNSOl8QfBXxD4GuFaSzut0TEq6547Z9K6v4b/GiHQJ47LxBaywxocG8RC7uP8AaUdPwzXovhL9szStYma38SaWyyofLdTGTg98oMsPqQBXUXfw3+HPxlt2ayks7eVxlcXC7s9eikkfjio917Gt+6NjwH4U8P8AxX09ptGvrPVI9vzCGQMyfVeo/KofEP7JFlq9s6tbNn1XBUfUjgfjivMvF37FOraIwvPDt9PujO5SkzKy/RlC/qDVa3+P3xw+D1ukLX8t5a23C/brQThR06qUP55qZaaCVlfzLvib/gn1HqSM0OnoZOceXEHY+vQ56V5h4x/4JtpeRtDNYG4jk4EU0I5+obIr06x/4KieMtNmZdS8P+HdQ7FiJomPr8u4it2x/wCCqkMhK33geNmI4WC9KqfwK/1rCVOMtzuw+Mr0lanJo+OPEn/BH7wpcXEjSeCvD7PKcsx0+KLP1Ixn8x/SuW1H/ghz4F1KbzJPBdirHnfGzRoPxVx/OvuXU/8Agp9Zz7vJ8AxRt2aW+Jx+AUfzride/wCCgfiDU52az8P+GrMH7rPFLM6/99Pt/NTWcsPS6o9KOe45wVPn0X3nzJ4Y/wCCNPgnwpAbe30VLeAt5hjV5pFZvcGQjH4H+tdPb/sR+CPhBaPLcafpunKwG4sAc88dDuHOOn8q7bxb+1F4y8V+Yj6hb2scgwUtYRH+R7V5zqstxqTtNcXEs0hOWMreZn86nljHSJ6X9vZjVgqc6rt2Ob8c3uj6NHNaaFbqyyDDzABF4ORxgk/mK8x1Twu2oszTnzJGPJ9PpXqWq6R5qNt24PbGKym0L9590fjQtDlnXqX9+TZxGm+EYFkjTyztJwSR09/w6187/DrwZZ/Cv4k+LNS+O3hO81rUJtQLaN4vn019YsTYE4S3ijiDC3ZcjkqcnivsJNKwdu1PxXcPy4q3YWzWtz+6JhVuDsY8f7o+6v5Hj06iXFM87EWtdHx9+0B8OdL+O2n6JdfCHwfqM3ia1vbV28Vpo7aFBpltECZjJJIsLTArlcGI8E45xXqXjbxxcWoit9Luo7hLdU8++e3yt24XDbASpK7u4r3LxB4ZTxjpTWN1dXUUDKeYnbLY5wQzEHOMdutcPB+zLfS6zZ+TqFlJZSSIu5VEMkA3DCbMsDnp1HXnivPxjqxdqTPs+D6OUxk62YS16IwPh544vfEV2tjNpLBUUPPJbyr5KJjPmODzgdcAk+xqn8XvineaRLceGPDc1u3iq4jaKBmUyeVNtL/ZwVBXzXhVyvPyjnIYBTo+K5dS0zxVY+HfAl3ZnR7y08+Zo4P3kpMwSaUSEZBVdyYI+8RjIyw9O+Fv7PWn/DiN/sTX85kdZjPeSCSVOQemOXB5EmQeNuME16GFi3T9/c+Zz/E4atjJSwseWK0OY+DvwnbwzNc3V1e6lfX2onefOumkhiVgrFFUgfMjAhn65OMEHI9a0TQ101FkYdP/ANVaOm+HorJPMZVXbuP0JPX8ep96z/E2vx6ZbM/mx4A6ZrWpyxPD5bsb4g8ULpUch7RjOPWvGPil8ZmsjIVxyMACqXxg+L3lW8kccybm4JU+9fPPi3xfc6rctud2UE5xzXLKd2dlKglqaXir4g3Gr6g25sLk5IG4gd+K/S3/AINyvizqUvi/x14Nubwy6a9lFq9pDjKpKsnkykehJI4r8orzU7bRrFbq7+0XHnbkgt7Ubri9l2k+TF2LEdWztXnJzxX64f8ABul+zRrnhy48afEzX545bjUoV0CzSBSlvDGJPPmQDA3sr+UDIcEkMMdCY3ZVZRS0P1Jf7tZXirxDY+EfDGoatqU62+n6bA91cytwqRxgsxPtgGtZ0+WvnP8A4KxeKJvBv/BOn4uXdvvEjaFJbnZ1CzOkLf8AjrmtKnuwcl0RzUU5VY0+jZ+DP7Qvxdvf2i/j/wCL/Gd+8k02vapc3QLfwQl9sSr/ALARR9CR9a53TNJ8+ReBjPerHhPSPtq7drR+Wz4Hqu5uPpjaR9DW5qOp6H4Sjjk1C+tLNT1Msqrj8M+vFfCVKzlNyluz9fwdCNOkqa2Q+Hw/CIVO1WAHI7msjxHo9pFZ7mby/Y9Kz/Enx48PaPj7NdXFzJj5BDbSShvxUEVx2qeMPGnj2Fl0vw7qFrG33JbyAxqw9R1PT1p6dDoVK70M7x6LewQ7ioC85BDD9M/rXz38WPGlsJ5YY2Vm/wBkg9/rmvcrj9nLxV4t8xte1TyYZPvR2cXlKfYnqefaof8AhlPQ/D1q7Q2O6bu75kZj9T0qoyin7x0cjXurc+OdRs77V7kusEhUn5Seme1fX3/BKHxTdK/i7w60kjN5cWoxBzyOqSYHQAMV/CuZ8SfBKOJ3b7PtEYJUDgZ7dvXFdr+x14aXwX8fLkwgR299pd3aMDwSgdXH45BrsqY6MociOWOVv2vNNn0DPeGfxPtWWNJIwcru6nBGfpW9q8vmMyrIvkxlSW7Ad/zbFYvg3wpYz69fXBXdIsgtlkD5Azznmt6+iXR4IktyrfbAcluduD3/ABrx6kWtzSXuy5URwar9pkWNd23oox1rs/DNvKAkbLIWbpt/hrnfCvh+e6vdzM7GM7ijLivRdCMcSt/FMw2kr0WvPqSaYSk0NsrW4hutjTbh/dzW/prLZ/6x+M/e/hqtYwrAuCv7xzguegq1aR/Z53hkVGWMErv+6aIyvuZyXNuXLi9hkm2vtYPwpHQ1HLqD2D+VDas0a9CWqvF/oV9HDHCZFdSytj5V4NWobNtn72dFc84z0p3ElY/cu4i8+Fk7Nwfp3rG8X+D7bxh4Z1LTdQSOSz1K3e3nTG4bCMZ+oFblNnj82Lb3yCM9iORX6z5H4xY/nz/4Kg/8E49e/Ys+MGreINB06/1TwLrbtcv5UfmGFWddrqq85z970GT0Br5Lsruw8T27S6Xd29/EuceW4LREdQR69eBmv6lvih8G9E+L3hebSdds4bqzk+ZcArJHJnO9WBBH06EcHI4r83/2o/8Ag3Q8MePr7VNc0HyW1GcF0ksbiTTrwnPHy7jFIR152Zx3PFfTZXxBPDxUJ7I+bzTh+GIk6sbp+p+QU2mt32rxkZPJ/DrVSS1LRbthZf8AaQj+eP0r6W+Nv/BKn41/AO2aRNR/tqzjVglrr1iun3dwF6Ikg3Ix6YJYZOB3r598Wtqnw5mtIvHHhXxF4PmvB+6k1KNjb57gSDKdPQkV9fhOIMNW2dj4/GZHiabva6MSfT1u4WjliWSOQfMsreZ7jGRxzjp+Y61ia98ONG1y8huLmzh+0W64imTOYG7Mm7c6kdeJO1dlFFb63ayTWFxa30K8lraZZRj1+UmoX0aSJkbb8rruUhlOfbGc/mK9h06GIp/CpHnU6tbDz0biaPw6/ac+L/wVWSHS/Gtx4s0fYFSy8UxnWIbbH/PIsyyRZ6cOevQjg+0/BX/gr0ukao1t468J614Oa3Ab+0PD0zX9nJ/tPCyrIo9k346188tatuZVXbzyQeoqOa0WWFoZ1W4jYkYcc7f8a8DFcO4aT933We9h+JK8Hacrr0P1m/Zi/wCCnem/FeyabwX4z0bxgtvxLapOyXaD/bgcCVfxUdK+jPC37Zvh3xc32XxBp8NvNjbIrbWZM+ozkfjX89Pij4U6X4mmt7gxzQ31mR9nuBPIZIQDn5XUrID2+8QM9D0PpXgz9rf43fCdrG1sfFp8W6LbsijTfENp/aGyPOCguUVZlUjjJRto57Zr57GcN4un71P3ke/h+JMJUfLJ2Z+82o/Bf4c/GmItYXNrDLGCcRdifU9P1ry34h/8E/NU0yBptHu2uYPvEM4wfTBr84fhD/wWW0i38QfY/FHhnxJ4HnVsm906f+3NNt0B2kMUIkiTPPMZP0619z/s7/8ABS+Lx/D/AMUj4s8M+PLW1AaUadeRtLGp4BdCQ6c9mUHPFeBUhOm+WrFxZ7lOqp60mpI47xP8EfEnhJnF1YyNtPDKwauZkhktpvLlRkx1J6LX2p4b/a78JePH+y+INNa3c/LJ5kZXYfcFQTk4HGava5+zn8Pfi0rS6a9rbyTDI2vtYd/unn9Kx92Wp0e0ceh8NxxDzc7uewIPNSSRkpX0P8Qf2DNZ0aSSTSZzeW/UIc8jPrivH/FXwi17wjcPHdWFwuzqdvFZyhrob067erOQaz3H+GopNO7nbj2rR+yyCUIysrnseKHtXU4Zce9Tys09u3uZD6cM5+WkFlg/w1qSW+Cy/KdvpTRb5NTaw+bnVmU44Mdt2ATx7c1zfjmHxFqaXllpMVm1nNDGbeQIUaaQuu9d3VcLuOcdq64WplPyrkqQRz6HNaNhphYktlizb27bvb2pcqvcmpIz/hl8HofCj3zrI0xvZhcEvGAbf5QoRMZ+Uc5Hc89a7toI7OMLu7fMWqvZzLp9g21VjVfRic1zPjfx7DpVq7GaPp0JraM7RJhHmJvFfi2HSYZP3iEY6Zr58+LXxhd3kih2jqMk8VnfFj4ytdStHHKuGJHB5rx/WdVm1ucIGZ3kcKv+0ScAfn68Vy1anMzrjRityDxFr02qXLL8rMx9axHh+zz2q/Z5Ly+vZTb2tkj7WuZ8EhCeqrxkyDKgAgEthTtQaPJ51lbW9m1/qmp5W2sw21mG4IzOeqIpOS3oDt3HAP1l/wAE5v8AgmPrn7TPjc3XnSRWYRU1rxM0R8uGNJBmwtRjAwQDjjP3iRjFZRTvZF1KkYq0Tmv+Ccv/AATf8QftU/FXzvMaExx7dW1tICtnosJB/wBGtEYY+cZXcOWJLHbiv3b+DPwi0f4G+AdJ8M+H7OKz0zSofKVV6yHHzO3qzEAk1F8F/gfoPwG8H2Hh7wzYw6Zo+noQkKEs0rH+NmPJI5/76NdqFxW6p9WcMqjY1/u14/8At6fBW8/aG/Y8+I3g3T4/O1DXtCuLezj3bfMuAu6Jc+8iqPxr2F/u1VvmYWcjYJ2jcQBywHJH4jioqJu66NE06nJOMlumfzF2OlXmiSSW2WhvLeUwSq+NyMu5WXHrkHj25xxl9l8ILXXtXW81COOZl5VnhVip9Ocj9K/RD/gq/wD8Ey9S8FeO9Q+Jvw/02TUNE1qczatpllbF30yc/O90qgf6l8DeeqkdCCSPhXSfEMd0rRLMsm1ixK8KD3O44GO3XOe1fC4zCVKc9FofrGV5lSr0U29TV0D4Z6XpKLMtrbSS4+95W1l+mDj9K1FtLW4by/JjjXoWDMWH07VUsL+S7jHll1XszKVB+masW9yLZ/lXd6k1x06k7anp8qbvcrajoELsoWH92nQ56/Wsa88KR3pK+XgN6DmupnuMjr97svQUlsi79rbVLd/SuiFXSzOuNNHmOufCyNnZtpZcHg1wdtpH/CGfEnR7qOPav2iOKVh02MrI/wCrA/QGvd9euEUsqsd3TPavMfiNo8ctpt/ebpCACByDkY/Wq92wezbejPYfhn8NozodxdXHFvcTySJk43c4U/Tmti/8OafI5jtLaFkUjDMx3Jxz+tQ+GILrVPD+n2MLyeTb26Biewx3992K7DT/AAssNsvy4f26H61wVqlnZHnyjyyM3RtGXTtPXZyy9XI+Y1q6fom0h9oVT1x3q/b6PJbpuVo129zn/CtC1gjuQA21mHcGuSSu7jvcqpZbdvy7l9BQyJG7FmDqpHmZ6gZ7VoXVkwX5W/dr1de1RxQHexUxMxxgDq31zVRixFdXmgvlkWJ2t5DlSoHygDvUWo6PNqF200ccjJJyCMAfqa028za6sqsxH7vDcKfes+HUZCp+126zzA4L+Zt3D6VW24H7nUUUV+sH4uI4ytRSwF4mUt+VTUMMigVjG1fwna6/YNb3lvb3UL8NHOvnI34Nz74BFeIfFb/gm58Pfinb3CPYnS0nGXtrZI5LKVuu5raRSh5/xBBANfQ2ymyQ71x/XFEW1sPRqzPyA/aY/wCDdqxj1a71/wAPQ3VnNICDJ4ZnaPcvYtay5UH18tx+PQ/C/wAX/wDgn38aPgNBcSf2Va+MLOF2aNTA9nqroOxidVDkDn5C3Q1/THPatIuTtZsjGPlI/EVh+MfhtovxE0ySz1zStN1S3mXay3NssnH1OT6cggiuzD5niKErwk7HFXy2hVjrFH8qt14hs9J1g2Gs6fqXhzU41BkhvrKaEKSO5K8fU8VeksftsDNahbkY3AwsJOPU4zj8cV/Ql+0N/wAEnPAXx00eSwk8v+y2AC6bqtsNUtYsdPLMp8yPnHRuOwr87/2kv+Dea98I63Nq3g9da8MxwxlS+jb9TsnXsWhkJkUe0Y469Aa+mwvFSl/G1Z8xiuGVe9M/PJrGSEK22UL3ZUJ21UutODKx3srYO0HHJ7cV6N8RP2Vvi58FWup73w7a+M9HsiduoaITJcAdPnt+JQfX5OOewzXnNj450XWZlhN0tjfscNaXsZgnQ+mGwf0r6rB55h6sbJ2Pm8Vk9Wk7yVynqFh9qxvCybeBvyflKkMM5yOvY/n0rnrr4T2YkjuNLmuNJ1aEkx39nIbS8Uf3fPhKEjGfvK1d7f6S9qjb12n6HGOxz0wfrVWSwYL8pXkZBDZ/D616VTD0sTG7ipI5aOKq0HaEnE3fAf7anxq+D1jZ6Xb6lovjzTYTjyvFNm7XUK+iXluUds9MujYr6W+Bv/BYHwnDqNrpviW38UfDLVtwWae6P2rRFY9/tMA3BSeB5ycEjvXyKbNo33b2Lc8EcVAbVShXytkhVl8wSsG5BHbC9+4P9a+dxnC9KetL3We3h+KK1N8tX3vNH7QfA/8A4KOTatpcMmk69oPjPT8FvM0u+jvlC5xl/LJKdc/MBXvHhj9qbwP8Trb7Pr1rDYzSAAvIEVCfYswr+cQ/DKDw3q7ax4cu5/DGsxspiv8ASriexnjGOf8AVyBGYnj5kIwTXr/gT/goN8Y/hLaKutrpXxKtpsIP7XC2GqBR0UXUKbH5xzJHn3zzXzWK4cxtDVe8vI+nwnEGErK0nyvzP3c8X/sm+BfinafaNEvYlkbDZjdcj8icfjXjfxH/AGFte8LmSbT4ZbuFBkYJbP6V8P8AwJ/4LEeCVubWy8QXus/CrxE7BfsOsq8lnuPdL1MowPT59oGa+7vhX+33rlrpUMjLYa/p5UOt3aXiXUMqn0kjLKT7ZzXhyjODtUjys9jnW9N8yPD9f8Aap4ak23mn3kLqcEtEQPzrJ8kofmVl/DpX3N4c+P3w/wDjnD9m1O1it5pgoO4NjdkHHTPFP8QfsGeG/iBYyX2gag0bshdBGVZc5x9ai0X1ua060rbWPhy1tvIm3NgD1NLcazHbZG5a6b49fDXWPg3q8ljqEFwqoxCyOoCsM14R4z8d/YhMFZflHDZ+U81nKKWxvBOerOs8R/EddJiZmmTbg8E14L8U/iy+pzSRrJGeegPvWT46+Icl+WjEg9+eDXE/Zp9Zmz5W5m7D71cspvY76dOMYlW/mfWr1jgbWIAPoSe/p9eg74FacehS6f5NnZ2kd9rl5gQ2bttEQZGbzZT/AAxgA+7cBQc5rodN0CbSNVh0vRre3vtekQysrMrRadDuVPtNwM8J82VRsF9uCApLD7C/4Jxf8E15/j/r09xNcX1t4VinMut6+4BvNemDKRAmchY8AgYPyKeM1KTexhWra2OT/wCCdv8AwTZ1L9pHxpded9st9HYo3iHxDPG0MmoYUjyLQ4xtHCjyyQB8xIYBT+zXwr+Eei/Bvwjp+g+HbG303SdMiEEFvEMLgDG9v70jd3PJqx8P/hvpvwz8O6do+i2tvpulaVCbe2tbZdkSJnIwpzg9zg/MefauiVcGtKatqzllK4KuDTqKK0JWgMMioLiFpIj+eM4BNT0MMijcadijPpi3MDLIqncm1ueCM5Kkenavmr9pv/gk58Kf2lNVGrTaXceF9e27GvtAmFm0np5ibSknPspr6gK4FNdN64OOueRWNShCatJGlOtOD5os/HT9or/gjr8S/gct5q/hs2vj7Q4WIBtFaLVY1zj54mAV8Z/gYn27V8uXlrc6Fqs1jeWd9Z3kIPmQXNrJbyp65SRVYY9SMe9f0T3Nqz5xt653E/P74Pbj0/SvLfj1+x98Ov2ntPWPxj4a0/VbiHKxXfltDdW2RgFZRzn6kj2rx8RklOcr09D6XLeJqlF8tbVH4RJqMURX51ZZjgEcjjnr0qve60rSMq+YNn8WOD9K+1f2qf8Aginrvwx0m71jwD4nj1nSbctLNaay62NxCg5x5pGyYDsPlOcdeh+OdH+FuoXsfmX+LOMSGNQsitISOvAPT3r5vE4SeHlabufcZbnFPFK8UZ9taPqWfLjaSb+71I+tbWjfBNdYMU2oZLI4cKvI455rsvDXhKHw9ZbII41X+Nwd0kn19K3Le9EaeWvCnqT96vNeIvoj0pS6kehaL/ZzbY1jVW4bH90dK6BJUEC8fe4qnZQrkbGLbuoq1DafvFj/AI1zkE4qd9ThlKzHgKu1cZUcqD3pXnW1uIyqIy4Jbb1Xjoc96bJL/Zkm5tjMvKdSpPbmszW9T+0vi4VY/NIZhEep/wAKoIyuzZbVre6iRY/l5wqHufes+9umi1CGFYPMkmUsAh4UdOScYrKM0Kys7SBY4SCFU/e5FSJq/mXa/vo1iaJkbcM7jnIx+IFO5pJWLcz29lFIDt8xiV4kztPvWRdM1+6tFGrKq7SQ5wSOtJPqy2l3jfbxlkOUMXzA981k3ur3MM22zt5JocffXoT370nqSfv9RRRX6ofi4UUUUAFFFFACOMrTQje1PoosAwx7hUUlozdo2wc4K/1/+tViilyoLHG/Er4F+GfixYNDr2j2d87AKLgr5dygBH3ZVG8dOgIyODkEivlD9p3/AIIteB/jVoE1ui6beJJkY1WELNED0EdzGBIhz3Oc9DkE19wsMio5YmdMKxVvUYyPzBpptO6ZnKnFqzR+A/7Qv/BBnxj8GXWTwXqfiPQ2t2J8vUEOpaWyg8bZIEzj/rohx1z3HyX8RPhZ8Svg1q95H4q8EX11ZJ8x1fRYPtlq59f3ZJj9/MC4r+qSbTvMhaP5RGRjgn5vXI6GvN/HP7Ingfx6JpLnRbOwupVIN1pqtZykkY52MFPvuBzyMV6mEzbEUPhk7Hm4rKcPV05dT+X7RvFek+JLXzLW+tWfO0xNKqup9OuM+wJxV2TTGkj3+W3ltwrhcq57gN0JA5/Cv2l/ae/4IBeBfifYXFxpum6HfalLk/aGt00u+Vs8FZoAI3YdcyJ261+eX7QP/BHH4lfASVo9A1zULi7tW+ew8Rw7I3UnHF9Huibg8ZCdhnmvqsDxdBrlras+bxfC1Re9S2Pl99OzGxX5duOTimwWflTOysyeYCGwTk/y4PQ+2a0PiJpXij4N+I49P8d+EL7RSyFobuNPMtZuOSsy/u3/AOAsTUei6ppvia3+0adeWtzGo/hlXOfTBOcivqMLnGGrL3WfM4jK8VRequYl/wCGbS/tPJmgSFc52W/7pPyXAOe+4NmsTRPAF14B8TLq/g/WNY8K3xYSO+j3bWIkYcjdGn7pxnqCnIzyDyO6msvMVti7tvfGF/M8frVdrRgP7p9a1xWDw+I0kkzPD5jisOvcbWp6F8O/+Cj/AMXPBerRjxRoegfEDTYyvmy2cJ0XV5e3DKzRyEHB+ZlyAR1NfdH7Df8AwU3tfGGqNa+HtQ1Tw74gtebnwl4iX7Hq0EZPMqxsx86LGTujLeuK/NGWBnGNyt2IK5VgeoPsRkevPbrWL8VJvEj+G9H1fQ9UvLXxJ4JuPt2iXMY86e1K9I1kbMnl4z+7JK/hXzWYcKxVN1MPofUZbxPN1FSrxvfqfut+0N8Z9G/aA+HwmuIoIb1rdWcA5c/N1/SvzE+NOty6L4hudPUyeX5h2kema9l/ZU/aWj/ax/Ze0fxdZyJHqhJs9ctEG37FqEK4mQD7xWTd5vTgj05rx/4r6HL4n8a/6mY7Xw2I26d8kDA/EivzvEuUJ8jP0XCqW72epwelaXNrd3mEtJtP3h/9eu2tNGvNN1P+x9Bt7S78RGJriW5uY1mtdJRCCZJ13Aqx/gQjcTgkBcsLVnpF1a6r/wAI3oMcbawIhLqGozRfudGtW4356GbnKxnB6McKCw+3/wDgmJ/wTStfi7Yx6vrUN5b+C7W6S4ne4j23ni+6UEPNcOfmZCCMMOMApjBzXPGPNqVWrO9kZP8AwTf/AOCZq/G65e81Aapa+BbeUy3OqXIDX3iW4OCV8zAPlgFh0KqMKCc5H6w+FPB1r4N0iz07Tre3sdPsYxFDbwALHEqrtUYC8nHJJ71P4e8L2vhfT7SxsYYbWxsUMcEEK7EhQcKgAGNoHb1FaZOBWkVZHJLXVjVXBp1N30NKEXNUGw4nApvmY9ajluVEDN83YfdNV31i2trZpZZ4Y4o/vOzgKv8AwLOP1qZabsNb2SLfnf7LUjT7R91j7V5/4v8A2p/h54Ftmk1Txh4ftdn3kF9HJIP+AqSa8g8b/wDBWT4T+Ho5F03UtR8QXCjCpaafIIy3oXkCL+OfzrnqYyjD45o7KWBxFT4INn07LdomQd3/AHyf8n8Kja+hTG6QJu6B/lz+eK/PPxT/AMFlfEZlmXT/AAXolvCT+6a7vpZZGHuAuB9Aa8p+IX/BS74p+NoZFttWtfD8L8eVpEHkd+7sWb8QRXn1c9w0dIu56mH4bxlTdWP1A+IXxd8M/DjTGm13XNN0lcbgLu4WF35/hVsM34A18nfHb/grfoOkLcWHgrTLrWL5BtW+v0MNnnPVVJDSd+g6818DeIPiLrHjW/aXWNSvtUvGOTJd3c0zfh5hYD8AKbFCxtR5jNj+FC5Kp+ef0xXiYriJuXLTPo8HwmqceavqdT8Vvjz4r+O3iGXUPEuoXGqbx8tuzstrb/7kWdox6n61yyQKJ92yYgLtOSCzfXgcCpi625+X5ZFGRn7rVDJfs7KskfzSH5VPGa8GtWnOXNNn0VGnCkuWmrEU+F3ND9z+LPGKitbfz2++iH1J4FQatqLRq+9vMWPHI429vxrEn8Rraxs2Tj0qI2aN43Ouhmh0wrukJ3dCv8R9qmm8TC2Vh8vm/wDLTcucCuJtdame3eTdGyt0BP8Aq/pVXUNYPkAvJyvLN/eo2djGpSu7nTap4laRC25hDH0UtjOeOnT9axLjWpLi52hm6j536CsS61mScKysrD0z/OnWV0Z3XeyrjJOT8prZRQRjym5NAbu5ZGkCwxjcWJ4puq6qtxJbqrRqtsN6BTySOmfxrJk8SQ27bS2/1X+E1mz67FMskmVjZm59NtVyIu9zcGpMl8JpP9ZcHc+fvAjrj2xWafE80U0o/eKu8lAvQCsuTW1aVl3N8w+Ut978Kqx6pJEu1QpUdC3WolHsNW6n9IFFFFfqJ+KhRRRQAUUUUAFFFFABRRRQAUUUUADDIpjRkin0UWAhe33Jjv8A59qq3uhreW/lMtu0TcGOSLegHQgDIxkZH41oUUrIDxD4jfsEfD7xnb3klrp0nh+6uuZJNOmZYpj1+eE5jPPbaPrXwn+0x/wbyeHfEgvNV0nTYdQvrgmVptEnbSr53J7o5eBsdTnGQDjBxX6syqzphW2tkHNMktt5LdGPXB4NaU6k6fwsznThL4kfzgfGn/glt8VfgoZLfTdYXVngyy6T4htDZ3SKP4VllZonJ7FXTJx9D4H41h1j4UzWtr428J654PurofK13C7QyY6lX+7jv8hbiv6p/EPgzT/E1iYdU0/TtSh/uXUCzAD/AIGDn9K8E+Kf/BND4f8AxEima3jm0lp2LSQRxrdWLE/9O8+9QP8AdK/hXt4PPa9HR6nj43JcPW6an86GmzWHiC2afTb6zvo1PLQyggfieKs/ZfIgaZ2CwRfNO6/MsaD724jgDGea/Tb9qH/g3ksdQ1m91rw/aTWdwowL/wALytCxXI62jkjdjr5brwT24r4q8ef8EhPG39vHT5/iPFHpKsUYarptxbXcQ/2o9xjk/FsfSvepcXxcXGaszw63CzupRlZI47/gkf8AELxto3gz4rQeGPAdx4y8OyeIYLtbhdXitXguHjcNAkUi/NheS2cDg817Pr/gf4m+Ndbk1GbwrZ/DO38xSb3U9Uh1S+gG4ZaFYUCKxHALE7c5wcYPtf7LXwG8LfsgfCmPwv4fnkvftV295e3vlrJJqF1KVBkKpu28fIsYJHzdc8V9dfsjfsHt8W76w8UeN7GbTtHkZp7HSHhMMt1tJANzGRlVzhgCfmwBxmvh8RJVKsprqz7jCylSpRgpX0PMf+CaP/BMHS/GOh2uqaxa3Fj4KguXuY7OWNvtGvXh+ZrmR2JIjY8kfMG6AqDiv080nw/DoUNvBaw20FraxiGKKOPYsCAYAXH8PTj9ak0/SY9Mjhjhjhhht4xFHHEgVUUDAUAdF/2e3rVrfWa0VkD1dwZ9i5/lTXnUR7m+Ue9Ek+yIsylR7kV5t8ev2pfBv7P3h/7V4i1SOGaQgQWiDfcznI+7H198nAwM1M6iirs0p0p1HyU1dnoI1KMgN821jgMBuU/iM4/HFZvizx/pHgnSZbzV9Qs9LtYV3tLdzJGqgd/mI/TvXwT8Yf8Agqn4q8RXE1v4V0mx0K1YbY7u6Bur1x64G2OPj3b8OtfL/jjxtr/xG1pr7xBrWpazJIxLLdS7o0z/AHV6celeXiM0jHSEbs+kwfC9aouarKyPuT9oX/gqnpOmmbTPh5bx69fbONVukaO1jbI+4hw0mPYY7818h/GH43eJvigyzeNPEup6wY/3scAnNrbwseMJHHgYx6kmuLvtZ/sCwV/LjztPzqNq4HPK84/AiuD8Y+JZL945BcRzbmZepwMY9vevncZmFecve0Pr8uyDDUY6K77ljXfE9v8A2gIYV8mInggKFP1yCSfx61atUa/jLeew4yokkZ2/Afd/SuLOmrdxsqSNJJIQFBPAbPFaHgXU1u7KZZJm+1Wc/lTo3DRkdOPSvJqOUndn0McLGEbQVjalme3n3FPmb33LIfT2PerVnIt3atMq4aNtpUdjWZd+KLXT71fNbNjdS+W5P3oXPGR7ZwOtP1fWbTTbhru3kk3W/wC4kjXG11/vdfvevtXPKLuRqtGa486VdyyZV+ibfSp31Nl/dshMknB9BWDaeJke8JjcKuzeC33cGsvxJ4naSby/m+b7rD7pxzwfwoUe5k4vodFNrCxXHlK3nSDOWP3Rx2rOutWN2V8yVo3XOAfvD6VzieJMSeXLvk4DRyFcbPrinf2xFHI7R/vpu5fgH6f/AF6JJ9CORLcm1PUMna0rqy/fHb2zVJriParM+NxwB/Efwqtc+Jsy4eBTt6kj7317Vi6jrkj3UnlbGixw390+1aU46alxaRsSaoqlmUsNrbeTg/lUZv8AzvvM2325NYf21Z5l3MVXb82fWpDfxRr8sm5uwqvZrcmUkaSOqbn+YzMcZJ6Cq+p6uIgyRt8sI4J/izxWPPrcgLbTwox9e3FZsjTTAZbgH+dbRirGcnfYtPq73Mr4PTv6/SnNI0wC7vl75qFNO8vYq5P93/69XI9PdHUN827+72quXsYznYfZJslVmYsVzj2q1NLh/lzSR6ewT5Vbd9KdDYzMmWjcfVTRyxXxMxVWT2Vz+kyiiiv0g/IQooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKAAjIqN4dy9FqSigCodNJk3fudwPB2YI/I1U1rwdY+JI2j1GztbyJhho5oUkU/muf1rWooeu5Kikc3onwn8PeGU/4luh6Pp8m7dut7SNMkcjPBrchtGilVt3C8YLbuPY9evqTVignAoK22GzyeVEW7Dr7D1/CqN9q8NjbPNNuSGNDKztgKFHUk57Dn6D8Kt3Mi+Q2VLDGMetfDn/AAUC/aluPEniK68B+HppEsLWRV1yeCTa8sy4PkKRkjZhS2BgnA6EkY16ypx5mdmCwc8TVVOBpftUf8FFJtQuLjQ/hvJ5gBMVxqypgqQcnyeDnpgkgcEkc4r5A1Uvfatdahe3V1dX1181xcvKxnlbuGZ8kD/dxU9pous+Ibv7PpenyrD9wTTNtUDrlgvPWq/i39n3xZaWMl9Nd2ty0fKxwwMGH/Aif6V4NbETrPmR+lZdl2FwUUnJXffU5bWtUW33Lny07Rg8L756t+OKyX8QKY/utg98CuOk8ZXia7cWN9bTQPbkqTMoUN9Oasapff6CrK+zHUHtXk1sQ27Htci+y7mz4g1ldQ0gxs20SDy8ntnj+tcRq97GIIPLUIqPKyleSckDmpLzWZFtWXd8yj5f9o+1czrmsbmwqc+YGDegxg/rXHKzd2dFHRFC88USR6vGFWb7xAZRypAJzj8Kh1Dxb9j8Yw6rCtxPJqUf2W4ym1UKjIf0ySAPxrM8QlryzljhZVnjPm7j02jkj1yQCKwxdQ3NqklvJC6spkVPmBLdMHk8jr+FLlR0qppZnbP4pXWLd4JF/wBHmjZ85GVYcDv1BwfwpukeNHn0+aykx9psSLaT1lQjPmfXHFed2F/BYapd2/kXCxb45VLt0JIDY596mn11tN8T29+smyKZXtrlfXAO0j9KzlFXOeo43PRNO8ReXC0YZmCjywfVO345qxFqjhVhZt0anILdRXFW199ju2j3ZRWX9ea1/t28FueTtx3rGUXfQyc0tjfn1JkhOx1DbhyOeKiu9VyZFMreXEBxsBJ/WsmOZjyp57CozvkJU7jI5GcDNOK6GFSpK9krj5blZRnzZ9r9s4H5Uw3caxrGqsAvQAc/jTl0qS4LbQGTdj5SCQfp1q1/wj7WuyaXanmdfMPl5+gbBP4Ct6dOXYwnUS+N2ZTEhydu4Fe4GaRoLi44XzHLfw7QM10ejeF7rVZS1rZXN4SQNtvEZjz/ALma9A8L/skfErxzL5Ol/D7xdeBhkSR6e6xnv95sAfjiuhYdt2SZw1cbRhrKaPIV0WRiqLHI23kDHNWE0doSu5Qo7h/3ZH4NjP4Zr7E+F3/BGj4x/ES0STVodH8Hwt0XUbnzZCOvKwhx+DMK96+EP/BBPRtOdpPHHjbUNY3c/Z9IgFhF9CzF2P1AFd1LLZvQ82rxBh4LR3PzIjMdtOoI3SNxGoGXkJ4wE+9+ldjpnwJ8eXzxra+BvFtw1wA0fl6VNJuB9Cqmv29+Cv7FXw5+AOjRWfhvwvpVr5eCbqWJbi7kPq0sikn8MV6emn+WgCvznGTxx6fLivUo5J7vv7nj4jijm/hxPxF+HP8AwTC+OPxYkeP/AIQW70e1UqTLq8qWquM9huL/AKV65a/8ETPijFAqvrngy3YDmNWkk2/jtr9YFscMv3W9S+WP5nNTeTIvRs/kP6V108noJWkrnkzz7Ezd4+6TUUUV6h44UUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFNlban4gU6kYZX9aAOS+N/i7/hBfhF4l1Yf62x06eSEbtu+QIdi592wPxr837/wvJ4dSCG4lW61q+zcXtzJ/rVJwxGfUlhknstfoB+1npsmp/BLUo0AZA0UsoPdFmjZv0Br86de8TTXPjHUmkk3TG4cMx7AYwPxGCPoa8vMd4p7XPqOG4rmlLqeheC4Y9IjT5l+Y5ZiuT+ddRe6lbvakebuBOSMdq8ks/Fkke35gQPWr7+NGaLhV6VUakU+WJ2YyjL42eN/tn/Di21iX+1dJj8ma3G98DCvz7V8x/8ACbXTK0UysHIwQemK+zPGk41Ozkjk+ZXB3D1r45+Knh+TTfFNx5aBUMhK/Svn82wqjJTp9dz6bJMVzL2cynFrEg/5ablH3dx5FQTT+cPVX6jvWdbwsJwGDbufpVqO227c7vl9K8zlabSPalU5diOHT/Mu4mXZMcneuT6d65HWPL0nX7i3VBDbXTHCqP8AVOOep7HGK7o28kamS1LRzt1KqW/Qc1Q1bwzJqW5riMXUMy4MbDa2719eOv4UutmzH6y30ucVqFk01xHP5m5oVw3v2FVbvRptR02ZflkKqXBB4zit6HwjqRultxat5Yz5czqyugx0wRzkcVpaf4PWxaOKdzGzHhSf3h9fl6nPtW3sVLbUzqVopc02kUdJU6ppcLbf30iIrN2JHHFdBBpzC7jjZWjeNfnV/lbPsOp/DPqcDJr3z9nb/gnF8UP2gBA2h+FrjTdGuDldU1GM21sFx1CuBI3pwh6/jX29+z1/wQ18L+GoVbx54ivPE0mebOwjays1OOQXB8x/zX8eh6KOWzm9jw8Zn2HpbH5g6P4JudduPJs4Jru6zjybVDcS/gqZJ/CvpL4Gf8Emfi18ZNMt73+xdP0DTLnmO41qZomcYzkQqDJ/30or9cvhV+zp4M+C+hx6b4Z8O6XpNrFjaI0LuPcuxLE/U12Vvp628u5flboW+8z/AFJr2aGRqLvI+ZxXFNSelLQ+E/hF/wAEMPCWhx2kvjLxNq2uXCgNLaWCLZW5b03DLEfka+kPBP8AwT9+DvgBV/s74f8Ah9ZAAC88JuHJ9SZCevtivYIoWR+u7Pc1Nsr14YWlFWSPBrY2vUfNOTOb8O/CvQfCAK6Poui6WH6mzso4OnsFOa25NKBbcrsG+rAH8AQKsquDTq19nHsc8pSe7Kx08earfKWXJyRz+mP1zUqw4qSinyoz5Ve4zy2z2o8r6U+iqWhQ1U2mnUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUHkUUUAZXjTQo/EvhfULGVQy3kLQ4Pqwx/Ovyf8Ajj4avvht8R7+1vIykq3AScr90OqsMjOOCMc+tfrjdR+dbsv97j6+1eO/tG/sdeH/ANoPTvMutum6sqCJbqFN+VByFIOM/XrXBjqLqRXLuj2MozBYap72zPzMi8SFvusze4IP8uatJ4gePB/eMndlUnb+HX9K9g8bf8Em/iRZavJFoepaLdWYbIkmlMRI7fw0zR/+CQPxU1QL9q8XeE9LDdQI5LhgPpgZ/OvMpU6t9T6PEZnhKkLOVjxvU9djeNnD7lUcnHJ/Dr+leC/F/wAQaVeeJGt4po2uEG51BHH49P1r701P/ghJrXiOJBffG/WEXGHgttDhWPnqBucnFdB4F/4II+C/D6qmqeOPFeoRgD5LaKCyBPfJRSxz/vD+lbVsLWqJLoc2FzbD4d86d2flddiNn8xSvl56n5c/nim6bHNq2qmysba4vL9efs8EfmyEeoVckj6V+0vhb/gjl8CfDTFrjwxe6/NkFZNU1W5mII9AGUYPQ+xr2b4V/syeBfgdBMvhDwn4f8N/acec1jZxxySD0LbckD1OTWNPKZX942r8WxelOJ+Jvwj/AGDPjX8Zb1U0P4eeIrWN84uNUjGnQkYzkNKykjjtX0h8Iv8Agg38QdfvYpvGnibwv4dtGOZIdOgbULgcHGS6hM5x/Ea/VhLJQNoHmbjzvYtj6bsirEVuUI3YIXp2x+VejTyuhFaq541fiLFy0joj4m8If8ELfhXohjk1TVvFuvTKQWWW8ihgf22LESB7A19A/DL9h74VfB8o/h/wD4Vs7iPGJ/7OjacnoSZGBOfpjNeuFOKNldEcHSj8KPKqY6vU+OTKdtpvkKFVYVxwAi7UC44G3nH4YqaO0kEwZpNyqOFHCg/SplXBp1dNlaxy+pH5WOmKBG3tUlFSo2DYaq4NOooqgCiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigBHGVpkkRaPH581JQwyKOtweqsVTZqY1XC7R6ruNLFCYnHyrs+uP0x/Wp9lGyp5Ve4WvuNAz2FO2GhVwadVE8qG7DSNGSKfRQURxwssmeKkoooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKAP/Z";

        $('#modal').dialog('open');
        $('#hfid').val(id);
        $('#hftype').val(type);
        document.getElementById("btnsavebio").disabled = true;
    }
    function modal_bio2(id, type) {
        $("#modal_bio2").dialog({
            modal: true,
            autoOpen: false,
            title: "Biometric Capture",
            width: 300,
            height: 550
        });
        $('#modal_bio2').dialog('open');
        $('#hfimg').val('');
        document.getElementById("FPImage2").src = "data:image/jpg;base64,/9j/4AAQSkZJRgABAQEAZABkAAD/4QAiRXhpZgAATU0AKgAAAAgAAQESAAMAAAABAAEAAAAAAAD/2wBDAAIBAQIBAQICAgICAgICAwUDAwMDAwYEBAMFBwYHBwcGBwcICQsJCAgKCAcHCg0KCgsMDAwMBwkODw0MDgsMDAz/2wBDAQICAgMDAwYDAwYMCAcIDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAz/wAARCAHAAYwDASIAAhEBAxEB/8QAHwAAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoL/8QAtRAAAgEDAwIEAwUFBAQAAAF9AQIDAAQRBRIhMUEGE1FhByJxFDKBkaEII0KxwRVS0fAkM2JyggkKFhcYGRolJicoKSo0NTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uHi4+Tl5ufo6erx8vP09fb3+Pn6/8QAHwEAAwEBAQEBAQEBAQAAAAAAAAECAwQFBgcICQoL/8QAtREAAgECBAQDBAcFBAQAAQJ3AAECAxEEBSExBhJBUQdhcRMiMoEIFEKRobHBCSMzUvAVYnLRChYkNOEl8RcYGRomJygpKjU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6goOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4uPk5ebn6Onq8vP09fb3+Pn6/9oADAMBAAIRAxEAPwD9/KKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiignAoACcCm76C24Ypjnyk3f1pWdyZSaH76BJn1qEXalsfz4p3nKnJ/nTco9xxlcl30pbiq7X0ahed27sCM0NdqVVh8qt0Y8Z/rUc1tWxk3mUofJqslwplCll56DI5qSSZYV3MflHft+fSkqil8OpOtrvQmoqvDfJKRtVue4+b+WakFyD0WT/AL4Naa9RxknsSUUUUDCiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooJwKbvoAdRTd9G+gBzNtGajaYEd6WR8oaqXkmy1kPl+YMY2khd/tn3peb2J1vZDpb4RjOyTHrgdc4xjOc9/f68V43+0D+3j8P8A9nw/ZdS1RtS1hztGmaaUluk9fMBYCPjP3yD7Zr50/b5/4KH3ukeJdS8C+A9QNrcWjrHrGt22PMhkxn7PFngjHys/UE8c818SW8m+6mmaPdNcSGWRyS7SOerMx+Zm/wBok/QV8tm2eSov2dFXZ9VlOQOuvaVXZdj7i8Qf8FddSvLxh4e8Hww2uTg6nfszv+CDj8Gqv4f/AOCtHiaK/X+1/CuiPa7vm+yXkyyY7YL5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VjxvU9djeNnD7lUcnHJ/Dr+leC/F/wAQaVeeJGt4po2uEG51BHH49P1r701P/ghJrXiOJBffG/WEXGHgttDhWPnqBucnFdB4F/4II+C/D6qmqeOPFeoRgD5LaKCyBPfJRSxz/vD+lbVsLWqJLoc2FzbD4d86d2flddiNn8xSvl56n5c/nim6bHNq2qmysba4vL9efs8EfmyEeoVckj6V+0vhb/gjl8CfDTFrjwxe6/NkFZNU1W5mII9AGUYPQ+xr2b4V/syeBfgdBMvhDwn4f8N/acec1jZxxySD0LbckD1OTWNPKZX942r8WxelOJ+Jvwj/AGDPjX8Zb1U0P4eeIrWN84uNUjGnQkYzkNKykjjtX0h8Iv8Agg38QdfvYpvGnibwv4dtGOZIdOgbULgcHGS6hM5x/Ea/VhLJQNoHmbjzvYtj6bsirEVuUI3YIXp2x+VejTyuhFaq541fiLFy0joj4m8If8ELfhXohjk1TVvFuvTKQWWW8ihgf22LESB7A19A/DL9h74VfB8o/h/wD4Vs7iPGJ/7OjacnoSZGBOfpjNeuFOKNldEcHSj8KPKqY6vU+OTKdtpvkKFVYVxwAi7UC44G3nH4YqaO0kEwZpNyqOFHCg/SplXBp1dNlaxy+pH5WOmKBG3tUlFSo2DYaq4NOooqgCiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigBHGVpkkRaPH581JQwyKOtweqsVTZqY1XC7R6ruNLFCYnHyrs+uP0x/Wp9lGyp5Ve4WvuNAz2FO2GhVwadVE8qG7DSNGSKfRQURxwssmeKkoooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKAP/Z";

        $('#hfid').val(id);
        $('#hftype').val(type);

        document.getElementById("btnsavebio").disabled = true;
    }
//    function formreset(id) {
//        document.getElementById("actiontype").value = '2';
//        $('#hfid').val(id);
//
//        $('#admission').submit();
//    }
    function formreset(id, type) {
        document.getElementById("actiontype").value = '2';
        $('#hfid').val(id);
        $('#hftype').val(type);
        $('#admission').submit();
    }

    function Save() {
        document.getElementById("actiontype").value = '1';

        $('#admission').submit();
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


<?php echo $this->Form->create('admission', array('id' => 'admission', 'autocomplete' => 'off')); ?>

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

                                                if ($counter == 1) {
                                                    ?>


                                                    <?php if ($party[0]['photo_img'] != '') {
                                                        ?>
                             <!--<button type="button"  id='btncap' class="btn btn-primary disabled"><?php echo __('lblphotocapture'); ?></button>-->
                                                        <button type="button"  id='btncap' class="btn btn-primary" onclick="javascript: return formphoto(('<?php echo $party[0]['id']; ?>'));"><?php echo __('lblphotocapture'); ?></button>

                                                    <?php } else {
                                                        ?>
                                                        <button type="button"  id='btncap' class="btn btn-primary" onclick="javascript: return formphoto(('<?php echo $party[0]['id']; ?>'));"><?php echo __('lblphotocapture'); ?></button>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#otheroptions<?php echo $party[0]['party_id']; ?>">
                                                    <?php echo __('lbloptions'); ?>
                                                </button>
                                                <?php if ($fivefinger == 'Y') { ?>
                                                    <?php if ($party[0]['biometric_img'] != '' && $party[0]['biometric_img2'] != '' && $party[0]['biometric_img3'] != '' && $party[0]['biometric_img4'] != '' && $party[0]['biometric_img5'] != '') {
                                                        ?>    <button type="button"  id='btncap' class="btn btn-warning disabled"><?php echo __('lblfingercapture'); ?></button>
                                                    <?php } else {
                                                        ?>
                                                        <button type="button"  id='btncap' class="btn btn-warning" onclick="javascript: return formsave(('<?php echo $party[0]['id']; ?>'), 'PARTY');"><?php echo __('lblfingercapture'); ?></button>
                                                    <?php } ?>
                                                <?php } else { ?>

                                                    <?php if ($party[0]['biometric_img'] != '') {
                                                        ?>    <button type="button"  id='btncap' class="btn btn-warning disabled"><?php echo __('lblfingercapture'); ?></button>
                                                    <?php } else {
                                                        ?>
                                                        <button type="button"  id='btncap' class="btn btn-warning" onclick="javascript: return formsave(('<?php echo $party[0]['id']; ?>'), 'PARTY');"><?php echo __('lblfingercapture'); ?></button>
                                                    <?php } ?>
                                                <?php } ?>


                                                <?php if ($fivefinger == 'Y') { ?>
                                                    <?php if ($party[0]['biometric_img'] != '' || $party[0]['biometric_img2'] != '' || $party[0]['biometric_img3'] != '' || $party[0]['biometric_img4'] != '' || $party[0]['biometric_img5'] != '' || $party[0]['photo_img'] != '') {
                                                        ?>    <button type="button"  id='btncap' class="btn btn-success" onclick="javascript: return formreset(('<?php echo $party[0]['id']; ?>'), 'PARTY');"><?php echo __('lblreset'); ?></button>
                                                    <?php } else {
                                                        ?>
                                                        <button type="button"  id='btncap' class="btn btn-success disabled"><?php echo __('lblreset'); ?></button>
                                                        <?php
                                                    }
                                                } else {
                                                    if ($party[0]['biometric_img'] != '' || $party[0]['photo_img'] != '') {
                                                        ?>    <button type="button"  id='btncap' class="btn btn-success" onclick="javascript: return formreset(('<?php echo $party[0]['id']; ?>'), 'PARTY');"><?php echo __('lblreset'); ?></button>
                                                    <?php } else {
                                                        ?>
                                                        <button type="button"  id='btncap' class="btn btn-success disabled"><?php echo __('lblreset'); ?></button>
                                                        <?php
                                                    }
                                                }






                                                // lock button
                                                if ($lockflag == 1) {
                                                    ?>
                                                    <a href="<?php echo $this->webroot; ?>Registration/admission/PARTY/<?php echo $party[0]['party_id'] . "/" . $this->Session->read("csrftoken"); ?>" class="btn btn-primary"><?php echo __('lbllock'); ?></a>
                                                <?php } else {
                                                    ?>
                                                    <a href="" class="btn btn-primary disabled"><?php echo __('lbllock'); ?></a> 
                                                <?php } ?>


                                            <?php } // lock   ?>  
                                            <?php
                                            $uid = $party[0]['uid'];
                                            $variables = $this->requestAction('/App/dec/' . $uid);
                                            //$uid= $this->request->($party[0]['uid']);
//                              echo $variables;
//                               exit;
                                            ?>
                                                                                                                                                            <!--<button type="button"  id='btncap' class="btn btn-info" onclick="javascript: return formEkyc('<?php //echo $party[0]['party_id'];         ?>', '<?php //echo $variables;         ?>');"><?php //echo 'EKYC(Biometric)';         ?></button>-->
                                            <!--                                            <?php //if ($_SESSION['Auth']['User']['user_id'] == '6') {          ?>
                                                                                            <button type="button"  id='btncap' class="btn btn-info" onclick="javascript: return otpEkyc('<?php //echo $party[0]['id'];         ?>', '<?php //echo $variables;         ?>');"><?php //echo 'EKYC(OTP)';         ?></button>
                                            <?php // }   ?>   -->
<!--                                            <div class="dropdown">
                                                <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    EKYC  
                                                </button> 
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <li><a href="#" onclick="javascript: return formEkyc('<?php echo $party[0]['party_id']; ?>', '<?php echo $variables; ?>');"> Biomatric</a></li> 
                                                    <li>  <a  href="#" onclick="javascript: return otpEkyc('<?php echo $party[0]['party_id']; ?>', '<?php echo $variables; ?>');">OTP</a> </li>
                                                    <li>  <a  href="#">Something else here</a></li>
                                                </ul>
                                            </div>-->

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
                        // witness 
                        ?>


                    </tbody>
                </table>
                <center><h3 class="box-title" style="font-weight: bolder"><?php echo __('lblwitnesslists'); ?></h3></center>
                <table class="table table-striped table-bordered table-hover" id="Doclist111">
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
                        foreach ($witness as $witnessrow) {
                            //pr($witnessrow);exit;
                            $lockflag = 1;
                            ?>
                            <tr >
                                <td scope="row" style="text-align: center; font-weight:bold;"><?php echo ++$counter; ?></td>
                                <td style="text-align: center;"><?php echo $witnessrow['witness']['witness_full_name_' . $language]; ?></td>
                                <td style="text-align: center;"><?php echo $witnessrow['witness_type']['witness_type_desc_' . $language]; ?></td>    
                                <td style="text-align: center;"><?php echo $witnessrow['gender']['gender_desc_' . $language]; ?></td>
                                <td style="text-align: center;"><?php //echo $witnessrow['witness_type']['dob'];     ?></td>   
                                <td style="text-align: center;"><?php echo $witnessrow['witness']['age']; ?></td>



                                                <!--<td style="text-align: center;"><?php echo 'Witness'; ?></td>-->

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
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#otheroptionsw<?php echo $witnessrow['witness']['witness_id']; ?>">
                                            <?php echo __('lbloptions'); ?>
                                        </button>
                                        <?php if ($witnessrow['witness']['biometric_img'] != '') {
                                            ?>    <button type="button"  id='btncap' class="btn btn-warning disabled"> <?php echo __('lblfingercapture'); ?></button>
                                        <?php } else {
                                            ?>
                                            <button type="button"  id='btncap' class="btn btn-warning" onclick="javascript: return modal_bio2(('<?php echo $witnessrow['witness']['id']; ?>'), ('WITNESS'));"><?php echo __('lblfingercapture'); ?></button>
                                        <?php } ?>
                                        <?php if ($witnessrow['witness']['biometric_img'] != '' || $witnessrow['witness']['photo_img'] != '') {
                                            ?>    <button type="button"  id='btncap' class="btn btn-success" onclick="javascript: return formreset(('<?php echo $witnessrow['witness']['id']; ?>'), ('WITNESS'));"><?php echo __('lblreset'); ?></button>
                                        <?php } else {
                                            ?>
                                            <button type="button"  id='btncap' class="btn btn-success disabled"><?php echo __('lblreset'); ?></button>
                                            <?php
                                        }
                                        // lock button
                                        if ($lockflag == 1) {
                                            ?>
                                            <a href="<?php echo $this->webroot; ?>Registration/admission/WITNESS/<?php echo $witnessrow['witness']['witness_id'] . "/" . $this->Session->read("csrftoken"); ?>" class="btn btn-primary"><?php echo __('lbllock'); ?></a>
                                        <?php } else {
                                            ?>
                                            <a href="" class="btn btn-primary disabled"><?php echo __('lbllock'); ?></a> 
                                        <?php } ?>


                                    <?php } // lock     ?>  

                                </td>
                            </tr> 
                            <?php
                        }
                        ?>  
                    </tbody>
                </table>
                <center><h3 class="box-title headbolder"><?php echo __('lblinterfierlist'); ?></h3></center>
                <table class="table table-striped table-bordered table-hover" id="Doclist">
                    <thead>
                        <tr>
                            <th style="text-align: center;"><?php echo __('lblsrno'); ?></th> 
                            <th style="text-align: center;"><?php echo __('lblidentifiername'); ?></th>
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
                        foreach ($identifiers as $identifier) {
//                            pr($identifier);
                            $lockflag = 1;
                            if ($identifier[0]['photo_require'] == 'Y') {
                                ?>
                                <tr >
                                    <td scope="row" style="text-align: center; font-weight:bold;"><?php echo ++$counter; ?></td>
                                    <td style="text-align: center;"><?php echo $identifier[0]['identification_full_name_' . $language]; ?></td>
                                    <td style="text-align: center;"><?php echo $identifier[0]['gender_desc_' . $language]; ?></td>
                                    <td style="text-align: center;"><?php
                                        if (!empty($identifier[0]['dob'])) {
                                            $date = date_create($identifier[0]['dob']);
                                            echo date_format($date, 'd M Y');
                                        }
                                        ?></td>
                                    <td style="text-align: center;"><?php echo $identifier[0]['age']; ?></td> 
                                    <td style="text-align: center;">
                                        <?php
                                        $imagedata = $path['file_config']['filepath'] . $identifier[0]['photo_img'];
                                        if ($identifier[0]['photo_img'] != null && file_exists($imagedata)) {
                                            $image = file_get_contents($imagedata);
                                            $image_codes = base64_encode($image);
                                        } else if ($identifier[0]['camera_working_flag'] == 'N') {
                                            $image = file_get_contents('img/camera_cross.png', true);
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
                                        $imagedata1 = $path['file_config']['filepath'] . $identifier[0]['biometric_img'];
                                        if ($identifier[0]['biometric_img'] != null && file_exists($imagedata1)) {
                                            $image1 = file_get_contents($imagedata1);
                                            $image_codes1 = base64_encode($image1);
                                        } else if ($identifier[0]['biodevice_working_flag'] == 'N') {
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
                                        if ($identifier[0]['record_lock'] == 'Y') {
                                            echo __('lbllocked');
                                        } else {
                                            ?>
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#otheroptionsi<?php echo $identifier[0]['identification_id']; ?>">
                                                <?php echo __('lbloptions'); ?>
                                            </button>

                                            <?php if ($identifier[0]['biometric_img'] != '') {
                                                ?>    <button type="button"  id='btncap' class="btn btn-warning disabled"><?php echo __('lblfingercapture'); ?> </button>
                                            <?php } else {
                                                ?>
                                                <button type="button"  id='btncap' class="btn btn-warning" onclick="javascript: return modal_bio2(('<?php echo $identifier[0]['id']; ?>'), 'IDENTIFIRE');"><?php echo __('lblfingercapture'); ?></button>
                                            <?php } ?>
                                            <?php if ($identifier[0]['biometric_img'] != '' || $identifier[0]['photo_img'] != '') {
                                                ?>    <button type="button"  id='btncap' class="btn btn-success" onclick="javascript: return formreset(('<?php echo $identifier[0]['id']; ?>'), 'IDENTIFIRE');"><?php echo __('lblreset'); ?></button>
                                            <?php } else {
                                                ?>
                                                <button type="button"  id='btncap' class="btn btn-success disabled"><?php echo __('lblreset'); ?></button>
                                                <?php
                                            }  // lock button
                                            if ($lockflag == 1) {
                                                ?>
                                                <a href="<?php echo $this->webroot; ?>Registration/admission/IDENTIFIRE/<?php echo $identifier[0]['identification_id'] . "/" . $this->Session->read("csrftoken"); ?>" class="btn btn-primary"><?php echo __('lbllock'); ?></a>
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





            <div id="modal_bio2" style="display: none;" align = "center">
                <div class="box-body">
                    <div class="row">
                        <div class="form-group">
                            <center>
                                <img border="2" id="FPImage2" alt="Fingerpint Image" height=250 width=180 src="" > <br><br>
                                <input type="button" value="<?php echo __('lblcapturefingurprint'); ?>" onclick="captureFP1()"> <br>
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
            <div id="modal" style="display: none;" align = "center">
                <label for="fingerdescription_id" class="col-sm-4 control-label"><?php echo __('Select finger'); ?><span style="color: #ff0000">*</span></label> 
                <div class="col-sm-8">
                    <?php echo $this->Form->input('fingerdescription_id', array('label' => false, 'id' => 'fingerdescription_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $fingerdescription))); ?>
                    <span id="fingerdescription_id_error" class="form-error"><?php //echo $errarr['designation_id_error'];                           ?></span>
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
                                        <?php // echo $this->Form->input('block', array('label' => false, 'type' => 'textarea', 'placeholder' => 'block', 'id' => 'block', 'class' => ' form-control input-xlarge'));     ?>
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
        <input type='hidden' value='' name='hftype' id='hftype'/>
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

            $('#admission').submit();
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
                                <?php echo $this->Form->create('other_options', array('url' => array('controller' => 'Registration', 'action' => 'admission'), 'id' => 'other_options' . $party[0]['party_id'], 'class' => 'form-vertical')); ?>   
                                <div class="form-group col-sm-12">
                                    <?php
                                    echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken")));
                                    echo $this->Form->input('optionsid', array('type' => 'hidden', 'id' => 'optionsid', 'class' => 'form-control input-sm', 'label' => false, 'value' => $party[0]['party_id']));
                                    echo $this->Form->input('optionstype', array('type' => 'hidden', 'id' => 'optionsid', 'class' => 'form-control input-sm', 'label' => false, 'value' => 'PARTY'));

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


    <?php
    foreach ($witness as $witnessrow) {

        if ($witnessrow['witness']['record_lock'] == 'N') {
            ?> 


            <div id="otheroptionsw<?php echo $witnessrow['witness']['witness_id']; ?>" class="my-popup modal fade in" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content row">
                        <div class="modal-header custom-modal-header">
                            <button type="button" class="close" data-dismiss="modal">×</button>
                            <h4 class="modal-title"><?php echo __('lblotheroptions'); ?></h4>
                        </div>
                        <div class="modal-body">
                            <?php echo $this->Form->create('other_options', array('url' => array('controller' => 'Registration', 'action' => 'admission'), 'id' => 'other_options', 'class' => 'form-vertical')); ?>   
                            <div class="form-group col-sm-12">
                                <?php
                                echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken")));
                                echo $this->Form->input('optionsid', array('type' => 'hidden', 'id' => 'optionsid', 'class' => 'form-control input-sm', 'label' => false, 'value' => $witnessrow['witness']['witness_id']));
                                echo $this->Form->input('optionstype', array('type' => 'hidden', 'id' => 'optionsid', 'class' => 'form-control input-sm', 'label' => false, 'value' => 'WITNESS'));
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


            <?php
        }
    }
    ?>

    <?php
    foreach ($identifiers as $identifier) {

        if ($identifier[0]['photo_require'] == 'Y') {
            ?>


            <div id="otheroptionsi<?php echo $identifier[0]['identification_id']; ?>" class="my-popup modal fade in" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content row">
                        <div class="modal-header custom-modal-header">
                            <button type="button" class="close" data-dismiss="modal">×</button>
                            <h4 class="modal-title"><?php echo __('lblotheroptions'); ?></h4>
                        </div>
                        <div class="modal-body">
                            <?php echo $this->Form->create('other_options', array('url' => array('controller' => 'Registration', 'action' => 'admission'), 'id' => 'other_options', 'class' => 'form-vertical')); ?>   
                            <div class="form-group col-sm-12">
                                <?php
                                echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken")));
                                echo $this->Form->input('optionsid', array('type' => 'hidden', 'id' => 'optionsid', 'class' => 'form-control input-sm', 'label' => false, 'value' => $identifier[0]['identification_id']));
                                echo $this->Form->input('optionstype', array('type' => 'hidden', 'id' => 'optionsid', 'class' => 'form-control input-sm', 'label' => false, 'value' => 'IDENTIFIRE'));

                                $checkedflag = '';
                                if ($identifier[0]['camera_working_flag'] == 'N') {
                                    $checkedflag = "checked=checked";
                                }
                                ?> 
                                <label><input type="checkbox" name="data[other_options][camera_working_flag]" value="1" <?php echo @$checkedflag; ?> ><?php echo __('lblcameranotworking'); ?></label> <br>
                                <?php
                                $checkedflag = '';
                                if ($identifier[0]['biodevice_working_flag'] == 'N') {
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


            <?php
        }
    }
    ?>