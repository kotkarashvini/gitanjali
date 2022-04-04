<?php
echo $this->Html->script('../datepicker/public/javascript/zebra_datepicker');
echo $this->Html->css('../datepicker/public/css/default');
echo $this->element("Helper/jqueryhelper");
?>
<script type="text/javascript">
    $(document).ready(function () {
//-----------------------------------------------------------------------------------------------------------------------------------------------
    $('#btnNext').click(function () {

    $("#genernalinfoentry").submit();
    });
    });
//-----------------------------------------------------------------------------------------------------------------------------------------------
</script>
<script>
            $(document).ready(function () {
    $('#adj_date').Zebra_DatePicker({
    view: 'years'
    });
            $('#old_doc_reg_date').Zebra_DatePicker({
    view: 'years'
    });
            $('#ref_doc_reg_date').Zebra_DatePicker({
    view: 'years'
    });
            $('#date_of_entry').Zebra_DatePicker({
    view: 'years'
    });
    });</script>
<script>
            sortSelect('#ddlList12', 'text', 'asc');
            sortSelect('#ddlList2', 'text', 'asc');
            });
</script>
<?php echo $this->Form->create('genernalinfoentry', array('id' => 'genernalinfoentry', 'class' => 'form-vertical')); ?>
<?php
echo $this->element("NewCase/main_menu");
echo $this->element("NewCase/property_menu");
?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('Case Admission'); ?></h3></center>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Case Number:-<span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('', array('label' => false, 'id' => '', 'value' => $ccms_case, 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?>
                            <?php
                            if (isset($this->request->data['genernalinfoentry']['case_id'])) {
                                echo $this->Form->input('case_id', array('label' => false, 'id' => '', 'value' => $this->request->data['genernalinfoentry']['case_id'], 'class' => 'form-control input-sm', 'type' => 'text', 'readonly'));
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>   <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="case_type_id" class="col-sm-2 control-label"><?php echo __('Case Type'); ?> <span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('case_type_id', array('label' => false, 'id' => 'case_type_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $casetypedesc))); ?>
                            <span id="case_type_id_error" class="form-error"><?php echo $errarr['case_type_id_error']; ?></span>
                        </div>

                        <select id="ddlList">
                            <option value="3">Three</option>
                            <option value="1">One</option>
                            <option value="1">one</option>
                            <option value="1">a</option>
                            <option value="1">b</option>
                            <option value="1">A</option>
                            <option value="1">B</option>
                            <option value="1">Zero</option>
                            <option value="1">A</option>
                            <option value="1">b</option>
                        </select>


                        <select id="ddlList1">
                            <option value="3">Three</option>
                            <option value="1">One</option>
                            <option value="1">one</option>
                            <option value="1">a</option>
                            <option value="1">b</option>
                            <option value="1">A</option>
                            <option value="1">B</option>
                            <option value="1">Zero</option>
                            <option value="1">A</option>
                            <option value="1">b</option>
                        </select>
                        <label for="case_code" class="col-sm-2 control-label"><?php echo __('Case Code'); ?> <span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('case_code', array('label' => false, 'id' => 'case_code', 'class' => 'form-control input-sm', 'type' => 'text', 'autocomplete' => 'off')); ?>

                            <span id="case_code_error" class="form-error"><?php echo $errarr['case_code_error']; ?></span>
                        </div>
                        <label for="case_code" class="col-sm-2 control-label"><?php echo __('Case year'); ?> <span style="color: #ff0000">*</span></label>                             
                        <div class="col-sm-2">
                            <?php
                            // Sets the top option to be the current year.
                            $currently_selected = date('Y');
                            // Year to start available options at
                            $earliest_year = 1950;
                            // Set your latest year you want in the range, in this case we use PHP to just set it to the current year.
                            $latest_year = date('Y');
                            print '<select class="btn btn-default dropdown-toggle" name="data[genernalinfoentry][case_year]" type="button">';
                            // Loops over each int[year] from current year, back to the $earliest_year [1950]
                            foreach (range($latest_year, $earliest_year) as $i) {
                                // Prints the option with the next year in range.
                                print '<option value="' . $i . '"' . ($i === $currently_selected ? ' selected="selected"' : '') . '>' . $i . '</option>';
                            }
                            print '</select>';
                            ?>             
                        </div>
                    </div>
                </div> 
                <div  class="rowht">&nbsp;</div>   <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="case_type_id" class="col-sm-2 0control-label">Date of Entry <span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('date_of_entry', array('label' => false, 'id' => 'date_of_entry', 'type' => 'text', 'class' => 'form-control input-sm', 'autocomplete' => 'off')); ?>  
                            <span id="date_of_entry_error" class="form-error"><?php echo $errarr['date_of_entry_error']; ?></span>
                        </div>
                        <label for="case_code" class="col-sm-2 control-label">Applicant Name<span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('applicant_name', array('label' => false, 'id' => 'applicant_name', 'type' => 'text', 'class' => 'form-control input-sm', 'autocomplete' => 'off')); ?>  
                            <span id="applicant_name_error" class="form-error"><?php echo $errarr['applicant_name_error']; ?></span>
                        </div>
                        <label for="case_code" class="col-sm-2 control-label"><?php echo __('Case belongs to'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('case_belongs_to', array('label' => false, 'id' => 'case_belongs_to', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $sofficename))); ?>
                            <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
                            <span id="case_belongs_to_error" class="form-error"><?php echo $errarr['case_belongs_to_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>   <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="case_type_id" class="col-sm-2 control-label"><?php echo __('Objection type'); ?> <span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('objection_type_id', array('label' => false, 'id' => 'objection_type_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $objectiontype))); ?>
                            <span id="objection_type_id_error" class="form-error"><?php echo $errarr['objection_type_id_error']; ?></span>
                        </div>
                        <label for="case_code" class="col-sm-2 control-label"><?php echo __('Stamp duty'); ?>(Rs.)<span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('stamp_duty', array('label' => false, 'id' => 'stamp_duty', 'type' => 'text', 'class' => 'form-control input-sm', 'autocomplete' => 'off')); ?>  
                            <span id="stamp_duty_error" class="form-error"><?php echo $errarr['stamp_duty_error']; ?></span>
                        </div>
                        <label for="case_code" class="col-sm-2 control-label"><?php echo __('Lc paper'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('lc_paper', array('label' => false, 'id' => 'lc_paper', 'type' => 'text', 'class' => 'form-control', 'data-placement' => 'bottom', 'autocomplete' => 'off')); ?>
                            <span id="lc_paper_error" class="form-error"><?php echo $errarr['lc_paper_error']; ?></span>
                        </div>
                    </div>
                </div> 

                <div  class="rowht">&nbsp;</div>   <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="adjudication_no" class="col-sm-2 control-label"><?php echo __('Adjudication no'); ?> <span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('adjudication_no', array('label' => false, 'id' => 'adjudication_no', 'type' => 'text', 'class' => 'form-control input-sm', 'autocomplete' => 'off')); ?>  
                            <span id="adjudication_no_error" class="form-error"><?php echo $errarr['adjudication_no_error']; ?></span>
                        </div>
                        <!--<label for="case_code" class="col-sm-2 control-label"><?php echo __('adj_date'); ?> <span style="color: #ff0000">*</span></label>-->    
                        <!--<div class="col-sm-2">-->
                        <?php // echo $this->Form->input('adj_date', array('label' => false, 'id' => 'adj_date', 'type' => 'text', 'class' => 'form-control input-sm', 'autocomplete' => 'off'));  ?>  `
                            <!--<span id="adj_date_error" class="form-error"><?php // echo $errarr['adj_date_error']; ?></span>-->
                        <!--</div>-->
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <label for="case_type_id" class="col-sm-2 control-label"><?php echo __('Old document registration no'); ?> <span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('old_doc_reg_no', array('label' => false, 'id' => 'old_doc_reg_no', 'type' => 'text', 'class' => 'form-control input-sm', 'autocomplete' => 'off')); ?>  
                            <span id="old_doc_reg_no_error" class="form-error"><?php echo $errarr['old_doc_reg_no_error']; ?></span>
                        </div>
                        <label for="case_code" class="col-sm-2 control-label"><?php echo __('Old document registration date'); ?> <span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('old_doc_reg_date', array('label' => false, 'id' => 'old_doc_reg_date', 'type' => 'text', 'class' => 'form-control input-sm', 'autocomplete' => 'off')); ?>  
                            <!--<span id="old_doc_reg_date_error" class="form-error"><?php // echo $errarr['old_doc_reg_date_error']; ?></span>-->
                        </div>
                        <label for="case_type_id" class="col-sm-2 control-label"><?php echo __('Old document office'); ?> <span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('old_doc_office', array('label' => false, 'id' => 'old_doc_office', 'type' => 'text', 'class' => 'form-control input-sm', 'autocomplete' => 'off')); ?>  
                            <span id="old_doc_office_error" class="form-error"><?php echo $errarr['old_doc_office_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>   <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="case_code" class="col-sm-2 control-label"><?php echo __('Reference document registration no.'); ?>  <span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('ref_doc_reg_no', array('label' => false, 'id' => 'ref_doc_reg_no', 'type' => 'text', 'class' => 'form-control input-sm', 'autocomplete' => 'off')); ?>  
                            <span id="ref_doc_reg_no_error" class="form-error"><?php echo $errarr['ref_doc_reg_no_error']; ?></span>
                        </div>
                        <label for="case_type_id" class="col-sm-2 control-label"><?php echo __('Reference document registration date'); ?> <span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('ref_doc_reg_date', array('label' => false, 'id' => 'ref_doc_reg_date', 'type' => 'text', 'class' => 'form-control input-sm', 'autocomplete' => 'off')); ?>  
                            <!--<span id="ref_doc_reg_date_error" class="form-error"><?php //  echo $errarr['ref_doc_reg_date_error']; ?></span>-->
                        </div>
                        <label for="case_code" class="col-sm-2 control-label"><?php echo __('Reference document office'); ?><span style="color: #ff0000"></span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('ref_doc_office', array('label' => false, 'id' => 'ref_doc_office', 'type' => 'text', 'class' => 'form-control input-sm', 'autocomplete' => 'off')); ?>  
                            <?php //echo $this->Form->input('ref_doc_office', array('label' => false, 'id' => 'ref_doc_office', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $sofficename))); ?>
                            <span id="ref_doc_office_error" class="form-error"><?php echo $errarr['ref_doc_office_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>   <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <label for="case_code" class="col-sm-2 control-label"><?php echo __('salutation'); ?>   <span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('salutation', array('label' => false, 'id' => 'salutation', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $salutation))); ?>
                            <span id="salutation_error" class="form-error"><?php echo $errarr['salutation_error']; ?></span>
                        </div>
                        <!--<label for="case_type_id" class="col-sm-2 control-label"><?php echo __('advocate_f_name'); ?><span style="color: #ff0000">*</span></label>-->    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('advocate_f_name', array('label' => false, 'id' => 'advocate_f_name', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Advocate First Name', 'data-placement' => 'bottom', 'autocomplete' => 'off')); ?>
                            <span id="advocate_f_name_error" class="form-error"><?php echo $errarr['advocate_f_name_error']; ?></span>
                        </div>
                        <!--<label for="case_code" class="col-sm-2 control-label"><?php echo __('advocate_m_name'); ?><span style="color: #ff0000">*</span></label>-->    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('advocate_m_name', array('label' => false, 'id' => 'advocate_m_name', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Advocate Middle Name', 'data-placement' => 'bottom', 'autocomplete' => 'off')); ?>
                            <span id="advocate_m_name_error" class="form-error"><?php echo $errarr['advocate_m_name_error']; ?></span>
                        </div>
                        <!--<label for="case_code" class="col-sm-2 control-label"><?php echo __('advocate_l_name'); ?><span style="color: #ff0000">*</span></label>-->    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('advocate_l_name', array('label' => false, 'id' => 'advocate_l_name', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Advocate Last Name', 'data-placement' => 'bottom', 'autocomplete' => 'off')); ?>
                            <span id="advocate_l_name_error" class="form-error"><?php echo $errarr['advocate_l_name_error']; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>          
<div class="box box-primary">
    <div class="box-body">
        <div class="row center" >

            <input type="hidden"  id="continue_flag">
            <button type="reset"  id="btnCancel" name="btnCancel" class="btn btn-info"><?php echo __('btncancel'); ?></button>
            <button type="button" id="btnNext" name="btnNext" class="btn btn-info"><?php echo __('btnnext'); ?></button>
        </div>  
    </div>
</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>

