<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>

<?php
echo $this->element("Helper/jqueryhelper");
?> 
<script type="text/javascript">
    $(document).ready(function () {

        if (document.getElementById('hfhidden1').value == 'Y') {
            $('#divitemrate').slideDown(1000);
        } else {
            $('#divitemrate').hide();
        }
        $('#tableitemrate').dataTable({
            "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });

        $("input[name='data[itemrate][finyear_flag]']").click(function () {
            var rname = $(this).attr('name');
            if (this.value == 'Y' && rname == "data[itemrate][finyear_flag]") {
                $('#divfinyear').show();
            } else {
                $('#divfinyear').hide();
            }
        });

        $("input[name='data[itemrate][district_flag]']").click(function () {
            var rname = $(this).attr('name');
            if (this.value == 'Y' && rname == "data[itemrate][district_flag]") {
                $('#divdist').show();
            } else {
                $('#divdist').hide();
            }
        });
        $("input[name='data[itemrate][division_flag]']").click(function () {
            var rname = $(this).attr('name');
            if (this.value == 'Y' && rname == "data[itemrate][division_flag]") {
                $('#divdiv').show();
            } else {
                $('#divdiv').hide();
            }
        });
        $("input[name='data[itemrate][taluka_flag]']").click(function () {
            var rname = $(this).attr('name');
            if (this.value == 'Y' && rname == "data[itemrate][taluka_flag]") {
                $('#divtal').show();
            } else {
                $('#divtal').hide();
            }
        });
        $("input[name='data[itemrate][village_flag]']").click(function () {
            var rname = $(this).attr('name');
            if (this.value == 'Y' && rname == "data[itemrate][village_flag]") {
                $('#divvil').show();
            } else {
                $('#divvil').hide();
            }
        });
        $("input[name='data[itemrate][developed_land_types_flag]']").click(function () {
            var rname = $(this).attr('name');
            if (this.value == 'Y' && rname == "data[itemrate][developed_land_types_flag]") {
                $('#divdevl').show();
            } else {
                $('#divdevl').hide();
            }
        });
        $("input[name='data[itemrate][valutation_zone_flag]']").click(function () {
            var rname = $(this).attr('name');
            if (this.value == 'Y' && rname == "data[itemrate][valutation_zone_flag]") {
                $('#divvalzone').show();
            } else {
                $('#divvalzone').hide();
            }
        });



        // checkbox check event

//        $("#conffeetpe11 input:checkbox").change(function () {
//            var ischecked = $(this).is(':checked');
//            var id = $(this).val();
//            if (!ischecked) {
////                      alert('uncheckd ' + $(this).val());
//                $('#paramter_value' + id).prop("disabled", true);
//                $('#paramter_value' + id).val('');
//            }
//            if (ischecked) {
//                $('#paramter_value' + id).removeAttr('disabled');
//            }
//        });
//
    });

    function formadd() {
        document.getElementById("actiontype").value = '1';
    }

    function forcancel() {
        document.getElementById("actiontype").value = '2';
    }

    function formupdate(id, finyear_id, item_rate, usage_param_id, district_id,
            division_id, taluka_id, developed_land_types_id, village_id, valutation_zone_id, finyear_flag, district_flag,
            division_flag, taluka_flag, valutation_zone_flag, village_flag, developed_land_types_flag) {
//alert('hii');return false;
        $("input:radio").attr("checked", false);
        $('input[name="data[itemrate][finyear_flag]"][value="' + finyear_flag + '"]').prop('checked', 'checked');
        $('input[name="data[itemrate][district_flag]"][value="' + district_flag + '"]').prop('checked', 'checked');
        $('input[name="data[itemrate][division_flag]"][value="' + division_flag + '"]').prop('checked', 'checked');
        $('input[name="data[itemrate][taluka_flag]"][value="' + taluka_flag + '"]').prop('checked', 'checked');
        $('input[name="data[itemrate][valutation_zone_flag]"][value="' + valutation_zone_flag + '"]').prop('checked', 'checked');
        $('input[name="data[itemrate][village_flag]"][value="' + village_flag + '"]').prop('checked', 'checked');
        $('input[name="data[itemrate][developed_land_types_flag]"][value="' + developed_land_types_flag + '"]').prop('checked', 'checked');

        if (finyear_flag == 'Y') {
            $("#divfinyear").show();
            $('#finyear_id').val(finyear_id);
        } else {
            $('#finyear_id').val('');
            $("#divfinyear").hide();
        }
        if (district_flag == 'Y') {
            $("#divdist").show();
            $('#district_id').val(district_id);
        } else {
            $('#district_id').val('');
            $("#divdist").hide();
        }
        if (division_flag == 'Y') {
            $("#divdiv").show();
            $('#division_id').val(division_id);
        } else {
            $('#division_id').val('');
            $("#divdiv").hide();
        }
        if (taluka_flag == 'Y') {
            $("#divtal").show();
            $('#taluka_id').val(taluka_id);
        } else {
            $('#taluka_id').val('');
            $("#divtal").hide();
        }
        if (valutation_zone_flag == 'Y') {
            $("#divvalzone").show();
            $('#valutation_zone_id').val(valutation_zone_id);
        } else {
            $('#valutation_zone_id').val('');
            $("#divvalzone").hide();
        }
        if (village_flag == 'Y') {
            $("#divvil").show();
            $('#village_id').val(village_id);
        } else {
            $('#village_id').val('');
            $("#divvil").hide();
        }
        if (developed_land_types_flag == 'Y') {
            $("#divdevl").show();
            $('#developed_land_types_id').val(developed_land_types_id);
        } else {
            $('#developed_land_types_id').val('');
            $("#divdevl").hide();
        }
        $('#item_rate').val(item_rate);
        $('#usage_param_id').val(usage_param_id);
        $('#hfid').val(id);
        $('#hfupdateflag').val('Y');
        $('#btnadd').html('Save');
        return false;
    }

//    function formdelete(id) {
//        var result = confirm("Are you sure you want to delete this record?");
//        if (result) {
//            document.getElementById("actiontype").value = '3';
//            $('#hfid').val(id);
//        } else {
//            return false;
//        }
//    }
</script>

<?php echo $this->Form->create('itemrate', array('id' => 'itemrate', 'autocomplete' => 'off')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblitemrate'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Item Rate/itemrate_<?php echo $language; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-2">
                            <label for="usage_param_id" class="control-label"><?php echo __('lblitemlist'); ?></label>
                        </div>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('usage_param_id', array('options' => array($itemlist), 'empty' => '--select--', 'id' => 'usage_param_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                            <span id="usage_param_id_error" class="form-error"><?php echo $errarr['usage_param_id_error']; ?></span>   
                        </div>
                        <div class="col-sm-2">
                            <label for="item_rate" class="control-label"><?php echo __('lblitemrate'); ?></label>
                        </div>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('item_rate', array('label' => false, 'id' => 'item_rate', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="item_rate_error" class="form-error"><?php echo $errarr['item_rate_error']; ?></span>
                        </div>
                        <div  class="col-sm-2">&nbsp;</div>
                    </div>
                </div>

                <div  class="rowht"></div>

                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-2">
                            <label for="lblfineyer" class="control-label"><?php echo __('lblfineyer'); ?></label>
                        </div>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('finyear_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'Y', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'finyear_flag')); ?>
                            <span id="finyear_flag_error" class="form-error"><?php echo $errarr['finyear_flag_error']; ?></span>

                        </div> 
                        <div class="col-sm-2"></div>
                        <div class="col-sm-2" id="divfinyear">
                            <?php echo $this->Form->input('finyear_id', array('options' => array($finyear), 'id' => 'finyear_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                            <span id="finyear_id_error" class="form-error"><?php echo $errarr['finyear_id_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-2">
                            <label for="district_id" class="control-label"><?php echo __('lbladmdistrict'); ?></label>
                        </div>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('district_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'district_flag')); ?>
                            <span id="district_flag_error" class="form-error"><?php echo $errarr['district_flag_error']; ?></span>
                        </div> 
                        <div class="col-sm-2" hidden="true" id="divdist">
                            <?php echo $this->Form->input('district_id', array('options' => $districtdata, 'empty' => '--select--', 'id' => 'district_id', 'class' => 'form-control input-sm', 'label' => false)); ?>                            
                            <span id="district_id_error" class="form-error"><?php //echo $errarr['district_id_error'];     ?></span>
                        </div>
                        <div class="col-sm-2">
                            <label for="division_id" class="control-label"><?php echo __('lbladmdivision'); ?></label>
                        </div>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('division_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'division_flag')); ?>
                            <span id="division_flag_error" class="form-error"><?php echo $errarr['division_flag_error']; ?></span>
                        </div>
                        <div class="col-sm-2" hidden="true" id="divdiv">
                            <?php echo $this->Form->input('division_id', array('options' => array($divisiondata), 'empty' => '--select--', 'id' => 'division_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                            <span id="division_id_error" class="form-error"><?php //echo $errarr['division_id_error'];     ?></span>
                        </div>
                    </div>
                </div>                
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-2">
                            <label for="taluka_id" class="control-label"><?php echo __('lbladmtaluka'); ?></label>
                        </div>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('taluka_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'taluka_flag')); ?>
                            <span id="taluka_flag_error" class="form-error"><?php echo $errarr['taluka_flag_error']; ?></span>
                        </div>
                        <div class="col-sm-2" hidden="true" id="divtal">
                            <?php echo $this->Form->input('taluka_id', array('options' => $taluka, 'empty' => '--select--', 'id' => 'taluka_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                            <span id="taluka_id_error" class="form-error"><?php //echo $errarr['taluka_id_error'];     ?></span>
                        </div>
                        <div class="col-sm-2" >
                            <label for="village_id" class="control-label"><?php echo __('lbladmvillage'); ?></label>
                        </div>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('village_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'village_flag')); ?>
                            <span id="village_flag_error" class="form-error"><?php echo $errarr['village_flag_error']; ?></span>
                        </div>
                        <div class="col-sm-2" hidden="true" id="divvil">
                            <?php echo $this->Form->input('village_id', array('options' => $village, 'empty' => '--select--', 'id' => 'village_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                            <span id="village_id_error" class="form-error"><?php //echo $errarr['village_id_error'];     ?></span>

                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>

                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-2" >
                            <label for="lblLandType" class="control-label" ><?php echo __('lbldellandtype'); ?></label>                              
                        </div> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('developed_land_types_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'developed_land_types_flag')); ?>
                            <span id="developed_land_types_flag_error" class="form-error"><?php echo $errarr['developed_land_types_flag_error']; ?></span>
                        </div>
                        <div class="col-sm-2" hidden="true" id="divdevl">
                            <?php echo $this->Form->input('developed_land_types_id', array('options' => array($Developedland), 'empty' => '--select--', 'id' => 'developed_land_types_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                            <span id="developed_land_types_id_error" class="form-error"><?php //echo $errarr['developed_land_types_id_error'];     ?></span>
                        </div>
                        <div class="col-sm-2">
                            <label for="valutation_zone_id" class="control-label"><?php echo __('lblvalzone'); ?></label>
                        </div>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('valutation_zone_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'valutation_zone_flag')); ?>
                            <span id="valutation_zone_flag_error" class="form-error"><?php echo $errarr['valutation_zone_flag_error']; ?></span>
                        </div>
                        <div class="col-sm-2" hidden="true" id="divvalzone">
                            <?php echo $this->Form->input('valutation_zone_id', array('options' => array($valuationzone), 'empty' => '--select--', 'id' => 'valutation_zone_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                            <span id="valutation_zone_id_error" class="form-error"><?php //echo $errarr['valutation_zone_id_error'];         ?></span>
                        </div>
                    </div>
                </div>


                <div  class="rowht"></div> <div  class="rowht"></div> <div  class="rowht"></div>
                <div class="row center">
                    <div class="form-group">
                        <div class="col-sm-12 tdselect">
                            <button id="btnadd" name="btnadd" class="btn btn-info "  onclick="javascript: return formadd();">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp; <?php echo __('lblbtnAdd'); ?></button>
                            <button id="btnadd" name="btncancel" class="btn btn-info "  onclick="javascript: return forcancel();">
                                <span class="glyphicon glyphicon-floppy-remove"></span>&nbsp;&nbsp; <?php echo __('btncancel'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-primary">

            <div class="box-body" id="divitemrate">
                    <table id="tableitemrate" class="table table-striped table-bordered table-hover">  
                        <thead >  
                            <tr>  
                                <th class="center"><?php echo __('lblitem'); ?></th>
                                <th class="center"><?php echo __('lblitemrate'); ?></th>
                                <!--<th class="center"><?php echo __('lbldependantupon'); ?></th>-->
                                <th class="center width10"><?php echo __('lblaction'); ?></th>
                            </tr>  
                        </thead>

                        <?php for ($i = 0; $i < count($itemrate); $i++) { ?>
                            <tr>
                                <td ><?php echo $itemrate[$i][0]['usage_param_desc_' . $language]; ?></td>
                                <td ><?php echo $itemrate[$i][0]['item_rate']; ?></td>
    <!--                                <td >
                                    //<?php
//                                    $attr_name = "";
//                                    $k = 1;
//                                    $ids = "";
//                                    $val = "";
//                                    for ($j = 0; $j < count($attribute); $j++) {
//                                        if ($itemrate[$i][0]['prohibited_id'] == $attribute[$j][0]['prohibited_id']) {
//                                            $attr_name.= " " . "$k ) " . $attribute[$j][0]['eri_attribute_name'] . " = " . $attribute[$j][0]['paramter_value'] . "<br>";
//                                            $ids.="," . $attribute[$j][0]['paramter_id'];
//                                            $val.=",'" . $attribute[$j][0]['paramter_value'] . "'";
//                                            $k++;
//                                        }
//                                    }
//                                    echo substr($attr_name, 1);
//                                    
                                ?>
                                </td>-->

                                <td >
                                    <button id="btnupdate" name="btnupdate" class="btn btn-default "  onclick="javascript: return formupdate(
                                                        ('<?php echo $itemrate[$i][0]['id']; ?>'),
                                                        ('<?php echo $itemrate[$i][0]['finyear_id']; ?>'),
                                                        ('<?php echo $itemrate[$i][0]['item_rate']; ?>'),
                                                        ('<?php echo $itemrate[$i][0]['usage_param_id']; ?>'),
                                                        ('<?php echo $itemrate[$i][0]['district_id']; ?>'),
                                                        ('<?php echo $itemrate[$i][0]['division_id']; ?>'),
                                                        ('<?php echo $itemrate[$i][0]['taluka_id']; ?>'),
                                                        ('<?php echo $itemrate[$i][0]['developed_land_types_id']; ?>'),
                                                        ('<?php echo $itemrate[$i][0]['village_id']; ?>'),
                                                        ('<?php echo $itemrate[$i][0]['valutation_zone_id']; ?>'),
                                                        ('<?php echo $itemrate[$i][0]['finyear_flag']; ?>'),
                                                        ('<?php echo $itemrate[$i][0]['district_flag']; ?>'),
                                                        ('<?php echo $itemrate[$i][0]['division_flag']; ?>'),
                                                        ('<?php echo $itemrate[$i][0]['taluka_flag']; ?>'),
                                                        ('<?php echo $itemrate[$i][0]['valutation_zone_flag']; ?>'),
                                                        ('<?php echo $itemrate[$i][0]['village_flag']; ?>'),
                                                        ('<?php echo $itemrate[$i][0]['developed_land_types_flag']; ?>'));">
                                        <span class="glyphicon glyphicon-pencil"></span></button>
                                    <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'itemrate_delete', $itemrate[$i][0]['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
                                </td>
                            </tr>
                        <?php } ?>
                    </table> 
                    <?php if (!empty($itemrate)) { ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
            </div>
        </div>


    </div>
    <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>

</div>

<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>




