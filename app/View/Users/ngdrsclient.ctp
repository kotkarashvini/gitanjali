<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>

<?php echo $this->Form->create('ngdrsclient', array('type' => 'file', 'id' => 'ngdrsclient')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title" style="font-weight: bolder"><?php echo __('lblngdrscitizen'); ?></h3></center>
            </div>
            <div class="box-body">

                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="center"> <?php echo __('lbldevicename'); ?> </th>
                            <th class="center">&nbsp;</th>
                            <th class="center"><?php echo __('lbldownload'); ?></th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td ><label for="" > <?php echo __('lblsequjenthumbdevice'); ?></label>  </td>
                            <td ><?php echo $this->Html->image('Biometric_Authentication-30-512.png', array('alt' => 'CakePHP', 'border' => '0', 'height' => '50px', 'width' => '50px')); ?> </td>
                            <td ><?php echo $this->Html->link('Secugen Client', array('controller' => 'Users', 'action' => 'secugenclient')); ?></td>
                            
                        </tr>
                        <tr>
                            <td ><label for="" > <?php echo __('lblscannerdevice'); ?></label>  </td>
                            <td ><?php echo $this->Html->image('scanner_icon.jpg', array('alt' => 'CakePHP', 'border' => '0', 'height' => '50px', 'width' => '50px')); ?> </td>
                            <td ><?php echo $this->Html->link('Scanner Client', array('controller' => 'Users', 'action' => 'scannerclient')); ?></td>
                        </tr>
                        <tr>
                            <td ><label for="" ><?php echo __('lblwebcamdevice'); ?></label>  </td>
                            <td ><?php echo $this->Html->image('web-camera-icon-14.png', array('alt' => 'CakePHP', 'border' => '0', 'height' => '50px', 'width' => '50px')); ?> </td>
                            <td ><?php echo $this->Html->link('Webcam Client', array('controller' => 'Users', 'action' => 'webcamclient')); ?></td>
                           
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<?php echo $this->Form->end(); ?>

<script language="JavaScript" type="text/javascript">
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
    });
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