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


<script>
    $(document).ready(function () {


        var level1 = '<?php echo $configure[0]['levelconfig']['is_level_1_id']; ?>';
        var level2 = '<?php echo $configure[0]['levelconfig']['is_level_2_id']; ?>';
        var level3 = '<?php echo $configure[0]['levelconfig']['is_level_3_id']; ?>';
        var level4 = '<?php echo $configure[0]['levelconfig']['is_level_4_id']; ?>';


//village drop down list code
        $('#village_name_en').change(function () {
            //alert($('#village_name_en').val());
            var village = $("#village_name_en option:selected").val();
            var i;
            $.getJSON('getlandtype_village', {village: village}, function (data)
            {
//                alert(level1);
                var sc = '<option>select</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#Developedland option").remove();
                $("#Developedland").append(sc);
            });
        });
//
        $('#Developedland').change(function () {

            var develop_head = $("#Developedland option:selected").val();
            //  alert(develop_head);
            $('#Developedland').val(develop_head);
        });


        // village dependencies

        $('#district_id').change(function () {
//            alert($('#district_id').val());
            var dist = $("#district_id option:selected").val();
            var i;
            $.getJSON('getvillagename_village', {dist: dist}, function (data)
            {
                //alert(data);
                var sc = '<option>select</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#village_name_en option").remove();
                $("#village_name_en").append(sc);
            });
        });
//
        $('#village_name_en').change(function () {

            var develop_head = $("#village_name_en option:selected").val();
            //  alert(develop_head);
            $('#village_name_en').val(develop_head);
        });


        //level 1 dropdown list code


        $('#level_1_desc_eng').change(function () {

            var level1_list = $("#level_1_desc_eng option:selected").val();
            $.getJSON('getlevel1_list', {level1_list: level1_list}, function (data)
            {
                var sc = '<option>select</option>';
                var sc1 = '<option>select</option>';
                $.each(data.data1, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $.each(data.data2, function (index, val) {
                    sc1 += "<option value=" + index + ">" + val + "</option>";
                });


                if (level2 == 1) {
                    $("#list_1_desc_eng option").remove();
                    $("#list_1_desc_eng").append(sc);

                    $("#level_2_desc_eng option").remove();
                    $("#level_2_desc_eng").append(sc1);

                } else if (level2 == 0 && level3 == 1) {
                    $("#list_1_desc_eng option").remove();
                    $("#list_1_desc_eng").append(sc);
//                    
                    $("#level_3_desc_eng option").remove();
                    $("#level_3_desc_eng").append(sc1);
                } else if (level2 == 0 && level3 == 0 && level4 == 1) {
                    $("#list_1_desc_eng option").remove();
                    $("#list_1_desc_eng").append(sc);
//                    
                    $("#level_4_desc_eng option").remove();
                    $("#level_4_desc_eng").append(sc1);
                }


            });
        });


        //level 2 dropdown list code

        $('#level_2_desc_eng').change(function () {

//            alert($('#level_2_desc_eng').val());
            var level2_list = $("#level_2_desc_eng option:selected").val();
            var i;
            $.getJSON('getlevel2_list', {level2_list: level2_list}, function (data)
            {
                var sc = '<option>select</option>';
                $.each(data.data1, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });

                var sc1 = '<option>select</option>';
                $.each(data.data2, function (index, val) {
                    sc1 += "<option value=" + index + ">" + val + "</option>";
                });


                if (level3 == 1) {
                    $("#list_2_desc_eng option").remove();
                    $("#list_2_desc_eng").append(sc);
//                    
                    $("#level_3_desc_eng option").remove();
                    $("#level_3_desc_eng").append(sc1);
                } else {
                    $("#list_2_desc_eng option").remove();
                    $("#list_2_desc_eng").append(sc);
//                    
                    $("#level_4_desc_eng option").remove();
                    $("#level_4_desc_eng").append(sc1);
                }

            });
        });


        //level 3 dropdown list code

        $('#level_3_desc_eng').change(function () {

            var level3_list = $("#level_3_desc_eng option:selected").val();
            var i;
            $.getJSON('getlevel3_list', {level3_list: level3_list}, function (data)
            {
//                alert(data);exit;
                var sc = '<option>select</option>';
                $.each(data.data1, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#list_3_desc_eng option").remove();
                $("#list_3_desc_eng").append(sc);

                var sc4 = '<option>select</option>';
                $.each(data.data2, function (index, val) {
                    sc4 += "<option value=" + index + ">" + val + "</option>";
                });
                $("#level_4_desc_eng option").remove();
                $("#level_4_desc_eng").append(sc4);



            });
        });

        //level 4 dropdown list code

        $('#level_4_desc_eng').change(function () {

            var level4_list = $("#level_4_desc_eng option:selected").val();
            $.getJSON('getlevel4_list', {level4_list: level4_list}, function (data)
            {
                var sc = '<option>select</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#list_4_desc_eng option").remove();
                $("#list_4_desc_eng").append(sc);
            });
        });


        $('#list_4_desc_eng').change(function () {

            var develop_head = $("#list_4_desc_eng option:selected").val();
            $('#list_4_desc_eng').val(develop_head);
        });
        $('#level_3_desc_eng').change(function () {

            var develop_head = $("#level_3_desc_eng option:selected").val();
            $('#level_3_desc_eng').val(develop_head);
        });

        $('#level_2_desc_eng').change(function () {

            var develop_head = $("#level_2_desc_eng option:selected").val();
            $('#level_2_desc_eng').val(develop_head);
        });


        $('#level_4_desc_eng').change(function () {

            var develop_head = $("#level_4_desc_eng option:selected").val();
            $('#level_4_desc_eng').val(develop_head);
        });


    });

    function formsave() {
//         alert('hi');
        document.getElementById("actiontype").value = '1';
        $('#village').submit();
    }

</script>


<?php echo $this->Form->create('village', array('id' => 'village')); ?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title" style="font-weight: bolder"><?php echo __('lbladmvillage'); ?></h3></center>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-3"></div>
                        <label for="district_id"class="control-label col-sm-3"><?php echo __('lbladmdistrict'); ?></label>
                        <div class="col-sm-3" ><?php echo $this->Form->input('district_id', array('options' => array('empty' => '--select--', $districtdata), 'id' => 'district_id', 'label' => false, 'class' => 'form-control input-sm')); ?></div>
                        <div class="col-sm-3"></div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-3"></div>
                        <label for="village_name_en"class="control-label col-sm-3"><?php echo __('lbladmvillage'); ?></label>
                        <div class="col-sm-3" ><?php echo $this->Form->input('village_name_en', array('options' => array(), 'empty' => '--select--', 'id' => 'village_name_en', 'label' => false, 'class' => 'form-control input-sm')); ?></div>
                        <div class="col-sm-3"></div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
                <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
                <div id="village"  class="col-sm-12">
                    <table id="tablevillage" class="table table-striped table-bordered table-condensed" width="100%">  
                        <thead style="background-color: rgb(243, 214, 158);">  
                            <tr> 
                                <?php for ($i = 0; $i < count($configure); $i++) { ?>
                                    <?php if ($configure[$i]['levelconfig']['is_developed_land_types_id'] == 1) { ?>
                                        <td style="text-align: center;"><?php echo __('lbldellandtype'); ?></td><?php } ?>
                                    <?php if ($configure[$i]['levelconfig']['is_level_1_id'] == 1) { ?>
                                        <td style="text-align: center;"><?php echo __('lblLevel1'); ?></td>
                                        <td style="text-align: center;"><?php echo __('lblLevel1list'); ?></td><?php } ?>
                                    <?php if ($configure[$i]['levelconfig']['is_level_2_id'] == 1) { ?>
                                        <td style="text-align: center;"><?php echo __('lblLevel2'); ?></td>
                                        <td style="text-align: center;"><?php echo __('lblLevel2list'); ?></td><?php } ?>
                                    <?php if ($configure[$i]['levelconfig']['is_level_3_id'] == 1) { ?>
                                        <td style="text-align: center;"><?php echo __('lblLevel3'); ?></td>
                                        <td style="text-align: center;"><?php echo __('lblLevel3list'); ?></td><?php } ?>
                                    <?php if ($configure[$i]['levelconfig']['is_level_4_id'] == 1) { ?>
                                        <td style="text-align: center;"><?php echo __('lblLevel4'); ?></td>
                                        <td style="text-align: center;"><?php echo __('lblLevel4list'); ?></td><?php } ?>
                                <?php } ?>
                            </tr>  
                        </thead>

                        <tr>
                            <?php for ($i = 0; $i < count($configure); $i++) { ?>
                                <?php if ($configure[$i]['levelconfig']['is_developed_land_types_id'] == 1) { ?>
                                    <td style="text-align: center"><?php echo $this->Form->input('Developedland', array('options' => array(), 'empty' => '--select--', 'id' => 'Developedland', 'label' => false, 'class' => 'form-control input-sm')); ?></td><?php } ?>
                                <?php if ($configure[$i]['levelconfig']['is_level_1_id'] == 1) { ?>
                                    <td style="text-align: center"><?php echo $this->Form->input('level_1_desc_eng', array('options' => array('empty' => '--select--', $level1propertydata), 'id' => 'level_1_desc_eng', 'label' => false, 'class' => 'form-control input-sm')); ?></td>

                                    <td style="text-align: center"><?php echo $this->Form->input('list_1_desc_eng', array('options' => array(), 'empty' => '--select--', 'id' => 'list_1_desc_eng', 'label' => false, 'class' => 'form-control input-sm')); ?></td><?php } ?>
                                <?php if ($configure[$i]['levelconfig']['is_level_2_id'] == 1) { ?>
                                    <td style="text-align: center"><?php echo $this->Form->input('level_2_desc_eng', array('options' => array('empty' => '--select--', $level2propertydata), 'id' => 'level_2_desc_eng', 'label' => false, 'class' => 'form-control input-sm')); ?></td>

                                    <td style="text-align: center"><?php echo $this->Form->input('list_2_desc_eng', array('options' => array(), 'empty' => '--select--', 'id' => 'list_2_desc_eng', 'label' => false, 'class' => 'form-control input-sm')); ?></td><?php } ?>
                                <?php if ($configure[$i]['levelconfig']['is_level_3_id'] == 1) { ?>
                                    <td style="text-align: center"><?php echo $this->Form->input('level_3_desc_eng', array('options' => array('empty' => '--select--', $level3propertydata), 'id' => 'level_3_desc_eng', 'label' => false, 'class' => 'form-control input-sm')); ?></td>

                                    <td style="text-align: center"><?php echo $this->Form->input('list_3_desc_eng', array('options' => array(), 'empty' => '--select--', 'id' => 'list_3_desc_eng', 'label' => false, 'class' => 'form-control input-sm')); ?></td><?php } ?>
                                <?php if ($configure[$i]['levelconfig']['is_level_4_id'] == 1) { ?>
                                    <td style="text-align: center"><?php echo $this->Form->input('level_4_desc_eng', array('options' => array('empty' => '--select--', $level4propertydata), 'id' => 'level_4_desc_eng', 'label' => false, 'class' => 'form-control input-sm')); ?></td>

                                    <td style="text-align: center"><?php echo $this->Form->input('list_4_desc_eng', array('options' => array(), 'empty' => '--select--', 'id' => 'list_4_desc_eng', 'label' => false, 'class' => 'form-control input-sm')); ?></td><?php } ?>
                            <?php } ?>
                        </tr>


                    </table> 
                </div>
                <div class="row" style="text-align: center">
                    <button id="btnsave" name="btnsave" class="btn btn-primary " onclick="javascript: return formsave();"><?php echo __('btnsave'); ?></button>
                </div>

            </div>

        </div>
    </div>
</div>


<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>
