<script>
    $(document).ready(function () {
        if ($('#hfhidden1').val() == 'Y')
        {
            $('#divpartydetail').slideDown(1000);
        } else {
            $('#divpartydetail').hide();
        }

        $('#from,#to,#curfrom,#curto').datepicker({
            todayBtn: "linked",
            language: "it",
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy"
        });


        $('#party_last_name').keyup(function () {
            var from = $("#from").val();
            var to = $("#to").val();
            var party_name = $('#party_last_name').val();
            if (this.value.length < 4)
            {
                return false;
            }
            else
            {
                $.post('<?php echo $this->webroot; ?>Citizenentry/get_party_byname', {from: from, to: to, last_name: party_name}, function (data)
                {
//                    alert(data);

                    $('#get_party_byname').html(data);

                });

            }
        });
        var actiontype = document.getElementById('actiontype').value;
        if (actiontype == '1') {

            var from = $("#from").val();
            var to = $("#to").val();
            var party_name = $('#party_last_name').val();
            if (party_name.length < 4)
            {
                return false;
            }
            else
            {
                $.post('<?php echo $this->webroot; ?>Citizenentry/get_party_byname', {from: from, to: to, last_name: party_name}, function (data)
                {
                    $('#get_party_byname').html(data);
                });
            }
        }

    });
    function formview(token, id) {
        document.getElementById("actiontype").value = '1';
        $('#hfid').val(id);
        $('#hftoken').val(token);
        $('#doc_search').submit;
    }

</script>
<?php $doc_lang = $this->Session->read('doc_lang'); ?> 
<?php echo $this->Form->create('doc_search', array('id' => 'doc_search', 'autocomplete' => 'off')); 
 $language = $this->Session->read("sess_langauge");
?>

<?php echo $this->element("Citizenentry/main_menu"); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lbldocsearch'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/citizenentry/search_partyname_<?php echo $doc_lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div>
            </div>

            <div class="box-body">
                <div class="row" id="bydate">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="" class="col-sm-2 control-label"><?php echo __('lblfromdate'); ?></label>    
                        <div class="col-sm-2" ><?php echo $this->Form->input('from', array('label' => false, 'id' => 'from', 'class' => 'form-control input-sm', 'type' => 'text')); ?></div>
                        <label for="" class="col-sm-2 control-label"><?php echo __('lbltodate'); ?></label>    
                        <div class="col-sm-2" ><?php echo $this->Form->input('to', array('label' => false, 'id' => 'to', 'class' => 'form-control input-sm', 'data-date-format' => "mm/dd/yyyy", 'type' => 'text')); ?></div>

                    </div>
                </div>

                <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
                <div class="row" id="token">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="" class="col-sm-2 control-label"><?php echo __('lblpartylastname'); ?></label>    
                        <div class="col-sm-2" ><?php echo $this->Form->input('party_last_name', array('label' => false, 'id' => 'party_last_name', 'class' => 'form-control input-sm', 'type' => 'text')); ?></div>

                    </div>
                </div>
                <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
                <div id="get_party_byname">

                </div>
            </div>
        </div>

    </div>
</div>
<?php if (!empty($documentrecord)) { ?>
    <div class="box box-primary">
        <div class="form-group">
            <div class="tab-content">
                <div id="home" class="tab-pane fade in active">

                    <table id="tablegeninfo" class="table table-striped table-bordered table-hover">  
                        <thead>  
                            <tr> 
                                <th class="center"><?php echo __('lblsrno'); ?></th>
                                <th class="center"><?php echo __('lbldocrno'); ?></th>
                                <th class="center"><?php echo __('lblarticlename'); ?></th>
                                <th class="center"><?php echo __('lblsummery1'); ?></th>
                                <th class="center"><?php echo __('lblsummery2'); ?></th>

                            </tr>  
                        </thead>
                        <?php
                        $i = 1;
                        foreach ($documentrecord as $rec) {
                            ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $rec[0]['doc_reg_no']; ?></td>
                                <td><?php echo $rec[0]['article_desc_'.$language]; ?></td>
                                <td>  <a type="button" href="<?php echo $this->webroot; ?>viewRegSummary1/<?php echo $rec[0]['token_no']; ?>/I" class="btn btn-warning btn-xs pull-left"  data-toggle="modal" data-target="#myModal_rpt">View Summery 1</a></td>
                                <td><a type="button" href="<?php echo $this->webroot; ?>viewRegSummary2/<?php echo $rec[0]['token_no']; ?>/I" class="btn btn-warning btn-xs pull-left"  data-toggle="modal" data-target="#myModal_rpt2">View Summery 2</a></td>

                            </tr>
                        <?php } ?>

                    </table> 
                </div>

            </div>
        </div>
    </div>
<?php } ?>

