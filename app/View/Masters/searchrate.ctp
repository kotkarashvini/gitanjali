<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>
<?php
//echo $this->Html->script('jquery.dataTables');
//echo $this->Html->script('dataTables.bootstrap');
?>

<script type="text/javascript">
    $(document).ready(function () {

        if (document.getElementById('hfhidden1').value == 'Y') {
            $('#divsearchrate').slideDown(1000);
        }
        else {
            $('#divsearchrate').hide();
        }
        $('#tablesearchrate').dataTable({
            "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });

        if (document.getElementById('hfhidden2').value == 'Y') {
            $('#divsearchrate1').slideDown(1000);
        }
        else {
            $('#divsearchrate1').hide();
        }
        $('#tablesearchrate1').dataTable({
            "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });

        $('#district_id').change(function () {
            var district = $("#district_id option:selected").val();
            var token = $("#token").val();
            $.getJSON("get_taluka_name", {district: district, token: token}, function (data)
            {
                var sc = '<option value="">--Select--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#taluka_id option").remove();
                $("#taluka_id").append(sc);
            });
        })

        $('#taluka_id').change(function () {
            var taluka = $("#taluka_id option:selected").val();
            var token = $("#token").val();
            $.getJSON("get_village_name", {taluka: taluka, token: token}, function (data)
            {
                var sc = '<option value="">--Select--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#village_id").prop("disabled", false);
                $("#village_id option").remove();
                $("#village_id").append(sc);
            });
        })

    });

    function formsearch() {
        document.getElementById("actiontype").value = '1';
    }

    function forcancel() {
        document.getElementById("actiontype").value = '2';
    }
</script>

<?php echo $this->Form->create('searchrate', array('id' => 'ratereport', 'autocomplete' => 'off')); ?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
<div class="row">
    <div class="col-lg-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblratesearch'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/admin/searchrate_<?php echo $lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 

            </div>
            <div class="box-body">
                <div class="row">
                    <label for="district_id " class="col-sm-2 control-label"><?php echo __('lbladmdistrict'); ?><span style="color: #ff0000">*</span></label>    
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('district_id', array('label' => false, 'id' => 'district_id', 'class' => 'form-control input-sm', 'options' => array($District), 'empty' => '--Select--')); ?>
                        <span id="district_id_error" class="form-error"><?php //echo $errarr[district_id_error'];         ?></span>
                    </div>
                    <label for="taluka_id " class="col-sm-2 control-label"><?php echo __('lbladmtaluka'); ?><span style="color: #ff0000">*</span></label>    
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('taluka_id', array('label' => false, 'id' => 'taluka_id', 'class' => 'form-control input-sm', 'options' => array($taluka), 'empty' => '--Select--')); ?>
                        <span id="taluka_id_error" class="form-error"><?php //echo $errarr[taluka_id_error'];         ?></span>
                    </div>
                    <label for="village_id " class="col-sm-2 control-label"><?php echo __('lbladmvillage'); ?><span style="color: #ff0000">*</span></label>    
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('village_id', array('label' => false, 'id' => 'village_id', 'class' => 'form-control input-sm', 'options' => array($village), 'empty' => '--Select--')); ?>
                        <span id="village_id_error" class="form-error"><?php //echo $errarr[village_id_error'];         ?></span>
                    </div>
                </div>

                <div class="rowht"></div><div class="rowht"></div><div class="rowht"></div>
                <div class="row center">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <button id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript: return formsearch();">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp; <?php echo __('lblsearch'); ?></button> 
                            <button id="btnadd" name="btncancel" class="btn btn-info "  onclick="javascript: return forcancel();">
                                <span class="glyphicon glyphicon-floppy-remove"></span>&nbsp;&nbsp; <?php echo __('btncancel'); ?></button>
                        </div>
                    </div>
                </div><br>
            </div>
        </div>
        <div class="box box-primary">
            <div class="box-body" id="divsearchrate">

                <table id="tablesearchrate" class="table table-striped table-bordered table-hover">  
                    <thead >  
                        <tr>
                            <th class="center"> <?php echo __('lbladmvillage'); ?></th>
                            <th class="center"> <?php echo __('lbldellandtype'); ?></th>
                            <th class="center"><?php echo __('lbllevel'); ?></th>
                            <th class="center"> <?php echo __('lbllistdesc'); ?></th> 
                            <th class="center"> <?php echo __('lblusamaincat'); ?></th>
                            <th class="center"><?php echo __('lblsubcat'); ?></th>
                            <th class="center"><?php echo __('lblsubccategory'); ?></th>
                            <th class="center width10"><?php echo __('lblrate'); ?></th>
                        </tr>  
                    </thead>

                    <?php for ($i = 0; $i < count($grid); $i++) { ?>
                        <tr>
                            <td class="tblbigdata"><?php echo $grid[$i][0]['village_name_' . $lang]; ?></td>
                            <td class="tblbigdata"><?php echo $grid[$i][0]['developed_land_types_desc_' . $lang]; ?></td>
                            <td class="tblbigdata"><?php echo $grid[$i][0]['level_1_desc_' . $lang]; ?></td>
                            <td class="tblbigdata"><?php echo $grid[$i][0]['list_1_desc_' . $lang]; ?></td>
                            <td class="tblbigdata"><?php echo $grid[$i][0]['usage_main_catg_desc_' . $lang]; ?></td>
                            <td class="tblbigdata"><?php echo $grid[$i][0]['usage_sub_catg_desc_' . $lang]; ?></td>
                            <td class="tblbigdata"><?php echo $grid[$i][0]['usage_sub_sub_catg_desc_' . $lang]; ?></td>
                            <td ><?php echo $grid[$i][0]['prop_rate']; ?></td>
                        </tr>
                    <?php } ?>
                </table> 
                <?php if (!empty($grid)) { ?>
                    <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                    <input type="hidden" value="N" id="hfhidden1"/><?php } ?>

            </div>
            <div class="box-body" id="divsearchrate1">

                <table id="tablesearchrate1" class="table table-striped table-bordered table-hover">  
                    <thead >  
                        <tr>  
                            <th class="center"><?php echo __('lbladmtaluka'); ?> </th>
                            <th class="center"><?php echo __('lbldellandtype'); ?> </th>
                            <th class="center"><?php echo __('lblusamaincat'); ?> </th>
                            <th class="center"><?php echo __('lblsubcat'); ?></th>
                            <th class="center"><?php echo __('lblsubccategory'); ?></th>
                            <th class="center"><?php echo __('lblvalzone'); ?></th>
                            <th class="center"><?php echo __('lblvalsubzone'); ?></th>
                            <th class="center"><?php echo __('lblrate'); ?></th>
                        </tr>  
                    </thead>

                    <?php for ($i = 0; $i < count($grid1); $i++) { ?>
                        <tr>
                            <td ><?php echo $grid1[$i][0]['taluka_name_' . $lang]; ?></td>
                            <td ><?php echo $grid1[$i][0]['developed_land_types_desc_' . $lang]; ?></td>
                            <td ><?php echo $grid1[$i][0]['usage_main_catg_desc_' . $lang]; ?></td>
                            <td ><?php echo $grid1[$i][0]['usage_sub_catg_desc_' . $lang]; ?></td>
                            <td ><?php echo $grid1[$i][0]['usage_sub_sub_catg_desc_' . $lang]; ?></td>
                            <td ><?php echo $grid1[$i][0]['valuation_zone_desc_' . $lang]; ?></td>
                            <td ><?php echo $grid1[$i][0]['subzone_desc']; ?></td>
                            <td ><?php echo $grid1[$i][0]['prop_rate']; ?></td>
                        </tr>
                    <?php } ?>
                </table> 
                <?php if (!empty($grid1)) { ?>
                    <input type="hidden" value="Y" id="hfhidden2"/><?php } else { ?>
                    <input type="hidden" value="N" id="hfhidden2"/><?php } ?>

            </div>
        </div>

    </div>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
</div>

<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>




