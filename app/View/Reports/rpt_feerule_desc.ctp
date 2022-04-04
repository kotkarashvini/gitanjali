<script>
    $(document).ready(function () {


    });


</script>

<script>

    $(function () {
        $('#go').click(function () {
            $('form').submit();
        });
    });
</script>


<?php echo $this->Form->create('rpt_feerule_desc', array('id' => 'rpt_feerule_desc')); ?>

<div class = "box box-primary">
    <div class = "box-header with-border">
        <center><h3 class = "box-title headbolder"> Report </h3></center>
    </div>
    
    <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
    <div class="row center">
        <div class="form-group">
            
            <button id="go" class="btn btn-info" type="submit"> Download Result </button>
            <input type="hidden" id="actiontype" name="hfaction" class="btn btn-primary">
        </div>
    </div>

    <div ></div>


</div>    

