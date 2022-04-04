
<script language="JavaScript" type="text/javascript">
    $(document).ready(function () {
        function disableBack() {
            window.history.forward();
        }
        window.onload = disableBack();
        window.onpageshow = function (evt) {
            if (evt.persisted)
                disableBack();
        };

        $('#doc_reg_date').datepicker({
            todayBtn: "linked",
            language: "it",
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy"
        });
        $('#paymentmode_id').val('');
        getFees($('input:radio[name="data[certificatesissuedetails][ctype]"]').val());
        var actiontype = document.getElementById('actiontype').value;
        if (actiontype == '1') {
            $("#fund_code").prop("readonly", false);
            $('#fund_code').val('');
            $("#fund_desc").prop("readonly", false);
            $('#fund_desc').val('');
        }
        $('input:radio[name="data[certificatesissuedetails][ctype]"]').change(function () {
            ($(this).val() == 'E') ? ($('.encumbrance').show()) : ($('.encumbrance').hide());
            getFees($(this).val());
        });

        $('#paymentmode_id').change(function (e) {
            var mode = $('#paymentmode_id').val();
//            var reff_no = $('#reff_no').val();
            if (mode === '')
            {
                alert("Please Select Payment Mode");
                e.preventDefault();
                return;
            } else {
                $.post('<?php echo $this->webroot; ?>Registration/get_payment_details_simple_reciept', {mode: mode}, function (data)
                {
                    $('#paydetails').html(data);
                    $(document).trigger('_page_ready');
                    $('#pdate').datepicker({
                        todayBtn: "linked",
                        language: "it",
                        autoclose: true,
                        todayHighlight: true,
                        format: "dd-mm-yyyy"
                    });
                    $('#estamp_issue_date').datepicker({
                        todayBtn: "linked",
                        language: "it",
                        autoclose: true,
                        todayHighlight: true,
                        format: "dd-mm-yyyy"
                    });

                    $('#bank_id').change(function (e) {

                        var bank = $(this).val();

                        if (bank !== '')
                        {
                            $.postJSON('<?php echo $this->webroot; ?>Citizenentry/get_bank_branch', {bank: bank, csrftoken:<?php echo $this->Session->read('csrftoken'); ?>}, function (data)
                            {
                                var sc = '<option>--select--</option>';
                                $.each(data, function (index, val) {
                                    sc += "<option value=" + index + ">" + val + "</option>";
                                });
                                $("#branch_id option").remove();
                                $("#branch_id").append(sc);


                                $('#branch_id').change(function (e) {

                                    var branch = $(this).val();

                                    if (branch !== '')
                                    {
                                        $.postJSON('<?php echo $this->webroot; ?>Citizenentry/get_bank_branch_code', {branch: branch, csrftoken:<?php echo $this->Session->read('csrftoken'); ?>}, function (data)
                                        {
                                            $.each(data, function (index, val) {
                                                $("#ifsc_code").val(val);
                                            });
                                        });

                                    }
                                });


                            });

                        }
                    });
                });

            }
        });

    });

    function getFees(cert_id) {
        $.post('<?php echo $this->webroot; ?>getCertFees', {'ctype': cert_id}, function (fees) {
            $('#fee_amount').val(fees);
        }, 'json');
    }
    function Formsave() {
        document.getElementById("actiontype").value = '1';
        document.getElementById("hfaction").value = 'S';
        $("#username").val($('#user_id option:selected').text());
        $('#asssignworkdetails').submit();
    }
    function Cancelform() {
        document.getElementById("actiontype").value = '2';
        $('#asssignworkdetails').submit();
    }
</script>
<?php $doc_lang = $this->Session->read('doc_lang'); ?> 
<?php echo $this->Form->create('certificatesissuedetails', array('type' => 'file', 'id' => 'certificatesissuedetails')); ?>
 <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
