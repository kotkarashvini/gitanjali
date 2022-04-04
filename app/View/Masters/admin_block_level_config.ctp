<?php
echo $this->Html->script('https://www.google.com/jsapi');
?>
<script>
    $(document).ready(function () {
        $("#btnSave").click(function () {
            var action = $("#hdnAction").val();
            if (action != 'U') {
                $("#hdnAction").val('SV');
            }
            else {
                $("#hdnAction").val('U');
            }
            $('input').val(function (_, value) {
                return $.trim(value);
            });
            $("#admin_block_level_config").submit();
        });

        $("#btnCancel").click(function () {
            $("#hdnAction").val('C');
        });

        $("#btnExit").click(function () {
            $("#hdnAction").val('E');
            $("#formID").submit();
        });
        $('#tblLabel').dataTable({
            "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
    });
</script>

<?php echo $this->Form->create('admin_block_level_config', array('id' => 'admin_block_level_config')); ?>
<div class="row">
    <div class="col-lg-12">
         <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblblocklvlconhead'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/AdminBlockConfig/admin_block_level_config_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="use" class="col-sm-4 control-label"><b><?php echo __('lbllevel'); ?></b></label>
                        <?php foreach ($languagelist as $langcode) { ?>

                            <td>
                                <label class="col-sm-2"> 
                                    <?php echo __('lblname') . " ( " . $langcode['mainlanguage']['language_name'] . ")"; ?>
                                </label>
                            </td>
                        <?php } ?>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="State(Level 1)" class="control-label col-sm-2">
                            <?php echo __('lblLevel1'); ?>&nbsp;(<?php echo __('lbladmstate'); ?>)</label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input($name[16], array('type' => 'radio', 'options' => array('Y' => 'Yes'), 'value' => 'Y', 'legend' => false, 'div' => false, 'class' => 'select')); ?>
                            <span id="is_state_error" class="form-error"><?php echo $errarr['is_state_error']; ?></span>
                        </div>
                        <?php
                        foreach ($languagelist as $key => $langcode) {
                            ?>
                            <div class="col-md-2">
                                <?php echo $this->Form->input('statename_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'statename_' . $langcode['mainlanguage']['language_code'], 'type' => 'text', 'class' => 'textbox', 'class' => 'textbox', 'maxlength' => '100')) ?>
                                <span id="<?php echo 'statename_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error">
                                    <?php echo $errarr['statename_' . $langcode['mainlanguage']['language_code'] . '_error']; ?>
                                </span>
                            </div>
                        <?php } ?>
                        <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="Divion(Level 2)" class="control-label col-sm-2">
                            <?php echo __('lbldivlvl2'); ?>&nbsp;(<?php echo __('lbladmdivision'); ?>)</label>
                        <div class="col-sm-2"> <?php echo $this->Form->input($name[18], array('type' => 'radio', 'options' => array('Y' => 'Yes', 'N' => 'No'), 'legend' => false, 'div' => false, 'class' => 'select')); ?>
                            <span id="is_div_error" class="form-error"><?php echo $errarr['is_div_error']; ?></span>
                        </div>            
                        <?php
                        foreach ($languagelist as $key => $langcode) {
                            ?>
                            <div class="col-md-2">
                                <?php echo $this->Form->input('divisionname_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'divisionname_' . $langcode['mainlanguage']['language_code'], 'type' => 'text', 'class' => 'textbox', 'class' => 'textbox', 'maxlength' => '100')) ?>
                                <span id="<?php echo 'divisionname_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error">
                                    <?php echo $errarr['divisionname_' . $langcode['mainlanguage']['language_code'] . '_error']; ?>
                                </span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="District (Level 3)" class="control-label col-sm-2">
                            <?php echo __('lbldistlvl'); ?>&nbsp;(<?php echo __('lbladmdistrict'); ?>)</label>
                        <div class="col-sm-2"> <?php echo $this->Form->input($name[17], array('type' => 'radio', 'options' => array('Y' => 'Yes', 'N' => 'No'), 'legend' => false, 'div' => false, 'class' => 'select')); ?>
                            <span id="is_dist_error" class="form-error"><?php echo $errarr['is_dist_error']; ?></span>
                        </div>            
                        <?php
                        foreach ($languagelist as $key => $langcode) {
                            ?>
                            <div class="col-md-2">
                                <?php echo $this->Form->input('districtname_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'districtname_' . $langcode['mainlanguage']['language_code'], 'type' => 'text', 'class' => 'textbox', 'class' => 'textbox', 'maxlength' => '100')) ?>
                                <span id="<?php echo 'districtname_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error">
                                    <?php echo $errarr['districtname_' . $langcode['mainlanguage']['language_code'] . '_error']; ?>
                                </span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="Taluka (Level 4)" class="control-label col-sm-2">
                            <?php echo __('lblLevel4'); ?>&nbsp;(<?php echo __('lbladmtaluka'); ?>)</label>
                        <div class="col-sm-2"> <?php echo $this->Form->input($name[19], array('type' => 'radio', 'options' => array('Y' => 'Yes', 'N' => 'No'), 'legend' => false, 'div' => false, 'class' => 'select')); ?>

                            <span id="is_taluka_error" class="form-error"><?php echo $errarr['is_taluka_error']; ?></span></div>            
                        <?php
                        foreach ($languagelist as $key => $langcode) {
                            ?>
                            <div class="col-md-2">
                                <?php echo $this->Form->input('talukaname_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'talukaname_' . $langcode['mainlanguage']['language_code'], 'type' => 'text', 'class' => 'textbox', 'class' => 'textbox', 'maxlength' => '100')) ?>
                                <span id="<?php echo 'talukaname_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error">
                                    <?php echo $errarr['talukaname_' . $langcode['mainlanguage']['language_code'] . '_error']; ?>
                                </span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="ZP (Level 5)" class="control-label col-sm-2">
                            <?php echo __('lblzplvl'); ?>&nbsp;(<?php echo __('lbladmzp'); ?>)</label>
                        <div class="col-sm-2"> <?php echo $this->Form->input($name[20], array('type' => 'radio', 'options' => array('Y' => 'Yes', 'N' => 'No'), 'legend' => false, 'div' => false, 'class' => 'select')); ?>
                            <span id="is_zp_error" class="form-error"><?php echo $errarr['is_zp_error']; ?></span>
                        </div>            
                        <?php
                        foreach ($languagelist as $key => $langcode) {
                            ?>
                            <div class="col-md-2">
                                <?php echo $this->Form->input('zpname_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'zpname_' . $langcode['mainlanguage']['language_code'], 'type' => 'text', 'class' => 'textbox', 'class' => 'textbox', 'maxlength' => '100')) ?>
                                <span id="<?php echo 'zpname_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error">
                                    <?php echo $errarr['zpname_' . $langcode['mainlanguage']['language_code'] . '_error']; ?>
                                </span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="Block (Level 6)" class="control-label col-sm-2">
                            <?php echo __('lblblocklvl'); ?>&nbsp;(<?php echo __('lbladmblock'); ?>)</label>
                        <div class="col-sm-2"> <?php echo $this->Form->input($name[21], array('type' => 'radio', 'options' => array('Y' => 'Yes', 'N' => 'No'), 'legend' => false, 'div' => false, 'class' => 'select')); ?>
                            <span id="is_block_error" class="form-error"><?php echo $errarr['is_block_error']; ?></span>
                        </div>            
                        <?php foreach ($languagelist as $key => $langcode) {
                            ?>
                            <div class="col-md-2">
                                <?php echo $this->Form->input('blockname_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'blockname_' . $langcode['mainlanguage']['language_code'], 'type' => 'text', 'class' => 'textbox', 'class' => 'textbox', 'maxlength' => '100')) ?>
                                <span id="<?php echo 'blockname_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error">
                                    <?php echo $errarr['blockname_' . $langcode['mainlanguage']['language_code'] . '_error']; ?>
                                </span>
                            </div> 
                        <?php } ?>
                    </div>
                </div>
                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center">
                    <div class="form-group">
                        <input type="button" value="<?php echo __('btnsave'); ?>" id="btnSave"  class="btn btn-info">
                        <input type="reset" value="<?php echo __('btncancel'); ?>" id="btnCancel" class="btn btn-info">
                                 
                        <input type="hidden" id="hdnAction" name="frmaction" value="<?php echo $actontype; ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->form->end(); ?>

