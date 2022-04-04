<?php
echo $this->Html->script('../datepicker/public/javascript/zebra_datepicker');
echo $this->Html->css('../datepicker/public/css/default');
?>
<script>
    $(document).ready(function () {

        $('#intimation_date').Zebra_DatePicker({
            view: 'years'
        });
        $('#case_admited_date').Zebra_DatePicker({
            view: 'years'
        });
        $('#notice_1_date').Zebra_DatePicker({
            view: 'years'
        });
        $('#hearing_date').Zebra_DatePicker({
            view: 'years'
        });
        $('#order_date').Zebra_DatePicker({
            view: 'years'
        });
    });
</script>
<script>
    function formadd() {
        document.getElementById("actiontype").value = '1';
        document.getElementById("hfaction").value = 'S';
    }
    function formupdate(respondent_f_name, respondent_m_name, respondent_l_name, respondent_address, respondent_email_id, mobile_no, respondent_advocate_f_name, respondent_advocate_m_name, respondent_advocate_l_name, liable_for_payment_flag, id) {
        document.getElementById("actiontype").value = '1';
        $('#respondent_f_name').val(respondent_f_name);
        $('#respondent_m_name').val(respondent_m_name);
        $('#respondent_l_name').val(respondent_l_name);
        $('#respondent_address').val(respondent_address);
        $('#respondent_email_id').val(respondent_email_id);
        $('#mobile_no').val(mobile_no);
        $('#respondent_advocate_f_name').val(respondent_advocate_f_name);
        $('#respondent_advocate_m_name').val(respondent_advocate_m_name);
        $('#respondent_advocate_l_name').val(respondent_advocate_l_name);
        $('#liable_for_payment_flag').val(liable_for_payment_flag);
        $('#hfid').val(id);
        $('#hfupdateflag').val('Y');
        $('#btnadd').html('Save');
        return false;
    }
</script>
<?php echo $this->Form->create('respondententry', array('url' => array('controller' => 'NewCase', 'action' => 'respondententry'), 'id' => 'respondententry', 'class' => 'form-vertical')); ?>
<?php
echo $this->element("NewCase/main_menu");
echo $this->element("NewCase/property_menu");
?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('Respondent Details'); ?></h3></center>
            </div>
            <div class="box-body">
                <?php // for ($i = 1; $i <= $noofresp1; $i++) {  ?>

                <div class="row">
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Case Number:-<span style="color: #ff0000"></span></label>
                        <div class="col-sm-2">
                            <b><?php echo $case_code_id; ?></b>
                        </div>
                    </div>
                </div>
                   <!--<br>-->
                <div class="row">
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Case Code:-<span style="color: #ff0000"></span></label>
                        <div class="col-sm-2">
                            <b><?php echo $ccms_case; ?></b>
                        </div>
                    </div>
                </div>
