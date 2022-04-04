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
    $('#tabledocument').dataTable({
    "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
    } else {
    $('#tabledocument').dataTable({
    "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
    }
    var actiontype = document.getElementById('actiontype').value;
            if (actiontype == '2') {
    $('.tdsave').show();
            $('.tdselect').hide();
            $('#document_desc_en').focus();
    }
    });</script>

<script>
            function formadd() {
            document.getElementById("hfaction").value = 'S';
                    document.getElementById("actiontype").value = '1';
            }

    //dyanamic function creation for collecting parameters in update function     
    function formupdate(article_id,
<?php
foreach ($languagelist as $langcode) {
    ?>
    <?php echo 'document_desc_' . $langcode['mainlanguage']['language_code']; ?>,
<?php } ?>

    id) {
    document.getElementById("actiontype").value = '1';
//dyanamic function creation for Assigning value to text boxes in update function  according to language code   
<?php
foreach ($languagelist as $langcode) {
    // pr($langcode);
    ?>
        $('#document_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>').val(document_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>);
<?php } ?>
    $('#hfid').val(id);
            $('#article_id').val(article_id);
            $('#hfupdateflag').val('Y');
            $('#btnadd').html('Save');
            return false;
    }


</script> 

<?php echo $this->Form->create('document', array('id' => 'document', 'autocomplete' => 'off')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lbldocumenttitle'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/admin/document_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label"><?php echo __('lblarticlename'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('article_id', array('options' => array($articlelist), 'empty' => '--select--', 'id' => 'article_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                            <span id="article_id_error" class="form-error"><?php echo $errarr['article_id_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <?php
                        //creating dyanamic text boxes using same array of config language
                        foreach ($languagelist as $key => $langcode) {
                            ?>
                            <div class="col-md-3">
                                <label><?php echo __('lbldocdtls') . "  " . $langcode['mainlanguage']['language_name']; ?>
                                    <span style="color: #ff0000">*</span>
                                </label>    
                                <?php echo $this->Form->input('document_desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'document_desc_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => "255")) ?>
                                <span id="<?php echo 'document_desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error">
                                    <?php echo $errarr['document_desc_' . $langcode['mainlanguage']['language_code'] . '_error']; ?>
                                </span>
                            </div>
                        <?php } ?>
                        <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
                    </div>
                </div>
                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center" >
                    <div class="form-group">
                        <button id="btnadd" type="submit"name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-primary">

            <div class="box-body">
                <div id="selectdocument">
                    <table id="tabledocument" class="table table-striped table-bordered table-hover" >
                        <thead >  
                            <tr>  
                                <?php
//  creating dyanamic table header using same array of config language
                                foreach ($languagelist as $langcode) {
                                    ?>

                                    <td class="center"><?php echo __('lbldocdtls') . " (" . $langcode['mainlanguage']['language_name'] . ")"; ?></td>
                                <?php } ?>


                                <td class="width10 center"><?php echo __('lblaction'); ?></td>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php foreach ($documentrecord as $documentrecord1): ?>
                                <tr>
                                    <!--<td style="text-align: center"><?php echo $state; ?></td>-->
                                    <?php
                                    //  creating dyanamic table data(coloumns) using same array of config language
                                    foreach ($languagelist as $langcode) {
                                        // pr($langcode);
                                        ?>
                                        <td class="center"><?php echo $documentrecord1['document']['document_desc_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                    <?php } ?>

                                    <td class="width10 center">
                                        <button id="btnupdate" name="btnupdate" type="button" data-toggle="tooltip" title="Edit" class="btn btn-default " style="text-align: center;"  onclick="javascript: return formupdate(('<?php echo $documentrecord1['document']['article_id']; ?>'),
                                        <?php
                                        //  creating dyanamic parameters  using same array of config language for sending to update function
                                        foreach ($languagelist as $langcode) {
                                            // pr($langcode);
                                            ?>
                                                        ('<?php echo $documentrecord1['document']['document_desc_' . $langcode['mainlanguage']['language_code']]; ?>'),
                                        <?php } ?>
                                                    ('<?php echo $documentrecord1['document']['id']; ?>'));">
                                            <span class="glyphicon glyphicon-pencil"></span>
                                        </button>
                                        <!--                                        <button id="btndelete" name="btndelete" type="button" class="btn btn-default " style="text-align: center;"    onclick="javascript: return formdelete(
                                                                                                        ('<?php echo $documentrecord1['document']['id']; ?>'));">
                                                                                    <span class="glyphicon glyphicon-remove"></span>
                                                                                </button>-->


                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'delete_document', $documentrecord1['document']['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
                                    </td>

                                <?php endforeach; ?>
                                <?php unset($documentrecord1); ?>
                        </tbody>
                    </table>
                    <?php if (!empty($documentrecord)) { ?>
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




