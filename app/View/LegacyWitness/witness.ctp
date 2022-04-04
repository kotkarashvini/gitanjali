<script type="text/javascript">
    $(document).ready(function () {

        $.post(host + 'LegacyWitness/get_witness_feilds', {csrftoken: csrftoken}, function (fields)
        {
            if (fields) {
                $("#witness_fields").html(fields);
                $(document).trigger('_page_ready');
                show_data_messages();
                show_error_messages();
            } else {
                window.location.href = "<?php echo $this->webroot; ?>LegacyWitness/witness/<?php echo $this->Session->read('csrftoken'); ?>";
                            }
                        });
                    });

                    var host = '<?php echo $this->webroot; ?>';
                    var nameformat = '<?php echo $name_format ?>';
                    var lang = '<?php echo $laug ?>';
                    var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;


                    function edit_witness(id)
                    {
                        $.post(host + 'LegacyWitness/get_witness_feilds', {id: id, csrftoken: csrftoken}, function (data)
                        {
                            $("#witness_fields").html(data);

                            $('#hfid').val(id);
                            $('#hfupdateflag').val('Y');

                            if ($('#village_id').length && $("#village_id option:selected").val() != '') {
                                var village_id = $("#village_id option:selected").val();
                                $.post(host + 'Citizenentry/behavioral_patterns', {ref_id: 3, behavioral_id: 2, village_id: village_id, ref_val: id, csrftoken: csrftoken}, function (data1)
                                {

                                    $('.partyaddress').html(data1);
                                    $(document).trigger('_page_ready');
                                    show_data_messages();
                                    show_error_messages();
                                });
                            } else {
                                $(document).trigger('_page_ready');
                                show_data_messages();
                                show_error_messages();
                            }

                        });
                    }
                    function formdelete(id) {
                        var result = confirm("Are you sure you want to delete this record?");
                        $('#hfid').val(id);

                        if (result) {
                            $.post('<?php echo $this->webroot; ?>LegacyWitness/delete_witness', {id: id, csrftoken: csrftoken}, function (data1)
                            {

                                if (data1.trim() == 1)
                                {
                                    alert('Witness deleted successfully');
                                    window.location.href = "<?php echo $this->webroot; ?>LegacyWitness/witness/<?php echo $this->Session->read('csrftoken'); ?>";
                                                    } else
                                                    {
                                                        alert('Error');
                                                    }
                                                });
                                            } else {
                                                return false;
                                            }
                                        }
                                        function forcancel() {
                                            document.getElementById("actiontype").value = '2';
                                            window.location.href = "<?php echo $this->webroot; ?>LegacyWitness/witness/<?php echo $this->Session->read('csrftoken'); ?>";
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
echo $this->Html->css('popup');
$tokenval = $this->Session->read("Leg_Selectedtoken");

echo $this->Form->create('witness', array('id' => 'witness', 'class' => 'form-vertical', 'autocomplete' => 'off'));
echo $this->element("Registration/main_menu");
echo $this->element("Citizenentry/property_menu");
$doc_lang = $this->Session->read('doc_lang');
echo $this->element("Helper/jqueryhelper");
?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblwitnesshead'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/citizenentry/witness_<?php echo $doc_lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
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
                                            <?php echo $this->Form->input('', array('label' => false, 'id' => '', 'value' => $Leg_Selectedtoken, 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?>
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
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>

        <div class="box box-primary">
            <div class="box-body">
                <div id="witness_fields"></div>
            </div>
        </div>

        <div class="box box-primary">
            <div class="box-body">
                <div class="row center">
                    <div class="form-group">
                        <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
                        <button type="submit" id="btnCancel" name="btnCancel" class="btn btn-info" onclick="javascript: return formadd();"><?php echo __('btnsave'); ?></button>
                        <input type="button" id="btnNext" name="btnNext" class="btn btn-info" value="<?php echo __('btncancel'); ?>" onclick="javascript: return forcancel();">
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-primary" id="divwitness">
            <div class="box-body">
                <table id="tablewitness" class="table table-striped table-bordered table-hover">  
                    <thead >  
                        <tr>  
                            <th class="center"><?php echo __('lblname'); ?></th>

                            <th class="center width10"><?php echo __('lblaction'); ?></th>
                        </tr>  
                    </thead>
                    <tbody>
                        <?php for ($i = 0; $i < count($witness); $i++) { ?>
                            <tr>
                                <td class="tblbigdata"><?php echo $witness[$i][0]['witness_full_name_en']; ?></td>

                                <td >
                                    <input type="button" class="btn btn-info" value="<?php echo __('lblbtnedit'); ?>" onclick="edit_witness('<?php echo $witness[$i][0]['id']; ?>');"> 

                                    <a <?php echo $this->Html->Link("Delete", array('action' => 'delete_witness', $this->Session->read('csrftoken'), $witness[$i][0]['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-info"), array('Are you sure?')); ?></a>

                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table> 
                <?php if (!empty($witness)) { ?>
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

