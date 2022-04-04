<!--<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>-->
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
<!--<div >-->


<?php echo $this->Form->create('welcome'); 


 echo $this->Form->end(); ?>
<!--</div>-->
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
<script language="JavaScript" type="text/javascript">
    $(document).ready(function () {

//        if (!navigator.onLine)
//        {
//            window.location = '../cterror.html';
//        }
//        function disableBack() {
//            window.history.forward()
//        }
//
//        window.onload = disableBack();
//        window.onpageshow = function (evt) {
//            if (evt.persisted)
//                disableBack()
//        }
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
