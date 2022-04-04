<script>
 $(document).ready(function () {

    $('#tablearticleparty').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
 });
    function formadd() {
        document.getElementById("actiontype").value = '1';
        document.getElementById("hfaction").value = 'S';
    }
    function formupdate(event_id, id) {
        //alert(event_id);
        document.getElementById("actiontype").value = '1';
        $('#event_id').val(event_id);
        $('#hfid').val(id);
        $('#hfupdateflag').val('Y');
        $('#btnadd').html('Save');
        return false;
    }

</script>


<?php echo $this->Form->create('otp_smsevent', array('id' => 'otp_smsevent')); ?>
<div class="row">
    <div class="col-lg-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('SMS Event Mapping'); ?>  </h3></center>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="event_id" class="col-sm-2 control-label"><?php echo __('Select SMS Event'); ?>  <span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('event_id', array('label' => false, 'id' => 'event_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $smsevents))); ?>
                            <span id="event_id_error" class="form-error"><?php //echo $errarr['event_id_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div> <div  class="rowht"></div><div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="type" class="col-sm-2 control-label"><?php echo __('Select'); ?>  <span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('type', array('label' => false, 'id' => 'type', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $type))); ?>
                            <span id="type_error" class="form-error"><?php //echo $errarr['type_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div> <div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center" >
                    <div class="form-group" >
                        <button id="btnadd" name="btnadd" class="btn btn-info "  onclick="javascript: return formadd();">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?>
                        </button>
                          <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-primary">
                    <div class="box-body">
                        <div id="selectbehavioural">
                            <table id="tablearticleparty" class="table table-striped table-bordered table-hover" >
                                <thead >  
                                    <tr> 
                                        <th class="center width10"><?php echo __('Sr.No.'); ?></th>
                                        <th class="center width10"><?php echo __('Event Desc'); ?></th>
                                        <th class="center width10"><?php echo __('Flag'); ?></th>
                                        <!--<th class="center width10"><?php //echo __('lblaction'); ?></th>-->
                                    </tr>  
                                </thead>
                                <tbody>
                                    <?php foreach ($smsevent as $rec): ?>
                                        <tr>
                                            <td><?php echo $rec[0]['event_id']; ?></td>
                                            <td><?php echo $rec[0]['event_desc_en']; ?></td>
                                            <td><?php echo $rec[0]['send_flag']; ?></td>
                                            
<!--                                            <td>
                                                <button id="btnupdate" name="btnupdate" type="button"  data-toggle="tooltip" title="Edit" class="btn btn-default "   onclick="javascript: return formupdate(
                                                                    ('<?php //echo $rec[0]['event_id']; ?>'));">
                                                    <span class="glyphicon glyphicon-pencil"></span>
                                                </button>
                                                <a <?php //echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'delete_articleparty_mapping', $articleparty1[0]['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
                                            </td>-->
                                        </tr>       
                                    <?php endforeach; ?>
                                    <?php unset($rec); ?>
                                </tbody>
                            </table>
                            <?php if (!empty($rec)) { ?>
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