<script type="text/javascript">
    $(document).ready(function () {
        $('#doc_reg_date').datepicker({
            todayBtn: "linked",
            language: "it",
            autoclose: true,
            todayHighlight: true,
            endDate: '+0d',
            format: "dd-mm-yyyy"
        });
        
        $('#article_id').change(function () {
            var article_id = $('#article_id').val();
            if ($('#article_id').val() != '') {
                $.post('<?php echo $this->webroot; ?>Searchlegacy/get_party_type', {article_id: article_id}, function (data)
                {   var sc2 = '';
                    $.each(data, function (index, val) {
                        sc2 += "<option value=" + index + ">" + val + "</option>";
                    });
                    $("#party_type_id option").remove();
                    $("#party_type_id").append(sc2);
                }, 'json');
            }
        });
    });
    
</script>
<?php
echo $this->Form->create('srch_legacy_appl', array('id' => 'srch_legacy_appl', 'autocomplete' => 'off'));
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo "Application For Search"; ?></h3></center>
            </div>
            <div class="box-body">
                <div class="row" >
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo 'Search is to be made from which year : From'; ?></label>
                        <div class="col-sm-3">
                           <?php echo $this->Form->input('year_one', array('label' => false, 'id' => 'year_one','style'=>'width:100px;', 'class' => 'form-control input-sm', 'type' => 'text', 'placeholder' => '', 'maxlength' => "255")); ?>
                        </div>
                        <label class="col-sm-3 control-label"><?php echo 'To'; ?></label>
                        <div class="col-sm-3" text-align="left">
                           <?php echo $this->Form->input('year_two', array('label' => false, 'id' => 'year_two','style'=>'width:100px;', 'class' => 'form-control input-sm', 'type' => 'text', 'placeholder' => '', 'maxlength' => "255")); ?>
                        </div>
                    </div>
                </div>
                <div class="row" >
                    <div class="form-group">  
                        <label class="col-sm-3 control-label"><?php echo 'Article : '; ?><!--<span style="color: #ff0000">*</span>--></label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('article_id', array('label' => false, 'id' => 'article_id', 'class' => 'form-control input-sm', 'empty' => '--Select--', 'options' => array($article))); ?>
                        </div>
                    </div>
                </div>
                <div class="row" >
                    <div class="form-group">  
                        <label class="col-sm-3 control-label"><?php echo 'Name of the Sub-Registrar’s office in which the document is registered : '; ?><!--<span style="color: #ff0000">*</span>--></label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('office_id', array('label' => false, 'id' => 'office_id', 'class' => 'form-control input-sm', 'empty' => '--Select--', 'options' => array($office))); ?>
                        </div>
                    </div>
                </div>
                <div class="row" >
                    <div class="form-group">  
                        <label class="col-sm-3 control-label"><?php echo 'Document Registration Number : '; ?><!--<span style="color: #ff0000">*</span>--></label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('doc_reg_no', array('label' => false, 'id' => 'doc_reg_no', 'class' => 'form-control input-sm', 'type' => 'text', 'placeholder' => '', 'maxlength' => "255")); ?>
                        </div>
                    </div>
                </div>
                <div class="row" >
                    <div class="form-group">  
                        <label class="col-sm-3 control-label"><?php echo 'Document Registration Date : '; ?><!--<span style="color: #ff0000">*</span>--></label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('doc_reg_date', array('label' => false, 'id' => 'doc_reg_date', 'class' => 'form-control input-sm', 'data-date-format' => "mm/dd/yyyy", 'type' => 'text')); ?>
                        </div>
                    </div>
                </div>
                <div class="row" >
                    <div class="form-group">  
                        <label class="col-sm-3 control-label"><?php echo 'Type of Applicant/ Party : '; ?><!--<span style="color: #ff0000">*</span>--></label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('party_type_id', array('label' => false, 'id' => 'party_type_id', 'class' => 'form-control input-sm', 'empty' => '--Select--', 'options' => array($partytype))); ?>
                        </div>
                    </div>
                </div>
                
                <div class="row" >
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo 'Name of the Applicant/ Party : '; ?></label>
                        <div class="col-sm-3">
                           <?php echo $this->Form->input('applicant_name', array('label' => false, 'id' => 'applicant_name', 'class' => 'form-control input-sm', 'type' => 'text', 'placeholder' => '', 'maxlength' => "255")); ?>
                        </div>
                    </div>
                </div>
                <div class="row" >
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo 'Name of Father/ Mother/ Spouse : '; ?></label>
                        <div class="col-sm-3">
                           <?php echo $this->Form->input('fmh_name', array('label' => false, 'id' => 'fmh_name', 'class' => 'form-control input-sm', 'type' => 'text', 'placeholder' => '', 'maxlength' => "255")); ?>
                        </div>
                    </div>
                </div>
                <div class="row" >
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo 'District : '; ?></label>
                        <div class="col-sm-3">
                           <?php echo $this->Form->input('district', array('label' => false, 'id' => 'district', 'class' => 'form-control input-sm', 'type' => 'text', 'placeholder' => '', 'maxlength' => "255")); ?>
                        </div>
                    </div>
                </div>
                <div class="row" >
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo 'Taluka : '; ?></label>
                        <div class="col-sm-3">
                           <?php echo $this->Form->input('taluka', array('label' => false, 'id' => 'taluka', 'class' => 'form-control input-sm', 'type' => 'text', 'placeholder' => '', 'maxlength' => "255")); ?>
                        </div>
                    </div>
                </div>
                <div class="row" >
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo 'Village : '; ?></label>
                        <div class="col-sm-3">
                           <?php echo $this->Form->input('village', array('label' => false, 'id' => 'village', 'class' => 'form-control input-sm', 'type' => 'text', 'placeholder' => '', 'maxlength' => "255")); ?>
                        </div>
                    </div>
                </div>
                <div class="row" >
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo 'Boundaries East : '; ?></label>
                        <div class="col-sm-3">
                           <?php echo $this->Form->input('boundries_east_en', array('label' => false, 'id' => 'boundries_east_en', 'class' => 'form-control input-sm', 'type' => 'text', 'placeholder' => '', 'maxlength' => "255")); ?>
                        </div>
                    </div>
                </div>
                <div class="row" >
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo 'Boundaries West : '; ?></label>
                        <div class="col-sm-3">
                           <?php echo $this->Form->input('boundries_west_en', array('label' => false, 'id' => 'boundries_west_en', 'class' => 'form-control input-sm', 'type' => 'text', 'placeholder' => '', 'maxlength' => "255")); ?>
                        </div>
                    </div>
                </div>
                <div class="row" >
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo 'Boundaries South : '; ?></label>
                        <div class="col-sm-3">
                           <?php echo $this->Form->input('boundries_south_en', array('label' => false, 'id' => 'boundries_south_en', 'class' => 'form-control input-sm', 'type' => 'text', 'placeholder' => '', 'maxlength' => "255")); ?>
                        </div>
                    </div>
                </div>
                <div class="row" >
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo 'Boundaries North : '; ?></label>
                        <div class="col-sm-3">
                           <?php echo $this->Form->input('boundries_north_en', array('label' => false, 'id' => 'boundries_north_en', 'class' => 'form-control input-sm', 'type' => 'text', 'placeholder' => '', 'maxlength' => "255")); ?>
                        </div>
                    </div>
                </div>
                <div class="row" >
                    <div class="form-group">
                        <div class="col-sm-6" align="center">
                           &nbsp;
                        </div>
                    </div>
                </div>
                <div class="row" >
                    <div class="form-group">
                        <div class="col-sm-6" align="center">
                            <button type="submit" id="btnCancel" name="btnCancel" class="btn btn-info"><?php echo "Search"; ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>


