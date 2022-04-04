<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>
<?php
echo $this->Html->script('jquery.dataTables');
echo $this->Html->script('dataTables.bootstrap');
echo $this->Html->script('Device/scanner.js');
//echo $this->Html->script('pdfobject.js');
echo $this->Html->script('Device/filereader.js');
echo $this->Html->script('Device/qrcodelib.js');
echo $this->Html->script('Device/WebCodeCam.js');
echo $this->Html->script('Device/main.js');
?>
<style type="text/css">
    .scanner-laser{
        position: absolute;
        margin: 30px;
        height: 30px;
        width: 30px;
    }
    .laser-leftTop{
        top: 0;
        left: 0;
        border-top: solid red 5px;
        border-left: solid red 5px; 
    }
    .laser-leftBottom{
        bottom: 0;
        left: 0;
        border-bottom: solid red 5px;
        border-left: solid red 5px; 
    }
    .laser-rightTop{
        top: 0;
        right: 0;
        border-top: solid red 5px;
        border-right: solid red 5px;    
    }
    .laser-rightBottom{
        bottom: 0;
        right: 0;
        border-bottom: solid red 5px;
        border-right: solid red 5px;    
    }
    div#response {
        color: red;
        font-weight: bold;
    }
</style>
<script type="text/javascript">


    function scanToJpg() {
        var docid = $('#doc_id').val();
        var path = '<?php echo $path ?>';
        var token = '<?php echo $token ?>';
        var saveflag = "S";

        if (docid != "") {
            $.post('<?php echo $this->webroot; ?>Registration/checkscan', {path: path, docid: docid, token: token}, function (data)
            {
                if (data == 1) {
                    var askUser = confirm('Document is alerady Exist...Do you want to replace this Document?');
                    if (!askUser) {
                        document.getElementById('response').innerHTML = 'You have Cancelled.';
                        return false;
                    } else {
                        saveflag = "U";
                    }
                }

                scanner.scan(displayServerResponse,
                        {
//                             "use_asprise_dialog": false,
                            "twain_cap_setting": {
                                "ICAP_PIXELTYPE": "TWPT_RGB", // Color
                                "ICAP_XRESOLUTION": "100", // DPI: 100
                                "ICAP_YRESOLUTION": "100",
                                "ICAP_SUPPORTEDSIZES": "TWSS_USLETTER" // Paper size: TWSS_USLETTER, TWSS_A4, ...
                            },
                            "output_settings": [
                                {
                                    "type": "return-base64",
                                    "format": "jpg"
                                },
                                {
                                    "type": "upload",
                                    "format": "pdf",
                                    "discard_blank_pages": "false", /** Default value: false */
                                    "blank_page_threshold": "0.02", /** Max ink coverage consider as blank */
                                    "upload_target": {
                                        "url": "https://<?php echo $_SERVER['HTTP_HOST']; ?>/Registration/upload",
                                        "post_fields": {
                                            "docid": docid,
                                            "saveflag": saveflag,
                                            "token": token
                                        },
                                        "cookies": document.cookie,
                                        "headers": [
                                            "Referer: " + window.location.href,
                                            "User-Agent: " + navigator.userAgent
                                        ]
                                    }
                                }
                            ]
                        }
                );
            });


        } else {
            document.getElementById('response').innerHTML = 'Please Scan Document QR Code and Enter No of Pages to be scan..!!! ';
            return;
        }
    }

    function displayServerResponse(successful, mesg, response) {
        if (!successful) { // On error
            document.getElementById('response').innerHTML = 'Failed: ' + mesg;
            return;
        }
        if (successful && mesg != null && mesg.toLowerCase().indexOf('user cancel') >= 0) { // User cancelled.
            document.getElementById('response').innerHTML = 'User cancelled';
            return;
        }
        var scannedImages = scanner.getScannedImages(response, true, false); // returns an array of ScannedImage
        document.getElementById('response').innerHTML = scanner.getUploadResponse(response);
        document.getElementById('images').innerHTML = '';
        for (var i = 0; (scannedImages instanceof Array) && i < scannedImages.length; i++) {
            var scannedImage = scannedImages[i];
            processScannedImage(scannedImage);
        }
    }
    /** Images scanned so far. */
    var imagesScanned = [];
    /** Processes a ScannedImage */
    function processScannedImage(scannedImage) {
        imagesScanned.push(scannedImage);
        $("#hfid").val(scannedImage.src);
        var elementImg = scanner.createDomElementFromModel({
            'name': 'img',
            'attributes': {
                'class': 'scanned',
                'src': scannedImage.src
            }
        });
        document.getElementById('images').appendChild(elementImg);
    }

    function done() {
        var docid = $('#doc_id').val();
        if (docid != "") {
            var askUser = confirm('Do you really want to save this document ?  If you click on YES den you will not change this document...!!!');
            if (askUser) {
                $('#scan').submit();
            } else {
                return false;
            }
        } else {
            document.getElementById('response').innerHTML = 'Please Select Document ID';
            return;
        }
    }

