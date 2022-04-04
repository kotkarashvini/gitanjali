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
    $('#tablesurvey').dataTable({
    "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
    } else {
    $('#tablesurvey').dataTable({
    "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
    }
    var actiontype = document.getElementById('actiontype').value;
            if (actiontype == '2') {
    $('.tdsave').show();
            $('.tdselect').hide();
            $('#survey_lable_en').focus();
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
    ?>
    <?php echo 'survey_lable_' . $langcode['mainlanguage']['language_code']; ?>,
<?php } ?>

    id) {
    document.getElementById("actiontype").value = '1';
//dyanamic function creation for Assigning value to text boxes in update function  according to language code   
<?php
foreach ($languagelist as $langcode) {
    // pr($langcode);
    ?>
        $('#survey_lable_<?php echo $langcode['mainlanguage']['language_code']; ?>').val(survey_lable_<?php echo $langcode['mainlanguage']['language_code']; ?>);
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
<?php echo $this->Form->create('survey', array('id' => 'survey', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblpropertyattribute'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/admin/survey_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
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
                                <?php echo $this->Form->input('survey_lable_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'survey_lable_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => "255")) ?>
                                <span id="<?php echo 'survey_lable_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error"><?php echo $errarr['survey_lable_' . $langcode['mainlanguage']['language_code'] . '_error']; ?></span>
                            </div>
                        <?php } ?>
                        <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
                    </div>
                </div>

                <div  class="rowht"></div>
                <div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center">
                    <div class="form-group" >
                        <div class="col-sm-12 tdselect center">
                            <button id="btnadd" type="submit"name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?>
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="box box-primary">

            <div class="box-body">
                <div id="selectsurvey">
                    <table id="tablesurvey" class="table table-striped table-bordered table-hover" >
                        <thead >  
                            <tr>  
                                <?php
                                //creating dyanamic table header using same array of config language
                                foreach ($languagelist as $langcode) {
                                    ?>
                                    <td class="center"><?php echo __('lblpropertyattributedescription') . " (" . $langcode['mainlanguage']['language_name'] . ")"; ?></td>
                                <?php } ?>
                                <td class="center width10"><?php echo __('lblaction'); ?></td>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php foreach ($surveyrecord as $surveyrecord1): ?>
                                <tr>
                                    <!--<td style="text-align: center"><?php echo $state; ?></td>-->
                                    <?php
                                    //  creating dyanamic table data(coloumns) using same array of config language
                                    foreach ($languagelist as $langcode) {
                                        ?>
                                        <td class="center"><?php echo $surveyrecord1['survey']['survey_lable_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                    <?php } ?>

                                    <td class="center width10">
                                        <button id="btnupdate" name="btnupdate"  type="button"data-toggle="tooltip" title="Edit" class="btn btn-default " style="text-align: center;"  onclick="javascript: return formupdate(
                                        <?php
                                        //  creating dyanamic parameters  using same array of config language for sending to update function
                                        foreach ($languagelist as $langcode) {
                                            // pr($langcode);
                                            ?>
                                                        ('<?php echo $surveyrecord1['survey']['survey_lable_' . $langcode['mainlanguage']['language_code']]; ?>'),
                                        <?php } ?>
                                                    ('<?php echo $surveyrecord1['survey']['id']; ?>'));">
                                            <span class="glyphicon glyphicon-pencil"></span>
                                        </button>
                                        <!--                                        <button id="btndelete" name="btndelete" type="button" class="btn btn-default " style="text-align: center;"    onclick="javascript: return formdelete(
                                                                                                        ('<?php echo $surveyrecord1['survey']['id']; ?>'));">
                                                                                    <span class="glyphicon glyphicon-remove"></span>
                                                                                </button>-->
                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'delete', $surveyrecord1['survey']['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
                                    </td>

                                <?php endforeach; ?>
                                <?php unset($surveyrecord1); ?>
                        </tbody>
                    </table>
                    <?php if (!empty($surveyrecord)) { ?>
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




