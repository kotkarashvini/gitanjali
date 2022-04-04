<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>

<script>
    $(document).ready(function () {
        var hfupdateflag = "<?php echo $hfupdateflag; ?>";
        if (hfupdateflag === 'Y')
        {
            $('#btnadd').html('Save');
        }
        if ($('#hfhidden1').val() === 'Y')
        {
            $('#tablebehavioural').dataTable({
                "iDisplayLength": 5,
                "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
            });
        } else {
            $('#tablebehavioural').dataTable({
                "iDisplayLength": 5,
                "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
            });
        }
        var actiontype = document.getElementById('actiontype').value;
        if (actiontype == '2') {
            $('.tdsave').show();
            $('.tdselect').hide();
            $('#pattern_desc_en').focus();
        }
    });
</script>

<script>
    function formadd() {

        document.getElementById("hfaction").value = 'S';
        document.getElementById("actiontype").value = '1';
    }

    //dyanamic function creation for collecting parameters in update function     
    function formupdate(behavioral_id, behavioral_details_id,
<?php
foreach ($languagelist as $langcode) {
    ?>
    <?php echo 'pattern_desc_' . $langcode['mainlanguage']['language_code']; ?>,
<?php } ?>
    id) {
        document.getElementById("actiontype").value = '1';
<?php
foreach ($languagelist as $langcode) {
    ?>
            $('#pattern_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>').val(pattern_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>);
<?php } ?>
        $('#hfid').val(id);
        $('#behavioral_id').val(behavioral_id);
        $('#behavioral_details_id').val(behavioral_details_id);
        $('#hfupdateflag').val('Y');
        $('#btnadd').html('Save');
                return false;
    }
</script> 
<?php echo $this->Form->create('behavioralpattens', array('id' => 'behavioralpattens', 'autocomplete' => 'off')); ?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
<div class="row">
    <div class="col-lg-12">
        <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblbehaviouralpatterns'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/BehaviouralPatterns/behavioralpattens_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label"><?php echo __('lblselectbehavior'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('behavioral_id', array('options' => array($Behaviourallist), 'empty' => '--select--', 'id' => 'behavioral_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                            <span id="behavioral_id_error" class="form-error"><?php echo $errarr['behavioral_id_error']; ?></span>
                        </div>
                        <label for="" class="col-sm-3 control-label"><?php echo __('lblselectbahaviordetails'); ?> <span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('behavioral_details_id', array('options' => array($Behaviouraldetaillist), 'empty' => '--select--', 'id' => 'behavioral_details_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                            <span id="behavioral_details_id_error" class="form-error"><?php echo $errarr['behavioral_details_id_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <?php
                        foreach ($languagelist as $key => $langcode) {
                            ?>
                            <div class="col-md-3">
                                <label><?php echo __('lblbehaviouralpatterns') . "  " . $langcode['mainlanguage']['language_name']; ?><span style="color: #ff0000">*</span></label>    
                                <?php echo $this->Form->input('pattern_desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'pattern_desc_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '200')) ?>
                                <span id="<?php echo 'pattern_desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error"><?php echo $errarr['pattern_desc_' . $langcode['mainlanguage']['language_code'] . '_error']; ?></span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center" >
                    <div class="form-group">
                        <button id="btnadd" name="btnadd" class="btn btn-info "  onclick="javascript: return formadd();">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?>
                        </button>
                    </div>
                </div>

            </div>
        </div>
        <div class="box box-primary">
            <div class="box-body">
                <div id="selectbehavioural">
                    <table id="tablebehavioural" class="table table-striped table-bordered table-hover" >
                        <thead >  
                            <tr>  
                                <th class="center"><?php echo __('lblbehaviour'); ?></th>
                                <th class="center"><?php echo __('lblbehaviordetails'); ?></th>
                                <?php
                                foreach ($languagelist as $langcode) {
                                    ?>
                                    <th class="center"><?php echo __('lblbehaviouralpatterns') . " (" . $langcode['mainlanguage']['language_name'] . ")"; ?></th>
                                <?php } ?>
                                <th class="center width10"><?php echo __('lblaction'); ?></th>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php foreach ($behaviouralpattenrecord as $behaviouralpattenrecord1): ?>
                                <tr>
                                    <td ><?php echo $behaviouralpattenrecord1['0']['behavioral_desc_en']; ?></td>
                                    <td ><?php echo $behaviouralpattenrecord1['0']['behavioral_details_desc_en']; ?></td>
                                    <?php
                                    foreach ($languagelist as $langcode) {
                                        ?>
                                        <td ><?php echo $behaviouralpattenrecord1['0']['pattern_desc_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                    <?php } ?>
                                    <td >
                                        <button id="btnupdate" name="btnupdate" type="button" data-toggle="tooltip" title="Edit"  class="btn btn-default "   onclick="javascript: return formupdate(('<?php echo $behaviouralpattenrecord1['0']['behavioral_id']; ?>'), ('<?php echo $behaviouralpattenrecord1['0']['behavioral_details_id']; ?>'),
                                        <?php
                                        foreach ($languagelist as $langcode) {
                                            ?>
                                                        ('<?php echo $behaviouralpattenrecord1['0']['pattern_desc_' . $langcode['mainlanguage']['language_code']]; ?>'),
                                        <?php } ?>
                                                    ('<?php echo $behaviouralpattenrecord1['0']['id']; ?>'));">
                                            <span class="glyphicon glyphicon-pencil"></span>
                                        </button>
                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'delete_behavioural_pattens', $behaviouralpattenrecord1['0']['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
                                    </td>
                                <?php endforeach; ?>
                                <?php unset($behaviouralpattenrecord1); ?>
                        </tbody>
                    </table>
                    <?php if (!empty($behaviouralrecord)) { ?>
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
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>




