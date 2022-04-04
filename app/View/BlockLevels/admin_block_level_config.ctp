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

            var r = confirm("Are you sure to save settings");
            if (r == true) {
                $("#admin_block_level_config").submit();
            } else {
                alert();
            }




        });

 $("#is_divN").click(function () {
    $(".divisionentry").hide(); 
 });
 $("#is_divY").click(function () {
    $(".divisionentry").show(); 
 });


 $("#is_subdivN").click(function () {
    $(".Subdivisionentry").hide(); 
 });
 $("#is_subdivY").click(function () {
    $(".Subdivisionentry").show(); 
 });
 
 
  $("#is_circleN").click(function () {
    $(".circleentry").hide(); 
 });
 $("#is_circleY").click(function () {
    $(".circleentry").show(); 
 });
 
 <?php 
  $data = @$this->request->data['admin_block_level_config'];
  ?>
    <?php if (@$data['is_div'] == 'N') { ?>
                                $(".divisionentry").hide(); 
    <?php } ?>
        
         <?php if (@$data['is_subdiv'] == 'N') { ?>
                                $(".Subdivisionentry").hide(); 
    <?php } ?>
        
         <?php if (@$data['is_circle'] == 'N') { ?>
                                $(".circleentry").hide(); 
    <?php } ?>
        
 
 

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

