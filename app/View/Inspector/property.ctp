<?php
$tokenval = $this->Session->read("Selectedtoken");
//echo $this->Form->create('property', array('id' => 'property','Controller'=>'Inspector','action'=>'property'));
//echo $this->Form->create('property', array('url' => array('controller'=>'Inspector', 'action'=>'property',$tokenval1),'id' => 'property'));

?>

<script>
    $(document).ready(function () {
//        var actiontype = document.getElementById("actiontype").value;
        var actiontype = "<?php echo $actiontype; ?>";
        if (actiontype == '1') {
            $('#divpropertydetail').slideDown(1000);
        }
    });
    function formselect(id) {
//        alert("hi"); return false;
        document.getElementById("actiontype").value = '1';
        $('#hfid').val(id);
        $('#property').submit();
    }

    function formsave(id,token) {
$('#hfid').val(id);
$('#hftoken').val(token);

        document.getElementById("actiontype").value = '2';
        $('#property').submit();
    }
    function formfinalsave() {
        document.getElementById("actiontype").value = '3';
        $('#property1').submit();
    }
</script>
<?PHP // PR($vflag);EXIT; ?>
<div class="row" id="listproperty">
    <div class="col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title headbolder"><?php echo __('lbllistofproperties'); ?></h3>
            </div>

            <div class="box-body">
                <div class="table-responsive">
                    <table id="prop_list_tbl" class="table table-striped table-bordered table-hover"> 
                        <thead >
                            <tr >
                                <th class="center">   <?php echo __('lblpropertydetails'); ?>  </th>
                                <th class="center">   <?php echo __('lbllocation'); ?> </th>
                                <th class="center"> <?php echo __('lblusage'); ?></th>
                                <th class="center">   <?php echo __('lblaction'); ?> </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($property_list as $key => $property) { ?>
                                <tr>
                                    <td>
                                        <?php
                                        $prop_name = "";
                                        foreach ($property_pattern as $key1 => $pattern) {
                                            if ($property[0]['property_id'] == $pattern[0]['mapping_ref_val']) {
                                                $prop_name.= "  " . $pattern[0]['pattern_desc_' . $lang] . " : <small>" . $pattern[0]['field_value_' . $lang] . "</small><br>";
                                            }
                                        }

                                        echo substr($prop_name, 1);
                                        ?>
                                    </td>
                                    <td>
                                        <?php echo $property[0]['village_name_' . $lang]; ?>
                                    </td>
                                    <td>
                                        <?php echo $property[0]['evalrule_desc_' . $lang]; ?>
                                    </td>
                                    <td><?php
                                        if (!empty($checkremark)) {
                                            for ($j = 0; $j < count($checkremark); $j++) {
//                                                pr($checkremark[$j][0]['property_no']);pr($property[0]['id']);
                                                if ($checkremark[$j][0]['property_no'] == $property[0]['id']) {
                                                    $btnname = "Checked";
                                                    break;
                                                } else {
                                                    $btnname = "Add Remark";
                                                }
                                            }
                                        } else {
                                            $btnname = "Add Remark";
                                        }
                                        ?>
                                        <!--<input type="button" class="btn btn-primary" value='<?php // echo $btnname; ?>' onclick="javascript: return formselect('<?php echo $property[0]['id']; ?>');">-->
                                        <a href="<?php  echo $this->request->webroot.'/Inspector/property/'.$tokenval1.'/'.$property[0]['id']; ?>" class = "btn btn-default"><?php echo $btnname; ?></a>
                                        <!--<a <?php // echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'btn btn-primary')), array('action' => 'property', $property[0]['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>-->
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div><br>
        
       <?php 
       echo $this->Form->create('property1', array('url' => array('controller'=>'Inspector', 'action'=>'property',$tokenval1),'id' => 'property1')); 
        echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); 
        ?>
        <?php if ($divfinal == 'Y') { ?> 
            <div class="box box-primary">
                <div class="box-body"><br>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <div class="col-sm-2"></div>
                                <label for="prohibition_end_flag" class="control-label col-sm-4"><?php echo __('lblverificompleted'); ?> </label>            
                                <div class="col-sm-4"><?php echo $this->Form->input('finalflag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => '', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'finalflag')); ?></div> 
                            </div>
                        </div>
                    </div><br>
                    <div class="row center">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <input type="button" name="save" value="<?php echo __('lblfinalsave'); ?>" class="btn btn-info" id ="btnSave" onclick="javascript: return formfinalsave();" >
                                <!--<a href="<?php //  echo $this->request->webroot.'/Inspector/property/'.$tokenval1.'/'.'000000'; ?>" class = "btn btn-default"><?php echo __('lblfinalsave'); ?></a>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div><br> <?php } ?>
            <?php echo $this->Form->end(); ?>
            <?php
            echo $this->Form->create('property', array('url' => array('controller'=>'Inspector', 'action'=>'property',$tokenval1),'id' => 'property')); 
             echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); 
             ?>
        <div class="box box-primary" id="divpropertydetail" hidden="true">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblpropertydetails'); ?></h3></center>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblfineyer'); ?>:-</label>
                            <div class="col-sm-4"><?php echo $property_record[0][0]['finyear_desc']; ?></div>
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lbladmdistrict'); ?>:-</label>
                            <div class="col-sm-4"><?php echo $property_record[0][0]['district_name_' . $lang]; ?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lbladmtaluka'); ?>:-</label>
                            <div class="col-sm-4"><?php echo $property_record[0][0]['taluka_name_' . $lang]; ?></div>
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblcorporation'); ?>:-</label>
                            <div class="col-sm-4"><?php echo $property_record[0][0]['governingbody_name_' . $lang]; ?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblcityvillage1'); ?>:-</label>
                            <div class="col-sm-4"><?php echo $property_record[0][0]['village_name_' . $lang]; ?></div>
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lbllocation'); ?>:-</label>
                            <div class="col-sm-4"><?php echo $property_record[0][0]['level_1_desc_' . $lang]; ?> ===> <?php echo $property_record[0][0]['list_1_desc_' . $lang]; ?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblusagecategory'); ?>:-</label>
                            <div class="col-sm-8"><?php echo $property_record[0][0]['usage_main_catg_desc_' . $lang]; ?> ===> <?php echo $property_record[0][0]['usage_sub_catg_desc_' . $lang]; ?> ===> <?php echo $property_record[0][0]['usage_sub_sub_catg_desc_' . $lang]; ?></div>

                        </div>
                    </div>
                </div>

                <?php
                foreach ($pattern_data as $key => $value) {
                    ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="party_id" class="col-sm-2 control-label"><?php echo $value['pattern']['pattern_desc_en']; ?>:</label>
                                <div class="col-sm-4"><?php echo $value['TrnBehavioralPatterns']['field_value_en']; ?></div>
                                <label for="party_id" class="col-sm-2 control-label"><?php echo $value['pattern']['pattern_desc_ll']; ?>:</label>
                                <div class="col-sm-4"><?php echo $value['TrnBehavioralPatterns']['field_value_ll']; ?></div>
                            </div>
                        </div>
                    </div>
                <?php } ?>





                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lbluniquepropnu'); ?>:-</label>
                            <div class="col-sm-4"><?php echo $property_record[0][0]['unique_property_no_en']; ?></div>
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('unique_property_no_ll'); ?>:-</label>
                            <div class="col-sm-4"><?php echo $property_record[0][0]['unique_property_no_ll']; ?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblboundryeast'); ?>:-</label>
                            <div class="col-sm-4"><?php echo $property_record[0][0]['boundries_east_en']; ?></div>
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('boundries_east_ll'); ?>:-</label>
                            <div class="col-sm-4"><?php echo $property_record[0][0]['boundries_east_ll']; ?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblboundrywest'); ?>:-</label>
                            <div class="col-sm-4"><?php echo $property_record[0][0]['boundries_west_en']; ?></div>
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('boundries_west_ll'); ?>:-</label>
                            <div class="col-sm-4"><?php echo $property_record[0][0]['boundries_west_ll']; ?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblboundriessouth'); ?>:-</label>
                            <div class="col-sm-4"><?php echo $property_record[0][0]['boundries_south_en']; ?></div>
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('boundries_south_ll'); ?>:-</label>
                            <div class="col-sm-4"><?php echo $property_record[0][0]['boundries_south_ll']; ?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblboundriesnorth'); ?>:</label>
                            <div class="col-sm-4"><?php echo $property_record[0][0]['boundries_north_ll']; ?></div>
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('boundries_north_ll'); ?>:-</label>
                            <div class="col-sm-4"><?php echo $property_record[0][0]['boundries_north_en']; ?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblremark'); ?>:-</label>
                            <div class="col-sm-4"><?php echo $property_record[0][0]['remark_en']; ?></div>
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('remark_ll'); ?>:-</label>
                            <div class="col-sm-4"><?php echo $property_record[0][0]['remark_ll']; ?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lbladditionalinfo'); ?>:-</label>
                            <div class="col-sm-4"><?php echo $property_record[0][0]['additional_information_en']; ?></div>
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lbladditionalinfo_ll'); ?>:-</label>
                            <div class="col-sm-4"><?php echo $property_record[0][0]['additional_information_ll']; ?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblfinalremark'); ?>:</label>
                            <div class="col-sm-4">
                                <?php echo $this->Form->input('remark', array('label' => false, 'id' => 'remark', 'value' => $remark, 'class' => 'form-control input-sm', 'type' => 'textarea')) ?>
                             <span id="remark_error" class="form-error"><?php echo $errarr['remark_error']; ?></span>
                            </div>
                            <label for="prohibition_end_flag" class="control-label col-sm-2"><?php echo __('lblaccepted'); ?> </label>            
                            <div class="col-sm-4">
                                <?php echo $this->Form->input('verified_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => $vflag, 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'verified_flag')); ?>
                            <span id="verified_flag_error" class="form-error"><?php echo $errarr['verified_flag_error']; ?></span>
                            </div> 
                        </div>
                    </div>
                </div><br>
                <div class="row center">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <input type="button" name="save" value="<?php echo __('btnsave'); ?>" class="btn btn-info" id ="btnSave" onclick="javascript: return formsave('<?php echo $property_record[0][0]['id']; ?>','<?php echo $tokenval1; ?>');" >
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
        <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
         <input type='hidden' value='<?php echo $hftoken; ?>' name='hftoken' id='hftoken'/>
    </div> 
</div> 

<?php echo $this->Form->end(); ?>
