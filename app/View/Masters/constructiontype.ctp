<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>

<script>
    $(document).ready(function () {

    $('#tableConstructiontype').dataTable({
    "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
    });</script>
<script>
            function formadd() {
            document.getElementById("hfaction").value = 'S';
                    document.getElementById("actiontype").value = '1';
            }
    function formupdate(
<?php
foreach ($languagelist as $langcode) {
    ?>
    <?php echo 'construction_type_desc_' . $langcode['mainlanguage']['language_code']; ?>,
<?php } ?>

    id) {
    document.getElementById("actiontype").value = '1';
<?php
foreach ($languagelist as $langcode) {
    ?>
        $('#construction_type_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>').val(construction_type_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>);
<?php } ?>
    $('#hfid').val(id);
            $('#hfupdateflag').val('Y');
            $('#btnadd').html('Save');
            return false;
    }
</script> 
<?php echo $this->Form->create('constructiontype', array('id' => 'constructiontype', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblconstuctiontye'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Construction Type/constructiontype_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <?php
                        //  creating dyanamic text boxes using same array of config language
                        foreach ($languagelist as $key => $langcode) {
                            ?>
                            <div class="col-md-3">
                                <label><?php echo __('lblconstuctiontye') . " ( " . $langcode['mainlanguage']['language_name'] . ")"; ?>
                                    <span style="color: #ff0000">*</span>
                                </label>    
                                <?php echo $this->Form->input('construction_type_desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'construction_type_desc_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '100')) ?>
                                <span id="<?php echo 'construction_type_desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error">
                                    <?php echo $errarr['construction_type_desc_' . $langcode['mainlanguage']['language_code'] . '_error']; ?>
                                </span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center">
                    <div class="form-group" >
                        <div class="col-sm-12 tdselect">
                            <button id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?>
                            </button>
                        </div>
                        <div class="col-sm-12 tdsave" hidden="true">
                            <button id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript: return formsave();">
                                <span class="glyphicon glyphicon-floppy-saved"></span>&nbsp;&nbsp;<?php echo __('btnsave'); ?>
                            </button>
                        </div>
                        <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-primary">
            <div class="box-body">
                <div id="selectConstructiontype">
                    <table id="tableConstructiontype" class="table table-striped table-bordered table-hover" >
                        <thead >  
                            <tr>  
                                <?php
//  creating dyanamic table header using same array of config language
                                foreach ($languagelist as $langcode) {
                                    ?>
                                    <th class="center"><?php echo __('lblconstuctiontye') . " ( " . $langcode['mainlanguage']['language_name'] . ")"; ?></th>
                                <?php } ?>
                                <th class="center width10"><?php echo __('lblaction'); ?></th>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php foreach ($Constructiontyperecord as $Constructiontyperecord1): ?>
                                <tr>
                                    <?php
                                    foreach ($languagelist as $langcode) {
                                        ?>
                                        <td ><?php echo $Constructiontyperecord1['constructiontype']['construction_type_desc_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                    <?php } ?>
                                    <td >
                                        <button id="btnupdate" name="btnupdate" type="button" data-toggle="tooltip" title="Edit" class="btn btn-default "   onclick="javascript: return formupdate(
                                        <?php
                                        foreach ($languagelist as $langcode) {
                                            ?>
                                                            ('<?php echo $Constructiontyperecord1['constructiontype']['construction_type_desc_' . $langcode['mainlanguage']['language_code']]; ?>'),
                                        <?php } ?>
                                                        ('<?php echo $Constructiontyperecord1['constructiontype']['id']; ?>'));">
                                            <span class="glyphicon glyphicon-pencil"></span>
                                        </button>
                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'construction_type_delete', $Constructiontyperecord1['constructiontype']['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
                                    </td>
                                <?php endforeach; ?>
                                <?php unset($Constructiontyperecord1); ?>
                        </tbody>
                    </table>
                    <?php if (!empty($Constructiontyperecord)) { ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
                </div>
            </div>
        </div>
    </div>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
            <!--<span id="actiontype_error" class="form-error"><?php //echo $errarr['actiontype_error'];   ?></span>-->
    <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
    <span id="hfaction_error" class="form-error"><?php echo $errarr['hfaction_error']; ?></span>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <span id="hfid_error" class="form-error"><?php echo $errarr['hfid_error']; ?></span>
    <input type='hidden' value='S' name='hfupdateflag' id='hfupdateflag'/>
    <span id="hfupdateflag_error" class="form-error"><?php echo $errarr['hfupdateflag_error']; ?></span>
</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>