<!--                <div class="row">
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Selected Case:-<span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('', array('label' => false, 'id' => '', 'value' => $ccms_case, 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?>
                        </div>
                    </div>
                </div>-->
                <br>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-3">
                            <!--<label><?php echo __('Salutation'); ?> </label>-->   
                            <?php echo $this->Form->input('salutation', array('label' => false, 'id' => 'salutation', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $salutation))); ?>
                        <span id="salutation_error" class="form-error"><?php  echo $errarr['salutation_error'];                       ?></span>
                        </div>
                        <div class="col-md-3"> 
                            <!--<label>   <?php echo __('Respondent First name'); ?></label>-->
                            <?php echo $this->Form->input('respondent_f_name', array('label' => false, 'id' => 'respondent_f_name', 'placeholder' => 'Respondent First Name', 'type' => 'text', 'class' => 'form-control', 'data-placement' => 'bottom', 'autocomplete' => 'off')); ?>
                            <span id="respondent_f_name_error" class="form-error"><?php echo $errarr['respondent_f_name_error']; ?></span>
                        </div> 
                        <div class="col-md-3">
                            <!--<label>   <?php echo __('Respondent middle name'); ?></label>-->
                            <?php echo $this->Form->input('respondent_m_name', array('label' => false, 'id' => 'respondent_m_name', 'placeholder' => 'Respondent Middle Name', 'type' => 'text', 'class' => 'form-control', 'data-placement' => 'bottom', 'autocomplete' => 'off')); ?>
                            <span id="respondent_m_name_error" class="form-error"><?php echo $errarr['respondent_m_name_error']; ?></span>
                        </div> 
                        <div class="col-md-3"> 
                            <!--<label>   <?php echo __('Respondent Last name'); ?></label>-->
                            <?php echo $this->Form->input('respondent_l_name', array('label' => false, 'id' => 'respondent_l_name', 'placeholder' => 'Respondent Last Name', 'type' => 'text', 'class' => 'form-control', 'data-placement' => 'bottom', 'autocomplete' => 'off')); ?>
                            <span id="respondent_l_name_error" class="form-error"><?php echo $errarr['respondent_l_name_error']; ?></span>
                        </div> 
                    </div>
                </div>
                <br>
                <?php // }  ?>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-3"> 
                            <!--<label>   <?php echo __('Respondent address'); ?></label>-->
                            <?php echo $this->Form->input('respondent_address', array('label' => false, 'id' => 'respondent_address', 'placeholder' => 'Respondent Address', 'type' => 'text', 'class' => 'form-control', 'data-placement' => 'bottom', 'autocomplete' => 'off')); ?>
                            <span id="respondent_address_error" class="form-error"><?php echo $errarr['respondent_address_error']; ?></span>
                        </div> 
                        <div class="col-md-3">
                            <!--<label>   <?php echo __('respondent_email_id'); ?></label>-->
                            <?php echo $this->Form->input('respondent_email_id', array('label' => false, 'id' => 'respondent_email_id', 'type' => 'text', 'placeholder' => 'Respondent Email', 'class' => 'form-control', 'data-placement' => 'bottom', 'autocomplete' => 'off')); ?>
                            <span id="respondent_email_id_error" class="form-error"><?php echo $errarr['respondent_email_id_error']; ?></span>
                        </div> 
                        <div class="col-md-3"> 
                            <!--<label>   <?php echo __('mobile_no'); ?></label>-->
                            <?php echo $this->Form->input('mobile_no', array('label' => false, 'id' => 'mobile_no', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Respondent Mobile No.', 'data-placement' => 'bottom', 'autocomplete' => 'off')); ?>
                            <span id="mobile_no_error" class="form-error"><?php echo $errarr['mobile_no_error']; ?></span>
                        </div> 
                    </div>
                </div>
                <br>
                <div class="row">  
                    <div class="form-group">
                        <!--                        <div class="col-md-3">
                                                    <label><?php echo __('liable_for_payment_flag'); ?></label>
                                                    <select name="data[NewCaseadd][liable_for_payment_flag]" class="dropdown-toggle" type="button">
                                                        <option value="">Select</option>
                                                        <option value="YES">YES</option>
                                                        <option value="NO">NO</option>
                                                    </select>
                                                </div>-->
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
            </div>      </div>
        <div class="box box-primary">
            <div class="box-body">
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-3">
                            <!--<label><?php echo __('salutation'); ?> </label>-->   
                            <?php echo $this->Form->input('salutation_id', array('label' => false, 'id' => 'salutation_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $salutation))); ?>
                            <span id="salutation_id_error" class="form-error"><?php echo $errarr['salutation_id_error']; ?></span>
                        </div>
                        <div class="col-md-3"> 
                            <!--<label>   <?php echo __('respondent_advocate_f_name'); ?></label>-->
                            <?php echo $this->Form->input('respondent_advocate_f_name', array('label' => false, 'id' => 'respondent_advocate_f_name', 'placeholder' => 'Respondent Advocate First Name', 'type' => 'text', 'class' => 'form-control', 'data-placement' => 'bottom', 'autocomplete' => 'off')); ?>
                            <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>
                            <span id="respondent_advocate_f_name_error" class="form-error"><?php echo $errarr['respondent_advocate_f_name_error']; ?></span>
                        </div> 
                        <div class="col-md-3">
                            <!--<label>   <?php echo __('respondent_advocate_m_name'); ?></label>-->
                            <?php echo $this->Form->input('respondent_advocate_m_name', array('label' => false, 'id' => 'respondent_advocate_m_name', 'placeholder' => 'Respondent Advocate Middle Name', 'type' => 'text', 'class' => 'form-control', 'data-placement' => 'bottom', 'autocomplete' => 'off')); ?>
                            <span id="respondent_advocate_m_name_error" class="form-error"><?php echo $errarr['respondent_advocate_m_name_error']; ?></span>
                        </div> 
                        <div class="col-md-3"> 
                            <!--<label>   <?php echo __('respondent_advocate_l_name'); ?></label>-->
                            <?php echo $this->Form->input('respondent_advocate_l_name', array('label' => false, 'id' => 'respondent_advocate_l_name', 'placeholder' => 'Respondent Advocate Last Name', 'type' => 'text', 'class' => 'form-control', 'data-placement' => 'bottom', 'autocomplete' => 'off')); ?>
                            <span id="respondent_advocate_l_name_error" class="form-error"><?php echo $errarr['respondent_advocate_l_name_error']; ?></span>
                        </div> 
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
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
                            <th class="center"><?php echo __('Respondent Name'); ?></th>
                            <th class="center"><?php echo __('Respondent Advocate name'); ?></th>
                            <th class="center"><?php echo __('Respondent Address'); ?> </th>
                            <th class="center"><?php echo __('Respondent Email'); ?> </th>
                            <th class="center"><?php echo __('Moblie NO.'); ?> </th>
                            <!--<th class="center"><?php// echo __('liable_for_payment_flag'); ?> </th>-->
                            <th class="center width16"><?php echo __('lblaction'); ?></th>
                        </tr>  
                    </thead>
                    <tr>
                        <?php
                        foreach ($resp_record as $resp_record1):
//                    pr($resp_record);
//                    exit;
                            ?>
                            <td class="tblbigdata"><?php echo $resp_record1[0]['respondent_f_name'] . " " . $resp_record1[0]['respondent_m_name'] . " " . $resp_record1[0]['respondent_l_name']; ?></td>
                            <td class="tblbigdata"><?php echo $resp_record1[0]['respondent_advocate_f_name'] . " " . $resp_record1[0]['respondent_advocate_m_name'] . " " . $resp_record1[0]['respondent_advocate_l_name']; ?></td>
                            <td class="tblbigdata"><?php echo $resp_record1[0]['respondent_address']; ?></td>
                            <td class="tblbigdata"><?php echo $resp_record1[0]['respondent_email_id']; ?></td>
                            <td class="tblbigdata"><?php echo $resp_record1[0]['mobile_no']; ?></td>
                            <!--<td class="tblbigdata"><?php //echo $resp_record1[0]['liable_for_payment_flag']; ?></td>-->
                            <td>
    <!--                                <input type="button" id="btnpren" name="btnpren" class="btn btn-info "  
                                       onclick="javascript: return edit(('<?php // echo $resp_record1[0]['id'];                                  ?>'));"                                  
                                       value="Is Presenter" />-->
                                <button id="btnupdate" name="btnupdate" class="btn btn-default "  onclick="javascript: return formupdate(
                                                    ('<?php echo $resp_record1[0]['respondent_f_name']; ?>'),
                                                    ('<?php echo $resp_record1[0]['respondent_m_name']; ?>'),
                                                    ('<?php echo $resp_record1[0]['respondent_l_name']; ?>'),
                                                    ('<?php echo $resp_record1[0]['respondent_address']; ?>'),
                                                    ('<?php echo $resp_record1[0]['respondent_email_id']; ?>'),
                                                    ('<?php echo $resp_record1[0]['mobile_no']; ?>'),
                                                    ('<?php echo $resp_record1[0]['respondent_advocate_f_name']; ?>'),
                                                    ('<?php echo $resp_record1[0]['respondent_advocate_m_name']; ?>'),
                                                    ('<?php echo $resp_record1[0]['respondent_advocate_l_name']; ?>'),
                                                    ('<?php echo $resp_record1[0]['liable_for_payment_flag']; ?>'),
                                                    ('<?php echo $resp_record1[0]['id']; ?>')
                                                    );">
                                    <span class="glyphicon glyphicon-pencil"></span></button>
                                <?php
                                $newid = $this->requestAction(
                                        array('controller' => 'NewCase', 'action' => 'encrypt', $resp_record1[0]['id'], $this->Session->read("randamkey"),
                                ));
                                ?>

                                <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'respondent_delete', $newid), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
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

</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>

