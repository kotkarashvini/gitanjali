
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblsearchtokenstatusbydate'); ?></h3></center>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="office" class="col-sm-2 control-label"><?php echo __('lblfromdate'); ?>:<span style="color: #ff0000">*</span></label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('from', array('label' => false, 'id' => 'from', 'class' => 'form-control input-sm')); ?>
                                <span id="from_error" class="form-error"><?php //echo $errarr['from_error']; ?></span>                    
                        </div>
                        
                        <label for="office" class="col-sm-2 control-label"><?php echo __('lbltodate'); ?>:<span style="color: #ff0000">*</span></label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('to', array('label' => false, 'id' => 'to', 'class' => 'form-control input-sm')); ?>
                            <span id="to_error" class="form-error"><?php //echo $errarr['to_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div> <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group center">
                        <button type="button" class="btn btn-info" id="cmdSubmit" name="cmdSubmit">
                            <?php echo __('lblsearch'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div id="alltoken">

        </div>

      
       
    </div>
</div>



<script>
    $(document).ready(function () {
        $('#from,#to').datepicker({
            todayBtn: "linked",
            language: "it",
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy"
        });

        $('#Doclist').DataTable();


        $("#cmdSubmit").click(function () {
            var from = $('#from').val();
            var to = $('#to').val();
           
            if(from == '' || to==''){
               alert('Please select From and to date');
                    return false;  
            }
            $.post('<?php echo $this->webroot; ?>Registration/get_all_token_bydate', {from: from, to: to}, function (data1)
            {
              
                $('#alltoken').html(data1);
            });

        });
    });

</script>