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


        var dist = '<?php echo $configure[0][0]['is_dist']; ?>';
        var subdiv = '<?php echo $configure[0][0]['is_zp']; ?>';
        var tal = '<?php echo $configure[0][0]['is_taluka']; ?>';
        var circle = '<?php echo $configure[0][0]['is_block']; ?>';
//       alert(circle);
        //district
        $('#division_id').change(function () {
//             alert('hii');
            var div = $("#division_id option:selected").val();
            $.getJSON('getdist', {div: div}, function (data)
            {
                var sc = '<option>select</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                if (dist == 1) {
                    $("#state_id option").remove();
                    $("#state_id").append(sc);
                } else if (dist == 0 && subdiv == 1) {
                    $("#subdivision_id option").remove();
                    $("#subdivision_id").append(sc);
                } else if (dist == 0 && subdiv == 0 && tal == 1) {
                    $("#taluka_id option").remove();
                    $("#taluka_id").append(sc);
                } else if (dist == 0 && subdiv == 0 && tal == 0 && circle == 1) {
                    $("#circle_id option").remove();
                    $("#circle_id").append(sc);
                } else {
                    $("#ulb_type_id option").remove();
                    $("#ulb_type_id").append(sc);
                }
            });
        });

        $('#state_id').change(function () {
            var dist_head = $("#state_id option:selected").val();
            $('#state_id').val(dist_head);
        });

        //sub division
        $('#state_id').change(function () {
            var dist = $("#state_id option:selected").val();
            $.getJSON('getsubdiv', {dist: dist}, function (data)
            {
                var sc = '<option>select</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                if (subdiv == 1) {
                    $("#subdivision_id option").remove();
                    $("#subdivision_id").append(sc);
                } else if (subdiv == 0 && tal == 1) {
                    $("#taluka_id option").remove();
                    $("#taluka_id").append(sc);
                } else if (subdiv == 0 && tal == 0 && circle == 1) {
                    $("#circle_id option").remove();
                    $("#circle_id").append(sc);
                } else {
                    $("#ulb_type_id option").remove();
                    $("#ulb_type_id").append(sc);
                }
            });
        });

        $('#subdivision_id').change(function () {

            var subdiv_head = $("#subdivision_id option:selected").val();
            $('#subdivision_id').val(subdiv_head);
        });

        // Taluka
        $('#subdivision_id').change(function () {
            var subdiv = $("#subdivision_id option:selected").val();
            var i;
            $.getJSON('gettalukaname', {subdiv: subdiv}, function (data)
            {
                var sc = '<option>select</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                if (tal == 1) {
                    $("#taluka_id option").remove();
                    $("#taluka_id").append(sc);
                } else if (tal == 0 && circle == 1) {
                    $("#circle_id option").remove();
                    $("#circle_id").append(sc);
                } else {
                    $("#ulb_type_id option").remove();
                    $("#ulb_type_id").append(sc);
                }
            });
        });

        $('#taluka_id').change(function () {

            var tal_head = $("#taluka_id option:selected").val();
            $('#taluka_id').val(tal_head);
        });

        // Circle
        $('#taluka_id').change(function () {
            var tal = $("#taluka_id option:selected").val();
            var i;
            $.getJSON('getcircle', {tal: tal}, function (data)
            {
                var sc = '<option>select</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                if (circle == 1) {
                    $("#circle_id option").remove();
                    $("#circle_id").append(sc);
                } else {
                    $("#ulb_type_id option").remove();
                    $("#ulb_type_id").append(sc);
                }
            });
        });

        $('#circle_id').change(function () {

            var tal_head = $("#circle_id option:selected").val();
            $('#circle_id').val(tal_head);
        });

        // Governing Body
        $('#circle_id').change(function () {
//            alert($('#circle_id').val());
            var ulb = $("#circle_id option:selected").val();
            var i;
            $.getJSON('getulb', {ulb: ulb}, function (data)
            {
                var sc = '<option>select</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#ulb_type_id option").remove();
                $("#ulb_type_id").append(sc);
            });
        });

        $('#ulb_type_id').change(function () {

            var ulb_head = $("#ulb_type_id option:selected").val();
            $('#ulb_type_id').val(ulb_head);
        });

        //Corporation List
        $('#ulb_type_id').change(function () {
//            alert($('#ulb_type_id').val());
            var ulb = $("#ulb_type_id option:selected").val();
            var i;
            $.getJSON('getcorp', {ulb: ulb}, function (data)
            {
                var sc = '<option>select</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#corp_id option").remove();
                $("#corp_id").append(sc);
            });
        });

        $('#corp_id').change(function () {

            var corp_head = $("#corp_id option:selected").val();
            $('#corp_id').val(corp_head);
        });
        
        //Village
        $('#corp_id').change(function () {
//            alert($('#ulb_type_id').val());
            var corp = $("#corp_id option:selected").val();
            var i;
            $.getJSON('getvillage', {corp: corp}, function (data)
            {
                var sc = '<option>select</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#village_id option").remove();
                $("#village_id").append(sc);
            });
        });

        $('#village_id').change(function () {

            var village_head = $("#village_id option:selected").val();
            $('#village_id').val(village_head);
        });



    });
    function formsave() {
        document.getElementById("actiontype").value = '1';

    }





</script> 


