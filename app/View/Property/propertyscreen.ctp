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

<script>
    $(document).ready(function () {
        if (!navigator.onLine)
        {
            //window.location = '../cterror.html';
        }
        function disableBack() {
            window.history.forward();
        }

        window.onload = disableBack();
        window.onpageshow = function (evt) {
            if (evt.persisted)
                disableBack();
        };

        $("input[type=text]").keyup(function () {
            $(this).val($(this).val().toUpperCase());
        });

//        //calander code
//        $('#daterange').datepicker({
//            format: "yyyy-mm-dd",
//            todayBtn: "linked",
//            //orientation: "top left",
//            calendarWeeks: true,
//            autoclose: true,
//            todayHighlight: true
//        });
//
//        $("#btnView").click(function () {
//            var abc = $("input:radio[name=viewby]:checked").val();
//            $("#filterby").val(abc);
//            $("#birthchecklist").submit();
//        });

    });

    function selectarticle() {
        var article_id = $("#article_id option:selected").val();
        document.getElementById("actiontype").value = '1';
        document.getElementById("hfarticle_id").value = article_id;
        $('#propertyscreen').submit();
    }

    function formsave() {
        document.getElementById("actiontype").value = '2';
        $('#propertyscreen').submit();
    }

</script>

<?php echo $this->Form->create('propertyscreen', array('id' => 'propertyscreen', 'class' => 'form-vertical')); ?>

<div class="panel panel-warning">
    <div class="panel-heading"><h2><?php echo __('lblpropscreen'); ?> </h2></div>
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-3">
                <?php //  $path = WWW_ROOT . 'files/menu.json';
//        $data = file_get_contents($path);
//        $json = json_decode($data, true);
        ?>

<!--<span>Change language to : <a href="#"><?php echo $json['language'][1];?></a>-->
                </div>
            <label for="article_id" class="control-label col-sm-3"><?php echo __('lblArticle'); ?></label>
            <div class="col-sm-3" ><?php echo $this->Form->input('article_id', array('options' => $article_id, 'id' => 'article_id', 'label' => false, 'class' => 'form-control input-sm', 'onchange' => 'javascript:selectarticle();')); ?></div>
        </div>

        <?php
        if ($articleparameters != NULL) {
            foreach ($articleparameters as $articleparameters1) {
                ?>
                <div class="row">
                    <div class="col-sm-3"></div>
                    <label for="article_id" class="control-label col-sm-3"><?php echo $articleparameters1['articleparameters']['parameter_desc']; ?></label>
                    <div class="col-sm-3" ><?php echo $this->Form->input($articleparameters1['articleparameters']['parameter_id'], array('label' => false, 'id' => $articleparameters1['articleparameters']['parameter_id'], 'class' => 'form-control input-sm', 'type' => 'text')); ?></div>
                    <div class="col-sm-3"></div>
                </div>
                <?php
            }
            ?>
            <br>
            <div class="row">
                <div class="col-sm-12" style="text-align: center">
                    <button id="btnSave" name="btnSave" class="btn btn-primary" onclick="javascript: return formsave();"><?php echo __('lblsaveandcal'); ?></button>
                    <button id="btnCancel" name="btnCancel" class="btn btn-primary" onclick="javascript: return formcancel();"><?php echo __('btncancel'); ?></button>
                    <button id="btnExit" name="btnExit" class="btn btn-primary" onclick="javascript: return formexit();"><?php echo __('lblexit'); ?></button>
                </div>
            </div>   
            <?php
        }
        ?>

        <div class="row">
            <input type='hidden' value=<?php echo $actiontype; ?> name='actiontype' id='actiontype'/> 
            <input type='hidden' value=<?php echo $hfarticle_id; ?> name='hfarticle_id' id='hfarticle_id'/>
        </div>
    </div>
</div>

<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>


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