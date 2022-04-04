<script>
    $(document).ready(function () {
        var hfupdateflag = "<?php echo $hfupdateflag; ?>";
        if (hfupdateflag === 'Y')
        {
            $('#btnadd').html('Save');
        }
        $('#tableratesearch').dataTable({
            "iDisplayLength": 10,
            "aLengthMenu": [[10, 15, -1], [10, 15, "All"]]
        });
    });
</script>  
<script>
    function formadd() {
        document.getElementById("hfaction").value = 'S';
        document.getElementById("actiontype").value = '1';
    }
    function formupdate(developed_land_types_id, village_id, usage_main_catg_id, usage_sub_catg_id, usage_sub_sub_catg_id, valutation_zone_id, valutation_subzone_id, taluka_id, district_id,
            division_id, ulb_type_id, construction_type_id, road_vicinity_id, usage_main_cat_id, search_id) {
//alert(usage_main_cat_id);
        document.getElementById("actiontype").value = '1';
        $('#developed_land_types_id').val(developed_land_types_id);
        $('#village_id').val(village_id);
        $('#usage_main_catg_id').val(usage_main_catg_id);
        $('#usage_sub_catg_id').val(usage_sub_catg_id);
        $('#usage_sub_sub_catg_id').val(usage_sub_sub_catg_id);
        $('#valutation_zone_id').val(valutation_zone_id);
        $('#valutation_subzone_id').val(valutation_subzone_id);
        $('#taluka_id').val(taluka_id);
        $('#district_id').val(district_id);
        $('#division_id').val(division_id);
        $('#ulb_type_id').val(ulb_type_id);
        $('#construction_type_id').val(construction_type_id);
        $('#road_vicinity_id').val(road_vicinity_id);
        $('#usage_main_cat_id').val(usage_main_cat_id);
        $('#hfsearch_id').val(search_id);
        $('#btnadd').html('Save');
        $('#hfupdateflag').val('Y');
//        $('input:radio[name="data[RateSearch][developed_land_types_id]"][value=' + developed_land_types_id + ']').attr('checked', true);
        $('input:radio[name="data[RateSearch][village_id]"][value=' + village_id + ']').attr('checked', true);
        $('input:radio[name="data[RateSearch][usage_main_catg_id]"][value=' + usage_main_catg_id + ']').attr('checked', true);
        $('input:radio[name="data[RateSearch][usage_sub_catg_id]"][value=' + usage_sub_catg_id + ']').attr('checked', true);
        $('input:radio[name="data[RateSearch][usage_sub_sub_catg_id]"][value=' + usage_sub_sub_catg_id + ']').attr('checked', true);
        $('input:radio[name="data[RateSearch][valutation_zone_id]"][value=' + valutation_zone_id + ']').attr('checked', true);
        $('input:radio[name="data[RateSearch][valutation_subzone_id]"][value=' + valutation_subzone_id + ']').attr('checked', true);
        $('input:radio[name="data[RateSearch][taluka_id]"][value=' + taluka_id + ']').attr('checked', true);
        $('input:radio[name="data[RateSearch][district_id]"][value=' + district_id + ']').attr('checked', true);
        $('input:radio[name="data[RateSearch][division_id]"][value=' + division_id + ']').attr('checked', true);
        $('input:radio[name="data[RateSearch][ulb_type_id]"][value=' + ulb_type_id + ']').attr('checked', true);
        $('input:radio[name="data[RateSearch][construction_type_id]"][value=' + construction_type_id + ']').attr('checked', true);
        $('input:radio[name="data[RateSearch][road_vicinity_id]"][value=' + road_vicinity_id + ']').attr('checked', true);
    }
    function formcancel() {
        document.getElementById("actiontype").value = '3';

    }
</script>

