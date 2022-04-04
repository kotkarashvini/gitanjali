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

        $('#village_id').change(function () {
            var village = $("#village_id option:selected").val();
            var token = "level2";
            var i;
            $.getJSON("get_level_name", {village: village, token: token}, function (data)
            {
                var sc = '<option value="empty">--Select--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#level_2_id").prop("disabled", false);
                $("#level_2_id option").remove();
                $("#level_2_id").append(sc);
            });
        })
    });

    function formadd() {
        var list_2_desc_en = $('#list_2_desc_en').val();
        var level_2_id = $('#level_2_id').val();
        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        var numbers = /^[0-9]+$/;
        var Alphanum = /^(?=.*?[a-zA-Z])[0-9a-zA-Z]+$/;
        var Alphanumdot = /^(?=.*?[a-zA-Z])[0-9a-zA-Z.]+$/;
        var password = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[#,@]).{8,}/;
        var alphbets = /^[a-z A-Z ]+$/;
        var alphbetscity = /^[ A-Za-z-() ]*$/;
        var alphanumnotspace = /^[0-9a-zA-Z]+$/;
        var alphanumsapcedot = /^(?=.*?[a-zA-Z])[0-9 a-zA-Z,.\-_]+$/;

        if (level_2_id == '') {

            $('#level_2_id').focus();
            alert('Please insert Level 2 property name');
            return false;
        }

        if (list_2_desc_en == '') {
            $('#list_2_desc_en').focus();
            alert('Please insert Level 2 List name');
            return false;
        }
        if (!list_2_desc_en.match(alphanumsapcedot) || list_2_desc_en.length > 100)
        {
            $('#list_2_desc_en').focus();
            alert('Only Alphabets are allowed in Desciption');
            return false;
        }

        document.getElementById("actiontype").value = '1';
        document.getElementById("hfaction").value = 'S';
    }

    function formupdate(list_2_desc_en, id, level_2_id, level_2_from_range, level_2_to_range, village_id) {
        document.getElementById("actiontype").value = '1';
        $("#village_id").prop("disabled", false);
        $("#level_2_id").prop("disabled", false);
        $('#list_2_desc_en').val(list_2_desc_en);
        $('#village_id').val(village_id);
        $('#hfid').val(id);
        $('#level_2_id').val(level_2_id);
        $('#level_2_from_range').val(level_2_from_range);
        $('#level_2_to_range').val(level_2_to_range);
        $('#hfupdateflag').val('Y');
        $('#btnadd').html('Save');
        return false;
    }

    function formdelete(id) {
        document.getElementById("actiontype").value = '3';
        document.getElementById("hfid").value = id;
    }
</script> 

<?php echo $this->Form->create('levellist2', array('id' => 'levellist2', 'autocomplete' => 'off')); ?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading" style="text-align: center"><b><?php echo __('lblLevel2list'); ?></b></div>
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
                            <label for="level_2_id" class="col-sm-2 control-label"><?php echo __('lblLevel2list'); ?><span style="color: #ff0000">*</span></label>    
                            <div class="col-sm-2">
                                <?php echo $this->Form->input('level_2_id', array('options' => array($Level2name), 'empty' => '--select--', 'id' => 'level_2_id', 'class' => 'form-control input-sm', 'label' => false, 'disabled' => 'disabled')); ?>
                            </div>
                            <label for="level_2_id" class="col-sm-2 control-label"><?php echo __('lblLevel2list'); ?><span style="color: #ff0000">*</span></label>    
                            <div class="col-sm-2">
                                <?php echo $this->Form->input('list_2_desc_en', array('label' => false, 'id' => 'list_2_desc_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="usage_sub_catg_desc_en" class="col-sm-2 control-label"><?php echo __('lblrangefrom'); ?> <span style="color: #ff0000">*</span></label>    
                            <div class="col-sm-2">
                                <?php echo $this->Form->input('level_2_from_range', array('label' => false, 'id' => 'level_2_from_range', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            </div>
                            <label for="usage_sub_catg_desc_en" class="col-sm-2 control-label"><?php echo __('lblrangeto'); ?> <span style="color: #ff0000">*</span></label>    
                            <div class="col-sm-2">
                                <?php echo $this->Form->input('level_2_to_range', array('label' => false, 'id' => 'level_2_to_range', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            </div>
                            <div class="col-sm-1 tdselect">
                                <button id="btnadd" name="btnadd" class="btn btn-info " style="text-align: center;" onclick="javascript: return formadd();">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;<?php echo __('lblbtnAdd'); ?></button>
                            </div>
                            <div class="col-sm-1 tdsave" hidden="true">
                                <button id="btnadd" name="btnadd" class="btn btn-info " style="text-align: center;" onclick="javascript: return formadd();">
                                    <span class="glyphicon glyphicon-floppy-saved"></span>&nbsp;<?php echo __('btnsave'); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-heading" style="text-align: center"><b><?php echo __('lblLevel2list'); ?></b></div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="tabledivisionnew" class="table table-striped table-bordered table-hover">  
                        <thead >  
                            <tr>  
                                <td style="text-align: center; font-weight:bold; width: 10%"><?php echo __('lbladmstate'); ?></td>
                                <td style="text-align: center; width: 8%"><b>Village</b></td>
                                <td style="text-align: center; font-weight:bold;"><?php echo __('lblLevel2list'); ?>&nbsp;<?php echo __('lbllevelname'); ?></td>
                                <td style="text-align: center; font-weight:bold;"><?php echo __('lblLevel2list'); ?>&nbsp;<?php echo __('lbllevelname'); ?></td>
                                <td style="text-align: center;font-weight:bold;"><?php echo __('lblrangefrom'); ?></td>
                                <td style="text-align: center;font-weight:bold;"><?php echo __('lblrangeto'); ?></td>
                                <td style="text-align: center; font-weight:bold; width: 8%"><?php echo __('lblaction'); ?></td>

                            </tr>  
                        </thead>
                        <tr>
                            <?php for ($i = 0; $i < count($levellist2record); $i++) { ?>
                                <td style="text-align: center"><?php echo $state; ?></td>
                                <td style="text-align: center;"><?php echo $levellist2record[$i][0]['village_name_' . $laug]; ?></td>
                                <?php if ($this->Session->read("sess_langauge") == 'en') { ?>
                                    <td style="text-align: center;"><?php echo $levellist2record[$i][0]['level_2_desc_en']; ?></td>
                                    <td style="text-align: center;"><?php echo $levellist2record[$i][0]['list_2_desc_en']; ?></td>
                                    <td style="text-align: center;"><?php echo $levellist2record[$i][0]['level_2_from_range']; ?></td>
                                    <td style="text-align: center;"><?php echo $levellist2record[$i][0]['level_2_to_range']; ?></td>
                                    <td style="text-align: center;">
                                        <button id="btnupdate" name="btnupdate" class="btn btn-default " style="text-align: center;" onclick="javascript: return formupdate(
                                                                ('<?php echo $levellist2record[$i][0]['list_2_desc_en']; ?>'),
                                                                ('<?php echo $levellist2record[$i][0]['id']; ?>'),
                                                                ('<?php echo $levellist2record[$i][0]['level_2_id']; ?>'),
                                                                ('<?php echo $levellist2record[$i][0]['level_2_from_range']; ?>'),
                                                                ('<?php echo $levellist2record[$i][0]['level_2_to_range']; ?>'),
                                                                ('<?php echo $levellist2record[$i][0]['village_id']; ?>'));">
                                            <span class="glyphicon glyphicon-pencil"><?php //echo $this->Html->image('edit.png');           ?></span><?php //echo __('lblbtnupdate_');           ?></button>

                                        <button id="btndelete" name="btndelete" class="btn btn-default " style="text-align: center;" onclick="javascript: return formdelete(('<?php echo $levellist2record[$i][0]['id']; ?>'));">
                                            <span class="glyphicon glyphicon-remove"><?php //echo $this->Html->image('delete.png');           ?></span><?php //echo __('lblbtndelete_');           ?></button>
                                    </td>
                                <?php } else { ?>
                                    <td style="text-align: center;"><?php echo $levellist2record[$i][0]['level_2_desc_ll']; ?></td>
                                    <td style="text-align: center;"><?php echo $levellist2record[$i][0]['list_2_desc_ll']; ?></td>
                                    <td style="text-align: center;"><?php echo $levellist2record[$i][0]['level_2_from_range']; ?></td>
                                    <td style="text-align: center;"><?php echo $levellist2record[$i][0]['level_2_to_range']; ?></td>
                                    <td style="text-align: center;">
                                        <button id="btnupdate" name="btnupdate" class="btn btn-default " style="text-align: center;" 
                                                onclick="javascript: return formupdate(
                                                                        ('<?php echo $levellist2record[$i][0]['list_2_desc_ll']; ?>'),
                                                                        ('<?php echo $levellist2record[$i][0]['id']; ?>'),
                                                                        ('<?php echo $levellist2record[$i][0]['level_2_id']; ?>'),
                                                                        ('<?php echo $levellist2record[$i][0]['level_2_from_range']; ?>'),
                                                                        ('<?php echo $levellist2record[$i][0]['level_2_to_range']; ?>'), ('<?php echo $levellist2record[$i][0]['village_id']; ?>'));">
                                            <span class="glyphicon glyphicon-pencil"><?php //echo $this->Html->image('edit.png');           ?></span><?php //echo __('lblbtnupdate_');           ?></button>

                                        <button id="btndelete" name="btndelete" class="btn btn-default " style="text-align: center;" onclick="javascript: return formdelete(('<?php echo $levellist2record[$i][0]['id']; ?>'));">
                                            <span class="glyphicon glyphicon-remove"><?php //echo $this->Html->image('delete.png');           ?></span><?php //echo __('lblbtndelete_');           ?></button>
                                    </td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                        <?php unset($districtrecord1); ?>
                    </table> 
                    <?php if (!empty($districtrecord)) { ?>
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



