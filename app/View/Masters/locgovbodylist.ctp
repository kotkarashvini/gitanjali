<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>

<script type="text/javascript">
    $(document).ready(function () {

    if (document.getElementById('hfhidden1').value == 'Y') {
    $('#divfees_items').slideDown(1000);
    }
    else {
    $('#divfees_items').hide();
    }
    $('#tablefees_items').dataTable({
    "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
            $('input[type=radio][name=is_boolean]').change(function () {
    if (this.value == 'Y') {
    $('#boolyes').show();
            $('#boolno').hide();
            $('#info_value').val('');
    }
    else if (this.value == 'N') {
    $('#boolno').show();
            $('#boolyes').hide();
            $('input[name="conf_bool_value"]').prop('checked', false);
    }
    else {
    $('#boolyes').hide();
            $('#boolno').hide();
    }
    });
    });
            function formadd() {
            document.getElementById("actiontype").value = '1';
//        var boolval=$("input[name=conf_bool_value]:checked").val();
//        var infoval=$('#info_value').val();
//        alert(boolval); alert(infoval);
//        if($('#hfupdateflag').val=='Y' && boolval!=''){
//            $('#info_value').val('');
//        }
//        if($('#hfupdateflag').val=='Y' && infoval!=''){
//            $('input[name="conf_bool_value"]').prop('checked', false);
//            alert($("input[name=conf_bool_value]:checked").val());
//        }
            }

    function forcancel() {
    document.getElementById("actiontype").value = '2';
    }

    function formupdate(
<?php foreach ($languagelist as $langcode) { ?>
    <?php echo 'governingbody_name_' . $langcode['mainlanguage']['language_code']; ?>,
<?php } ?> ulb_type_id, class_type, id) {
    document.getElementById("actiontype").value = '1';
<?php foreach ($languagelist as $langcode) { ?>
        $('#governingbody_name_<?php echo $langcode['mainlanguage']['language_code']; ?>').val(governingbody_name_<?php echo $langcode['mainlanguage']['language_code']; ?>);
<?php } ?>

    $('#ulb_type_id').val(ulb_type_id);
            $('#class_type').val(class_type);
            $('#hfid').val(id);
            $('#hfupdateflag').val('Y');
            $('#btnadd').html('Save');
            return false;
    }


</script>

<?php echo $this->Form->create('locgovbodylist', array('id' => 'locgovbodylist', 'autocomplete' => 'off')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lbllocalgovbodylist'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/LocalGoverningBodyList/locgovbodylist_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
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
                                <label><?php echo __('lbllocalgoberningbody') . "  " . $langcode['mainlanguage']['language_name']; ?>
                                    <span style="color: #ff0000">*</span>
                                </label>  </div>  
                        <div class="col-md-3">
                                <?php echo $this->Form->input('governingbody_name_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'governingbody_name_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => "100")) ?>
                                <span id="<?php echo 'governingbody_name_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error">
                                    <?php echo $errarr['governingbody_name_' . $langcode['mainlanguage']['language_code'] . '_error']; ?>
                                </span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        
                        <label for="ulb_type_id" class="control-label col-sm-3" ><?php echo __('lblgovbodyname'); ?><span style="color: #ff0000">*</span></label>            
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('ulb_type_id', array('label' => false, 'id' => 'ulb_type_id', 'class' => 'form-control input-sm', 'options' => array($corpclassdata), 'empty' => '--Select--')); ?>
                             
                            <span id="ulb_type_id_error" class="form-error"><?php echo $errarr['ulb_type_id_error']; ?></span>
                        </div>
                        <label for="class_type" class="control-label col-sm-2"><?php echo __('lblclasstype'); ?><span style="color: #ff0000">*</span></label>   
                               
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('class_type', array('label' => false, 'id' => 'class_type', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => "1")); ?>
                            <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
                            <span id="class_type_error" class="form-error"><?php echo $errarr['class_type_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div> <div  class="rowht"></div> <div  class="rowht"></div>
                <div class="row center">
                    <div class="form-group">
                        <div class="col-sm-12 tdselect">
                            <button id="btnadd" type="submit"name="btnadd" class="btn btn-info "onclick="javascript: return formadd();">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp; <?php echo __('lblbtnAdd'); ?></button>
                            <button id="btnadd" name="btncancel" class="btn btn-info " onclick="javascript: return forcancel();">
                                <span class="glyphicon glyphicon-floppy-remove"></span>&nbsp;&nbsp; <?php echo __('btncancel'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-primary">

            <div class="box-body" id="divfees_items">

                <table id="tablefees_items" class="table table-striped table-bordered table-hover">  
                    <thead >  
                        <tr>  
                            <th class="center"><?php echo __('lblgovbodyname'); ?></th>
                            <th class="center"><?php echo __('lblclasstype'); ?></th>
                            <th class="center"><?php echo __('lblgovbodylistname'); ?></th>
                            <th class="center width10"><?php echo __('lblaction'); ?></th>
                        </tr>  
                    </thead>

                    <?php for ($i = 0; $i < count($locgovbodylist); $i++) { ?>
                        <tr>
                            <td ><?php echo $locgovbodylist[$i][0]['class_description_' . $laug]; ?></td>
                            <td ><?php echo $locgovbodylist[$i][0]['class_type']; ?></td>
                            <td ><?php echo $locgovbodylist[$i][0]['governingbody_name_' . $laug]; ?></td>
                            <td >
                                <button id="btnupdate" name="btnupdate" class="btn btn-default "  onclick="javascript: return formupdate(
                                <?php
                                //  creating dyanamic parameters  using same array of config language for sending to update function
                                foreach ($languagelist as $langcode) {
                                    ?>
                                                        ('<?php echo $locgovbodylist[$i][0]['governingbody_name_' . $langcode['mainlanguage']['language_code']]; ?>'),
                                <?php } ?>
                                                    ('<?php echo $locgovbodylist[$i][0]['ulb_type_id']; ?>'), ('<?php echo $locgovbodylist[$i][0]['class_type']; ?>'), ('<?php echo $locgovbodylist[$i][0]['id']; ?>'));">
                                    <span class="glyphicon glyphicon-pencil"></span></button>
                                <?php
                                $newid = $this->requestAction(
                                        array('controller' => 'Masters', 'action' => 'encrypt', $locgovbodylist[$i][0]['id'], $this->Session->read("randamkey"),
                                ));
                                ?>
                                <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'locgovbodylist_delete', $newid), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>

                            </td>
                        </tr>
                    <?php } ?>
                </table> 
                <?php if (!empty($locgovbodylist)) { ?>
                    <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                    <input type="hidden" value="N" id="hfhidden1"/><?php } ?>

            </div>
        </div>


    </div>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
</div>

<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>




