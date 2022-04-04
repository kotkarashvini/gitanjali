<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>
<!--<script>
    function PopIt() {
        return "Are you sure you want to leave?";
    }
    function UnPopIt() { /* nothing to return */
    }

    $(document).ready(function () {
        window.onbeforeunload = PopIt;
        $("a").click(function () {
            window.onbeforeunload = UnPopIt;
        });
    });
</script>-->
<?php
echo $this->Html->script('jquery.dataTables');
echo $this->Html->script('dataTables.bootstrap');
?>
<script>
    $(document).ready(function () {
        if ($('#hfhidden1').val() == 'Y')
        {
            $('#tabledivisionnew').dataTable({
                "iDisplayLength": 10,
                "aLengthMenu": [[10, 15, -1], [10, 15, "All"]]
            });
        } else {
            $('#tabledivisionnew').dataTable({
                "iDisplayLength": 10,
                "aLengthMenu": [[10, 15, -1], [10, 15, "All"]]
            });
        }

        $('#district_id').change(function () {
            var district = $("#district_id option:selected").val();
            var token = $("#token").val();
            $.getJSON("get_taluka_name", {district: district, token: token}, function (data)
            {
                var sc = '<option value="empty">--Select--</option>';
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
                var sc = '<option value="empty">--Select--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#village_id").prop("disabled", false);
                $("#village_id option").remove();
                $("#village_id").append(sc);
            });
        })

    });

    function formadd() {

        document.getElementById("actiontype").value = '1';
        document.getElementById("hfaction").value = 'S';
    }

    function formupdate(level_1_desc_en, surveynotype_id, village_id, id) {
        $("#village_id").prop("disabled", false);
        $('#level_1_desc_en').val(level_1_desc_en);
        $('#surveynotype_id').val(surveynotype_id);
        $('#village_id').val(village_id);
        $('#hfid').val(id);
        $('#hfupdateflag').val('Y');
        $('#btnadd').html('Save');
        return false;
    }

    function formdelete(id) {

        document.getElementById("actiontype").value = '3';
        document.getElementById("hfid").value = id;
    }
</script> 

<?php echo $this->Form->create('level1', array('id' => 'level1', 'autocomplete' => 'off')); ?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading" style="text-align: center"><b><?php echo __('lblLevel1'); ?></b></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="district_id" class="col-sm-2 control-label"><?php echo __('lbladmdistrict'); ?><span style="color: #ff0000">*</span></label> 
                            <div class="col-sm-2">
                                <?php echo $this->Form->input('district_id', array('label' => false, 'id' => 'district_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $District))); ?>
                            </div>
                            <label for="taluka_id" class="col-sm-2 control-label"><?php echo __('lbladmtaluka'); ?><span style="color: #ff0000">*</span></label> 
                            <div class="col-sm-2">
                                <?php echo $this->Form->input('taluka_id', array('label' => false, 'id' => 'taluka_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--'))); ?>
                            </div>
                            <label for="village_id" class="col-sm-2 control-label"><?php echo __('lbladmvillage'); ?><span style="color: #ff0000">*</span></label> 
                            <div class="col-sm-2">
                                <?php echo $this->Form->input('village_id', array('options' => array($village), 'empty' => '--select--', 'id' => 'village_id', 'class' => 'form-control input-sm', 'label' => false, 'disabled' => 'disabled')); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="level_1_desc_en" class="col-sm-2 control-label"><?php echo __('lblLevel1'); ?><span style="color: #ff0000">*</span></label>    
                            <div class="col-sm-2">
                                <?php echo $this->Form->input('level_1_desc_en', array('label' => false, 'id' => 'level_1_desc_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            </div>
                            <label for="surveynotype_id" class="col-sm-2 control-label">Survey/Door No Details<span style="color: #ff0000">*</span></label>    
                            <div class="col-sm-2">
                                <?php echo $this->Form->input('surveynotype_id', array('options' => array($surveyno), 'empty' => '--select--', 'id' => 'surveynotype_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                            </div>
                            <div class="col-sm-2 tdselect">
                                <button id="btnadd" name="btnadd" class="btn btn-primary " style="text-align: center;"  onclick="javascript: return formadd();">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;<?php echo __('lblbtnAdd'); ?></button>
                            </div>
                            <div class="col-sm-2 tdsave" hidden="true">
                                <button id="btnadd" name="btnadd" class="btn btn-primary " style="text-align: center;"   onclick="javascript: return formadd();">
                                    <span class="glyphicon glyphicon-floppy-saved"></span>&nbsp;<?php echo __('btnsave'); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-heading" style="text-align: center"><b><?php echo __('lblLevel1'); ?></b></div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="tabledivisionnew" class="table table-striped table-bordered table-hover">  
                        <thead >  
                            <tr>  
                                <td style="text-align: center;width: 10%"><b><?php echo __('lbladmstate'); ?></b></td>
                                <td style="text-align: center; width: 8%"><b>Village</b></td>
                                <td style="text-align: center;"><b><?php echo __('lblLevel1'); ?>&nbsp;<?php echo __('lbllevelname'); ?></b></td>
                                <td style="text-align: center; width: 8%"><b>Survey/Door Details</b></td>
                                <td style="text-align: center; width: 8%"><b><?php echo __('lblaction'); ?></b></td>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php for ($i = 0; $i < count($level1record); $i++) { ?>
                                <tr>
                                    <td style="text-align: center"><?php echo $state; ?></td>
                                    <td style="text-align: center;"><?php echo $level1record[$i][0]['village_name_' . $laug]; ?></td>
                                    <td style="text-align: center;"><?php echo $level1record[$i][0]['level_1_desc_' . $laug]; ?></td>
                                    <td style="text-align: center;"><?php echo $level1record[$i][0]['surveynotype_desc_' . $laug]; ?></td>
                                    <td style="text-align: center;">
                                        <button id="btnupdate" name="btnupdate" class="btn btn-default " style="text-align: center;" 
                                                onclick="javascript: return formupdate(
                                                                                    ('<?php echo $level1record[$i][0]['level_1_desc_' . $laug]; ?>'),
                                                                                    ('<?php echo $level1record[$i][0]['surveynotype_id']; ?>'),
                                                                                    ('<?php echo $level1record[$i][0]['village_id']; ?>'),
                                                                                    ('<?php echo $level1record[$i][0]['id']; ?>'));">
                                            <span class="glyphicon glyphicon-pencil"></span></button>

                                        <button id="btndelete" name="btndelete" class="btn btn-default " style="text-align: center;" 
                                                onclick="javascript: return formdelete(('<?php echo $level1record[$i][0]['id']; ?>'));">
                                            <span class="glyphicon glyphicon-remove"></span></button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <?php
                    if (!empty($level1record)) {
                        ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
                </div>
            </div>


        </div>
    </div>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
</div>


<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>

