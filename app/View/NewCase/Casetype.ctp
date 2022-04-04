<script>
    $(document).ready(function () {
        $('#tablecasetype').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
    });
</script> 
<script>
    function formadd() {
        document.getElementById("hfaction").value = 'S';
        document.getElementById("actiontype").value = '1';
    }
    function formupdate(case_type_desc, id) {
        document.getElementById("actiontype").value = '1';
        $('#case_type_desc').val(case_type_desc);
        $('#hfid').val(id);
        $('#btnadd').html('Save');
        $('#hfupdateflag').val('Y');
        return false;
    }
    function formcancel() {
        document.getElementById("actiontype").value = '3';
    }
</script> 
<?php echo $this->Form->create('CaseType', array('id' => 'CaseType', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title">Case Type</h3></center>
            </div>
            <div class="box-body">
                <div class="row" style="text-align: center">
                    <div class="form-group">
                        <label for="case_type_code" class="col-sm-3 control-label">Case Type code :<span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('case_type_code', array('label' => false, 'id' => 'case_type_code', 'class' => 'form-control input-sm', 'type' => 'text')) ?>

                            <span id="case_type_code_error" class="form-error"><?php echo $errarr['case_type_code_error']; ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-body">
                <div class="row" style="text-align: center">
                    <div class="form-group">
                        <label for="case_status_code" class="col-sm-3 control-label">Case Type Description :<span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('case_type_desc', array('label' => false, 'id' => 'case_type_desc', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>
                            <span id="case_type_desc_error" class="form-error"><?php echo $errarr['case_type_desc_error']; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-primary">
            <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
            <div class="row" style="text-align: center">
                <div class="form-group">
                    <button id="btnadd"type="submit" name="btnadd" class="btn btn-info " style="text-align: center;" onclick="javascript: return formadd();">
                        <span class="glyphicon glyphicon-plus"></span><?php echo __('btnsave'); ?>
                    </button>
                    <button id="btncancel" name="btncancel" class="btn btn-info " style="text-align: center;" onclick="javascript: return formcancel();">
                        <?php echo __('btncancel'); ?>
                    </button>
                </div>
            </div>
        </div>
        <div class="box box-primary">
            <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
            <div class="box-body">
                <div class="table-responsive">
                    <table id="tablecasetype" class="table table-striped table-bordered table-hover">  
                        <thead> 
                            <tr>  
                                <th class="center width10">Case Type code</th>
                                <th class="center width10">Case Type Description</th>

                                <th class="center width16"><?php echo __('lblaction'); ?></th>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php foreach ($casetyperecord as $casetyperecord1): ?>
                                <tr>
                                    <td ><?php echo $casetyperecord1['CaseType']['case_type_code']; ?></td>
                                    <td ><?php echo $casetyperecord1['CaseType']['case_type_desc']; ?></td>
                                    <td style="text-align: center;">
                                        <?php
                                        $newid = $this->requestAction(
                                                array('controller' => 'NewCase', 'action' => 'encrypt', $casetyperecord1['CaseType']['case_type_id'], $this->Session->read("randamkey"),
                                        ));
                                        ?>
                                        <button id="btnupdate" name="btnupdate" class="btn btn-default update" data-toggle = "tooltip" title = "Edit" style="text-align: center;" onclick="javascript: return formupdate(
                                                        ('<?php echo $casetyperecord1['CaseType']['case_type_desc']; ?>'),
                                                        ('<?php echo $casetyperecord1['CaseType']['case_type_id']; ?>'));">
                                            <span class="glyphicon glyphicon-pencil"></span></button>

                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'delete_casetype', $newid), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
                                    </td>
                                </tr>

                            <?php endforeach; ?>

                            <?php unset($docrecord1); ?>
                        </tbody>
                    </table> 
                    <?php if (!empty($docrecord1)) { ?>
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

<?php $this->Form->end();?>
