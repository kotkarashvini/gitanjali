<script>
    $(document).ready(function () {
        $('#tableobjection').dataTable({
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
    function formupdate(objection_name, id) {
        document.getElementById("actiontype").value = '1';
        $('#objection_name').val(objection_name);
        $('#hfid').val(id);
        $('#btnadd').html('Save');
        $('#hfupdateflag').val('Y');
        return false;
    }
    function formcancel() {
        document.getElementById("actiontype").value = '3';
    }
</script>   
<?php echo $this->Form->create('ObjectionType', array('id' => 'ObjectionType', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title">Objection Type</h3></center>
            </div>
            <div class="box-body">
                <div class="row" style="text-align: center">
                    <div class="form-group">
                        <label for="objection_name" class="col-sm-3 control-label">Objection Type Description :<span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('objection_name', array('label' => false, 'id' => 'objection_name', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>
                            <span id="objection_name_error" class="form-error"><?php echo $errarr['objection_name_error']; ?></span>
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
                    <table id="tableobjection" class="table table-striped table-bordered table-hover">  
                        <thead> 
                            <tr>  
                                <th class="center width10">Case Type Description</th>
                                <th class="center width16"><?php echo __('lblaction'); ?></th>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php foreach ($objectionrecord as $objectionrecord1): ?>
                                <tr>
                                    <td ><?php echo $objectionrecord1['ObjectionType']['objection_name']; ?></td>
                                    <td style="text-align: center;">
                                        <?php
                                        $newid = $this->requestAction(
                                                array('controller' => 'NewCase', 'action' => 'encrypt', $objectionrecord1['ObjectionType']['objection_type_id'], $this->Session->read("randamkey"),
                                        ));
                                        ?>
                                        <button id="btnupdate" name="btnupdate" data-toggle = "tooltip" title ="Edit"  class="btn btn-default update" style="text-align: center;" onclick="javascript: return formupdate(
                                                        ('<?php echo $objectionrecord1['ObjectionType']['objection_name']; ?>'),
                                                        ('<?php echo $objectionrecord1['ObjectionType']['objection_type_id']; ?>'));">
                                            <span class="glyphicon glyphicon-pencil"></span></button>

                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'delete_Objectiontype', $newid), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php unset($objectionrecord1); ?>
                        </tbody>
                    </table> 
                    <?php if (!empty($objectionrecord1)) { ?>
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
