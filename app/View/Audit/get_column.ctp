<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html"/> </noscript>

<?php
echo $this->Html->script('jquery.dataTables');
echo $this->Html->script('dataTables.bootstrap');
?>
<script>
    $(document).ready(function () {
        $('#from,#to,#curfrom,#curto').datepicker({
            todayBtn: "linked",
            language: "it",
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy"
        });
        var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
        $('#audittrail').click(function () {
            var audittable = '<?php echo $audittablename; ?>';
            var columnlist = $('input:checkbox:checked').map(function () {
                if (this.value != 'on') {
                    return this.value;
                }
            }).get().join(',');
            var lable = $('input:checkbox:checked').map(function () {
                if (this.value != 'on') {
                    return $(this).next("label").text().split(' ').join('%20');
                }
            }).get();


            var from = $("#from").val();
            var to = $("#to").val();
            var table_id = $("#table_id").val();
<?php if ($token_flag == 'Y') {
    ?>
                var token_no = $("#token_audit").val();
<?php } else { ?>
                var token_no = '';
<?php } ?>


            $.post('<?php echo $this->webroot; ?>Audit/display_table', {columnlist: columnlist, audittable: audittable, from: from, to: to, token_no: token_no, table_id: table_id, lable: lable, csrftoken: csrftoken}, function (data1)
            {

                if (data1 == 'em') {
                    alert('Please select column');
                    return false;
                } else if (data1 == 'error')
                {
                    window.location.href = "<?php echo $this->webroot; ?>Error/exception_occurred";
                }
                else if (data1 == 'n')
                {
                    alert('Enter valid token number');
                    $("#token_audit").val('');
                    $("#token_audit").focus();
                    return false;
                }
                else {
                    $("#tablelist").html(data1);
                }

            });

        });
        $('#curtable').click(function () {
            var tablename = '<?php echo $tablename; ?>';
            var columnlist = $('input:checkbox:checked').map(function () {
                if (this.value != 'on') {
                    return this.value;
                }
            }).get().join(',');
            var lable = $('input:checkbox:checked').map(function () {
                if (this.value != 'on') {
                    return $(this).next("label").text().split(' ').join('%20');
                }
            }).get();
            var from = $("#curfrom").val();
            var to = $("#curto").val();
            var table_id = $("#table_id").val();
<?php if ($token_flag == 'Y') {
    ?>
                var token_no = $("#token_curr").val();
<?php } else { ?>
                var token_no = '';
<?php } ?>

            $.post('<?php echo $this->webroot; ?>Audit/display_currenttable', {columnlist: columnlist, tablename: tablename, from: from, to: to, token_no: token_no, table_id: table_id, lable: lable, csrftoken: csrftoken}, function (data1)
            {

                if (data1 == 'em') {
                    alert('Please select column');
                    return false;
                }
                else if (data1 == 'n')
                {
                    alert('Enter valid token number');
                    $("#token_curr").val('');
                    $("#token_curr").focus();
                    return false;
                } else if (data1 == 'error')
                {
                    window.location.href = "<?php echo $this->webroot; ?>Error/exception_occurred";
                } else {
                    $("#tablelist2").html(data1);
                }

            });

        });


    });
</script>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    <div class="form-group">                           
                        <label for="Select Columns" class="control-label col-sm-2" ><?php echo __('lblselectcolumn'); ?></label> 
                        <div class="col-sm-6" style="height:25vh;overflow-y: scroll; border: 2px #00529B ridge;padding-left: 3%; "> 
                            <?php echo $this->Form->input('column_name', array('type' => 'select', 'options' => $columnNames, 'label' => false, 'multiple' => 'checkbox', 'id' => 'column_name')); ?>
                            <span id="column_name_error" class="form-error"><?php //echo $errarr['column_name_error'];  ?></span>

                        </div> 
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblaudittable'); ?></h3></center>
            </div>
            <div class="box-body">
                <?php if ($token_flag == 'Y') { ?>
                    <div class=" row">
                        <div class="form-group">
                            <div class="col-sm-2"></div>
                            <label for="" class="col-sm-2 control-label"><?php echo __('lbltokenno'); ?></label>  
                            <div class="col-sm-2" ><?php echo $this->Form->input('token_audit', array('label' => false, 'id' => 'token_audit', 'class' => 'form-control input-sm', 'type' => 'text')); ?></div>

                        </div>
                    </div>
                <?php } ?>
                <div  class="rowht"></div> <div  class="rowht"></div> 
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="" class="col-sm-2 control-label"><?php echo __('lblfromdate'); ?></label>    
                        <div class="col-sm-2" ><?php echo $this->Form->input('from', array('label' => false, 'id' => 'from', 'class' => 'form-control input-sm', 'type' => 'text')); ?>
                            <span id="from_error" class="form-error"><?php //echo $errarr['from_error'];  ?></span>
                        </div>
                        <label for="" class="col-sm-2 control-label"><?php echo __('lbltodate'); ?></label>    
                        <div class="col-sm-2" ><?php echo $this->Form->input('to', array('label' => false, 'id' => 'to', 'class' => 'form-control input-sm', 'data-date-format' => "mm/dd/yyyy", 'type' => 'text')); ?>
                            <span id="to_error" class="form-error"><?php //echo $errarr['to_error'];  ?></span>
                        </div>

                    </div>
                </div>
                <div  class="rowht"></div> <div  class="rowht"></div>

                <div class=" row">
                    <div class="form-group center">
                        <input type="button" name="Check Audit Table" id="audittrail" value="<?php echo __('lblviewaudittrial'); ?>" />
                    </div>
                </div>
                <hr style="border: 1px lightgray solid">
                <div id="tablelist">

                </div>
            </div>
        </div>


        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblcurrenttable'); ?></h3></center>
            </div>
            <div class="box-body">
                <?php if ($token_flag == 'Y') { ?>
                    <div class=" row">
                        <div class="form-group">
                            <div class="col-sm-2"></div>
                            <label for="" class="col-sm-2 control-label"><?php echo __('lbltokenno'); ?></label>  
                            <div class="col-sm-2" ><?php echo $this->Form->input('token_curr', array('label' => false, 'id' => 'token_curr', 'class' => 'form-control input-sm', 'type' => 'text')); ?></div>

                        </div>
                    </div>
                <?php } ?>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="" class="col-sm-2 control-label"><?php echo __('lblfromdate'); ?></label>    
                        <div class="col-sm-2" ><?php echo $this->Form->input('curfrom', array('label' => false, 'id' => 'curfrom', 'class' => 'form-control input-sm', 'type' => 'text')); ?></div>
                        <label for="" class="col-sm-2 control-label"><?php echo __('lbltodate'); ?></label>    
                        <div class="col-sm-2" ><?php echo $this->Form->input('curto', array('label' => false, 'id' => 'curto', 'class' => 'form-control input-sm', 'data-date-format' => "mm/dd/yyyy", 'type' => 'text')); ?></div>

                    </div>
                </div>
                <div  class="rowht"></div>   <div  class="rowht"></div>

                <div class=" row">
                    <div class="form-group center">
                        <input type="button" name="Check Audit Table" id="curtable" value="<?php echo __('lblviewcurrentrecord'); ?>" />
                    </div>
                </div>
                <hr style="border: 1px lightgray solid">
                <div id="tablelist2">

                </div>
            </div>
        </div>

        <input type="hidden" id="table_id" value="<?php echo $table_id; ?>">

    </div>


</div>




</div>
</div>


