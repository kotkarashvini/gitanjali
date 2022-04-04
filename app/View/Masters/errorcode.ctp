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
    $('#tableunit').dataTable({
    "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
    }
    });</script>
<script>
            function formadd() {
            document.getElementById("actiontype").value = '1';
                    document.getElementById("hfaction").value = 'S';
            }

    function formupdate(<?php
foreach ($languagelist as $langcode) {
    ?>
    <?php echo 'error_messages_' . $langcode['mainlanguage']['language_code']; ?>,
<?php } ?> error_code, error_code_id) {
    document.getElementById("actiontype").value = '1';
            $('#editform').show('slow');
<?php
foreach ($languagelist as $langcode) {
    ?>
        $('#error_messages_<?php echo $langcode['mainlanguage']['language_code']; ?>').val(error_messages_<?php echo $langcode['mainlanguage']['language_code']; ?>);
<?php } ?>
    $('#error_code').val(error_code);
            $('#error_code_id').val(error_code_id);
            $('#hfupdateflag').val('Y');
            $('#hfid').val(error_code_id);
            $('#hfaction').val('Y');
            $('#btnadd').html('Save');
            return false;
    }
</script> 
<?php echo $this->Form->create('errorcode', array('id' => 'errorcode', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary" >
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblerrorcode'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/admin/errorcode_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body" hidden="true" id="editform">
                <div class="row">
                    <div class="form-group">
                        <label for="errorcode" class="col-sm-3 control-label"><?php echo __('lblerrorcode'); ?><span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('error_code', array('label' => false, 'id' => 'error_code', 'class' => 'form-control input-sm', 'data-toggle' => 'tooltip', 'title' => 'Do Not Enter Any Changes', array('readonly' => 'readonly'))) ?>
                            <span id="error_code_error" class="form-error"><?php echo $errarr['error_code_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <?php
                        foreach ($languagelist as $key => $langcode) {
                            ?>
                            <div class="col-sm-3">
                                <label><?php echo __('lblerrorcode') . "  " . $langcode['mainlanguage']['language_name']; ?><span style="color: #ff0000">*</span></label>    
                                <?php echo $this->Form->input('error_messages_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'error_messages_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'maxlength' => '100', 'type' => 'text')) ?>
                                <span id="<?php echo 'error_messages_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error"><?php echo $errarr['error_messages_' . $langcode['mainlanguage']['language_code'] . '_error']; ?></span>
                            </div>
                        <?php } ?>
                        <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>
                        <!--                             <div>
                                                        <label for="errorcode" class="col-sm-2 control-label"><?php echo __('lblerrorcode'); ?><span style="color: #ff0000">*</span></label> 
                                                        <div class="col-sm-2">
                        <?php echo $this->Form->input('error_code', array('label' => false, 'id' => 'error_codeid', 'class' => 'form-control input-sm', 'data-toggle' => 'tooltip', 'title' => 'Do Not Enter Any Changes')) ?>
                                                            <span id="error_code_error" class="form-error"><?php echo $errarr['error_code_error']; ?></span>
                                                        </div>
                                                    </div>-->
                    </div>
                </div>
                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center">
                    <div class="form-group">
                        <button id="btnadd" name="btnadd" class="btn btn-info " style="text-align: center;" onclick="javascript: return formadd();">
                            <span class="glyphicon glyphicon-plus"></span><?php echo __('lblAdd'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-primary" >
            <div class="box-body">
                <table id="tableunit" class="table table-striped table-bordered table-hover">  
                    <thead>  
                        <tr>  
                            <th class="center"><?php echo __('lblerrorcode'); ?></th>
                            <?php
                            foreach ($languagelist as $langcode) {
                                // pr($langcode);
                                ?>
                                <th class="center"><?php echo __('lblerrorcode') . "  " . $langcode['mainlanguage']['language_name']; ?></th>
                            <?php } ?>

                            <th class="center"><?php echo __('lblaction'); ?></th>
                        </tr>  
                    </thead>
                    <tbody>
                        <?php foreach ($errorcoderecord as $unitrecord1): ?>
                            <tr>
                                <td class="tblbigdata"><?php echo $unitrecord1['NGDRSErrorCode']['error_code']; ?></td>
                                <?php
                                foreach ($languagelist as $langcode) {
                                    ?>
                                    <td ><?php echo $unitrecord1['NGDRSErrorCode']['error_messages_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                <?php } ?>

                                <td >
                                    <button id="btnupdate" name="btnupdate" type="button" data-toggle="tooltip" title="Edit" class="btn btn-default " style="text-align: center;"  onclick="javascript: return formupdate(
                                    <?php
                                    foreach ($languagelist as $langcode) {
                                        ?>
                                                    ('<?php echo $unitrecord1['NGDRSErrorCode']['error_messages_' . $langcode['mainlanguage']['language_code']]; ?>'),
                                    <?php } ?>
                                                ('<?php echo $unitrecord1['NGDRSErrorCode']['error_code']; ?>'),
                                                        ('<?php echo $unitrecord1['NGDRSErrorCode']['error_code_id']; ?>'));">
                                        <span class="glyphicon glyphicon-pencil"></span>
                                    </button>
                                    <?php
                                    $newid = $this->requestAction(
                                            array('controller' => 'Masters', 'action' => 'encrypt', $unitrecord1['NGDRSErrorCode']['error_code_id'], $this->Session->read("randamkey"),
                                    ));
                                    ?>
                                    <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'errorcode_delete', $newid), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php unset($unitrecord1); ?>
                    </tbody>
                </table> 
                <?php
                if (!empty($errorcoderecord)) {
                    ?>
                    <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                    <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
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
<script type="text/javascript" src="https://www.google.com/jsapi">
</script>
<script type="text/javascript">
            // Load the Google Transliterate API
            google.load("elements", "1", {
            packages: "transliteration"
            });
            function onLoad() {
            var options = {
            sourceLanguage:
                    google.elements.transliteration.LanguageCode.ENGLISH,
                    destinationLanguage:
                    [google.elements.transliteration.LanguageCode.MARATHI],
                    shortcutKey: 'ctrl+e',
                    transliterationEnabled: true
            };
                    // Create an instance on TransliterationControl with the required
                    // options.
                    var control =
                    new google.elements.transliteration.TransliterationControl(options);
                    // Enable transliteration in the textbox with id
                    // 'transliterateTextarea'.
                    control.makeTransliteratable(['error_messages_ll']);
            }
    google.setOnLoadCallback(onLoad);
</script>



