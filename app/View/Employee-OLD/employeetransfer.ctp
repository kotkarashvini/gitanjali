<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>
<!--<script>

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

</script>-->
<script>
    $(document).ready(function () {
//      var actiontype = document.getvElementById('actiontype').value;
        $('#emp_name').change(function () {
            var employeetransfer = $("#emp_name option:selected").val();
            $.ajax({
                type: "POST",
                url: "<?php echo $this->webroot; ?>Employee/getemployeetransfer",
                data: {'employeetransfer': employeetransfer},
                success: function (data) {

                    $('#office_name').val(data);
                }
            });
        });
    });

    function formsave() {

        document.getElementById("actiontype").value = '1';
        $('#saveemptransfer').submit();
    }
</script>


<?php echo $this->Form->create('employeetransfer', array('id' => 'employeetransfer', 'autocomplete' => 'off')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title" style="font-weight: bolder"><?php echo __('lblemptranshead'); ?></h3></center>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-3">&nbsp;</div>
                        <label for="emp_name" class="col-sm-3 control-label"><?php echo __('lblempselect'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php
                            $all_strtolower = array_map('strtolower', $emp_name);
                            $all_uppcase = array_map('ucwords', $all_strtolower);
                            echo $this->Form->input('emp_name', array('type' => 'select',
                                'error' => false,
                                'options' => $emp_name,
                                'id' => 'emp_name',
                                'label' => false,
                                'class' => 'form-control input-sm'));
                            ?>
                        </div>
                        <div class="col-sm-3">&nbsp;</div>
                    </div> 
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-3">&nbsp;</div>
                        <label for="current_office" class="col-sm-3 control-label"><?php echo __('lblcurrofc'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('current_office', array('label' => false, 'id' => 'office_name', 'type' => 'text', 'class' => 'form-control')); ?>
                        </div>
                        <div class="col-sm-3">&nbsp;</div>
                    </div> 
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-3">&nbsp;</div>
                        <label for="transfer_office" class="col-sm-3 control-label"><?php echo __('lbltrnsfto'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('transfer_office', array('options' => array('empty' => '--select--', $employeetrans), 'id' => 'transfer_office', 'label' => false, 'class' => 'form-control input-sm')); ?>
                        </div>
                        <div class="col-sm-3">&nbsp;</div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group" style="text-align: center">
                        <button id="btnAdd" name="btnAdd" class="btn btn-primary " onclick="javascript: return formsave();"><?php echo __('btnsave'); ?></button>
                        <input type='hidden' value=<?php echo $actiontypeval; ?> name='actiontype' id='actiontype'/>
                        <input type='hidden' value=<?php echo $hfactionval; ?> name='hfaction' id='hfaction'/>
                    </div> 
                </div>
            </div>
        </div>

    </div>
</div>

<?php echo $this->Form->end(); ?>

