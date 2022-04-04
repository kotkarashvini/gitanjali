<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>

<script>
    $(document).ready(function () {
    if ($('#hfhidden1').val() == 'Y')
    {
    $('#tabledepreciation').dataTable({
    "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
    } else {
    $('#tabledepreciation').dataTable({
    "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
    }
    var actiontype = document.getElementById('actiontype').value;
            if (actiontype == '2') {
    $('.tdsave').show();
            $('.tdadd').hide();
            $('#deprication_type_desc_en').focus();
    }
    });
            function formadd() {

            document.getElementById("actiontype").value = '1';
            }
    function formupdate(<?php
foreach ($languagelist as $langcode) {
    // This language list consist of code and name of language and we just concate it with construction_type_desc which is field name from database.Means construction_type_desc_en,or ll,or ll1,or ll2,or ll3..
    ?>
    <?php echo 'deprication_type_desc_' . $langcode['mainlanguage']['language_code']; ?>,
<?php } ?> id) {
    document.getElementById("actiontype").value = '2';
            //dyanamic function creation for Assigning value to text boxes in update function  according to language code   
<?php
foreach ($languagelist as $langcode) {
    // this again assigns value to the text boxes with concatination of languagelist array and construction_type_desc field from database
    ?>
        $('#deprication_type_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>').val(deprication_type_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>);
<?php } ?>
    //  $('#deprication_type_desc_en').val(deprication_type_desc_en);
    $('#hfid').val(id);
            $('#hfupdateflag').val('Y');
            $('#btnedit').html('Update');
            $('.tdadd').hide();
            $('.tdsave').show();
            return false;
    }
    function formsave() {
    var deprication_type_desc_en = $('#deprication_type_desc_en').val();
            document.getElementById("actiontype").value = '3';
    }

</script> 
<?php echo $this->Form->create('depreciation', array('id' => 'depreciation', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lbldepreciation'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Depreciation Type/depreciation_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <?php
                        //creating dyanamic text boxes using same array of config language
                        foreach ($languagelist as $key => $langcode) {
                            ?>
                            <div class="col-md-3">
                                <label><?php echo __('lbldepreciation') . "  (" . $langcode['mainlanguage']['language_name'] . ")"; ?><span style="color: #ff0000">*</span></label>    
                                <?php echo $this->Form->input('deprication_type_desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'deprication_type_desc_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '20')) ?>
                                <span id="<?php echo 'deprication_type_desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error"><?php echo $errarr['deprication_type_desc_' . $langcode['mainlanguage']['language_code'] . '_error']; ?></span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div  class="rowht"></div>
                <div class="row center">
                    <div class="form-group">
                        <div class="col-sm-12 tdadd">
                            <button id="btnadd" name="btnadd" class="btn btn-info "  
                                    onclick="javascript: return formadd();">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?></button>
                        </div>
                        <div class="col-sm-12 tdsave" hidden="true">
                            <button id="btnadd" name="btnadd" class="btn btn-info "  
                                    onclick="javascript: return formsave();">
                                <span class="glyphicon glyphicon-floppy-saved"></span>&nbsp;&nbsp;<?php echo __('btnsave'); ?></button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="box box-primary">

            <div class="box-body">
                <div id="selectdepreciation">
                    <table class="table table-striped table-bordered table-hover" id="tabledepreciation">
                        <thead >  
                            <tr>  
<!--                                <td ><b><?php echo __('lbldepredesc'); ?></b></td>-->
                                <?php
//  creating dyanamic table header using same array of config language
                                foreach ($languagelist as $langcode) {
                                    // pr($langcode);
                                    ?>
                                    <th class="center"><?php echo __('lbldepreciation') . " (" . $langcode['mainlanguage']['language_name'] . ")"; ?></th>
                                <?php } ?>
                                <th class="center width10"><?php echo __('lblaction'); ?></th>
                            </tr>  
                        </thead>
                        <tbody>
                            <tr>
                                <?php foreach ($depreciationrecord as $depreciationrecord1): ?>
                                                <!--                                    <td ><?php echo $depreciationrecord1['depreciation']['deprication_type_desc_' . $this->Session->read("sess_langauge")]; ?></td>-->
                                    <?php
                                    //  creating dyanamic table data(coloumns) using same array of config language
                                    foreach ($languagelist as $langcode) {
                                        // pr($langcode);
                                        ?>
                                        <td ><?php echo $depreciationrecord1['depreciation']['deprication_type_desc_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                    <?php } ?>
                                    <td >
                                        <button id="btnupdate" name="btnupdate" type="button" class="btn btn-default " data-toggle="tooltip" title="Edit"  onclick="javascript: return formupdate(
                                        <?php
                                        //  creating dyanamic parameters  using same array of config language for sending to update function
                                        foreach ($languagelist as $langcode) {
                                            // pr($langcode);
                                            ?>
                                                            ('<?php echo $depreciationrecord1['depreciation']['deprication_type_desc_' . $langcode['mainlanguage']['language_code']]; ?>'),
                                        <?php } ?>
                                                        ('<?php echo $depreciationrecord1['depreciation']['id']; ?>'));">
                                            <span class="glyphicon glyphicon-pencil"><?php //echo $this->Html->image('edit.png');                          ?></span><?php //echo __('lblUpdate_' . $this->Session->read("sess_langauge"));                          ?>
                                        </button>

                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'deprication_type_delete', $depreciationrecord1['depreciation']['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
                                    </td>
                                </tr>
                            <?php endforeach;
                            ?>
                            <?php unset($depreciationrecord1); ?>
                        </tbody>
                    </table>
                    <?php
                    if (!empty($depreciationrecord)) {
                        ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
                </div>
            </div>
        </div>


    </div>
    <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
</div>

<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>