<?php echo $this->Form->create('RateSearch', array('id' => 'RateSearch', 'autocomplete' => 'off')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblratesearchrule'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Rate Search Rule/ratesearch_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-1"></div>
                        <label for="developed_land_types_id" class="col-sm-3 control-label"><?php echo __('lbldellandtype'); ?><span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('developed_land_types_id', array('label' => false, 'id' => 'developed_land_types_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $Developedlandtypedata))); ?>
                            <span id="developed_land_types_id_error" class="form-error"><?php echo $errarr['developed_land_types_id_error']; ?></span>
                        </div>
                        <label for="usage_main_catg_id" class="col-sm-3 control-label"><?php echo __('lblusagecategory'); ?>:<span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('usage_main_cat_id', array('label' => false, 'id' => 'usage_main_cat_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $Usagemainmaindata))); ?>
                            <span id="usage_main_cat_id_error" class="form-error"><?php echo $errarr['usage_main_cat_id_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-1"></div>
                        <label for="village_id" class="control-label col-sm-3"><?php echo __('lbladmvillage'); ?></label>            
                        <div class="col-sm-2"> <?php echo $this->Form->input('village_id', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'village_id')); ?></div> 
                        <label for="usage_main_catg_id" class="control-label col-sm-3"><?php echo __('lblusamaincat'); ?></label>            
                        <div class="col-sm-2"> <?php echo $this->Form->input('usage_main_catg_id', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'usage_main_catg_id')); ?></div>                        
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-1"></div>
                        <label for="usage_sub_catg_id" class="control-label col-sm-3"><?php echo __('lblsubcat'); ?></label>            
                        <div class="col-sm-2"> <?php echo $this->Form->input('usage_sub_catg_id', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'usage_sub_catg_id')); ?></div> 
                        <label for="usage_sub_sub_catg_id" class="control-label col-sm-3"><?php echo __('lblsubccategory'); ?></label>            
                        <div class="col-sm-2"> <?php echo $this->Form->input('usage_sub_sub_catg_id', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'usage_sub_sub_catg_id')); ?></div>                        
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-1"></div>
                        <label for="valutation_zone_id" class="control-label col-sm-3"><?php echo __('lblvalzone'); ?></label>            
                        <div class="col-sm-2"> <?php echo $this->Form->input('valutation_zone_id', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'valutation_zone_id')); ?></div> 
                        <label for="valutation_subzone_id" class="control-label col-sm-3"><?php echo __('lblvalsubzone'); ?></label>            
                        <div class="col-sm-2"> <?php echo $this->Form->input('valutation_subzone_id', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'valutation_subzone_id')); ?></div>                        
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-1"></div>
                        <label for="taluka_id" class="control-label col-sm-3"><?php echo __('lbladmtaluka'); ?></label>            
                        <div class="col-sm-2"> <?php echo $this->Form->input('taluka_id', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'taluka_id')); ?></div>                        
                        <label for="district_id" class="control-label col-sm-3"><?php echo __('lbladmdistrict'); ?></label>            
                        <div class="col-sm-2"> <?php echo $this->Form->input('district_id', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'district_id')); ?></div> 
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-1"></div>
                        <label for="division_id" class="control-label col-sm-3"><?php echo __('lbladmdivision'); ?></label>            
                        <div class="col-sm-2"> <?php echo $this->Form->input('division_id', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'division_id')); ?></div>                        
                        <label for="ulb_type_id" class="control-label col-sm-3"><?php echo __('lblulbtypeid'); ?></label>            
                        <div class="col-sm-2"> <?php echo $this->Form->input('ulb_type_id', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'ulb_type_id')); ?></div> 
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-1"></div>
                        <label for="construction_type_id" class="control-label col-sm-3"><?php echo __('lblconstuctiontye'); ?></label>            
                        <div class="col-sm-2"> <?php echo $this->Form->input('construction_type_id', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'construction_type_id')); ?></div>                        
                        <label for="road_vicinity_id" class="control-label col-sm-3"><?php echo __('lblroadvicinity'); ?></label>            
                        <div class="col-sm-2"> <?php echo $this->Form->input('road_vicinity_id', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;', 'N' => '&nbsp;No'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'road_vicinity_id')); ?></div> 
                    </div>
                </div>

                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center">
                    <div class="form-group">
                        <button id="btnadd"type="submit" name="btnadd" class="btn btn-info" onclick="javascript: return formadd();">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?>
                        </button>
                        <button id="btncancel" name="btncancel" class="btn btn-info "onclick="javascript: return formcancel();">
                            <span class="glyphicon glyphicon-remove"></span>&nbsp;&nbsp; <?php echo __('btncancel'); ?></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-primary">

            <div class="box-body">
                <div id="selectratesearch" class="table-responsive">
                    <table id="tableratesearch" class="table table-striped table-bordered table-hover">
                        <thead >  
                            <tr>  
                                <th class="center"><?php echo __('lbldellandtype'); ?></th>
                                <th class="center"><?php echo __('lbladmvillage'); ?></th>
                                <th class="center"><?php echo __('lblusamaincat'); ?></th>
                                <th class="center"><?php echo __('lblsubcat'); ?></th>
                                <th class="center"><?php echo __('lblsubccategory'); ?></th>
                                <th class="center"><?php echo __('lblvalzone'); ?></th>
                                <th class="center"><?php echo __('lblvalsubzone'); ?></th>
                                <th class="center"><?php echo __('lbladmtaluka'); ?></th>
                                <th class="center"><?php echo __('lbladmdistrict'); ?></th>
                                <th class="center"><?php echo __('lbladmdivision'); ?></th>
                                <th class="center"><?php echo __('lblulbtypeid'); ?></th>
                                <th class="center"><?php echo __('lblconstuctiontye'); ?></th>
                                <th class="center"><?php echo __('lblroadvicinity'); ?></th>
                                <th class="center"><?php echo __('lblusagecategory'); ?></th>

                                <th class="center width10"><?php echo __('lblaction'); ?></th>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php foreach ($ratesearch as $ratesearch1): ?>
                                <tr>
                                    <td class="tblbigdata"><?php echo $ratesearch1['0']['developed_land_types_desc_en']; ?></td>
                                    <td class="tblbigdata"><?php echo $ratesearch1['0']['village_id']; ?></td>
                                    <td class="tblbigdata"><?php echo $ratesearch1['0']['usage_main_catg_id']; ?></td>
                                    <td class="tblbigdata"><?php echo $ratesearch1['0']['usage_sub_catg_id']; ?></td>
                                    <td class="tblbigdata"><?php echo $ratesearch1['0']['usage_sub_sub_catg_id']; ?></td>
                                    <td class="tblbigdata"><?php echo $ratesearch1['0']['valutation_zone_id']; ?></td>
                                    <td class="tblbigdata"><?php echo $ratesearch1['0']['valutation_subzone_id']; ?></td>
                                    <td class="tblbigdata"><?php echo $ratesearch1['0']['taluka_id']; ?></td>
                                    <td class="tblbigdata"><?php echo $ratesearch1['0']['district_id']; ?></td>

                                    <td class="tblbigdata"><?php echo $ratesearch1['0']['division_id']; ?></td>
                                    <td class="tblbigdata"><?php echo $ratesearch1['0']['ulb_type_id']; ?></td>
                                    <td class="tblbigdata"><?php echo $ratesearch1['0']['construction_type_id']; ?></td>
                                    <td class="tblbigdata"><?php echo $ratesearch1['0']['road_vicinity_id']; ?></td>
                                    <td class="tblbigdata"><?php echo $ratesearch1['0']['usage_main_catg_desc_en']; ?></td>

                                    <td class="width10">
                                        <button id="btnupdate" name="btnupdate" type="button" data-toggle="tooltip" title="Edit"  class="btn btn-default " style="text-align: center;"  onclick="javascript: return formupdate(
                                                            ('<?php echo $ratesearch1['0']['developed_land_types_id']; ?>'),
                                                            ('<?php echo $ratesearch1['0']['village_id']; ?>'),
                                                            ('<?php echo $ratesearch1['0']['usage_main_catg_id']; ?>'),
                                                            ('<?php echo $ratesearch1['0']['usage_sub_catg_id']; ?>'),
                                                            ('<?php echo $ratesearch1['0']['usage_sub_sub_catg_id']; ?>'),
                                                            ('<?php echo $ratesearch1['0']['valutation_zone_id']; ?>'),
                                                            ('<?php echo $ratesearch1['0']['valutation_subzone_id']; ?>'),
                                                            ('<?php echo $ratesearch1['0']['taluka_id']; ?>'),
                                                            ('<?php echo $ratesearch1['0']['district_id']; ?>'),
                                                            ('<?php echo $ratesearch1['0']['division_id']; ?>'),
                                                            ('<?php echo $ratesearch1['0']['ulb_type_id']; ?>'),
                                                            ('<?php echo $ratesearch1['0']['construction_type_id']; ?>'),
                                                            ('<?php echo $ratesearch1['0']['road_vicinity_id']; ?>'),
                                                            ('<?php echo $ratesearch1['0']['usage_main_cat_id']; ?>'),
                                                            ('<?php echo $ratesearch1['0']['search_id']; ?>'));">
                                            <span class="glyphicon glyphicon-pencil"></span>
                                        </button>

                                        <!--                                        <button id="btndelete" name="btndelete" class="btn btn-default " style="text-align: center;" 
                                                                                        onclick="javascript: return formdelete(('<?php echo $ratesearch1['0']['search_id']; ?>'));">
                                                                                    <span class="glyphicon glyphicon-remove"></span>
                                                                                </button>-->
                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'ratesearch_delete', $ratesearch1['0']['search_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
                                    </td>

                                <?php endforeach; ?>
                                <?php unset($ratesearch1); ?>
                        </tbody>
                    </table>
                    <?php if (!empty($ratesearch)) { ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
                </div>
            </div>
        </div>

    </div>
    <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
    <input type='hidden' value='<?php echo $hfsearch_id; ?>' name='hfsearch_id' id='hfsearch_id'/>
    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
</div>