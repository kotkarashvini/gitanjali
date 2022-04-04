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
        if ($('#hfhidden1').val() === 'Y')
        {
            $('#tablevillagelevelmapping1').dataTable({
                "iDisplayLength": 10,
                "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
            });
        }

        $('#village_id').change(function () {
            var village_id = $("#village_id option:selected").val();
            $.getJSON('getalllevel', {village_id: village_id}, function (data)
            {
                var sc = '<option value="">--select--</option>';
                var sc1 = '<option value="">--select--</option>';
                var sc2 = '<option value="">--select--</option>';
                var sc3 = '<option value="">--select--</option>';
                var sc4 = '<option value="">--select--</option>';
                var sc5 = '<option value="">--select--</option>';
                var sc6 = '<option value="">--select--</option>';
                var sc7 = '<option value="">--select--</option>';

                $.each(data.data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $.each(data.data1, function (index, val) {
                    sc1 += "<option value=" + index + ">" + val + "</option>";
                });

                $.each(data.data2, function (index, val) {
                    sc2 += "<option value=" + index + ">" + val + "</option>";
                });
                $.each(data.data3, function (index, val) {
                    sc3 += "<option value=" + index + ">" + val + "</option>";
                });

                $.each(data.data4, function (index, val) {
                    sc4 += "<option value=" + index + ">" + val + "</option>";
                });
                $.each(data.data5, function (index, val) {
                    sc5 += "<option value=" + index + ">" + val + "</option>";
                });

                $.each(data.data6, function (index, val) {
                    sc6 += "<option value=" + index + ">" + val + "</option>";
                });

                $.each(data.data7, function (index, val) {
                    sc7 += "<option value=" + index + ">" + val + "</option>";
                });

                $("#level_1_desc_eng").prop("disabled", false);
                $("#level_1_desc_eng option").remove();
                $("#level_1_desc_eng").append(sc);

                $("#list_1_desc_eng").prop("disabled", false);
                $("#list_1_desc_eng option").remove();
                $("#list_1_desc_eng").append(sc1);

                $("#level_2_desc_eng").prop("disabled", false);
                $("#level_2_desc_eng option").remove();
                $("#level_2_desc_eng").append(sc2);

                $("#list_2_desc_eng").prop("disabled", false);
                $("#list_2_desc_eng option").remove();
                $("#list_2_desc_eng").append(sc3);

                $("#level_3_desc_eng").prop("disabled", false);
                $("#level_3_desc_eng option").remove();
                $("#level_3_desc_eng").append(sc4);

                $("#list_3_desc_eng").prop("disabled", false);
                $("#list_3_desc_eng option").remove();
                $("#list_3_desc_eng").append(sc5);

                $("#level_4_desc_eng").prop("disabled", false);
                $("#level_4_desc_eng option").remove();
                $("#level_4_desc_eng").append(sc6);

                $("#list_4_desc_eng").prop("disabled", false);
                $("#list_4_desc_eng option").remove();
                $("#list_4_desc_eng").append(sc7);

                $("#level_1_desc_eng").show();
                $("#list_1_desc_eng").show();
                $("#level_2_desc_eng").show();
                $("#list_2_desc_eng").show();
                $("#level_3_desc_eng").show();
                $("#list_3_desc_eng").show();
                $("#level_4_desc_eng").show();
                $("#list_4_desc_eng").show();
//                $("#level_1_desc_eng option").remove();
//                $("#level_1_desc_eng").append(sc1);
            });
        });


//        $('#level_1_desc_eng').change(function () {
//            var level1_list = $("#level_1_desc_eng option:selected").val();
//            $.getJSON('getlevel1_list1', {level1_list: level1_list}, function (data)
//            {
//                var sc = '<option>select</option>';
//                var sc1 = '<option>select</option>';
//                $.each(data.data1, function (index, val) {
//                    sc += "<option value=" + index + ">" + val + "</option>";
//                });
//                $("#list_1_desc_eng").prop("disabled", false);
//                $("#list_1_desc_eng option").remove();
//                $("#list_1_desc_eng").append(sc);
//            });
//        });
//
//        $('#level_2_desc_eng').change(function () {
//            var level2_list = $("#level_2_desc_eng option:selected").val();
//            $.getJSON('getlevel2_list1', {level2_list: level2_list}, function (data)
//            {
//                var sc = '<option>select</option>';
//                $.each(data.data1, function (index, val) {
//                    sc += "<option value=" + index + ">" + val + "</option>";
//                });
//                $("#list_2_desc_eng").prop("disabled", false);
//                $("#list_2_desc_eng option").remove();
//                $("#list_2_desc_eng").append(sc);
//            });
//        });
//
//        $('#level_3_desc_eng').change(function () {
//
//            var level3_list = $("#level_3_desc_eng option:selected").val();
//            $.getJSON('getlevel3_list1', {level3_list: level3_list}, function (data)
//            {
//                var sc = '<option>select</option>';
//                $.each(data.data1, function (index, val) {
//                    sc += "<option value=" + index + ">" + val + "</option>";
//                });
//                $("#list_3_desc_eng").prop("disabled", false);
//                $("#list_3_desc_eng option").remove();
//                $("#list_3_desc_eng").append(sc);
//            });
//        });
//
//        $('#level_4_desc_eng').change(function () {
//            var level4_list = $("#level_4_desc_eng option:selected").val();
//            $.getJSON('getlevel4_list1', {level4_list: level4_list}, function (data)
//            {
//                var sc = '<option>select</option>';
//                $.each(data, function (index, val) {
//                    sc += "<option value=" + index + ">" + val + "</option>";
//                });
//                $("#list_4_desc_eng").prop("disabled", false);
//                $("#list_4_desc_eng option").remove();
//                $("#list_4_desc_eng").append(sc);
//            });
//        });

    });

    function formadd() {

        var village_id = $('#village_id').val();
        var developed_land_types_id = $('#developed_land_types_id').val();
        var level1_id = $('#level1_id').val();
        var level2_id = $('#level2_id').val();
        var level3_id = $('#level3_id').val();
        var level4_id = $('#level4_id').val();
        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        var numbers = /^[0-9]+$/;
        var Alphanum = /^(?=.*?[a-zA-Z])[0-9a-zA-Z]+$/;
        var Alphanumdot = /^(?=.*?[a-zA-Z])[0-9a-zA-Z.]+$/;
        var password = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[#,@]).{8,}/;
        var alphbets = /^[a-z A-Z ]+$/;
        var alphbetscity = /^[ A-Za-z-() ]*$/;
        var alphanumnotspace = /^[0-9a-zA-Z]+$/;
        var alphanumsapcedot = /^(?=.*?[a-zA-Z])[0-9 a-zA-Z,.\-_]+$/;

        if (village_id == '') {

            $('#village_id').focus();
            alert('Please Select Village');
            return false;
        }

        if (developed_land_types_id == '') {

            $('#developed_land_types_id').focus();
            alert('Please Select Land Type');
            return false;
        }

        if (level1_id == '') {

            $('#level1_id').focus();
            alert('Please Select Level 1');
            return false;
        }

        if (level2_id == '') {

            $('#level2_id').focus();
            alert('Please Select Level 2');
            return false;
        }

        if (level3_id == '') {

            $('#level3_id').focus();
            alert('Please Select Level 3');
            return false;
        }

        if (level4_id == '') {

            $('#level4_id').focus();
            alert('Please Select Level 4');
            return false;
        }

        document.getElementById("actiontype").value = '1';
    }

    function formupdate(id, village_id, developed_land_types_id, level1_id, level2_id, level3_id, level4_id, prop_level1_list_id, prop_level2_list_id, prop_level3_list_id, prop_level4_list_id) {
        document.getElementById("actiontype").value = '2';
        $("#list_1_desc_eng").prop("disabled", false);
        $("#list_2_desc_eng").prop("disabled", false);
        $("#list_3_desc_eng").prop("disabled", false);
        $("#list_4_desc_eng").prop("disabled", false);
        $('#id1').val(id);
        $("#btnadd").html("Save");
        $("#hfupdateflag").val('Y');

        $.getJSON('getalllevel', {village_id: village_id}, function (data)
        {
            var sc = '<option value="">--select--</option>';
            var sc1 = '<option value="">--select--</option>';
            var sc2 = '<option value="">--select--</option>';
            var sc3 = '<option value="">--select--</option>';
            var sc4 = '<option value="">--select--</option>';
            var sc5 = '<option value="">--select--</option>';
            var sc6 = '<option value="">--select--</option>';
            var sc7 = '<option value="">--select--</option>';

            $.each(data.data, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $.each(data.data1, function (index, val) {
                sc1 += "<option value=" + index + ">" + val + "</option>";
            });

            $.each(data.data2, function (index, val) {
                sc2 += "<option value=" + index + ">" + val + "</option>";
            });
            $.each(data.data3, function (index, val) {
                sc3 += "<option value=" + index + ">" + val + "</option>";
            });

            $.each(data.data4, function (index, val) {
                sc4 += "<option value=" + index + ">" + val + "</option>";
            });
            $.each(data.data5, function (index, val) {
                sc5 += "<option value=" + index + ">" + val + "</option>";
            });

            $.each(data.data6, function (index, val) {
                sc6 += "<option value=" + index + ">" + val + "</option>";
            });

            $.each(data.data7, function (index, val) {
                sc7 += "<option value=" + index + ">" + val + "</option>";
            });

            $("#level_1_desc_eng").prop("disabled", false);
            $("#level_1_desc_eng option").remove();
            $("#level_1_desc_eng").append(sc);

            $("#list_1_desc_eng").prop("disabled", false);
            $("#list_1_desc_eng option").remove();
            $("#list_1_desc_eng").append(sc1);

            $("#level_2_desc_eng").prop("disabled", false);
            $("#level_2_desc_eng option").remove();
            $("#level_2_desc_eng").append(sc2);

            $("#list_2_desc_eng").prop("disabled", false);
            $("#list_2_desc_eng option").remove();
            $("#list_2_desc_eng").append(sc3);

            $("#level_3_desc_eng").prop("disabled", false);
            $("#level_3_desc_eng option").remove();
            $("#level_3_desc_eng").append(sc4);

            $("#list_3_desc_eng").prop("disabled", false);
            $("#list_3_desc_eng option").remove();
            $("#list_3_desc_eng").append(sc5);

            $("#level_4_desc_eng").prop("disabled", false);
            $("#level_4_desc_eng option").remove();
            $("#level_4_desc_eng").append(sc6);

            $("#list_4_desc_eng").prop("disabled", false);
            $("#list_4_desc_eng option").remove();
            $("#list_4_desc_eng").append(sc7);

            $('#village_id').val(village_id);
            $('#Developedland').val(developed_land_types_id);
            $('#level_1_desc_eng').val(level1_id);
            $('#level_2_desc_eng').val(level2_id);
            $('#level_3_desc_eng').val(level3_id);
            $('#level_4_desc_eng').val(level4_id);
            $('#list_1_desc_eng').val(prop_level1_list_id);
            $('#list_2_desc_eng').val(prop_level2_list_id);
            $('#list_3_desc_eng').val(prop_level3_list_id);
            $('#list_4_desc_eng').val(prop_level4_list_id);


        });
        return false;
    }
    function formdelete(id) {
        var result = confirm("Are you sure you want to delete this record?");
        if (result) {
            document.getElementById("actiontype").value = '4';
            $('#id1').val(id);
        } else {
            return false;
        }
    }
</script> 

<?php echo $this->Form->create('villagelevelmapping', array('type' => 'file', 'class' => 'villagelevelmapping', 'autocomplete' => 'off', 'id' => 'villagelevelmapping')); ?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
<div class="row">
    <div class="col-lg-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lbladmvillage'); ?></h3></center>
            </div>
            <div class="box-body">
                <table id="tablevillagelevelmapping" class="table table-striped table-bordered table-hover">  
                    <thead >  
                        <tr> 
                            <?php for ($i = 0; $i < count($configure); $i++) { ?>
                                <th class="center width10"><?php echo __('lbladmvillage'); ?></th>
                                <?php if ($configure[$i]['levelconfig']['is_level_1_id'] == 1) { ?>
                                    <th class="center width10"><?php echo __('lblLevel1'); ?></th>
                                    <th class="center width10"><?php echo __('lblLevel1list'); ?></th><?php } ?>
                                <?php if ($configure[$i]['levelconfig']['is_level_2_id'] == 1) { ?>
                                    <th class="center width10"><?php echo __('lbldivlvl2'); ?></th>
                                    <th class="center width10"><?php echo __('lblLevel2list'); ?></th><?php } ?>
                                <?php if ($configure[$i]['levelconfig']['is_level_3_id'] == 1) { ?>
                                    <th class="center width10"><?php echo __('lbldistlvl'); ?></th>
                                    <th class="center width10"><?php echo __('lblLevel3list'); ?></th><?php } ?>
                                <?php if ($configure[$i]['levelconfig']['is_level_4_id'] == 1) { ?>
                                    <th class="center width10"><?php echo __('lblLevel4'); ?></th>
                                    <th class="center width10"><?php echo __('lblLevel4list'); ?></th><?php } ?>
                                <th class="center width10"><?php echo __('lblaction'); ?> </th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php for ($i = 0; $i < count($configure); $i++) { ?>
                                <td ><?php echo $this->Form->input('village_id', array('options' => array($villagenname), 'empty' => '--select--', 'id' => 'village_id', 'class' => 'form-control input-sm', 'label' => false)); ?></td>
                                <?php if ($configure[$i]['levelconfig']['is_level_1_id'] == 1) { ?>
                                    <td ><?php echo $this->Form->input('level1_id', array('options' => array($level1propertydata), 'empty' => '--select--', 'id' => 'level_1_desc_eng', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?></td>
                                    <td ><?php echo $this->Form->input('prop_level1_list_id', array('options' => array($level1propertylist), 'empty' => '--select--', 'id' => 'list_1_desc_eng', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?></td><?php } ?>
                                <?php if ($configure[$i]['levelconfig']['is_level_2_id'] == 1) { ?>
                                    <td ><?php echo $this->Form->input('level2_id', array('options' => array($level2propertydata), 'empty' => '--select--', 'id' => 'level_2_desc_eng', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?></td>
                                    <td ><?php echo $this->Form->input('prop_level2_list_id', array('options' => array($level2propertylist), 'empty' => '--select--', 'id' => 'list_2_desc_eng', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?></td><?php } ?>
                                <?php if ($configure[$i]['levelconfig']['is_level_3_id'] == 1) { ?>
                                    <td ><?php echo $this->Form->input('level3_id', array('options' => array($level3propertydata), 'empty' => '--select--', 'id' => 'level_3_desc_eng', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?></td>
                                    <td ><?php echo $this->Form->input('prop_level3_list_id', array('options' => array($level3propertylist), 'empty' => '--select--', 'id' => 'list_3_desc_eng', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?></td><?php } ?>
                                <?php if ($configure[$i]['levelconfig']['is_level_4_id'] == 1) { ?>
                                    <td ><?php echo $this->Form->input('level4_id', array('options' => array($level4propertydata), 'empty' => '--select--', 'id' => 'level_4_desc_eng', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?></td>
                                    <td ><?php echo $this->Form->input('prop_level4_list_id', array('options' => array($level4propertylist), 'empty' => '--select--', 'id' => 'list_4_desc_eng', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?></td><?php } ?>
                                <td class="tdselect" >
                                    <button id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                                        <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?></button>
                                </td>
                            <?php } ?>
                        </tr>
                    </tbody>
                </table> 
            </div>
        </div>

        <div class="box box-primary">

            <div class="box-body">
                <div class="table-responsive">
                    <table id="tablevillagelevelmapping1" class="table table-striped table-bordered table-hover">  
                        <thead >  
                            <tr> 
                                <?php for ($i = 0; $i < count($configure); $i++) { ?>
                                    <th class="center width10"><?php echo __('lbladmvillage'); ?></th>
                                    <?php if ($configure[$i]['levelconfig']['is_level_1_id'] == 1) { ?>
                                        <th class="center width10"><?php echo __('lblLevel1'); ?></th>
                                        <th class="center width10"><?php echo __('lblLevel1list'); ?></th><?php } ?>
                                    <?php if ($configure[$i]['levelconfig']['is_level_2_id'] == 1) { ?>
                                        <th class="center width10"><?php echo __('lbldivlvl2'); ?></th>
                                        <th class="center width10"><?php echo __('lblLevel2list'); ?></th><?php } ?>
                                    <?php if ($configure[$i]['levelconfig']['is_level_3_id'] == 1) { ?>
                                        <th class="center width10"><?php echo __('lbldistlvl'); ?></th>
                                        <th class="center width10"><?php echo __('lblLevel3list'); ?></th><?php } ?>
                                    <?php if ($configure[$i]['levelconfig']['is_level_4_id'] == 1) { ?>
                                        <th class="center width10"><?php echo __('lblLevel4'); ?></th>
                                        <th class="center width10"><?php echo __('lblLevel4list'); ?></th><?php } ?>
                                    <th class="center width10"><?php echo __('lblaction'); ?> </th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <?php for ($i = 0; $i < count($raterecord); $i++) { ?>
                                    <td ><?php echo $raterecord[$i][0]['village_name_' . $language]; ?></td>
                                    <?php if ($raterecord[$i][0]['is_level_1_id'] == 1) { ?>
                                        <td ><?php echo $raterecord[$i][0]['level_1_desc_' . $language]; ?></td>
                                        <td ><?php echo $raterecord[$i][0]['list_1_desc_' . $language]; ?><?php } ?>
                                        <?php if ($raterecord[$i][0]['is_level_2_id'] == 1) { ?>
                                        <td ><?php echo $raterecord[$i][0]['level_2_desc_' . $language]; ?></td>
                                        <td ><?php echo $raterecord[$i][0]['list_2_desc_' . $language]; ?></td><?php } ?>
                                    <?php if ($raterecord[$i][0]['is_level_3_id'] == 1) { ?>
                                        <td ><?php echo $raterecord[$i][0]['level_3_desc_' . $language]; ?></td>
                                        <td ><?php echo $raterecord[$i][0]['list_3_desc_' . $language]; ?></td><?php } ?>
                                    <?php if ($raterecord[$i][0]['is_level_4_id'] == 1) { ?>
                                        <td ><?php echo $raterecord[$i][0]['level_4_desc_' . $language]; ?></td>
                                        <td ><?php echo $raterecord[$i][0]['list_4_desc_' . $language]; ?></td><?php } ?>
                                    <td >
                                        <button id="btnupdate<?php echo $raterecord[$i][0]['id']; ?>" name="btnupdate" class="btn btn-default "onclick="javascript: return formupdate('<?php echo $raterecord[$i][0]['id']; ?>', '<?php echo $raterecord[$i][0]['village_id']; ?>', '<?php echo $raterecord[$i][0]['developed_land_types_id']; ?>',
                                                            '<?php echo $raterecord[$i][0]['level1_id']; ?>', '<?php echo $raterecord[$i][0]['level2_id']; ?>', '<?php echo $raterecord[$i][0]['level3_id']; ?>', '<?php echo $raterecord[$i][0]['level4_id']; ?>'
                                                            , '<?php echo $raterecord[$i][0]['prop_level1_list_id']; ?>', '<?php echo $raterecord[$i][0]['prop_level2_list_id']; ?>', '<?php echo $raterecord[$i][0]['prop_level3_list_id']; ?>', '<?php echo $raterecord[$i][0]['prop_level4_list_id']; ?>');">
                                            <span class="glyphicon glyphicon-pencil"></span>
                                        </button>
                                        <button id="btndelete" name="btndelete" class="btn btn-default "  onclick="javascript: return formdelete(('<?php echo $raterecord[$i][0]['id']; ?>'));">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table> 
                    <?php if (!empty($raterecord)) { ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
                </div>
            </div>
        </div>

    </div>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='id1' id='id1'/>
    <input type='hidden' value='<?php echo $hfname; ?>' name='name1' id='name1'/>
    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
</div>

<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>