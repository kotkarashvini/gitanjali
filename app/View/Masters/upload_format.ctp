<script>
    $(document).ready(function () {
        $('#tableupload').dataTable({
            "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
    });
</script>
<script>
    function formadd() {
        document.getElementById("hfaction").value = 'S';
        document.getElementById("actiontype").value = '1';
    }
    function formupdate(field_type, upload_size, id) {
        document.getElementById("actiontype").value = '1';
        $('#field_type').val(field_type);
        $('#upload_size').val(upload_size);
        $('#hfid').val(id);
        $('#btnadd').html('Save');
        $('#hfupdateflag').val('Y');
    }
    function formdelete(id) {
        var result = confirm("Are you sure you want to delete this record?");
        if (result) {
            document.getElementById("actiontype").value = '4';
            document.getElementById("hfid").value = id;
            $('#id1').val(id);
        } else {
            return false;
        }
    }
    function formcancel() {
        document.getElementById("actiontype").value = '3';

    }
</script>

<?php echo $this->Form->create('upload_file_format', array('id' => 'upload_file_format', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lbluploadfileformat'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/admin/upload_format_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="field_type" class="col-sm-2 control-label"><?php echo __('lblfiletypeformat'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('field_type', array('label' => false, 'id' => 'field_type', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                        <span id="field_type_error" class="form-error"><?php //echo $errarr[field_type_error'];     ?></span>
                        </div>
                        <label for="upload_size" class="col-sm-2 control-label"><?php echo __('lbluploadsizelimit'); ?><span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('upload_size', array('label' => false, 'id' => 'upload_size', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                        <span id="upload_size_error" class="form-error"><?php //echo $errarr[upload_size_error'];     ?></span>
                        </div>
                    </div>
                </div>
                <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center">
                    <div class="form-group">
                        <button id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?>
                        </button>
                        <button id="btncancel" name="btncancel" class="btn btn-info " onclick="javascript: return formcancel();">
                            <?php echo __('btncancel'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-primary">

            <div class="box-body">
                <div id="selectupload">
                    <table id="tableupload" class="table table-striped table-bordered table-hover" style="width: 100%">
                        <thead >  
                            <tr>  
                                <th class="center"><?php echo __('lblfiletypeformat'); ?></th>
                                <th class="center"><?php echo __('lbluploadsizelimit'); ?></th>
                                <th class="center width10"><?php echo __('lblaction'); ?></th>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php foreach ($fileupload as $fileupload1): ?>
                                <tr>
                                    <td ><?php echo $fileupload1['upload_file_format']['field_type']; ?></td>
                                    <td ><?php echo $fileupload1['upload_file_format']['upload_size']; ?></td>
                                    <td >
                                        <button id="btnupdate" name="btnupdate" type="button" data-toggle="tooltip" title="Edit"  class="btn btn-default "   onclick="javascript: return formupdate(
                                                        ('<?php echo $fileupload1['upload_file_format']['field_type']; ?>'),
                                                        ('<?php echo $fileupload1['upload_file_format']['upload_size']; ?>'),
                                                        ('<?php echo $fileupload1['upload_file_format']['id']; ?>'));">
                                            <span class="glyphicon glyphicon-pencil"></span>
                                        </button>
                                        <a href="<?php echo $this->webroot; ?>Masters/delete_upload_format/<?php echo $fileupload1['upload_file_format']['id']; ?>" class="btn btn-warning" onclick="return confirm('Are You Sure ? ')"> <span class="glyphicon glyphicon-remove"></span></a>
                                   
                                        <!--<a <?php //echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'delete_behavioural_pattens', $behaviouralpattenrecord1['0']['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?'));            ?></a>-->
                                    </td>
                                <?php endforeach; ?>
                                <?php unset($rolerecord1); ?>
                        </tbody>
                    </table>
                    <?php if (!empty($rolerecord)) { ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
                </div>
            </div>
        </div>

    </div>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
</div>