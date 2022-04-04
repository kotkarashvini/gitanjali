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
            <div class="panel-heading" style="text-align: center;"><big><b><?php echo __('lblchooseyourrole');   ?></b></big></div>

            <div id="collapseOne" class="panel-collapse collapse in">
                <div class="panel-body">
                    <div class="col-md-12">
                        <!--<div class="form-group" >-->
                        <div class="text text-danger"><?php echo __('lblchooseyourrolenote');   ?>  ( <span class="text text-default"><?php echo __('lblchooseyourroleclick');   ?></span>) </div>
                        <br>
                            <ul> 
                                <?php 
                                
                                foreach ($usermodules as $usermodule): ?>  

                                    <li> 
                                        <a href="<?php echo $this->webroot . 'Users/welcome'.'/' . $usermodule[0]['role_id']; ?>">
                                           <?php echo $usermodule[0]['role_name_'.$lang] ?>  <!--   <span class="fa fa-check"></span>-->
                                            <!--<h4><?php  // echo $usermodule[0]['module_name_'.$lang]." - ". $usermodule[0]['role_name_'.$lang] ?></h4>-->
                                        </a>
                                        
                                        <?php if($currentrole==$usermodule[0]['role_id']){ ?>
                                        <!--<span class="text-success">Selected</span>-->
                                     <?php    }?>
                                    </li>
                                <?php endforeach; ?>
                                <?php unset($usermodule); ?>
                            </ul>
                        <!--</div>-->
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