</script>
<script>
    $(document).ready(function () {

        //  defalut-settings
        $('#qr-canvas').WebCodeCam({
            ReadQRCode: true, // false or true
            ReadBarecode: true, // false or true
            width: 320,
            height: 240,
            videoSource: {
                id: true, //default Videosource
                maxWidth: 640, //max Videosource resolution width
                maxHeight: 480 //max Videosource resolution height
            },
            flipVertical: false, // false or true
            flipHorizontal: false, // false or true
            zoom: -1, // if zoom = -1, auto zoom for optimal resolution else int
            beep: "<?php echo $this->webroot; ?>js/beep.mp3", // string, audio file location
            autoBrightnessValue: false, // functional when value autoBrightnessValue is int
            brightness: 0, // int 
            grayScale: false, // false or true
            contrast: 0, // int 
            threshold: 0, // int 
            sharpness: [], //or matrix, example for sharpness ->  [0, -1, 0, -1, 5, -1, 0, -1, 0]
            resultFunction: function (resText, lastImageSrc) {
//                         resText as decoded code, lastImageSrc as image source
//                        example:
//                alert(resText);
//                document.getElementById('response1').innerHTML = resText;
                $('#doc_id').val(resText);

            },
            getUserMediaError: function () {
//                        callback funtion to getUserMediaError
//                        example:
                alert('Sorry, the browser you are using doesn\'t support getUserMedia');

            },
            cameraError: function (error) {
//                         callback funtion to cameraError, 
//                        example:
                var p, message = 'Error detected with the following parameters:\n';
                for (p in error) {
                    message += p + ': ' + error[p] + '\n';
                }
                alert(message);

            }
        });
    });
</script>
<?php
if ($view_flag == NULL) {
    echo $this->element("Registration/main_menu");
}
?><br>
<?php echo $this->Form->create('scan', array('id' => 'scan', 'autocomplete' => 'off')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border" style="text-align: center"><b><?php echo __('lblscanner'); ?></b></div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12"><div id="response"></div></div>
                </div><br><br>
                <?php if (!isset($filename)) { ?>
                    <div id="div1">
                        <div class=""><br><br>
                            <!--                        <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="col-sm-4"></div>
                                                            <label for="doc_id" class="col-sm-2 control-label">Document ID<span style="color: #ff0000">*</span></label>
                                                            <div class="col-sm-2"><?php // echo $this->Form->input('doc_id', array('label' => false, 'id' => 'doc_id', 'class' => 'form-control input-sm', 'maxlength' => '100', 'type' => 'hidden', 'value'=>$token))   ?></div>
                                                        </div>
                            
                                                    </div><br>-->

                            <div class="row">
                                <div class="form-group">

                                    <div class="col-sm-4 col-sm-offset-4" style="position: relative;display: inline-block;">
                                        <canvas id="qr-canvas" width="320" height="240"></canvas>      
                                        <div class="scanner-laser laser-rightBottom" style="opacity: 1.5;"></div>
                                        <div class="scanner-laser laser-rightTop" style="opacity: 1.5;"></div>
                                        <div class="scanner-laser laser-leftBottom" style="opacity: 1.5;"></div>
                                        <div class="scanner-laser laser-leftTop" style="opacity: 1.5;"></div>
                                    </div>
                                    <div class="col-sm-2">&nbsp;</div>
                                    <div class="col-sm-6">
                                    </div> </div>
                            </div><br><br>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="col-sm-4"></div>
                                    <div class="col-sm-4 center"><?php echo $this->Form->input('doc_id', array('label' => false, 'id' => 'doc_id', 'style' => 'text-align: left; color: red; font-weight: bold; font-size: larger', 'class' => 'form-control input-sm sample', 'type' => 'text', 'readonly' => 'readonly')) ?></div>
                                </div>
                            </div><br>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group"><br>
                                        <div class="row">
                                            <center><button type="button" onclick="scanToJpg();" class="btn btn-success"><?php echo __('lblscan'); ?></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <button type="button"  onclick="done();" class="btn btn-success"><?php echo __('lbldone'); ?></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <?php if ($view_flag == 'O') { ?>
                                                    <a href="<?php echo $this->webroot; ?>Registration/index_scan" class="btn btn-success"><?php echo __('lblscanpend'); ?></a>  
                                                <?php } ?>
                                            </center><br><br>
                                            <div id="images" class="col-sm-10 col-sm-offset-1"></div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <div id="div2">
                        <div class="box box-primary"><br><br>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="col-sm-4"></div>
                                    <div class="col-sm-2"><?php echo $this->Form->input('filename', array('label' => false, 'id' => 'filename', 'class' => 'form-control input-sm', 'maxlength' => '100', 'type' => 'hidden', 'value' => $token)) ?></div>
                                </div>

                            </div>

                            <br>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group"><br>
                                        <div class="row">

                                            <?php if (isset($filename)) { ?>
                                                <div class="PDF col-sm-10 col-sm-offset-1" id="pdf">
                                                    <object data="<?php echo $this->webroot; ?>Registration/loadfile/<?php echo $filename; ?>" type="application/pdf" width="100%" height="500px">
                                                        <!--alt : <a href="your.pdf">my.pdf</a>-->
                                                    </object>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>




