<?php
echo $this->Html->script('dataTables.bootstrap');
echo $this->Html->script('jquery.dataTables');
?>
<script>
    $(document).ready(function () {

    $("#execution_date").datepicker();
            var hfupdateflag = "<?php echo $hfupdateflag; ?>";
            if (hfupdateflag === 'Y')
    {
    $('#btnadd').html('Save');
    }
    if ($('#hfhidden1').val() === 'Y')
    {
    $('#tableexternal_interface').dataTable({
    "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
    } else {
    $('#tableexternal_interface').dataTable({
    "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
    }
    var actiontype = document.getElementById('actiontype').value;
            if (actiontype == '2') {
    $('.tdsave').show();
            $('.tdselect').hide();
            $('#interface_desc_en').focus();
    }
    });</script>

<script>
            function formadd() {
            document.getElementById("hfaction").value = 'S';
                    document.getElementById("actiontype").value = '1';
            }

    //dyanamic function creation for collecting parameters in update function     
    function formupdate(interface_id, interface_url, remark, execution_date, igr_ownership, interface_user_id, interface_password,
<?php
foreach ($languagelist as $langcode) {
    // pr($langcode);
    ?>
    <?php echo 'interface_desc_' . $langcode['mainlanguage']['language_code']; ?>,
<?php } ?>

    id) {
    document.getElementById("actiontype").value = '1';
//dyanamic function creation for Assigning value to text boxes in update function  according to language code   
<?php
foreach ($languagelist as $langcode) {
    // pr($langcode);
    ?>
        $('#interface_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>').val(interface_desc_<?php echo $langcode['mainlanguage']['language_code']; ?>);
<?php } ?>
    $('#hfid').val(id);
            $('#interface_id').val(interface_id);
            $('#interface_url').val(interface_url);
            $('#remark').val(remark);
            $('#execution_date').val(execution_date);
            $('#igr_ownership').val(igr_ownership);
            $('#interface_user_id').val(interface_user_id);
            $('#interface_password').val(interface_password);
            $('input:radio[name="data[external_interface][igr_ownership]"][value=' + igr_ownership + ']').attr('checked', true);
            $('#hfupdateflag').val('Y');
            $('#btnadd').html('Save');
            return false;
    }

</script> 


<?php echo $this->Form->create('external_interface', array('id' => 'external_interface', 'class' => 'form-vertical')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblexternalinterface'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/admin/external_interface_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
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
                                <label><?php echo __('lblinterfacedescription') . "  " . $langcode['mainlanguage']['language_name']; ?><span style="color: #ff0000">*</span></label>    
                                <?php echo $this->Form->input('interface_desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'interface_desc_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => "255")) ?>
                                <span id="<?php echo 'interface_desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error"><?php echo $errarr['interface_desc_' . $langcode['mainlanguage']['language_code'] . '_error']; ?></span>
                            </div>
                        <?php } ?>
                        <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="interface_url" class="col-sm-3 control-label"><?php echo __('lblinterfaceurl'); ?><span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('interface_url', array('label' => false, 'id' => 'interface_url', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => "255")) ?>
                            <span id="interface_url_error" class="form-error"><?php echo $errarr['interface_url_error']; ?></span>
                        </div>
                        <label for="interface_id" class="col-sm-3 control-label"><?php echo __('lblremark'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('remark', array('label' => false, 'id' => 'remark', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => "255")) ?>
                            <span id="remark_error" class="form-error"><?php echo $errarr['remark_error']; ?></span>
                        </div>

                    </div>
                </div>
                <div  class="rowht"></div>

                <div class="row">
                    <div class="form-group">
                        <label for="execution_date" class="col-sm-3 control-label"><?php echo __('lblexecutiondate'); ?><span style="color: #ff0000">*</span></label>    
                        <div class=" col-sm-3">
                            <?php echo $this->Form->input('execution_date', array('label' => false, 'id' => 'execution_date', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="execution_date_error" class="form-error"><?php echo $errarr['execution_date_error']; ?></span>
                        </div>
                        <label for="igr_ownership" class="col-sm-3 control-label"><?php echo __('lbligrownership'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('igr_ownership', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'igr_ownership')); ?>                       
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>
                
                <div class="row">
                    <div class="form-group">
                        <label for="interface_user_id" class="col-sm-3 control-label"><?php echo __('lblinterfaceuserid'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('interface_user_id', array('label' => false, 'id' => 'interface_user_id', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => "255")) ?>
                            <span id="interface_user_id_error" class="form-error"><?php echo $errarr['interface_user_id_error']; ?></span>
                        </div>
                        <label for="interface_password" class="col-sm-3 control-label"><?php echo __('lblinterfacepassword'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('interface_password', array('label' => false, 'id' => 'interface_password', 'class' => 'form-control input-sm', 'type' => 'password', 'maxlength' => "100")) ?>
                            <span id="interface_password_error" class="form-error"><?php echo $errarr['interface_password_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center">
                    <div class="form-group">
                        <button id="btnadd" type="submit"name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?>
                        </button>
                    </div>
                </div>
                <div  class="rowht"></div>
            </div>
        </div>

        <div class="box box-primary">

            <div class="box-body">
                <div id="selectexternal_interface">
                    <table id="tableexternal_interface" class="table table-striped table-bordered table-hover" >
                        <thead >  
                            <tr>  

                                <?php
//  creating dyanamic table header using same array of config language
                                foreach ($languagelist as $langcode) {
                                    // pr($langcode);
                                    ?>
                                    <th class="center"><?php echo __('lblinterfacedescription') . " (" . $langcode['mainlanguage']['language_name'] . ")"; ?></th>
                                <?php } ?>

                                <th class="center"><?php echo __('lblremark'); ?></th>
                                <th class="center width10" ><?php echo __('lblaction'); ?></th>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php foreach ($externalrecord as $externalrecord1): ?>
                                <tr>

                                    <?php
                                    //  creating dyanamic table data(coloumns) using same array of config language
                                    foreach ($languagelist as $langcode) {
                                        // pr($langcode);
                                        ?>
                                        <td ><?php echo $externalrecord1['external_interface']['interface_desc_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                    <?php } ?>

                                    <td ><?php echo $externalrecord1['external_interface']['remark']; ?></td>   

                                    <td >
                                        <button id="btnupdate" name="btnupdate" type="button" class="btn btn-default "   onclick="javascript: return formupdate(
                                                            ('<?php echo $externalrecord1['external_interface']['interface_id']; ?>'),
                                                            ('<?php echo $externalrecord1['external_interface']['interface_url']; ?>'),
                                                            ('<?php echo $externalrecord1['external_interface']['remark']; ?>'),
                                                            ('<?php echo $externalrecord1['external_interface']['execution_date']; ?>'),
                                                            ('<?php echo $externalrecord1['external_interface']['igr_ownership']; ?>'),
                                                            ('<?php echo $externalrecord1['external_interface']['interface_user_id']; ?>'),
                                                            ('<?php echo $externalrecord1['external_interface']['interface_password']; ?>'),
                                        <?php
                                        //  creating dyanamic parameters  using same array of config language for sending to update function
                                        foreach ($languagelist as $langcode) {
                                            // pr($langcode);
                                            ?>
                                                        ('<?php echo $externalrecord1['external_interface']['interface_desc_' . $langcode['mainlanguage']['language_code']]; ?>'),
                                        <?php } ?>
                                                    ('<?php echo $externalrecord1['external_interface']['id']; ?>'));">
                                            <span class="glyphicon glyphicon-pencil"></span>
                                        </button>
                                        <!--                                        <button id="btndelete" name="btndelete" type="button" class="btn btn-default "     onclick="javascript: return formdelete(
                                                                                                        ('<?php echo $externalrecord1['external_interface']['id']; ?>'));">
                                                                                    <span class="glyphicon glyphicon-remove"></span>
                                                                                </button>-->
                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'delete_external_interface', $externalrecord1['external_interface']['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
                                    </td>

                                <?php endforeach; ?>
                                <?php unset($externalrecord1); ?>
                        </tbody>
                    </table>
                    <?php if (!empty($externalrecord)) { ?>
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