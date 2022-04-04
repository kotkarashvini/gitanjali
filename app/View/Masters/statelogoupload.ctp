<script type="text/javascript">
    $(document).ready(function () {

        if (document.getElementById('hfhidden1').value == 'Y') {
            $('#divratefactor').slideDown(1000);
        }
        else {
            $('#divratefactor').hide();
        }
        $('#tableratefactor').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });

        function readURL(input) {

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#logo_path").change(function () {
            readURL(this);
        });

    });

    function formadd() {
        document.getElementById("actiontype").value = '1';
    }

    function forcancel() {
        document.getElementById("actiontype").value = '2';
    }

    function formupdate(id, state_id, logo_path) {
//        alert(logo_path);
        $('#hfid').val(id);
        $('#hfdelete').val(logo_path);
        $('#state_id').val(state_id);
        $('#logo_path').val(logo_path);
        $('#hfupdateflag').val('Y');
        $('#btnadd').html('Save');
        return false;
    }

    function formdelete(id) {
        var result = confirm("Are you sure you want to delete this record?");
        if (result) {
            document.getElementById("actiontype").value = '3';
            $('#hfid').val(id);
        } else {
            return false;
        }
    }
</script>

<?php echo $this->Form->create('state_logo', array('class' => 'form-horizontal', 'role' => 'form', 'type' => 'file')); ?>
<div class="row">
    <div class="col-lg-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblstatelogo'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/admin/statelogoupload_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="state_id " class="col-sm-2 control-label"><?php echo __('lblselectstate'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('state_id', array('label' => false, 'id' => 'state_id', 'class' => 'form-control input-sm', 'empty' => '--Select--', 'options' => array($state_list))); ?>
                            <span id="state_id_error" class="form-error"><?php //echo $errarr['state_id_error'];  ?></span>
                        </div>
                        <label for="state_id " class="col-sm-2 control-label"><?php echo __('lblselectimage'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('logo_path', array("type" => "file", "size" => "50", 'error' => false, 'label' => false, 'placeholder' => 'Upload Image', 'id' => 'logo_path', 'class' => 'Cntrl1')); ?>
                            <span id="logo_path_error" class="form-error"><?php //echo $errarr['slogo_path_error'];  ?></span> 
                        </div>
                        <div class="col-sm-1"></div>
                        <div class="col-sm-2">
                            <img id="blah" src="#" alt="" width="150" height="150"/>
                        </div>
                    </div>
                </div>
                <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
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
        <div class="box box-primary">

            <div class="box-body" id="divratefactor">
                <table id="tableratefactor" class="table table-striped table-bordered table-hover">  
                    <thead >  
                        <tr>  
                            <th class="center"><?php echo __('lbladmstate'); ?></th>
                            <th class="center"><?php echo __('lblimgpath'); ?></th>
                            <th class="center"><?php echo __('lblimg'); ?></th>
                            <th class="center width10"><?php echo __('lblaction'); ?></th>
                        </tr>  
                    </thead>

                    <?php for ($i = 0; $i < count($statelogo); $i++) { ?>
                        <tr>
                            <td ><?php echo $statelogo[$i][0]['state_name_' . $language]; ?></td>
                            <td ><?php echo $statelogo[$i][0]['logo_path']; ?></td>
                            <td ><?php echo $this->Html->image($statelogo[$i][0]['logo_path'], array('class' => 'img-responsive', 'aling' => 'center', 'width' => '100', 'height' => '100')); ?></td>
                            <td >
                                <button id="btnupdate" name="btnupdate" class="btn btn-default "  onclick="javascript: return formupdate(
                                                ('<?php echo $statelogo[$i][0]['id']; ?>'),
                                                ('<?php echo $statelogo[$i][0]['state_id']; ?>'),
                                                ('<?php echo $statelogo[$i][0]['logo_path']; ?>'));">
                                    <span class="glyphicon glyphicon-pencil"></span></button>

                                <button id="btndelete" name="btndelete" class="btn btn-default "  onclick="javascript: return formdelete(('<?php echo $statelogo[$i][0]['id']; ?>'));">
                                    <span class="glyphicon glyphicon-remove"></span></button>
                            </td>
                        </tr>
                    <?php } ?>
                </table> 
                <?php if (!empty($statelogo)) { ?>
                    <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                    <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
            </div>
        </div>

        <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
        <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
        <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
        <input type='hidden' value='<?php echo $hfdelete; ?>' name='hfdelete' id='hfdelete'/>
    </div>
</div>