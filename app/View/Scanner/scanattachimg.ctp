<script type="text/javascript">
    $(document).ready(function () {

        

        function readURL(input) {

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#imgInp").change(function () {
            readURL(this);
        });

    });

    function formadd() {
        document.getElementById("actiontype").value = '1';
    }

    function forcancel() {
        document.getElementById("actiontype").value = '2';
    }

</script>

<?php echo $this->Form->create('scanattachimg', array('class' => 'form-horizontal', 'role' => 'form', 'type' => 'file')); ?>
<div class="row">
    <div class="col-lg-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblattachqrtoscannedfile'); ?></h3></center>
            </div><br>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="state_id " class="col-sm-2 control-label"><?php echo __('lblselectfile'); ?> :-<span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('scanfile', array("type" => "file", "size" => "50", 'error' => false, 'label' => false, 'placeholder' => 'Upload Image', 'id' => 'scanfile')); ?>
                        </div>
                        <label for="state_id " class="col-sm-2 control-label"><?php echo __('lblselectimage'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('logo_path', array("type" => "file", "size" => "50", 'error' => false, 'label' => false, 'placeholder' => 'Upload Image', 'id' => 'imgInp')); ?>
                        </div>
                        <div class="col-sm-1"></div>
                        <div class="col-sm-2">
                            <img id="blah" src="#" alt="" width="150" height="150"/>
                        </div>
                    </div>
                </div><br>
                <div class="row">
                                <div class="form-group">
                                    <div class="col-sm-1"></div>
                                    <label for="destname" class="col-sm-2 control-label center"><?php echo __('lbldestinationfilename'); ?> :-<span style="color: #ff0000">*</span></label>    
                                    <div class="col-sm-2 center"><?php echo $this->Form->input('destname', array('label' => false, 'id' => 'destname', 'style' => 'text-align: left; color: red; font-weight: bold; font-size: larger', 'class' => 'form-control input-sm sample', 'type' => 'text')) ?></div>
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