<?php
//pr($currentstate);
echo $this->Form->create('admin_block_level_config', array('id' => 'admin_block_level_config'));
?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="note">
             <?php echo __('lblnote'); ?>  <span style="color: #ff0000">*</span> <?php echo __('lblstarmandatorynote'); ?>
         </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo @$currentstate['currentstate']['state_name'] . " - "; ?><?php echo __('lblblocklvlconhead'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/AdminBlockConfig/admin_block_level_config_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="use" class="col-sm-4 form-label"><b><?php echo __('lbllevels'); ?></b></label>
                        <?php foreach ($languagelist as $langcode) { ?>

                            <td>
                                <label class="col-sm-2"> 
                                    <?php echo __('lblLabel') . " ( " . $langcode['mainlanguage']['language_name'] . ")"; ?>
                                </label>
                            </td>
                        <?php } ?>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="State(Level 1)" class="form-label col-sm-2">
                            <?php echo __('lblLevel1'); ?>&nbsp;(<?php echo __('lbladmstate'); ?>)<span style="color: #ff0000">*</span></label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('is_state', array('type' => 'radio', 'div' => false, 'id' => 'is_state', 'options' => array('Y' => 'Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'), 'value' => 'Y', 'legend' => false, 'class' => '')); ?>
                            <span id="is_state_error" class="form-error"></span>
                        </div>
                        <?php
                        foreach ($languagelist as $key => $langcode) {
                            ?>
                            <div class="col-md-2">
                                
                                <?php 
                                $readonly='';
                                if($langcode['mainlanguage']['language_code']=='en'){
                                    $readonly='readonly';
                                }
                                
                                echo $this->Form->input('statename_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'statename_' . $langcode['mainlanguage']['language_code'], 'type' => 'text', 'class' => 'form-control', 'class' => 'form-control', 'maxlength' => '100','readonly'=>$readonly)) ?>
                                <span id="<?php echo 'statename_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error">

                                </span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="Divion(Level 2)" class="form-label col-sm-2">
                            <?php echo __('lbldivlvl2'); ?>&nbsp;(<?php echo __('lbladmdivision'); ?>)</label>
                        <div class="col-sm-2"> <?php echo $this->Form->input('is_div', array('type' => 'radio', 'div' => false, 'id' => 'is_div', 'options' => array('Y' => 'Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => 'No'), 'legend' => false, 'class' => '')); ?>
                            <span id="is_div_error" class="form-error"></span>
                        </div>  
                        
                        
                        <?php
                        foreach ($languagelist as $key => $langcode) {
                            ?>
                            <div class="col-md-2 divisionentry">
                                <?php echo $this->Form->input('divisionname_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'divisionname_' . $langcode['mainlanguage']['language_code'], 'type' => 'text', 'class' => 'form-control divisionentry', 'maxlength' => '100')) ?>
                                <span id="<?php echo 'divisionname_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error divisionentry">

                                </span>
                            </div>
                        <?php } ?>
                        
                        
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="District (Level 3)" class="form-label col-sm-2">
                            <?php echo __('lbldistlvl'); ?>&nbsp;(<?php echo __('lbladmdistrict'); ?>)<span style="color: #ff0000">*</span></label>
                        <div class="col-sm-2"> <?php echo $this->Form->input('is_dist', array('type' => 'radio', 'div' => false, 'id' => 'is_dist', 'options' => array('Y' => 'Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'), 'value' => 'Y', 'legend' => false, 'class' => '')); ?>
                            <span id="is_dist_error" class="form-error"></span>
                        </div>            
                        <?php
                        foreach ($languagelist as $key => $langcode) {
                          $readonly='';
                                if($langcode['mainlanguage']['language_code']=='en'){
                                    $readonly='readonly';
                                }
                            ?>
                            <div class="col-md-2">
                                <?php echo $this->Form->input('districtname_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'districtname_' . $langcode['mainlanguage']['language_code'], 'type' => 'text', 'class' => 'form-control',  'maxlength' => '100','readonly'=>$readonly)) ?>
                                <span id="<?php echo 'districtname_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error">

                                </span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="Sub Division (Level 4)" class="form-label col-sm-2">
                            <?php echo __('lblLevel4'); ?>&nbsp;(<?php echo __('lbladmsubdiv'); ?>)</label>
                        <div class="col-sm-2"> <?php echo $this->Form->input('is_subdiv', array('type' => 'radio', 'div' => false, 'id' => 'is_subdiv', 'options' => array('Y' => 'Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => 'No'), 'legend' => false, 'class' => '')); ?>

                            <span id="is_subdiv_error" class="form-error"></span></div>            
                        <?php
                        foreach ($languagelist as $key => $langcode) {
                            ?>
                            <div class="col-md-2 Subdivisionentry">
                                <?php echo $this->Form->input('subdivname_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'subdivname_' . $langcode['mainlanguage']['language_code'], 'type' => 'text', 'class' => 'form-control Subdivisionentry',  'maxlength' => '100')) ?>
                                <span id="<?php echo 'subdivname_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error Subdivisionentry">                                     
                                </span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="Taluka (Level 5)" class="form-label col-sm-2">
                            <?php echo __('lblLevel5'); ?>&nbsp;(<?php echo __('lbladmtaluka'); ?>)<span style="color: #ff0000">*</span></label>
                        <div class="col-sm-2"> <?php echo $this->Form->input('is_taluka', array('type' => 'radio', 'div' => false, 'id' => 'is_taluka', 'options' => array('Y' => 'Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'), 'value' => 'Y', 'legend' => false, 'class' => '')); ?>
                            <span id="is_taluka_error" class="form-error"></span>
                        </div>            
                        <?php
                        foreach ($languagelist as $key => $langcode) {
                           $readonly='';
                                if($langcode['mainlanguage']['language_code']=='en'){
                                    $readonly='readonly';
                                }
                            ?>
                            <div class="col-md-2">
                                <?php echo $this->Form->input('talukaname_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'talukaname_' . $langcode['mainlanguage']['language_code'], 'type' => 'text', 'class' => 'form-control', 'maxlength' => '100','readonly'=>$readonly)) ?>
                                <span id="<?php echo 'talukaname_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error">

                                </span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="Circle (Level 6)" class="form-label col-sm-2"> 
                            <?php echo __('lblLevel6'); ?>&nbsp;(<?php echo __('lbladmcircle'); ?>)</label>  
                        <div class="col-sm-2"> 
                            <?php echo $this->Form->input('is_circle', array('type' => 'radio', 'div' => false, 'id' => 'is_circle', 'options' => array('Y' => 'Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => 'No'), 'legend' => false, 'class' => '')); ?>
                            <span id="is_circle_error" class="form-error"></span>
                        </div>            
                        <?php foreach ($languagelist as $key => $langcode) {
                            ?>
                            <div class="col-md-2 circleentry">
                                <?php echo $this->Form->input('circlename_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'circlename_' . $langcode['mainlanguage']['language_code'], 'type' => 'text',  'class' => 'form-control circleentry', 'maxlength' => '100')) ?>
                                <span id="<?php echo 'circlename_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error circleentry">

                                </span>
                            </div> 
                        <?php } ?>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="Village (Level 7)" class="form-label col-sm-2">
                            <?php echo __('lblblocklv7'); ?>&nbsp;(<?php echo __('lbladmvillage'); ?>)<span style="color: #ff0000">*</span></label>
                        <div class="col-sm-2"> <?php echo $this->Form->input('is_village', array('type' => 'radio', 'div' => false, 'id' => 'is_village', 'options' => array('Y' => 'Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'), 'value' => 'Y', 'legend' => false, 'class' => '')); ?>
                            <span id="is_village_error" class="form-error"></span>
                        </div>            
                        <?php foreach ($languagelist as $key => $langcode) {
                          $readonly='';
                                if($langcode['mainlanguage']['language_code']=='en'){
                                    $readonly='readonly';
                                }
                            ?>
                            <div class="col-md-2">
                                <?php echo $this->Form->input('villagename_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'villagename_' . $langcode['mainlanguage']['language_code'], 'type' => 'text', 'class' => 'form-control',  'maxlength' => '100','readonly'=>$readonly)) ?>
                                <span id="<?php echo 'villagename_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error">                                     
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


<div class="row">
    <div class="col-md-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo @$currentstate['currentstate']['state_name'] . " - "; ?><?php echo __('lblblocklvlconheadsetup'); ?></h3></center>

            </div>
            <div class="box-body">
              <?php echo $this->element("BlockLevel/main_menu"); ?>
            </div>
        </div>
    </div>
</div> 



<?php echo $this->form->end(); ?>

