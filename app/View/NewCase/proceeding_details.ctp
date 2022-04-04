<?php
echo $this->Html->script('../datepicker/public/javascript/zebra_datepicker');
echo $this->Html->css('../datepicker/public/css/default');
?>
<script>
    $(document).ready(function () {
        $('#hearing_date').Zebra_DatePicker({
            view: 'years'
        });
        $('#next_hearing_date').Zebra_DatePicker({
            view: 'years'
        });
    });
</script>
<script>
    function formadd() {
        document.getElementById("actiontype").value = '1';
        document.getElementById("hfaction").value = 'S';
    }
    function formupdate(hearing_date, next_hearing_date, remark, proceeding_id) {
        document.getElementById("actiontype").value = '1';
        $('#hearing_date').val(hearing_date);
        $('#next_hearing_date').val(next_hearing_date);
        $('#remark').val(remark);
        $('#hfid').val(proceeding_id);
        $('#hfupdateflag').val('Y');
        $('#btnadd').html('Save');
        return false;
    }
</script>
<?php echo $this->Form->create('proceeding_details', array('id' => 'proceeding_details', 'class' => 'form-vertical')); ?>
<?php
echo $this->element("NewCase/main_menu");
echo $this->element("NewCase/property_menu");
?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('Proceeding Details'); ?></h3></center>
            </div>
            <div class="box-body">
                <?php // for ($i = 1; $i <= $noofresp1; $i++) { ?>

                <div class="row">
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Case Number<span style="color: #ff0000"></span></label>
                        <div class="col-sm-2">
                            : <?php echo $case_code_id; ?>
                        </div>
                        <label for="" class="col-sm-2 control-label">Case Code<span style="color: #ff0000"></span></label>
                        <div class="col-sm-2">
                            : <?php echo $ccms_case; ?>
                        </div>

                        <label for="" class="col-sm-2 control-label">Stamp Duty Revised<span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                            : <?php echo $stamp_duty_revised; ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
<!--                        <label for="" class="col-sm-2 control-label">Selected Case:-<span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                        <?php echo $this->Form->input('', array('label' => false, 'id' => '', 'value' => $ccms_case, 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?>
                        </div>-->
                        <label for="" class="col-sm-2 control-label">Hearing Date<span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                            : <?php
                            $date = date_create($fhdate);
                            echo date_format($date, "d/m/Y");
                            ?>
                        </div>
                        <label for="" class="col-sm-2 control-label">Place Of Hearing<span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                            : <?php echo $place_of_hearing; ?>
                        </div>


                    </div>
                </div>
            </div>
        </div>
        <div class="box box-primary">
            <div class="box-body">
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-5 col-md-offset-1" >
                            <label>   <?php echo __('Current Proceeding Details/Remark'); ?>:</label>
                            <?php echo $this->Form->input('remark', array('label' => false, 'id' => 'remark', 'type' => 'textarea', 'class' => 'form-control', 'data-placement' => 'bottom', 'autocomplete' => 'off')); ?>
                            <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>
                            <span id="remark_error" class="form-error"><?php echo $errarr['remark_error']; ?></span>

                            <label>   <?php echo __('Select Case Status'); ?></label>
                            <?php echo $this->Form->input('case_status_id', array('label' => false, 'id' => 'case_status_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $case_status))); ?>
                            <span id="case_status_id_error" class="form-error"><?php echo $errarr['case_status_id_error']; ?></span>

                            <label>   <?php echo __('Next Hearing Date'); ?></label>
                            <?php echo $this->Form->input('next_hearing_date', array('label' => false, 'id' => 'next_hearing_date', 'type' => 'text', 'class' => 'form-control', 'data-placement' => 'bottom', 'autocomplete' => 'off')); ?>
                            <!--<span id="next_hearing_date_error" class="form-error"><?php //echo $errarr['next_hearing_date_error'];      ?></span>-->
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="remark" class="col-sm-2 0control-label">Party present for Judgement<span style="color: #ff0000">*</span></label>   
                        <div class="col-sm-3">
                            <div class="usage-list" id="usage-list">
                                <?php echo $this->Form->input('usage_cat_id', array('type' => 'select', 'options' => $listdata, 'id' => 'usage_cat_id', 'multiple' => 'checkbox', 'label' => false, 'class' => ' usage_cat_id')); ?>
                            </div>
                            <span id="case_status_id_error" class="form-error"><?php echo $errarr['case_status_id_error']; ?></span>
                        </div>
                    </div>
                </div> 


            </div> </div>
        <div class="box box-primary">
            <div class="box-body">
                <div class="row center" >
                    <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
                    <input type="hidden"  id="continue_flag">
                    <button type="reset"  id="btnCancel" name="btnCancel" class="btn btn-info"><?php echo __('btncancel'); ?></button>
                    <!--<button type="button" id="btnNext" name="btnNext" class="btn btn-info"><?php echo __('btnnext'); ?></button>-->
                    <button id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                        <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?>
                    </button>
<!--<button type="button" class="btn btn-primary" id="btnnewdock" name="btnnewdock" onclick="location.href = '<?php echo $this->Html->url(array('controller' => 'NewCase', 'action' => 'genernalinfoentry')); ?>';">-->
                </div>  
            </div>
        </div>
        <div class="box box-primary">
            <div class="box-body">
                <table id="tableParty" class="table table-striped table-bordered table-condensed">  
                    <thead>  
                        <tr>  
                            <!--<th class="center"><?php echo __('case_belongs_to'); ?></th>-->
                            <!--<th class="center"><?php echo __('hearing_date'); ?></th>-->
                            <th class="center"><?php echo __('Next Hearing Date'); ?></th>
                            <th class="center"><?php echo __('Remark'); ?> </th>
                            <th class="center width16"><?php echo __('lblaction'); ?></th>
                        </tr>  
                    </thead>
                    <tr>
                        <?php
                        foreach ($resp_record as $resp_record1):
                            ?>
                                                <!--<td class="tblbigdata"><?php echo $resp_record1[0]['hearing_date']; ?></td>-->
                            <td class="tblbigdata"><?php echo $resp_record1[0]['next_hearing_date']; ?></td>
                            <td class="tblbigdata"><?php echo $resp_record1[0]['remark']; ?></td>
                            <td>
                                <?php
//                                $newid = $this->requestAction(
//                                        array('controller' => 'NewCase', 'action' => 'encrypt', $resp_record1[0]['proceeding_id'], $this->Session->read("randamkey"),
//                                ));
                                ?>
                                <button id="btnupdate" name="btnupdate" class="btn btn-default "  onclick="javascript: return formupdate(
                                                ('<?php echo $resp_record1[0]['hearing_date']; ?>'),
                                                ('<?php echo $resp_record1[0]['next_hearing_date']; ?>'),
                                                ('<?php echo $resp_record1[0]['remark']; ?>'),
                                                ('<?php echo $resp_record1[0]['proceeding_id']; ?>')
                                                );">
                                    <span class="glyphicon glyphicon-pencil"></span></button>
                                <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'proceeding_delete', $resp_record1[0]['proceeding_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
                            </td>
                        </tr>
                    <?php endforeach;
                    ?>
                    <?php unset($resp_record1); ?>
                </table> 
            </div>
            <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
            <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
            <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
            <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
    <?php echo $this->Js->writeBuffer(); ?>

