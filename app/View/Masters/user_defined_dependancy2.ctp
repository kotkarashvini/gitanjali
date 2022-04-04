<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>
<?php
echo $this->Html->script('dataTables.bootstrap');
echo $this->Html->script('jquery.dataTables');
echo $this->Element('Validationscript/dynamicscript');
echo $this->Html->script('commonvalidationjs');
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
    $('#tableuser_defined_dependancy2').dataTable({
    "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
    } else {
    $('#tableuser_defined_dependancy2').dataTable({
    "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
    }
    var actiontype = document.getElementById('actiontype').value;
            if (actiontype == '2') {
    $('.tdsave').show();
            $('.tdselect').hide();
            $('#user_defined_dependency2_desc_en').focus();
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
    <?php echo 'user_defined_dependency2_desc_' . $langcode['mainlanguage']['language_code']; ?>,
<?php } ?>

    id) {
    document.getElementById("actiontype").value = '1';
//dyanamic function creation for Assigning value to text boxes in update function  according to language code   
<?php
foreach ($languagelist as $langcode) {
    // pr($langcode);
    ?>
        $('#user_defined_dependency2_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>').val(user_defined_dependency2_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>);
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

<?php echo $this->Form->create('user_defined_dependancy2', array('id' => 'user_defined_dependancy2', 'autocomplete' => 'off')); ?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading" style="text-align: center"><b><?php echo __('lbluserdefineddependency2'); ?></b></div>

            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">

                        <?php
//  creating dyanamic text boxes using same array of config language
                        foreach ($languagelist as $key => $langcode) {
                            // pr($langcode);
                            ?>
                            <div class="col-md-6">
                                <label><?php echo __('lbluserdefineddependency2') . "  " . $langcode['mainlanguage']['language_name']; ?><span style="color: #ff0000">*</span></label>    
                                <?php echo $this->Form->input('user_defined_dependency2_desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'user_defined_dependency2_desc_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                <span id="<?php echo 'user_defined_dependency2_desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error"><?php echo $errarr['user_defined_dependency2_desc_' . $langcode['mainlanguage']['language_code'] . '_error']; ?></span>
                            </div>
                        <?php } ?>

                        <div class="col-sm-1 tdselect"><br>
                            <button id="btnadd" name="btnadd" class="btn btn-primary " style="text-align: center;" onclick="javascript: return formadd();">
                                <span class="glyphicon glyphicon-plus"></span><?php echo __('lblbtnAdd'); ?>
                            </button>
                        </div>
           
                    </div>
                </div>
            </div>


         <div class="panel-heading" style="text-align: center"><b><?php echo __('lbluserdefineddependency2'); ?></b></div>
            <div class="panel-body">
                <div id="selectuser_defined_dependancy2" class="table-responsive">
                    <table id="tableuser_defined_dependancy2" class="table table-striped table-bordered table-hover" >
                        <thead >  
                            <tr>  
                                <!--<td style="text-align: center; width: 10%;"><?php echo __('lbladmstate'); ?></td>-->
                                <?php
//  creating dyanamic table header using same array of config language
                                foreach ($languagelist as $langcode) {
                                    // pr($langcode);
                                    ?> 
                                     <td style="text-align: center;"><?php  echo __('lbluserdefineddependency2')." (" . $langcode['mainlanguage']['language_name'].")"; ?></td>
                                <?php } ?>


                                <td style="text-align: center; width: 10%;"><?php echo __('lblaction'); ?></td>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php foreach ($user_defined_dependancy2record as $userdefinedep2record1): ?>
                                <tr>
                                    <!--<td style="text-align: center"><?php echo $state; ?></td>-->
                                    <?php
                                    //  creating dyanamic table data(coloumns) using same array of config language
                                    foreach ($languagelist as $langcode) {
                                        // pr($langcode);
                                        ?>
                                        <td style="text-align: center;"><?php echo $userdefinedep2record1['user_defined_dependancy2']['user_defined_dependency2_desc_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                    <?php } ?>

                                    <td style="text-align: center;">
                                        <button id="btnupdate" name="btnupdate" type="button"data-toggle="tooltip" title="Edit" class="btn btn-default " style="text-align: center;"  onclick="javascript: return formupdate(
                                        <?php
                                        //  creating dyanamic parameters  using same array of config language for sending to update function
                                        foreach ($languagelist as $langcode) {
                                            // pr($langcode);
                                            ?>
                                                            ('<?php echo $userdefinedep2record1['user_defined_dependancy2']['user_defined_dependency2_desc_' . $langcode['mainlanguage']['language_code']]; ?>'),
                                        <?php } ?>
                                                        ('<?php echo $userdefinedep2record1['user_defined_dependancy2']['id']; ?>'));">
                                            <span class="glyphicon glyphicon-pencil"></span>
                                        </button>
                                        <!--                                        <button id="btndelete" name="btndelete" type="button" class="btn btn-default " style="text-align: center;"    onclick="javascript: return formdelete(
                                                                                                        ('<?php echo $userdefinedep2record1['user_defined_dependancy2']['id']; ?>'));">
                                                                                    <span class="glyphicon glyphicon-remove"></span>
                                                                                </button>-->
                                       <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'delete_user_defined_dependancy2', $userdefinedep2record1['user_defined_dependancy2']['id']), array('escape' => false, 'data-toggle' => 'tooltip','title' => __('Delete'),'class' => "btn btn-default"), array('Are you sure?')); ?></a>
                                    </td>

                                <?php endforeach; ?>
                                <?php unset($userdefinedep2record1); ?>
                        </tbody>
                    </table>
                    <?php if (!empty($user_defined_dependancy2record)) { ?>
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




