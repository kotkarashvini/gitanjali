<script type="text/javascript">
    $(document).ready(function () {
  var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;

        var type = $("#identifire_type option:selected").val();
        if (type == 2) {
           // $("#partytype").show();
            $("#identification_fields").hide();
        } else {
           // $("#partytype").hide();
             var host = '<?php echo $this->webroot; ?>';
        $.post(host + 'Citizenentry/get_identification_feilds', {csrftoken: csrftoken, type: type}, function (fields)
        {
            if (fields) {
                $("#identification_fields").show();
                $("#identification_fields").html(fields);
                $(document).trigger('_page_ready');
                show_data_messages();
                show_error_messages();
            } else {
                window.location.href = "<?php echo $this->webroot; ?>Citizenentry/identification/<?php echo $this->Session->read('csrftoken'); ?>";
                            }

                        });
        }
       


                        $('#identifire_type').change(function () {
                            var type = $("#identifire_type option:selected").val();
                            var host = '<?php echo $this->webroot; ?>';


                            if (type == 2) {
                                $("#identification_fields").hide();
                               // $("#partytype").show();
                            } else {
                               // $("#partytype").hide();
                                $.post(host + 'Citizenentry/get_identification_feilds', {csrftoken: csrftoken, type: type}, function (fields)
                                {
                                    if (fields) {
                                        $("#identification_fields").show();
                                        $("#identification_fields").html(fields);
                                        $(document).trigger('_page_ready');
                                        show_data_messages();
                                        show_error_messages();
                                    } else {
                                        window.location.href = "<?php echo $this->webroot; ?>Citizenentry/identification/<?php echo $this->Session->read('csrftoken'); ?>";
                                                            }

                                                        });
                                                    }

                                                });


                                            });

                                            var host = '<?php echo $this->webroot; ?>';
                                            var nameformat = '<?php echo $name_format ?>';
                                            var lang = '<?php echo $laug ?>';
                                            var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
                                            function set_master(master_id, office_id) {
                                                var type = $("#identifire_type option:selected").val();

                                                $.post(host + 'Citizenentry/get_identification_feilds', {master_id: master_id, csrftoken: csrftoken, type: type}, function (data)
                                                {

                                                    $('#master_id').val(master_id);
                                                    $('#master_office_id').val(office_id);

                                                    $("#identification_fields").html(data);
                                                    $(document).trigger('_page_ready');
                                                    // $('#hfid').val(id);
                                                    $('#hfupdateflag').val('N');

                                                    if ($('#village_id').length && $("#village_id option:selected").val() != '') {
                                                        var village_id = $("#village_id option:selected").val();
                                                        $.post(host + 'Citizenentry/behavioral_patterns', {ref_id: 9999, behavioral_id: 2, village_id: village_id, ref_val: master_id, csrftoken: csrftoken}, function (data1)
                                                        {

                                                            $('.partyaddress').html(data1);
                                                        });
                                                    }

                                                });

                                            }

                                            function edit_ident(id, type)
                                            {
                                                $.post(host + 'Citizenentry/get_identification_feilds', {id: id, csrftoken: csrftoken, type: type}, function (data)
                                                {
                                                    $("#identification_fields").html(data);
                                                    $(document).trigger('_page_ready');
                                                    $('#hfid').val(id);
                                                    $('#hfupdateflag').val('Y');

                                                    if ($('#village_id').length && $("#village_id option:selected").val() != '') {
                                                        var village_id = $("#village_id option:selected").val();
                                                        $.post(host + 'Citizenentry/behavioral_patterns', {ref_id: 5, behavioral_id: 2, village_id: village_id, ref_val: id, csrftoken: csrftoken}, function (data1)
                                                        {

                                                            $('.partyaddress').html(data1);
                                                            $("#identifire_type").val(type);
                                                        });
                                                    }

                                                });
                                            }
                                            function formdelete(id) {
                                                var result = confirm("Are you sure you want to delete this record?");
                                                $('#hfid').val(id);

                                                if (result) {
                                                    $.post('<?php echo $this->webroot; ?>Citizenentry/delete_identifire', {id: id, csrftoken: csrftoken}, function (data1)
                                                    {

                                                        if (data1.trim() == 1)
                                                        {
                                                            alert('identifire deleted successfully');
                                                            window.location.href = "<?php echo $this->webroot; ?>Citizenentry/identification/<?php echo $this->Session->read('csrftoken'); ?>";
                                                                            } else
                                                                            {
                                                                                alert('Error');
                                                                            }
                                                                        });
                                                                    } else {
                                                                        return false;
                                                                    }
                                                                }
                                                                function show_data_messages() {
<?php
if (isset($fromdata)) {
    ?>
    <?php
    foreach ($fromdata as $keyfield => $message) {
        ?>
                                                                            $("#<?php echo $keyfield ?>").val("<?php echo $message ?>");
    <?php } ?>

<?php }
?>
                                                                }
