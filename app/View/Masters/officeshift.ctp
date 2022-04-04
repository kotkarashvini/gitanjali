<?php $doc_lang = $this->Session->read('doc_lang'); ?> 
<script>

    $(document).ready(function () {


    $('#tablelofficeshift').dataTable({
    "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
//            $("#from_time").timepicker({'timeFormat': 'HH:mm'});
            $('#from_time').timepicker({ 'timeFormat': 'HH:mm' });
            $("#to_time").timepicker({'timeFormat': 'HH:mm'});
            $("#lunch_from_time").timepicker({'timeFormat': 'HH:mm'});
            $("#lunch_to_time").timepicker({'timeFormat': 'HH:mm'});
            $("#tatkal_from_time").timepicker({'timeFormat': 'HH:mm'});
            $("#tatkal_to_time").timepicker({'timeFormat': 'HH:mm'});
            $("#appnt_from_time").timepicker({'timeFormat': 'HH:mm'});
            $("#appnt_to_time").timepicker({'timeFormat': 'HH:mm'});
    });
            function formadd() {
            document.getElementById("hfaction").value = 'S';
                    document.getElementById("actiontype").value = '1';
            }
    function formupdate(<?php
foreach ($languagelist as $langcode) {
    ?>
    <?php echo 'desc_' . $langcode['mainlanguage']['language_code']; ?>,
<?php } ?> from_time, to_time, lunch_from_time, lunch_to_time, tatkal_from_time, tatkal_to_time, tatkal_days, appnt_from_time, appnt_to_time, id) {
    document.getElementById("actiontype").value = '1';
<?php
foreach ($languagelist as $langcode) {
    ?>
        $('#desc_<?php echo $langcode['mainlanguage']['language_code']; ?>').val(desc_<?php echo $langcode['mainlanguage']['language_code']; ?>);
<?php } ?>
    $('#hfupdateflag').val('Y');
            $('#from_time').val(from_time);
            $('#to_time').val(to_time);
            $('#lunch_from_time').val(lunch_from_time);
            $('#lunch_to_time').val(lunch_to_time);
            $('#tatkal_from_time').val(tatkal_from_time);
            $('#tatkal_to_time').val(tatkal_to_time);
            $('#tatkal_days').val(tatkal_days);
            $('#appnt_from_time').val(appnt_from_time);
            $('#appnt_to_time').val(appnt_to_time);
            $('#hfid').val(id);
            $('#btnadd').html('Save');
            return false;
    }


</script>

<?php echo $this->Form->create('officeshift', array('id' => 'officeshift')); ?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
<div class="row">
    <div class="col-lg-12">
        <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblofficeshift'); ?></h3>
                    <div class="box-tools pull-right">
                        <a  href="<?php echo $this->webroot; ?>helpfiles/Masters/officeshift_<?php echo $doc_lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                    </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="from_time" class="col-sm-2 control-label"><?php echo __('lblfromtime'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('from_time', array('label' => false, 'id' => 'from_time', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="from_time_error" class="form-error"><?php echo $errarr['from_time_error']; ?></span>
                        </div>
                        <label for="to_time" class="col-sm-2 control-label"><?php echo __('lbltotime'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('to_time', array('label' => false, 'id' => 'to_time', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="to_time_error" class="form-error"><?php echo $errarr['to_time_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>  
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="from_time" class="col-sm-2 control-label">Lunch From Time <span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('lunch_from_time', array('label' => false, 'id' => 'lunch_from_time', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="lunch_from_time_error" class="form-error"><?php echo $errarr['lunch_from_time_error']; ?></span>
                        </div>
                        <label for="to_time" class="col-sm-2 control-label">Lunch To Time<span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('lunch_to_time', array('label' => false, 'id' => 'lunch_to_time', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="lunch_to_time_error" class="form-error"><?php echo $errarr['lunch_to_time_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>  
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="from_time" class="col-sm-2 control-label">Tatkal From Time <span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('tatkal_from_time', array('label' => false, 'id' => 'tatkal_from_time', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="tatkal_from_time_error" class="form-error"><?php echo $errarr['tatkal_from_time_error']; ?></span>
                        </div>
                        <label for="to_time" class="col-sm-2 control-label">Tatkal To Time <span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('tatkal_to_time', array('label' => false, 'id' => 'tatkal_to_time', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="tatkal_to_time_error" class="form-error"><?php echo $errarr['tatkal_to_time_error']; ?></span>
                        </div>
                    </div>
                </div>

                <div  class="rowht"></div> <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>

                        <?php
//creating dyanamic text boxes using same array of config language
                        foreach ($languagelist as $key => $langcode) {
                            ?>
                            <div class="col-md-2">
                                <label><?php echo __('description') . "  " . $langcode['mainlanguage']['language_name']; ?>
                                    <span style="color: #ff0000">*</span>
                                </label>  </div>
				<div class="col-md-2">  
                                <?php echo $this->Form->input('desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'desc_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => "255")) ?>
                                <span id="<?php echo 'desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error">
                                    <?php echo $errarr['desc_' . $langcode['mainlanguage']['language_code'] . '_error']; ?>
                                </span>
                            </div>
                        <?php } ?>

                    </div>
                </div>
                <div  class="rowht"></div>  <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="from_time" class="col-sm-2 control-label"> Tatkal Days<span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('tatkal_days', array('label' => false, 'id' => 'tatkal_days', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="tatkal_days_error" class="form-error"><?php echo $errarr['tatkal_days_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>  
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="from_time" class="col-sm-2 control-label">Working Hours From Time<span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('appnt_from_time', array('label' => false, 'id' => 'appnt_from_time', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="appnt_from_time_error" class="form-error"><?php echo $errarr['appnt_from_time_error']; ?></span>
                        </div>
                        <label for="to_time" class="col-sm-2 control-label">Working Hours To Time <span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('appnt_to_time', array('label' => false, 'id' => 'appnt_to_time', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="appnt_to_time_error" class="form-error"><?php echo $errarr['appnt_to_time_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>  <div  class="rowht"></div>
                <div class="row center">
                    <div class="form-group">
                        <button id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?>
                        </button>
                        <button id="btncancel" name="btncancel" class="btn btn-info " onclick="javascript: return formcancel();">
                            &nbsp;&nbsp;<?php echo __('btncancel'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-primary">

            <div class="box-body">
                <div id="selectlocal_governing_body">
                    <table id="tablelofficeshift" class="table table-striped table-bordered table-hover" >
                        <thead >  
                            <tr>  
                                <th class="center">From Time</th>
                                <th class="center">To Time</th>
                                <th class="center">Tatkal From Time</th>
                                <th class="center">Tatkal To Time</th>

                                <?php foreach ($languagelist as $langcode) { ?>
                                    <th class="center"><?php echo __('Description') . " ( " . $langcode['mainlanguage']['language_name'] . ")"; ?></th>
                                <?php } ?>
                                <th class="center width10"><?php echo __('lblaction'); ?></th>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php foreach ($officeshift as $officeshift1): ?>
                                <tr>
                                    <td ><?php echo $officeshift1['officeshift']['from_time']; ?></td>
                                    <td ><?php echo $officeshift1['officeshift']['to_time']; ?></td>
                                    <td ><?php echo $officeshift1['officeshift']['tatkal_from_time']; ?></td>

                                    <td ><?php echo $officeshift1['officeshift']['tatkal_to_time']; ?></td>

                                    <?php
                                    foreach ($languagelist as $langcode) {
                                        ?>
                                        <td ><?php echo $officeshift1['officeshift']['desc_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                    <?php } ?>
                                    <td >
                                        <button id="btnupdate" name="btnupdate"  type="button" data-toggle="tooltip" title="Edit" class="btn btn-default "  
                                                onclick="javascript: return formupdate(
                                                <?php foreach ($languagelist as $langcode) { ?>
                                                                            ('<?php echo $officeshift1['officeshift']['desc_' . $langcode['mainlanguage']['language_code']]; ?>'),
                                                <?php } ?>
                                                                        ('<?php echo $officeshift1['officeshift']['from_time']; ?>'),
                                                                                ('<?php echo $officeshift1['officeshift']['to_time']; ?>'),
                                                                                ('<?php echo $officeshift1['officeshift']['lunch_from_time']; ?>'),
                                                                                ('<?php echo $officeshift1['officeshift']['lunch_to_time']; ?>'),
                                                                                ('<?php echo $officeshift1['officeshift']['tatkal_from_time']; ?>'),
                                                                                ('<?php echo $officeshift1['officeshift']['tatkal_to_time']; ?>'),
                                                                                ('<?php echo $officeshift1['officeshift']['tatkal_days']; ?>'),
                                                                                ('<?php echo $officeshift1['officeshift']['appnt_from_time']; ?>'),
                                                                                ('<?php echo $officeshift1['officeshift']['appnt_to_time']; ?>'),
                                                                                ('<?php echo $officeshift1['officeshift']['id']; ?>'));">
                                            <span class="glyphicon glyphicon-pencil"></span>


                                        </button>      
                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'delete_officeshift', $officeshift1['officeshift']['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
                                    </td>
                                <?php endforeach; ?>
                                <?php unset($officeshift1); ?>
                        </tbody>
                    </table>
                    <?php if (!empty($officeshift)) { ?>
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