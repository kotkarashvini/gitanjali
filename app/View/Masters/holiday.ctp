<script>
    $(document).ready(function () {
         $('.date').datepicker({
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            calendarWeeks: true,
            autoclose: true,
            todayHighlight: true
        });

            $('#tableholiday').dataTable({
    "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
            $('#tableappointment').dataTable({
    "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
    });</script>

<script>
            function formadd() {
                    document.getElementById("actiontype").value = '1';
            }

    //dyanamic function creation for collecting parameters in update function     
    function formupdate(holiday_fdate,
<?php foreach ($languagelist as $langcode) { ?>
    <?php echo 'holiday_desc_' . $langcode['mainlanguage']['language_code']; ?>,
<?php } ?>  id) {
    document.getElementById("actiontype").value = '1';
//dyanamic function creation for Assigning value to text boxes in update function  according to language code   
<?php
foreach ($languagelist as $langcode) {
    ?>
        $('#holiday_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>').val(holiday_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>);
<?php } ?>
    $('#hfid').val(id);
            $('#holiday_fdate').val(holiday_fdate);
//            $('#holiday_tdate').val(holiday_tdate);
            $('#hfaction').val('U');
            $('#btnadd').html('Save');
            return false;
    }

</script> 
<?php echo $this->Form->create('holiday', array('id' => 'holiday')); ?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
<div class="row">
    <div class="col-lg-12">
         <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>
        <div class="box box-primary">
          
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblhday'); ?></h3></center>
                
                    <div class="box-tools pull-right">
                        <a  href="<?php echo $this->webroot; ?>helpfiles/Masters/holiday_<?php echo $lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                    </div> 
            </div>
            <div class="box-body">

                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
<?php
//creating dyanamic text boxes using same array of config language
foreach ($languagelist as $key => $langcode) {
    ?>
                        <br><br>
                            <div class="col-md-3">
                                <label><?php echo __('lblholidaydescription') . "  " . $langcode['mainlanguage']['language_name']; ?><span style="color: #ff0000">*</span></label>     </div>
                        <div class="col-md-3">
                                     <?php echo $this->Form->input('holiday_desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'holiday_desc_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                        </div>
                                <span id="<?php echo 'holiday_desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error"><?php echo $errarr['holiday_desc_' . $langcode['mainlanguage']['language_code'] . '_error']; ?></span>
                               
                            
<?php } ?>
                    </div>
                </div>
               <br>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <div class="col-md-3">
                        <label for="holiday_fdate" class="col-sm-3 control-label"><?php echo __('lbldate'); ?><span style="color: #ff0000">*</span></label>     </div>
                        <div class="col-sm-3">
                        <?php echo $this->Form->input('holiday_fdate', array('label' => false, 'id' => 'holiday_fdate', 'class' => 'date form-control input-sm', 'type' => 'text')) ?>
                            
                        </div>
                        <span id="holiday_fdate_error" class="form-error"><?php //echo $errarr['holiday_fdate_error']; ?></span>
                    </div>
                </div>
                
                 <div  class="rowht"></div>
<!--                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="holiday_tdate" class="col-sm-3 control-label"><?php  echo __('lblhdaytodate'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                        <?php echo $this->Form->input('holiday_tdate', array('label' => false, 'id' => 'holiday_tdate', 'class' => 'date form-control input-sm', 'type' => 'text')) ?>
                            <span id="holiday_tdate_error" class="form-error"><?php //echo $errarr['holiday_tdate_error']; ?></span>
                        </div>
                    </div>
                </div>-->
                

                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center">
                    <div class="form-group">
                        <button id="btnadd" name="btnadd" class="btn btn-info "  onclick="javascript: return formadd();">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>

<?php if (!empty($holidayrecord)) { ?>
            <div class="box box-primary">

                <div class="box-body">
                    <div id="selectholiday">
                        <table id="tableholiday" class="table table-striped table-bordered table-hover" >
                            <thead >  
                                <tr>  
                                    <th class="center"><?php echo __('lbldate'); ?></th>
                                    <!--<th class="center"><?php //echo __('lbltodate'); ?></th>-->
    <?php
//  creating dyanamic table header using same array of config language
    foreach ($languagelist as $langcode) {
        // pr($langcode);
        ?>
                                        <th class="center"><?php echo __('lblholidaydescription') . " (" . $langcode['mainlanguage']['language_name'] . ")"; ?></th>
                                    <?php } ?>
                                    <th class="center width10"><?php echo __('lblaction'); ?></th>
                                </tr>  
                            </thead>
                            <tbody>
    <?php foreach ($holidayrecord as $holidayrecord1): ?>
                                    <tr>
                                        <td class="tblbigdata"><?php echo $holidayrecord1['holiday']['holiday_fdate']; ?></td>
                                        <!--<td class="tblbigdata"><?php //echo $holidayrecord1['holiday']['holiday_tdate']; ?></td>-->
        <?php
        //  creating dyanamic table data(coloumns) using same array of config language
        foreach ($languagelist as $langcode) {
            // pr($langcode);
            ?>
                                            <td class="tblbigdata"><?php echo $holidayrecord1['holiday']['holiday_desc_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                        <?php } ?>

                                        <td >
                                            <button id="btnupdate" name="btnupdate" type="button" class="btn btn-default "   onclick="javascript: return formupdate(('<?php echo $holidayrecord1['holiday']['holiday_fdate']; ?>'),
        <?php
        //  creating dyanamic parameters  using same array of config language for sending to update function
        foreach ($languagelist as $langcode) {
            // pr($langcode);
            ?>
                                                            ('<?php echo $holidayrecord1['holiday']['holiday_desc_' . $langcode['mainlanguage']['language_code']]; ?>'),
                                            <?php } ?>
                                                        ('<?php echo $holidayrecord1['holiday']['id']; ?>'));">
                                                <span class="glyphicon glyphicon-pencil"></span>
                                            </button>
                                            <!--                                        <button id="btndelete" name="btndelete" type="button" class="btn btn-default "     onclick="javascript: return formdelete(
                                                                                                            ('<?php echo $holidayrecord1['holiday']['id']; ?>'));">
                                                                                        <span class="glyphicon glyphicon-remove"></span>
                                                                                    </button>-->
                                            <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'delete_holiday', $holidayrecord1['holiday']['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
                                        </td>
                                    </tr> 
    <?php endforeach; ?>
                                <?php unset($holidayrecord1); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
<?php } ?>
        <?php if (!empty($appointment)) { ?>
            <div class="box box-primary">
                <div class="box-body">
                    <table id="tableappointment" class="table table-striped table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th class="center"><?php echo __('Token No.'); ?></th>
                                <th class="center"><?php echo __('Appointment Date'); ?></th>
                                <th class="center"><?php echo __('Appointment Time'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
    <?php
    foreach ($appointment as $appointment1):
        ?>
                                <tr>
                                    <td><?php echo $appointment1[0]['token_no']; ?></td>
                                    <td><?php echo $appointment1[0]['appointment_date']; ?></td>
                                    <td><?php echo $appointment1[0]['sheduled_time']; ?></td>
                                </tr>
                            
    <?php endforeach; ?>
</tbody>
                    </table>
                </div>
            </div>
<?php } ?>
        <input type='hidden' value='<?php echo $action; ?>' name='actiontype' id='actiontype'/>
        <input type='hidden' value='<?php echo $hfaction; ?>' name='hfaction' id='hfaction'/>
        <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>

    </div>
</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>