<input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
<input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
<input type='hidden' value='<?php echo $hftoken; ?>' name='hftoken' id='hftoken'/>

<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>



<div id="myModal_rpt" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo __('lblsummery'); ?></h4>
            </div>
            <div class="modal-body" id="rpt_modal_body">
                <p>Loading ...... Please Wait!</p>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
            </div>
        </div>

    </div>
</div>

<div id="myModal_rpt2" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo __('lblsummery'); ?></h4>
            </div>
            <div class="modal-body" id="rpt_modal_body">
                <p>Loading ...... Please Wait!</p>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
            </div>
        </div>

    </div>
</div>
<div class="box box-primary" id="divpartydetail" hidden="true">
    <div class="box-header with-border">
        <center><h3 class="box-title headbolder"><?php echo __('lblpartydetails'); ?></h3></center>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="party_id" class="col-sm-2 control-label"><?php echo __('lbltokenno'); ?></label>
                    <div class="col-sm-4"><?php echo $hftoken; ?></div>
                    <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblpartytype'); ?></label>
                    <div class="col-sm-4"><?php echo $party_record[0][0]['party_type_desc_'.$language]; ?></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblpresentationexception'); ?></label>
                    <div class="col-sm-4"><?php echo $party_record[0][0]['desc_'.$language]; ?></div>
                    <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblSalutation'); ?></label>
                    <div class="col-sm-4"><?php echo $party_record[0][0]['salutation_desc_'.$language]; ?></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblpartyfirstname'); ?>[ENGLISH]:-</label>
                    <div class="col-sm-4"><?php echo $party_record[0][0]['party_fname_en']; ?></div>
                    <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblpartyfirstname'); ?>:-</label>
                    <div class="col-sm-4"><?php echo $party_record[0][0]['party_fname_ll']; ?></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblpartymiddlename'); ?> [ENGLISH`]:-</label>
                    <div class="col-sm-4"><?php echo $party_record[0][0]['party_mname_en']; ?></div>
                    <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblpartymiddlename'); ?>:-</label>
                    <div class="col-sm-4"><?php echo $party_record[0][0]['party_mname_ll']; ?></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblpartylastname'); ?> [ENGLISH]:-</label>
                    <div class="col-sm-4"><?php echo $party_record[0][0]['party_lname_en']; ?></div>
                    <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblpartylastname'); ?>:-</label>
                    <div class="col-sm-4"><?php echo $party_record[0][0]['party_lname_ll']; ?></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblpartyfullname'); ?> [ENGLISH]:-</label>
                    <div class="col-sm-4"><?php echo $party_record[0][0]['party_full_name_en']; ?></div>
                    <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblpartyfullname'); ?>:-</label>
                    <div class="col-sm-4"><?php echo $party_record[0][0]['party_full_name_ll']; ?></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblaliasname'); ?> [English]:</label>
                    <div class="col-sm-4"><?php echo $party_record[0][0]['alias_name_en']; ?></div>
                    <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblaliasname'); ?>:-</label>
                    <div class="col-sm-4"><?php echo $party_record[0][0]['alias_name_ll']; ?></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblfatherfname'); ?> [ENGLISH]:-</label>
                    <div class="col-sm-4"><?php echo $party_record[0][0]['father_fname_en']; ?></div>
                    <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblfatherfname_ll'); ?> :-</label>
                    <div class="col-sm-4"><?php echo $party_record[0][0]['father_fname_ll']; ?></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblfathermname'); ?> [ENGLISH]:-</label>
                    <div class="col-sm-4"><?php echo $party_record[0][0]['father_mname_en']; ?></div>
                    <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblfathermname_ll'); ?>:-</label>
                    <div class="col-sm-4"><?php echo $party_record[0][0]['father_mname_ll']; ?></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblfatherlname'); ?> [ENGLISH]:-</label>
                    <div class="col-sm-4"><?php echo $party_record[0][0]['father_lname_en']; ?></div>
                    <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblfatherlname_ll'); ?>:-</label>
                    <div class="col-sm-4"><?php echo $party_record[0][0]['father_lname_ll']; ?></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblfatherfullname'); ?>[ENGLISH]:-</label>
                    <div class="col-sm-4"><?php echo $party_record[0][0]['father_full_name_en']; ?></div>
                    <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblfatherfullname_ll'); ?>:-</label>
                    <div class="col-sm-4"><?php echo $party_record[0][0]['father_full_name_ll']; ?></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="party_id" class="col-sm-2 control-label"><?php echo __('lbldob'); ?></label>
                    <div class="col-sm-4"><?php echo $party_record[0][0]['dob']; ?></div>
                    <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblage'); ?>:</label>
                    <div class="col-sm-4"><?php echo $party_record[0][0]['age']; ?></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblIdentificationmark') . '1'; ?></label>
                    <div class="col-sm-4"><?php echo $party_record[0][0]['idetification_mark1_en']; ?></div>
                    <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblidentificationmark1'); ?>:</label>
                    <div class="col-sm-4"><?php echo $party_record[0][0]['idetification_mark1_ll']; ?></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblIdentificationmark') . '2'; ?></label>
                    <div class="col-sm-4"><?php echo $party_record[0][0]['idetification_mark2_en']; ?></div>
                    <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblidentificationmark2'); ?>:</label>
                    <div class="col-sm-4"><?php echo $party_record[0][0]['idetification_mark2_ll']; ?></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblpartycategory'); ?>:</label>
                    <div class="col-sm-4"><?php echo $party_record[0][0]['category_name_' . $lang]; ?></div>
                    <label for="party_id" class="col-sm-2 control-label"><?php echo __('lbluid'); ?>:</label>
                    <div class="col-sm-4"><?php echo $party_record[0][0]['uid']; ?></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblidentity'); ?>:</label>
                    <div class="col-sm-4"><?php echo $party_record[0][0]['idntity']; ?></div>
                    <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblidentitydetails') ?>:</label>
                    <div class="col-sm-4"><?php echo $party_record[0][0]['identificationtype_desc_en']; ?></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblfid'); ?>:</label>
                    <div class="col-sm-4"><?php echo $party_record[0][0]['fid']; ?></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblemailid'); ?>:</label>
                    <div class="col-sm-4"><?php echo $party_record[0][0]['email_id']; ?></div>
                    <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblmobileno'); ?>:</label>
                    <div class="col-sm-4"><?php echo $party_record[0][0]['mobile_no']; ?></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblgender'); ?>:</label>
                    <div class="col-sm-4"><?php echo $party_record[0][0]['gender_desc_'.$language]; ?></div>
                    <label for="party_id" class="col-sm-2 control-label"><?php echo __('lbloccupation'); ?>:</label>
                    <div class="col-sm-4"><?php echo $party_record[0][0]['occupation_name_'.$language]; ?></div>
                </div>
            </div>
        </div>
        <?php
        foreach ($pattern_data as $key => $value) {
            ?>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="party_id" class="col-sm-2 control-label"><?php echo $value['pattern']['pattern_desc_'.$language]; ?>:</label>
                        <div class="col-sm-4"><?php echo $value['TrnBehavioralPatterns']['field_value_'.$language]; ?></div>
                        <label for="party_id" class="col-sm-2 control-label"><?php echo $value['pattern']['pattern_desc_ll']; ?>:</label>
                        <div class="col-sm-4"><?php echo $value['TrnBehavioralPatterns']['field_value_ll']; ?></div>
                    </div>
                </div>
            </div>
        <?php } ?>


        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="party_id" class="col-sm-2 control-label"><?php echo __('lbladmdistrict'); ?>:</label>
                    <div class="col-sm-4"><?php echo $party_record[0][0]['district_name_'.$language]; ?></div>
                    <label for="party_id" class="col-sm-2 control-label"><?php echo __('lbladmtaluka'); ?>:</label>
                    <div class="col-sm-4"><?php echo $party_record[0][0]['taluka_name_'.$language]; ?></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="party_id" class="col-sm-2 control-label"><?php echo __('lbladmvillage'); ?>:</label>
                    <div class="col-sm-4"><?php echo $party_record[0][0]['village_name_'.$language]; ?></div>

                </div>
            </div>
        </div>

        <?php if (!empty($party_record)) { ?>
            <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
            <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
    </div>
</div>
