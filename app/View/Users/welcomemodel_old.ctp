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

<?php echo $this->Form->create('welcomemodel'); ?>
<div class="panel-body">
    <div class="panel-group" id="accordion">
        <div class="panel panel-default">
            <div class="panel-heading" style="text-align: center;"><big><b><?php echo __('lblappliations');   ?></b></big></div>

            <div id="collapseOne" class="panel-collapse collapse in">
                <div class="panel-body">
                    <div class="row">
                        <div class="form-group" style="padding-left: 20px;">
                            <ul>
                                <?php foreach ($usermodules as $usermodule): ?>  

                                    <li>
                                        <a href="<?php // echo $usermodule[0]['url'] . '/' . $usermodule[0]['role_id']; ?>">
                                        <a href="<?php echo $this->webroot . $usermodule[0]['url'].'/' . $usermodule[0]['role_id']; ?>">
                                            <h4><?php echo $usermodule[0]['module_name']."-". $usermodule[0]['role_name'] ?></h4>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                                <?php unset($usermodule); ?>
                            </ul>
                        </div>
                    </div>
                </div>
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
<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>
</html>
