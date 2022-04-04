<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>


<?php echo $this->Form->create('contactus', array('type' => 'file', 'id' => 'contactus')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title" style="font-weight: bolder"><?php echo __('Contact us'); ?></h3></center>
            </div>
            <div class="box-body">
                <!--<div class="row">-->
                    <!--<div class="form-group">-->
                        <h2>Page Under Construction</h2>
                    <!--</div>-->
                <!--</div>-->
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
           // window.location = '../cterror.html';
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