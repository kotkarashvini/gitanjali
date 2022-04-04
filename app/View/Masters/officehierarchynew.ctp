<?php $doc_lang = $this->Session->read('doc_lang'); ?> 
<script type="text/javascript">
    $(document).ready(function () {
        if (document.getElementById('hfhidden1').value == 'Y') {
            $('#divratefactor').slideDown(1000);
        }
        else {
            $('#divratefactor').hide();
        }
        $('#tableratefactor').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
    });
    function formadd() {
        document.getElementById("actiontype").value = '1';
    }
    function forcancel() {
        document.getElementById("actiontype").value = '2';
    }
    function formupdate(<?php
foreach ($languagelist as $langcode) {
    // This language list consist of code and name of language and we just concate it with construction_type_desc which is field name from database.Means construction_type_desc_en,or ll,or ll1,or ll2,or ll3..
    ?>
    <?php echo 'hierarchy_desc_' . $langcode['mainlanguage']['language_code']; ?>,
<?php } ?>id, repid) {

<?php
foreach ($languagelist as $langcode) {
    // this again assigns value to the text boxes with concatination of languagelist array and construction_type_desc field from database
    ?>
            $('#hierarchy_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>').val(hierarchy_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>);
<?php } ?>
//        $('#hierarchy_desc_en').val(hrdec);
        $('#hfid').val(id);
        $('#hierarchy_report_id').val(repid);
        $('#hfupdateflag').val('Y');
        $('#btnadd').html('Save');
        return false;
    }
</script>
<?php echo $this->Form->create('officehierarchynew', array('id' => 'officehierarchynew', 'class' => 'form-vertical')); ?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
<div class="row">
    <div class="col-lg-12">
        <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblofficehierarchy'); ?></h3>
                    <div class="box-tools pull-right">
                        <a  href="<?php echo $this->webroot; ?>helpfiles/Masters/officehierarchynew_<?php echo $doc_lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                    </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <?php
                        foreach ($languagelist as $key => $langcode) {
                            ?>
                            <div class="col-md-3">
                                <label><?php echo __('lblofficename') . " ( " . $langcode['mainlanguage']['language_name'] . ")"; ?>
                                    <span style="color: #ff0000">*</span>
                                </label>  
                            </div>
                        <div class="col-md-3">
                                <?php echo $this->Form->input('hierarchy_desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'hierarchy_desc_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '100')) ?>
                                <span id="<?php echo 'hierarchy_desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error">
                                    <?php echo $errarr['hierarchy_desc_' . $langcode['mainlanguage']['language_code'] . '_error']; ?>
                                </span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div  class="rowht"></div> 
                <div class="row">
                    <div class="form-group">
                        <label for="hierarchy_report_id " class="col-sm-3 control-label"><?php echo __('lblreportingoffice'); ?> <span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('hierarchy_report_id', array('label' => false, 'id' => 'hierarchy_report_id', 'class' => 'form-control input-sm', 'options' => array($officname), 'empty' => '--Select--')); ?>
                            <span id="hierarchy_report_id_error" class="form-error"><?php echo $errarr['hierarchy_report_id_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div> <div  class="rowht"></div> <div  class="rowht"></div> 
                <div class="row center">
                    <div class="form-group">
                        <button id="btnadd" name="btnadd" class="btn btn-info "  onclick="javascript: return formadd();">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp; <?php echo __('btnsave'); ?></button> 
                        <button id="btnadd" name="btncancel" class="btn btn-info "  onclick="javascript: return forcancel();">
                            <span class="glyphicon glyphicon-floppy-remove"></span>&nbsp;&nbsp; <?php echo __('btncancel'); ?></button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="box box-primary">
            <div class="box-body" id="divratefactor">
                
                    <table id="tableratefactor" class="table table-striped table-bordered table-hover">  
                        <thead >  
                            <tr>  
                                <?php
                                foreach ($languagelist as $langcode) {
                                    ?>
                                    <th class="center"><?php echo __('lblofficename') . " ( " . $langcode['mainlanguage']['language_name'] . ")"; ?></th>
                                <?php } ?>
                        <!--<td style="text-align: center; font-weight:bold;"><?php echo __('lblreportingoffice'); ?></td>-->
                                <th class="center width10"><?php echo __('lblaction'); ?></th>
                            </tr>  
                        </thead>
                        <?php for ($i = 0; $i < count($officehrcy); $i++) { ?>
                            <tr>
                                <?php
                                foreach ($languagelist as $langcode) {
                                    ?>
                                    <td ><?php echo $officehrcy[$i][0]['hierarchy_desc_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                <?php } ?>
                <!--<td ><?php echo $officehrcy[$i][0]['hierarchy_report_id']; ?></td>-->
                                <td >
                                    <button id="btnupdate" name="btnupdate" type="button" data-toggle="tooltip" title="Edit" class="btn btn-default "  onclick="javascript: return formupdate(
                                    <?php
                                    foreach ($languagelist as $langcode) {
                                        ?>
                                                    ('<?php echo $officehrcy[$i][0]['hierarchy_desc_' . $langcode['mainlanguage']['language_code']]; ?>'),
                                    <?php } ?>
                                                ('<?php echo $officehrcy[$i][0]['id']; ?>'),
                                                        ('<?php echo $officehrcy[$i][0]['hierarchy_report_id']; ?>'));">
                                        <span class="glyphicon glyphicon-pencil"></span></button>
                                    <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'officehierarchynew_delete', $officehrcy[$i][0]['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
                                </td>
                            </tr>
                        <?php } ?>
                    </table> 
                    <?php if (!empty($officehrcy)) { ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
               
            </div>
        </div>
        <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
        <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
        <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
    </div>
</div>