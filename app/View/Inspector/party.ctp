<?php
echo $this->Html->script('bootstrap-datepicker.min');
echo $this->Html->css('bootstrap-datepicker3.min');
?>

<script type="text/javascript">

    $(document).ready(function () {
         var actiontype = document.getElementById("actiontype").value;
         if (actiontype == '1') {
            $('#divpartydetail').slideDown(1000);
        }
    });
    function formselect (id) {
        document.getElementById("actiontype").value = '1';
            $('#hfid').val(id);
    }
</script>

<?php
echo $this->Html->css('popup');
$tokenval = $this->Session->read("Selectedtoken");
$csrftoken = $this->Session->read('csrftoken');
?>

<?php echo $this->Form->create('party', array('id' => 'party', 'class' => 'form-vertical')); ?>


<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblparty'); ?></h3></center>
            </div>
            <div class="box-body">
                <table id="tableParty" class="table table-striped table-bordered table-condensed">  
                    <thead>  
                        <tr>  

                            <th class="center"><?php echo __('lblpartyname'); ?></th>
                            <th class="center"><?php echo __('lblpartytype'); ?></th>
                            <th class="center"><?php echo __('lblpartycategory'); ?> </th>
                            <th class="center width10"><?php echo __('lblaction'); ?></th>
                        </tr>  
                    </thead>

                    <tr>
                        <?php foreach ($partyrecord as $party_record1): ?>
                            <td ><?php echo $party_record1[0]['party_full_name_'.$lang]; ?></td>
                            <td ><?php echo $party_record1[0]['party_type_desc_'.$lang]; ?></td>
                            <td ><?php echo $party_record1[0]['category_name_'.$lang]; ?></td>
                            <td ><button id="btndelete" name="btndelete" class="btn btn-default "  
                                        onclick="javascript: return formselect(('<?php echo $party_record1[0]['id']; ?>'));">
                                    <span class="glyphicon glyphicon-expand"><?php echo __('lblSelect'); ?></span></button>
                            </td>

                        </tr>


                    <?php endforeach;
                    ?>
                    <?php unset($party_record1); ?>


                </table> 
            </div>
        </div>
        <br>
        <div class="box box-primary" id="divpartydetail" hidden="true">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblpartydetails'); ?></h3></center>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lbltokenno'); ?></label>
                            <div class="col-sm-4"><?php echo $Selectedtoken; ?></div>
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblpartytype'); ?></label>
                            <div class="col-sm-4"><?php echo $party_record[0][0]['party_type_desc_en']; ?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblpresentationexception'); ?></label>
                            <div class="col-sm-4"><?php echo $party_record[0][0]['desc_en']; ?></div>
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblSalutation'); ?></label>
                            <div class="col-sm-4"><?php echo $party_record[0][0]['salutation_desc_en']; ?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblpartyfirstname'); ?>:-</label>
                            <div class="col-sm-4"><?php echo $party_record[0][0]['party_fname_en']; ?></div>
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblpartyfirstname_ll'); ?>:-</label>
                            <div class="col-sm-4"><?php echo $party_record[0][0]['party_fname_ll']; ?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblpartymiddlename'); ?>:-</label>
                            <div class="col-sm-4"><?php echo $party_record[0][0]['party_mname_en']; ?></div>
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblpartymiddlename_ll'); ?>:-</label>
                            <div class="col-sm-4"><?php echo $party_record[0][0]['party_mname_ll']; ?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblpartylastname'); ?>:-</label>
                            <div class="col-sm-4"><?php echo $party_record[0][0]['party_lname_en']; ?></div>
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblpartylastname_ll'); ?>:-</label>
                            <div class="col-sm-4"><?php echo $party_record[0][0]['party_lname_ll']; ?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblpartyfullname'); ?>:-</label>
                            <div class="col-sm-4"><?php echo $party_record[0][0]['party_full_name_en']; ?></div>
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblpartyfullname_ll'); ?>:-</label>
                            <div class="col-sm-4"><?php echo $party_record[0][0]['party_full_name_ll']; ?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblaliasname'); ?>:</label>
                            <div class="col-sm-4"><?php echo $party_record[0][0]['alias_name_en']; ?></div>
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblaliasname_ll'); ?>:-</label>
                            <div class="col-sm-4"><?php echo $party_record[0][0]['alias_name_ll']; ?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblfatherfname'); ?>:-</label>
                            <div class="col-sm-4"><?php echo $party_record[0][0]['father_fname_en']; ?></div>
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblfatherfname_ll'); ?>:-</label>
                            <div class="col-sm-4"><?php echo $party_record[0][0]['father_fname_ll']; ?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblfathermname'); ?>:-</label>
                            <div class="col-sm-4"><?php echo $party_record[0][0]['father_mname_en']; ?></div>
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblfathermname_ll'); ?>:-</label>
                            <div class="col-sm-4"><?php echo $party_record[0][0]['father_mname_ll']; ?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblfatherlname'); ?>:-</label>
                            <div class="col-sm-4"><?php echo $party_record[0][0]['father_lname_en']; ?></div>
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblfatherlname_ll'); ?>:-</label>
                            <div class="col-sm-4"><?php echo $party_record[0][0]['father_lname_ll']; ?></div>
                        </div>
                    </div>
                </div>
               
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblfatherfullname'); ?>:-</label>
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
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblIdentificationmark').'1'; ?></label>
                            <div class="col-sm-4"><?php echo $party_record[0][0]['idetification_mark1_en']; ?></div>
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblidentificationmark1_ll'); ?>:</label>
                            <div class="col-sm-4"><?php echo $party_record[0][0]['idetification_mark1_ll']; ?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblIdentificationmark').'2'; ?></label>
                            <div class="col-sm-4"><?php echo $party_record[0][0]['idetification_mark2_en']; ?></div>
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblidentificationmark2_ll'); ?>:</label>
                            <div class="col-sm-4"><?php echo $party_record[0][0]['idetification_mark2_ll']; ?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblpartycategory'); ?>:</label>
                            <div class="col-sm-4"><?php echo $party_record[0][0]['category_name_'.$lang]; ?></div>
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
                            <div class="col-sm-4"><?php echo $party_record[0][0]['gender_desc_en']; ?></div>
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lbloccupation'); ?>:</label>
                            <div class="col-sm-4"><?php echo $party_record[0][0]['occupation_name_en']; ?></div>
                        </div>
                    </div>
                </div>
                <?php
                     foreach ($pattern_data as $key=>$value) {
                        ?>
                 <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="party_id" class="col-sm-2 control-label"><?php echo $value['pattern']['pattern_desc_en'];?>:</label>
                            <div class="col-sm-4"><?php echo $value['TrnBehavioralPatterns']['field_value_en'];?></div>
                            <label for="party_id" class="col-sm-2 control-label"><?php echo $value['pattern']['pattern_desc_ll'];?>:</label>
                            <div class="col-sm-4"><?php echo $value['TrnBehavioralPatterns']['field_value_ll'];?></div>
                        </div>
                    </div>
                </div>
                     <?php    }           ?>
                
               
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lbladmdistrict'); ?>:</label>
                            <div class="col-sm-4"><?php echo $party_record[0][0]['district_name_en']; ?></div>
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lbladmtaluka'); ?>:</label>
                            <div class="col-sm-4"><?php echo $party_record[0][0]['taluka_name_en']; ?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lbladmvillage'); ?>:</label>
                            <div class="col-sm-4"><?php echo $party_record[0][0]['village_name_en']; ?></div>
                            
                        </div>
                    </div>
                </div>
                
                
            </div>
        </div>

        <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
        <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/><!--
        <input type='hidden' value='<?php // echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
        <input type='hidden' value='<?php // echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
        <input type='hidden' value='' name='propertyid' id='propertyid'/>-->
    </div>
</div>

<?php echo $this->Form->end(); ?>                
<?php echo $this->Js->writeBuffer(); ?>

