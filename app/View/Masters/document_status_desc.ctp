<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>
<?php
echo $this->Html->script('dataTables.bootstrap');
echo $this->Html->script('jquery.dataTables');
?>
<script>
    $(document).ready(function () {
    var hfupdateflag = "<?php echo $hfupdateflag; ?>";
            if (hfupdateflag === 'Y')
    {
    $('#btnadd').html('Save');
    }
    if ($('#hfhidden1').val() === 'Y')
    {
    $('#tabledocument_status_description').dataTable({
    "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
    } else {
    $('#tabledocument_status_description').dataTable({
    "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
    }
    var actiontype = document.getElementById('actiontype').value;
            if (actiontype == '2') {
    $('.tdsave').show();
            $('.tdselect').hide();
            $('#document_status_desc_en').focus();
    }
    });</script>

<script>
            function formadd() {
            document.getElementById("hfaction").value = 'S';
                    document.getElementById("actiontype").value = '1';
            }

    //dyanamic function creation for collecting parameters in update function     
    function formupdate(
<?php
foreach ($languagelist as $langcode) {
    // pr($langcode);
    ?>
    <?php echo 'document_status_desc_' . $langcode['mainlanguage']['language_code']; ?>,
<?php } ?>

    id) {
    document.getElementById("actiontype").value = '1';
//dyanamic function creation for Assigning value to text boxes in update function  according to language code   
<?php
foreach ($languagelist as $langcode) {
    // pr($langcode);
    ?>
        $('#document_status_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>').val(document_status_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>);
<?php } ?>
    $('#hfid').val(id);
            $('#hfupdateflag').val('Y');
            $('#btnadd').html('Save');
            return false;
    }
//    function formdelete(id) {
//    document.getElementById("actiontype").value = '3';
//            document.getElementById("hfid").value = id;
//    }
//    function formdelete(id) {
//    var result = confirm("Are you sure you want to delete this record?");
//            if (result) {
//    //  alert('okkk');
//    document.getElementById("actiontype").value = '3';
//            $('#hfid').val(id);
//    } else {
//    alert();
//            return false;
//    }
//    }

</script> 

<?php echo $this->Form->create('document_status_description', array('id' => 'document_status_description', 'autocomplete' => 'off')); ?>

<div class="row">
    <div class="col-lg-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lbldocstatusdesc'); ?></h3></center>

                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/admin/document_status_desc_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <?php
                        //creating dyanamic text boxes using same array of config language
                        foreach ($languagelist as $key => $langcode) {
                            ?>
                            <div class="col-md-4">
                                <label><?php echo __('lblpropertyattributedescription') . "  " . $langcode['mainlanguage']['language_name']; ?><span style="color: #ff0000">*</span></label>    
                                <?php echo $this->Form->input('document_status_desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'document_status_desc_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => "255")) ?>
                                <span id="<?php echo 'document_status_desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error"><?php echo $errarr['document_status_desc_' . $langcode['mainlanguage']['language_code'] . '_error']; ?></span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                  <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center">
                    <div class="form-group" >
                        <div class="col-sm-12 tdselect">
                            <button id="btnadd"type="submit" name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?>
                            </button>
                        </div>

                    </div>
                </div>

            </div>
        </div>
        <div class="box box-primary">

            <div class="box-body">
                <div id="selectdocument_status_description">
                    <table id="tabledocument_status_description" class="table table-striped table-bordered table-hover" >
                        <thead >  
                            <tr>  
                                <!--<td style="text-align: center; width: 10%;"><?php echo __('lbladmstate'); ?></td>-->
                                <?php
//  creating dyanamic table header using same array of config language
                                foreach ($languagelist as $langcode) {
                                    // pr($langcode);
                                    ?>
                                    <th class="center"><?php echo __('lblpropertyattributedescription') . " (" . $langcode['mainlanguage']['language_name'] . ")"; ?></th>
                                <?php } ?>


                                <th class="center width10"><?php echo __('lblaction'); ?></th>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php foreach ($document_status_descriptionrecord as $document_status_descriptionrecord1): ?>
                                <tr>
                                    <!--<td style="text-align: center"><?php echo $state; ?></td>-->
                                    <?php
                                    //  creating dyanamic table data(coloumns) using same array of config language
                                    foreach ($languagelist as $langcode) {
                                        // pr($langcode);
                                        ?>
                                        <td ><?php echo $document_status_descriptionrecord1['document_status_description']['document_status_desc_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                    <?php } ?>

                                    <td >
                                        <button id="btnupdate" name="btnupdate" type="button" data-toggle="tooltip" title="Edit" class="btn btn-default "   onclick="javascript: return formupdate(
                                        <?php
                                        //  creating dyanamic parameters  using same array of config language for sending to update function
                                        foreach ($languagelist as $langcode) {
                                            // pr($langcode);
                                            ?>
                                                        ('<?php echo $document_status_descriptionrecord1['document_status_description']['document_status_desc_' . $langcode['mainlanguage']['language_code']]; ?>'),
                                        <?php } ?>
                                                    ('<?php echo $document_status_descriptionrecord1['document_status_description']['id']; ?>'));">
                                            <span class="glyphicon glyphicon-pencil"></span>
                                        </button>
                                        <!--                                        <button id="btndelete" name="btndelete" type="button" class="btn btn-default "     onclick="javascript: return formdelete(
                                                                                                        ('<?php echo $document_status_descriptionrecord1['document_status_description']['id']; ?>'));">
                                                                                    <span class="glyphicon glyphicon-remove"></span>
                                                                                </button>-->
                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'delete_document_status_desc', $document_status_descriptionrecord1['document_status_description']['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
                                    </td>

                                <?php endforeach; ?>
                                <?php unset($document_status_descriptionrecord1); ?>
                        </tbody>
                    </table>
                    <?php if (!empty($document_status_descriptionrecord)) { ?>
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




