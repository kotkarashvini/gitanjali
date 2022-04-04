<?php
/* Form Created by Yogesh, 
 * Updated by Shridhar on 19-June-2017 as per Anjali Madam (Sorted LabelName  ASC, Set Value on Edit, Auto Scroll on Select)
 */
?>
<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>
<script>
    $(document).ready(function () {
    $('#tableFormlabel').dataTable({
    "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
    });
</script>
<script>
            function formadd() {
            document.getElementById("hfaction").value = 'S';
                    document.getElementById("actiontype").value = '1';
            }
    //dyanamic function creation for collecting parameters in update function     
    function formupdate(labelname,
<?php
foreach ($languagelist as $langcode) {
    // pr($langcode);
    ?>
    <?php echo 'label_desc_' . $langcode['mainlanguage']['language_code']; ?>,
<?php } ?>

    id) {
    document.getElementById("actiontype").value = '1';
//dyanamic function creation for Assigning value to text boxes in update function  according to language code   
<?php
foreach ($languagelist as $langcode) {
    // pr($langcode);
    ?>
        $('#label_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>').val(label_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>);
<?php } ?>
    $('#labelname').val(labelname);
            $('#hfid').val(id);
            $("html, body").animate({scrollTop: '150'}, "slow");
            $('#hfupdateflag').val('Y');
            $('#btnadd').html('Save');
            return false;
    }
</script> 
<?php echo $this->Form->create('Formlabel', array('type' => 'file', 'class' => 'journal_voucher', 'autocomplete' => 'off', 'id' => 'formID')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder" ><?php echo __('lbllabel'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/ScreenLabel/formlabel_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="labelname" class="control-label col-sm-3"><?php echo __('lbllabelname'); ?>(e.g lbl)<span style="color: #ff0000">*</span></label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('labelname', array('label' => false, 'id' => 'labelname', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '50')) ?>
                            <span id="labelname_error" class="form-error"><?php echo $errarr['labelname_error']; ?></span>
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
                                <label><?php echo __('lbllabeldescription') . "  " . $langcode['mainlanguage']['language_name']; ?><span style="color: #ff0000">*</span></label>    
                                <?php echo $this->Form->input('label_desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'label_desc_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '255')) ?>
                                <span id="<?php echo 'label_desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error"><?php echo $errarr['label_desc_' . $langcode['mainlanguage']['language_code'] . '_error']; ?></span>
                            </div>
                        <?php } ?>
                        <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
                    </div>
                </div>
                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center">
                    <div class="form-group">
                        <button id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-primary">

            <div class="box-body">
                <div id="selectFormlabel">
                    <table id="tableFormlabel" class="table table-striped table-bordered table-hover" >
                        <thead >  
                            <tr>  

                                <?php
//  creating dyanamic table header using same array of config language
                                foreach ($languagelist as $langcode) {
                                    ?>
                                    <th class="center"><?php echo __('lbllabeldescription') . " (" . $langcode['mainlanguage']['language_name'] . ")"; ?></th>
                                <?php } ?>

                                <th class="center"><?php echo __('lblformlabel'); ?></th>
                                <th class="center width10"><?php echo __('lblaction'); ?></th>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php foreach ($labelrecord as $labelrecord1): ?>
                                <tr>

                                    <?php
                                    //  creating dyanamic table data(coloumns) using same array of config language
                                    foreach ($languagelist as $langcode) {
                                        // pr($langcode);
                                        ?>
                                        <td class="tblbigdata"><?php echo $labelrecord1['Formlabel']['label_desc_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                    <?php } ?>
                                    <td class="tblbigdata"><?php echo $labelrecord1['Formlabel']['labelname']; ?></td>

                                    <td >
                                        <button id="btnupdate" name="btnupdate" data-toggle="tooltip" title="Edit" type="button" class="btn btn-default "   onclick="javascript: return formupdate(('<?php echo $labelrecord1['Formlabel']['labelname']; ?>'),
                                        <?php
                                        //  creating dyanamic parameters  using same array of config language for sending to update function
                                        foreach ($languagelist as $langcode) {
                                            // pr($langcode);
                                            ?>
                                                            ('<?php echo $labelrecord1['Formlabel']['label_desc_' . $langcode['mainlanguage']['language_code']]; ?>'),
                                        <?php } ?>
                                                        ('<?php echo $labelrecord1['Formlabel']['id']; ?>'));">
                                            <span class="glyphicon glyphicon-pencil"></span>
                                        </button>
                                        <?php
                                        $newid = $this->requestAction(
                                                array('controller' => 'Masters', 'action' => 'encrypt', $labelrecord1['Formlabel']['id'], $this->Session->read("randamkey"),
                                        ));
                                        ?>

                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'delete_Formlabel', $newid), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
                                    </td>

                                <?php endforeach; ?>
                                <?php unset($labelrecord1); ?>
                        </tbody>
                    </table>
                    <?php if (!empty($labelrecord)) { ?>
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