<div class="box box-primary">
    <div class="box-header with-border">
        <center><h3 class="box-title headbolder"><?php echo __('lblreqforcertcopy'); ?></h3></center>
        <div class="box-tools pull-right">
            <a  href="<?php echo $this->webroot; ?>helpfiles/citizenentry/certificatesissuedetails_<?php echo $doc_lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-lg-12">
                <?php echo $this->Form->input('office_name_en', array('type' => 'hidden', 'id' => 'office_name_en')); ?>
                <div class="fieldset">

                    <div class="rowht"></div><div class="rowht"></div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-3"></div>
                            <label for="Filter Record By" class="control-label col-sm-2"><?php echo __('lblissuetype'); ?></label>    
                            <div class="col-sm-7"> <?php echo $this->Form->input('ctype', array('type' => 'radio', 'options' => array('E' => '&nbsp;' . __('lblEncumbrance') . '&nbsp;&nbsp;', 'C' => '&nbsp;' . __('lblCertifiedCopy') . '&nbsp;&nbsp;&nbsp;'), 'value' => 'E', 'legend' => false, 'div' => false, 'id' => 'ctype')); ?>
                                <span id="ctype_error" class="form-error"><?php //echo $errarr['ctype_error']; ?></span>

                            </div>                            
                        </div>
                    </div>
                    <div class="rowht"></div><div class="rowht"></div>

                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-3"></div>
                            <label for="Applicant Name" class="control-label col-sm-2"><?php echo __('lblApplicantName'); ?></label>    
                            <div class="col-sm-4"> <?php echo $this->Form->input('applicant_name', array('type' => 'text', 'label' => false, 'class' => 'form-control input-sm text-lowercase', 'legend' => false, 'id' => 'applicant_name')); ?>
                                <span id="applicant_name_error" class="form-error"><?php //echo $errarr['applicant_name_error']; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="rowht"></div><div class="rowht"></div>

                    <div class="row">       
                        <div class="col-sm-3"></div>
                        <label for="doc_reg_no" class="control-label col-sm-2"><?php echo __('lblregno'); ?></label>
                        <div class="col-sm-4">
                            <?php echo $this->Form->input('doc_reg_no', array('label' => false, 'error' => false, 'id' => 'doc_reg_no', 'type' => 'text', 'class' => 'form-control input-sm text-lowercase')); ?>
                            <span id="doc_reg_no_error" class="form-error"><?php //echo //$errarr['doc_reg_no_error']; ?></span>
                        </div>
                    </div>
                    <div class="rowht"></div><div class="rowht"></div>

                    <div class="row">
                        <div class="col-sm-3"></div>
                        <label for="doc_reg_no" class="control-label col-sm-2"><?php echo __('lblregdate'); ?></label>
                        <div class="col-sm-4">
                            <?php echo $this->Form->input('doc_reg_date', array('label' => false, 'error' => false, 'id' => 'doc_reg_date', 'type' => 'text', 'class' => 'form-control input-sm text-lowercase')); ?>
                            <span id="doc_reg_date_error" class="form-error"><?php //echo $errarr['doc_reg_date_error']; ?></span>
                        </div>
                    </div>
                    <div class="rowht"></div><div class="rowht"></div>

                    <div class="row">
                        <div class="col-sm-3"></div>
                        <label for="office_id" class="control-label col-sm-2"><?php echo __('lblregoffice'); ?></label>
                        <div class="col-sm-4"> <?php
                            echo $this->Form->input('office_id', array(
                                'type' => 'select', 'error' => false, 'empty' => '--select--',
                                'options' => array($officedata), 'id' => 'office_id',
                                'label' => false, 'style' => 'width:100%;',
                            ));
                            ?>
                            <span id="office_id_error" class="form-error"><?php //echo $errarr['office_id_error']; ?></span>
                        </div>
                    </div>
                    <div class="rowht"></div><div class="rowht"></div>

                    <div class="row encumbrance">
                        <div class="col-sm-3 "></div>
                        <label for="uniq_prop_id" class="control-label col-sm-2"><?php echo __('lbluniquepropnu'); ?></label>
                        <div class="col-sm-4">
                            <?php echo $this->Form->input('uniq_prop_id', array('label' => false, 'error' => false, 'id' => 'uniq_prop_id', 'type' => 'text', 'class' => 'form-control input-sm text-lowercase')); ?>
                            <span id="uniq_prop_id_error" class="form-error"><?php //echo $errarr['uniq_prop_id_error'];          ?></span>
                        </div>  
                    </div>
                    <div class="rowht encumbrance"></div><div class="rowht encumbrance"></div>

                    <div class="row encumbrance">
                        <div class="col-sm-3"></div>
                        <label for="survey_no" class="control-label col-sm-2"><?php echo __('lblsurveyno'); ?></label>
                        <div class="col-sm-4">
                            <?php echo $this->Form->input('survey_no', array('label' => false, 'error' => false, 'id' => 'survey_no', 'type' => 'text', 'class' => 'form-control input-sm text-lowercase')); ?>
                            <span id="survey_no_error" class="form-error"><?php //echo $errarr['survey_no_error'];          ?></span>
                        </div>  
                    </div>
                    <div class="rowht encumbrance"></div><div class="rowht encumbrance"></div>

                    <!--                                        <div class="row">
                                                                <div class="col-sm-3"></div>
                                                                <label for="fee_rule" class="control-label col-sm-2"><?php //echo __('lblfeerule');                                                                  ?></label>
                                                                <div class="col-sm-4">
                    <?php //echo $this->Form->input('fee_rule', array('label' => false, 'error' => false, 'id' => 'fee_rule', 'type' => 'select', 'options' => $fee_rules, 'class' => 'form-control input-sm text-lowercase')); ?>
                                                                </div>  
                                                            </div> 
                                                            <div class="rowht"></div><div class="rowht"></div>-->
                    <div class="row">
                        <div class="col-sm-3"></div>
                        <label for="fee_amount" class="control-label col-sm-2"><?php echo __('lblfee'); ?></label>
                        <div class="col-sm-4">
                            <?php echo $this->Form->input('fee_amount', array('label' => false, 'error' => false, 'id' => 'fee_amount', 'type' => 'text', 'readonly' => true, 'class' => 'form-control input-sm text-lowercase')); ?>
                            <span id="fee_amount_error" class="form-error"><?php //echo $errarr['fee_amount_error']; ?></span>
                        </div>  
                    </div> 

                    <div class="box-body">
                        <div class="row">
                            <div class="row">
                                <div class="col-sm-3"></div>
                                <label for="" class="col-sm-2 control-label"><?php echo __('lblselectpaymode'); ?><span style="color: #ff0000">*</span></label>    
                                <div class="col-sm-4"> 
                                    <?php echo $this->Form->input('paymentmode_id', array('label' => false, 'id' => 'paymentmode_id', 'class' => 'form-control input-sm', 'type' => 'select', 'options' => $payment_mode, 'empty' => '--Select--')) ?>                         
                                    <span id="paymentmode_id_error" class="form-error"><?php //echo $errarr['paymentmode_id_error']; ?></span>
                                </div> 
                            </div> 
                            <div  class="rowht">&nbsp;</div>
                            <div class="row">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-9" id="paydetails">

                                </div> 
                            </div>


                        </div> 
                        <div  class="rowht">&nbsp;</div>

                    </div> 

                    <div class="rowht"></div><div class="rowht"></div>
                    <div class="col-sm-5"></div>
                    <button id="btnSave" name="btnSave" class="btn btn-primary " onclick="javascript: return Formsave();"><?php echo __('btnsave'); ?></button>
                    <input type="reset" id="btnCancel" name="btnCancel" class="btn btn-primary " value="<?php echo __('btncancel'); ?>">
                    <div class="rowht"></div><div class="rowht"></div>
                </div> 

                <input type='hidden' value=<?php echo $actiontypeval; ?> name='actiontype' id='actiontype'/>
                <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
                <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
                <br>  

            </div>
        </div>  
    </div>
</div>
<?php echo $this->Form->end(); ?>