<?php echo $this->Form->create('damblkdpnd', array('type' => 'file', 'class' => 'damblkdpnd', 'autocomplete' => 'off', 'id' => 'damblkdpnd')); ?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
<div class="panel panel-default">
    <div class="panel-heading" style="text-align: center;"><big><b><?php echo __('lbladmblockhead'); ?></b></big></div>
    <div class="panel-body">


        <div id="selectdamblkdpnd" class="table-responsive" >
            <table id="tabledamblkdpnd" class="table table-striped table-bordered table-condensed">  
                <thead style="background-color: rgb(243, 214, 158);">  
                    <tr>  
                        <?php for ($i = 0; $i < count($configure); $i++) { ?>
                            <?php if ($configure[$i][0]['is_state'] == 1) { ?>
                                <td style="text-align: center; font-weight:bold; "><?php echo __('lbladmstate'); ?></td><?php } ?>
                            <?php if ($configure[$i][0]['is_div'] == 1) { ?>
                                <td style="text-align: center; font-weight:bold;"><?php echo __('lblDivision'); ?></td><?php } ?>
                            <?php if ($configure[$i][0]['is_dist'] == 1) { ?>
                                <td style="text-align: center; font-weight:bold;"><?php echo __('lblDistrict'); ?></td><?php } ?>
                            <?php if ($configure[$i][0]['is_zp'] == 1) { ?>
                                <td style="text-align: center; font-weight:bold;"><?php echo __('lblSubDivision'); ?> </td><?php } ?>
                            <?php if ($configure[$i][0]['is_taluka'] == 1) { ?>
                                <td style="text-align: center; font-weight:bold;"><?php echo __('lbladmtaluka'); ?></td><?php } ?>
                            <?php if ($configure[$i][0]['is_block'] == 1) { ?>
                                <td style="text-align: center; font-weight:bold;"><?php echo __('lblCircle'); ?> </td><?php } ?>
                            <td style="text-align: center; font-weight:bold;"><?php echo __('lblCorporationClass'); ?> </td>
                            <td style="text-align: center; font-weight:bold;">Corporation Class List </td>
                            <td style="text-align: center; font-weight:bold;"><?php echo __('lblVillage'); ?> </td> 
                            <td style="text-align: center; font-weight:bold;"><?php echo __('lblAction'); ?> </td>
                        <?php }
                        ?>
                    </tr>  
                </thead>

                <tr>
                    <?php for ($i = 0; $i < count($configure); $i++) { ?>
                        <?php if ($configure[$i][0]['is_state'] == 1) { ?>
                            <td style="text-align: center"><?php echo $state; ?></td><?php } ?>
                        <?php if ($configure[$i][0]['is_div'] == 1) { ?>
                            <td style="text-align: center"><?php echo $this->Form->input('division_id', array('options' => $divisiondata, 'empty' => '--select--', 'id' => 'division_id', 'class' => 'form-control input-sm', 'label' => false)); ?></td><?php } ?>
                        <?php if ($configure[$i][0]['is_dist'] == 1) { ?>
                            <td style="text-align: center"><?php echo $this->Form->input('state_id', array('options' => $districtdata, 'empty' => '--select--', 'id' => 'state_id', 'class' => 'form-control input-sm', 'label' => false)); ?></td><?php } ?>
                        <?php if ($configure[$i][0]['is_zp'] == 1) { ?>
                            <td style="text-align: center"><?php echo $this->Form->input('subdivision_id', array('options' => $subdivdata, 'empty' => '--select--', 'id' => 'subdivision_id', 'class' => 'form-control input-sm', 'label' => false)); ?></td><?php } ?>
                        <?php if ($configure[$i][0]['is_taluka'] == 1) { ?>
                            <td style="text-align: center"><?php echo $this->Form->input('taluka_id', array('options' => $talukadata, 'empty' => '--select--', 'id' => 'taluka_id', 'class' => 'form-control input-sm', 'label' => false)); ?></td><?php } ?>
                        <?php if ($configure[$i][0]['is_block'] == 1) { ?>
                            <td style="text-align: center"><?php echo $this->Form->input('circle_id', array('options' => $circledata, 'empty' => '--select--', 'id' => 'circle_id', 'class' => 'form-control input-sm', 'label' => false)); ?></td><?php } ?>
                            
                        <td style="text-align: center"><?php echo $this->Form->input('ulb_type_id', array('options' => $ulbdata, 'empty' => '--select--', 'id' => 'ulb_type_id', 'class' => 'form-control input-sm', 'label' => false)); ?></td>
                        <td style="text-align: center"><?php echo $this->Form->input('corp_id', array('options' => $corpclasslist, 'empty' => '--select--', 'id' => 'corp_id', 'class' => 'form-control input-sm', 'label' => false)); ?></td>
                        <td style="text-align: center"><?php echo $this->Form->input('village_id', array('options' => '', 'empty' => '--select--', 'id' => 'village_id', 'class' => 'form-control input-sm', 'label' => false)); ?></td>
                        <td style="text-align: center;" class="tdselect">
                            <button id="btnadd" name="btnadd" class="btn btn-primary " style="text-align: center;" onclick="javascript: return formsave();"><?php echo __('btnsave'); ?></button>
                        </td>
                    <?php }
                    ?>
                </tr>
                <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
            </table> 
        </div>
    </div>
</div>
