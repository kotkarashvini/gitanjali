
<script>
    $(document).ready(function () {
        var hfupdateflag = "<?php echo $hfupdateflag; ?>";
        if (hfupdateflag === 'Y')
        {
            $('#btnadd').html('Save');
        }
        if ($('#hfhidden1').val() === 'Y')
        {
            $('#tableholiday').dataTable({
                "iDisplayLength": 5,
                "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
            });
        } else {
            $('#tableholiday').dataTable({
                "iDisplayLength": 5,
                "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
            });
        }
        var actiontype = document.getElementById('actiontype').value;
        if (actiontype == '2') {
            $('.tdsave').show();
            $('.tdselect').hide();
        }
    });</script>

<script>
    function formadd() {
        document.getElementById("hfaction").value = 'S';
        document.getElementById("actiontype").value = '1';
    }
    function formupdate(district_id, slot_time_minute, id) {
        document.getElementById("actiontype").value = '1';

        $('#district_id').val(district_id);
        $('#slot_time_minute').val(slot_time_minute);

        $('#hfupdateflag').val('Y');
        $('#hfid').val(id);
        $('#btnadd').html('Save');
        return false;
    }
</script> 
<?php echo $this->Form->create('timeslot', array('id' => 'timeslot')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lbltimeslot'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Timeslot/timeslot_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="district_id" class="col-sm-2 control-label"><?php echo __('lbladmdistrict'); ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('district_id', array('label' => false, 'id' => 'district_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select District--', $District))); ?>
                            <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
                            <span id="district_id_error" class="form-error"><?php echo $errarr['district_id_error']; ?></span>
                        </div>
                        <label for="slot_time_minute" class="col-sm-3 control-label"><?php echo __('lbltimeslotsinmin'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('slot_time_minute', array('label' => false, 'id' => 'slot_time_minute', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '5')) ?>
                            <span id="slot_time_minute_error" class="form-error"><?php echo $errarr['slot_time_minute_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center">
                    <div class="form-group" >
                        <button id="btnadd" name="btnadd" class="btn btn-info "onclick="javascript: return formadd();">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <div class="box box-primary">

            <div class="box-body">
                <div id="selectholiday">
                    <table id="tableholiday" class="table table-striped table-bordered table-hover" >
                        <thead >  
                            <tr>  
                                <th class="center"><?php echo __('lbladmdistrict'); ?></th>
                                <th class="center"><?php echo __('lbltimeslotsinmin'); ?></th>
                                <th class="center width10"><?php echo __('lblaction'); ?></th>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php foreach ($timeslotrecord as $timeslotrecord1): ?>
                                <tr>
                                    <td ><?php echo $timeslotrecord1['0']['district_name_en']; ?></td>
                                    <td ><?php echo $timeslotrecord1['0']['slot_time_minute']; ?></td>
                                    <td >
                                        <button id="btnupdate" name="btnupdate" type="button" data-toggle="tooltip" title="Edit" class="btn btn-default "  
                                                onclick="javascript: return formupdate(('<?php echo $timeslotrecord1['0']['district_id']; ?>'),
                                                                    ('<?php echo $timeslotrecord1['0']['slot_time_minute']; ?>'),
                                                                    ('<?php echo $timeslotrecord1['0']['id']; ?>'));">
                                            <span class="glyphicon glyphicon-pencil"></span>
                                        </button>
                                        <?php
                                        $newid = $this->requestAction(
                                                array('controller' => 'Masters', 'action' => 'encrypt', $timeslotrecord1['0']['id'], $this->Session->read("randamkey"),
                                        ));
                                        ?>

                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'timeslot_delete', $newid), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
                                    </td>

                                </tr>

                            <?php endforeach; ?>
                            <?php unset($timeslotrecord1); ?>
                        </tbody>
                    </table>
                    <?php if (!empty($timeslotrecord)) { ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
                </div>
            </div>
        </div>

        <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
        <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
        <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
        <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>

    </div>
</div>
