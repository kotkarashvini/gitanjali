<script type="text/javascript">
    $(document).ready(function () {

    });

    function formadd() {
        document.getElementById("actiontype").value = '1';
    }

    function forcancel() {
        document.getElementById("actiontype").value = '2';
    }

</script>

<?php echo $this->Form->create('combinepdf', array('class' => 'form-horizontal', 'role' => 'form', 'type' => 'file')); ?>
<div class="row">
    <div class="col-lg-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblattachqrtoscannedfile'); ?></h3></center>
            </div><br>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="srcfilename1 " class="col-sm-2 control-label"><?php echo __('lblselfirstfile'); ?> :-<span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('srcfilename1', array("type" => "file", "size" => "50", 'error' => false, 'label' => false, 'id' => 'srcfilename1')); ?>
                        </div>
                        <label for="srcfilename2 " class="col-sm-2 control-label"><?php echo __('lblselsecondfile'); ?> :-<span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('srcfilename2', array("type" => "file", "size" => "50", 'error' => false, 'label' => false,  'id' => 'srcfilename2')); ?>
                        </div>
                        
                    </div>
                </div><br>

                <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
                <div class="row center">
                    <div class="form-group">
                        <button id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp; <?php echo __('lblbtnAdd'); ?></button>
                        <button id="btnadd" name="btncancel" class="btn btn-info " onclick="javascript: return forcancel();">
                            <span class="glyphicon glyphicon-floppy-remove"></span>&nbsp; &nbsp;<?php echo __('btncancel'); ?></button>
                    </div>
                </div>
            </div>
        </div>
        

        <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
        <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
        <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
        <input type='hidden' value='<?php echo $hfdelete; ?>' name='hfdelete' id='hfdelete'/>
    </div>
</div>