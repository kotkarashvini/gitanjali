<script type="text/javascript">
    $(document).ready(function () {

        $('#tabledoc').dataTable({
            "bPaginate": false,
            "ordering": true
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

        $('#btn_delete').click(function () {
            var myCheckboxes = new Array();

            if ($('input:checkbox:checked').length > 0) {
                $('input:checkbox:checked').each(function () {
                    myCheckboxes.push($(this).attr('id'));
                });

                $.ajax({
                    type: "POST",
                    url: "<?php echo $this->webroot; ?>Masters/delete_prohibited_entry",
                    data: {'myCheckboxes': myCheckboxes},
                    success: function (data) {
                        if (data == 1)
                        {
                            alert('Records Deleted !!!');
                            window.location.reload();
                            return false;
                        } else {
                            alert('Select atleast one record!!!');
                        }
                    }
                });
            } else {
                alert("No records found!!!");
            }
        });
    });

    function formsearch() {
        document.getElementById("actiontype").value = '1';
    }

    function forcancel() {
        document.getElementById("actiontype").value = '2';
    }

    function formupdate(id, survey_no, pid, prohid) {
        alert("If you have selected Khata Number for update, then all related Plot Numbers will be updated with new Khata Number...!!!");
        $('#survey_no').focus();
        document.getElementById("hfaction").value = 'U';
        $('#hfid').val(id);
        $('#survey_no').val(survey_no);
        $('#hfparamid').val(pid);
        $('#hfprohid').val(prohid);
        $('#hfupdateflag').val('Y');
        $('#btnadd').html('Save');
        return false;
    }
</script>

<style>
    .table-responsive
    {
        overflow-y:auto;
        height:400px;
    }
</style>

<?php echo $this->Form->create('prohibited_property_edtdel', array('id' => 'prohibited_property_edtdel', 'autocomplete' => 'off')); ?>

<div class="row">
    <div class="col-lg-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('Prohibited Property (Khata/Plot) Updation'); ?></h3></center>
            </div>
            <div class="box-body">
                <div class="row">
                    <label for="district_id " class="col-sm-2 control-label"><?php echo __('Select District'); ?><span style="color: #ff0000">*</span></label>    
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('district_id', array('label' => false, 'id' => 'district_id', 'class' => 'form-control input-sm', 'options' => array($District), 'empty' => '--Select--')); ?>
                        <span id="district_id_error" class="form-error"><?php //echo $errarr[district_id_error'];                    ?></span>
                    </div>
                    <label for="taluka_id " class="col-sm-2 control-label"><?php echo __('Select Taluka'); ?><span style="color: #ff0000">*</span></label>    
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('taluka_id', array('label' => false, 'id' => 'taluka_id', 'class' => 'form-control input-sm', 'options' => array($taluka), 'empty' => '--Select--')); ?>
                        <span id="taluka_id_error" class="form-error"><?php //echo $errarr[taluka_id_error'];                    ?></span>
                    </div>
                    <label for="village_id " class="col-sm-2 control-label"><?php echo __('Select Village'); ?><span style="color: #ff0000">*</span></label>    
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('village_id', array('label' => false, 'id' => 'village_id', 'class' => 'form-control input-sm', 'options' => array($village), 'empty' => '--Select--')); ?>
                        <span id="village_id_error" class="form-error"><?php //echo $errarr[village_id_error'];                    ?></span>
                    </div>
                </div>
                <div class="rowht"></div><div class="rowht"></div>
                <div class="row">
                    <label for="survey_no" class="col-sm-2 control-label">&nbsp;<?php echo __('Survey No.'); ?></label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('survey_no', array('label' => false, 'id' => 'survey_no', 'class' => 'form-control input-sm', 'type' => 'text')); ?>
                        <span id="survey_no_error" class="form-error"><?php //echo $errarr['survey_no_error'];                 ?></span> 
                    </div>
<!--                     <label for="plot_no" class="col-sm-2 control-label">&nbsp;<?php //echo __('Plot No.'); ?></label>
                    <div class="col-sm-2">
                    <?php //echo $this->Form->input('plot_no', array('label' => false, 'id' => 'plot_no', 'class' => 'form-control input-sm', 'type' => 'text')); ?>
                        <span id="plot_no_error" class="form-error"><?php //echo $errarr['plot_no_error'];                 ?></span> 
                    </div>-->
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
                </div>
            </div>
        </div>
    </div>
</div>
<?php if (!empty($grid1)) { ?>
    <div class="box box-primary">
        <div class="box-body">

            <div id="selectdocument" class="table-responsive">
                <table id="tabledoc" class="table table-striped table-bordered table-hover">  
                    <thead >  
                        <tr>
                            <!--<th class="center"><?php //echo __('Id Primary'); ?></th>-->
                            <th class="center"><?php echo __('District Name'); ?></th>
                            <th class="center"><?php echo __('Taluka Name'); ?></th>
                            <th class="center"><?php echo __('Village Name'); ?></th>
                            <th class="center"><?php echo __('Survey Number'); ?></th>
                            <th class="center"><?php echo __('Attribute Name'); ?></th>
                            <th class="center"><?php echo __('Edit'); ?></th>
                            <th class="center"><?php echo __('Delete'); ?></th>
                        </tr>  
                    </thead>
                    <?php foreach ($grid1 as $res) : { ?>
                            <tr>
                                <!--<td ><?php // echo $res[0]['id']; ?></td>-->
                                <td ><?php echo $res[0]['district_name_en']; ?></td>
                                <td ><?php echo $res[0]['taluka_name_en']; ?></td>
                                <td ><?php echo $res[0]['village_name_en']; ?></td>
                                <td ><?php echo $res[0]['survey_no']; ?></td>
                                <td ><?php echo $res[0]['eri_attribute_name']; ?></td>
                                <td ><button id="btnupdate" name="btnupdate" type="button"  data-toggle="tooltip" title="Edit" class="btn btn-default " onclick="javascript: return formupdate(
                                                ('<?php echo $res[0]['id']; ?>'), ('<?php echo $res[0]['survey_no']; ?>'),
                                                ('<?php echo $res[0]['paramter_id']; ?>'), ('<?php echo $res[0]['prohibited_id']; ?>'));">
                                        <span class="glyphicon glyphicon-pencil"></span>
                                    </button></td>
                                <td style="text-align: center;width: 50px;"><input type="checkbox" name="myCheckboxes[]" id="<?php echo $res[0]['id']; ?>"></td>
                            </tr>
                        <?php } endforeach; ?>
                </table> 
                <div  class="rowht"></div>  <div  class="rowht"></div>
                <div class="row" style="text-align: center">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <button type="button" class="btn btn-danger" id="btn_delete"><span class="glyphicon glyphicon-floppy-remove"></span>&nbsp;&nbsp;<?php echo __('Delete'); ?></button>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
        <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
        <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
        <input type='hidden' value='<?php echo $hfparamid; ?>' name='hfparamid' id='hfparamid'/>
        <input type='hidden' value='<?php echo $hfprohid; ?>' name='hfprohid' id='hfprohid'/>
        <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
    </div>
</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>