</script>

<?php
echo $this->element("Helper/jqueryhelper");
echo $this->Html->css('popup');
$tokenval = $this->Session->read("Selectedtoken");

echo $this->Form->create('identification', array('id' => 'identification', 'class' => 'form-vertical'));

echo $this->element("Registration/main_menu");
if ($this->Session->read('sroidetifier') == 'N') {
    echo $this->element("Citizenentry/property_menu");
}
$doc_lang = $this->Session->read('doc_lang');
$language = $this->Session->read("sess_langauge");
?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblIdentifire'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Citizenentry/identification_<?php echo $doc_lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                    <?php if ($this->Session->read('sroidetifier') == 'Y') { ?>
                        <a href="<?php echo $this->webroot; ?>Registration/document_identification" class="btn btn-small btn-info" >  <?php echo __('BACK'); ?> </a>
                    <?php } ?>
                </div>



            </div>

            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="" class="col-sm-3 control-label"><b><?php echo __('lbltokenno'); ?> :-</b><span style="color: #ff0000"></span></label>   
                                        <div class="col-sm-3">
                                            <?php echo $this->Form->input('', array('label' => false, 'id' => '', 'value' => $Selectedtoken, 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-sm-4">
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <p style="color: red;"><b><?php echo __('lblnote'); ?>1:&nbsp;</b><?php echo __('lblengdatarequired'); ?>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($idenfire_disply['conf_reg_bool_info']['conf_bool_value'] == 'Y') { ?>
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <p style="color: red;"><b><?php echo __('lblnote'); ?>2:&nbsp;</b><?php echo __('lblidentifirecompulsarynote'); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        if (isset($masterrecord) && !empty($masterrecord)) {
            ?>
            <div class="row">
                <div class="col-sm-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title" style="font-weight: bolder"><?php echo __('Identifire Master List'); ?></h3>
                        </div>

                        <div class="box-body">
                            <table class="table table-striped table-bordered table-hover" id="oldrecord">
                                <thead >
                                    <tr class="table_title_red_brown">

                                        <th>
                                            <?php echo __('Identifire Name'); ?>
                                        </th>

                                        <th>
                                            <?php echo __('Mobile'); ?>
                                        </th>
                                        <th>
                                            <?php echo __('Email'); ?>
                                        </th>
                                        <th>
                                            <?php echo __('lblaction'); ?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($masterrecord as $rec) { ?> 

                                        <tr>
                                            <td><?php echo $rec['MstIdentification']['identification_full_name_' . $language]; ?></td>
                                            <td><?php echo $rec['MstIdentification']['mobile_no']; ?></td>
                                            <td><?php echo $rec['MstIdentification']['email_id']; ?></td>

                                            <td> <input type="button" class="btn btn-primary" value="Select" onclick="javascript: return set_master('<?php echo $rec['MstIdentification']['identification_id']; ?>', '<?php echo $rec['MstIdentification']['office_id']; ?>', '<?php //echo $ref_record1[0]['party_catg_id'];       ?>')"></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> 
            </div>
            <input type="hidden" id="flag" value="Y">


            <?php echo $this->Js->writeBuffer(); ?>
        <?php } ?>
        <?php echo $this->Form->input('party_type_flag', array('label' => false, 'id' => 'party_type_flag', 'class' => 'form-control input-sm', 'type' => 'hidden')); ?>
        <?php if ($this->Session->read("user_role_id") == '999901' || $this->Session->read("user_role_id") == '999902' || $this->Session->read("user_role_id") == '999903') { ?>
            <div class="box box-primary">
                <div class="box-body">
                    <div class="row">
                        <div class="form-group">
                            <label for="Identifire" class="col-sm-2 control-label" ><?php echo __('lblselectidentificationtype'); ?>: <span style="color: #ff0000">*</span></label> 
                            <div class="col-sm-2"><?php echo $this->Form->input('identifire_type', array('label' => false, 'id' => 'identifire_type', 'class' => 'form-control input-sm', 'options' => array($identifire_type))); ?></div>
                        </div>
                    </div>
                </div>

<!--                <div class="box-body" id="partytype">
                    <div class="row">
                        <div class="form-group">
                            <label for="party_type_id" class="col-sm-2 control-label"><?php echo __('lblpartytype'); ?></label> 
                            <div class="col-sm-3">
                                <?php //echo $this->Form->input('party_type_id', array('label' => false, 'id' => 'party_type_id', 'class' => 'form-control input-sm', 'options' => array($partytype_name))); ?>
                                <span id="party_type_id_error" class="form-error"><?php //echo $errarr['party_type_id_error'];              ?></span>
                            </div>
                        </div>
                    </div>
                </div>-->
            </div> 

        <?php } else if ($idenfire_disply['conf_reg_bool_info']['conf_bool_value'] == 'Y') { ?>
            <div class="box box-primary">
                <div class="box-body">
                    <div class="row">
                        <div class="form-group">
                            <label for="Identifire" class="col-sm-2 control-label" ><?php echo __('lblselectidentificationtype'); ?>: <span style="color: #ff0000">*</span></label> 
                            <div class="col-sm-2"><?php echo $this->Form->input('identifire_type', array('label' => false, 'id' => 'identifire_type', 'class' => 'form-control input-sm', 'options' => array($identifire_type))); ?></div>
                        </div>
                    </div>
                </div>
            </div> 





        <?php } ?>


        <div class="box box-primary">
            <div class="box-body">
                <div id="identification_fields"></div>
            </div>
        </div>

        <div class="box box-primary">
            <div class="box-body">
                <div class="row center">
                    <div class="form-group">
                        <?php echo $this->Form->input('master_id', array('label' => false, 'type' => 'hidden', 'id' => 'master_id')); ?>
                        <?php echo $this->Form->input('master_office_id', array('label' => false, 'type' => 'hidden', 'id' => 'master_office_id')); ?>
                        <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
                        <button type="submit" id="btnNext" name="btnNext" class="btn btn-info" onclick="javascript: return forcancel();"><?php echo __('btnsave'); ?></button>
                        <button type="submit" id="btnCancel" name="btnCancel" class="btn btn-info" onclick="javascript: return formadd();"><?php echo __('btncancel'); ?></button>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-primary" id="dividentification">
            <div class="box-body">
                <table id="tableidentification" class="table table-striped table-bordered table-hover">  
                    <thead >  
                        <tr>  
                            <th class="center"><?php echo __('lblname'); ?></th>  
                              <th class="center"><?php echo __('Party Type'); ?></th>  
                            <th class="center"><?php echo __('lblidentificationtype'); ?></th>  

                            <th class="center width10"><?php echo __('lblaction'); ?></th>
                        </tr>  
                    </thead>

                    <?php for ($i = 0; $i < count($identification); $i++) { ?>
                        <tr>
                            <td class="tblbigdata"><?php echo $identification[$i][0]['identification_full_name_en']; ?></td> 
                            <td class="tblbigdata"><?php echo $identification[$i][0]['party_type_desc_en']; ?></td> 
                            <td class="tblbigdata"><?php
                                if ($identification[$i][0]['identifire_type'] == 2) {
                                    echo 'SRO';
                                } else {
                                    echo 'Other';
                                }
                                ?></td>

                            <td>

                                <?php if ($identification[$i][0]['identifire_type'] != 2) { ?>
                                    <input type="button" class="btn btn-info" value="<?php echo __('lblbtnedit'); ?>" onclick="edit_ident('<?php echo $identification[$i][0]['id']; ?>', '<?php echo $identification[$i][0]['identifire_type']; ?>');"> 
                                <?php } ?>
                                <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'delete_identifire', $this->Session->read('csrftoken'), $identification[$i][0]['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>



                            </td>
                        </tr>
                    <?php } ?>
                </table> 
                <?php if (!empty($identification)) { ?>
                    <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                    <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
            </div>
        </div>

    </div>
</div>

<input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
<input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
<input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>



<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>

