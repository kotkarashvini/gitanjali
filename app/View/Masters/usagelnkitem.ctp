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
        $("#btnSave").click(function () {
            var desc = $("#main_id option:selected").text() + "/" + $("#sub_id option:selected").text() + "/" + $("#sub_sub_id option:selected").text();
            $("#hdnDesc").val(desc);
            $(':input').each(function () {
                $(this).val($.trim($(this).val()));
            });
            var action = $("#actionid").val();
            if (action != 'U') {
                $("#actionid").val('SV');
            }
            if ($("#main_id").val() == '')
            {
                $("#main_id").focus();
                $("#main_id").val('');
                alert("Please Select Main Category");
                return false;
            } else if ($("#sub_id").val() == '')
            {
                $("#sub_id").focus();
                $("#sub_id").val('');
                alert("Please Select Sub Category");
                return false;
            } else if ($("#sub_sub_id").val() == '')
            {
                $("#sub_sub_id").focus();
                $("#sub_sub_id").val('');
                alert("Please Select Sub Sub Category");
                return false;
            } else if ($("#item_list_id").val() == '')
            {
                $("#item_list_id").focus();
                $("#item_list_id").val('');
                alert("Please Select Item List");
                return false;
            } else if ($("#eval_id").val() == '')
            {
                $("#eval_id").focus();
                $("#eval_id").val('');
                alert("Please Select Evaluation Rule");
                return false;
            }
            else {
                $("#hid").val(id);
                $('#usagelnkitemlist').submit();
            }
        });
        $('#tableusglnkcat').dataTable({
            "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
        // 2 dropdownlist visibility
        $('#sub_sub_id').change(function () {
            var sub_sub_id = $("#sub_sub_id option:selected").val();
            var i;
            $.getJSON('getusagevisibility', {sub_sub_id: sub_sub_id}, function (data)
            {
                var sc1 = data['data1'];
                var sc2 = data['data2'];
                var sc3 = data['data3'];
                var sc4 = data['data4'];
                var sc5 = data['data5'];
                if (sc1 == 'Y') {
                    $("#construction_type_id").prop("disabled", false);
                    $('#constructiontype').show();
                    $('#constructiontype1').show();
                } else {
                    $("#construction_type_id").prop("disabled", true);
                    $('#constructiontype').hide();
                    $('#constructiontype1').hide();
                }
                if (sc2 == 'Y') {
                    $("#depreciation_id").prop("disabled", false);
                    $('#depreciationtype').show();
                    $('#depreciationtype1').show();
                } else {
                    $("#depreciation_id").prop("disabled", true);
                    $('#depreciationtype').hide();
                    $('#depreciationtype1').hide();
                }
                if (sc3 == 'Y') {
                    $("#road_vicinity_id").prop("disabled", false);
                    $('#roadvicinity').show();
                    $('#roadvicinity1').show();
                } else {
                    $("#road_vicinity_id").prop("disabled", true);
                    $('#roadvicinity').hide();
                    $('#roadvicinity1').hide();
                }
                if (sc4 == 'Y') {
                    $("#user_defined_dependency1_id").prop("disabled", false);
                    $('#ud1').show();
                    $('#ud11').show();
                } else {
                    $("#user_defined_dependency1_id").prop("disabled", true);
                    $('#ud1').hide();
                    $('#ud11').hide();
                }
                if (sc5 == 'Y') {
                    $("#user_defined_dependency2_id").prop("disabled", false);
                    $('#ud2').show();
                    $('#ud21').show();
                } else {
                    $("#user_defined_dependency2_id").prop("disabled", true);
                    $('#ud2').hide();
                    $('#ud21').hide();
                }

            });
        });


    });
//    function formupdate(id, mid, sid, ssid, itlist, constr, depre, roadv, userd1, userd2) {
    function formupdate(id, mid, sid, ssid) {
//        alert(id);

        $("#hid").val(id);
        $("#main_id").val(mid);
        $("#sub_id").val(sid);
        $("#sub_sub_id").val(ssid);
//        $("#item_list_id").val(itlist);
//                    $("#eval_id").val(erule);
//        $("#construction_type_id").val(constr);
//        $("#depreciation_id").val(depre);
//        $("#road_vicinity_id").val(roadv);
//        $("#user_defined_dependency1_id").val(userd1);
//        $("#user_defined_dependency2_id").val(userd2);
        $("#actionid").val('U');
        window.scrollTo(500, 200);
        return false;
    }
    function formdelete(id) {
        $("#hid").val(id);
        $("#actionid").val('D');
    }

</script>
<?php echo $this->Form->create('usagelnkitem', array('id' => 'usagelnkitem')); ?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading" style="text-align: center"><b><?php echo __('lblusagelinkcategory'); ?></b></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label"><?php echo __('lblusamaincat'); ?><span style="color: #ff0000">*</span></label> 
                            <label for="" class="col-sm-2 control-label"><?php echo __('lblUsagesubcategoryhead'); ?><span style="color: #ff0000">*</span></label>
                            <label for="" class="col-sm-2 control-label"><?php echo __('lblsubsubcategorydesc'); ?><span style="color: #ff0000">*</span></label>
<!--                        <label for="" class="col-sm-2 control-label" id="constructiontype" hidden="true">Construction Type<span style="color: #ff0000">*</span></label>
                            <label for="" class="col-sm-2 control-label" id="depreciationtype" hidden="true">Depreciation Type<span style="color: #ff0000">*</span></label>-->
<!--                        <label for="" class="col-sm-2 control-label"><?php // echo __('lblitemlistname');        ?><span style="color: #ff0000">*</span></label>-->
                        </div>
                    </div>
                </div>
                <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="col-sm-2">
                                <?php echo $this->Form->input($name[2], array('options' => $usgmain, 'empty' => '--select--', 'id' => 'main_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                            </div>
                            <div class="col-sm-2">
                                <?php echo $this->Form->input($name[3], array('options' => $usgsub, 'empty' => '--select--', 'id' => 'sub_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                            </div>
                            <div class="col-sm-2">
                                <?php echo $this->Form->input($name[4], array('options' => $usgsubsub, 'empty' => '--select--', 'id' => 'sub_sub_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12" style="height: 15px;">&nbsp;</div>
                <div class="row" style="text-align: center">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <?php echo $this->Form->button(__('btnsave'), array('id' => 'btnSave', 'class' => 'btn btn-primary')); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel-heading" style="text-align: center"><b><?php echo __('lblusagelinkcategory'); ?></b></div>
            <div class="panel-body">
                <div class="table-responsive" id="village">
                    <table class="table table-striped table-bordered table-hover" id="tableusglnkcat">
                        <thead >
                            <tr> 
                                <?php
                                echo "<td style='font-weight:bold'>" . __('lblsrno') . "</b></td>";
                                echo "<td style='font-weight:bold;text-align:center;'>" . __('lblusamaincat') . "</td>";
                                echo "<td style='font-weight:bold;text-align:center;'>" . __('lblUsagesubcategoryhead') . "</td>";
                                echo "<td style='font-weight:bold;text-align:center;'>" . __('lblsubsubcategorydesc') . "</td>";
//                                    echo "<td style='font-weight:bold;text-align:center;'>" . __('lblitemlistname') . "</td>";
                                echo "<td style='font-weight:bold;text-align:center;width:8%;'>" . __('lblaction') . "</td>";
                                ?>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php
                            $srno = 1;
                            foreach ($ucatlinkage as $uclk) {
                                $uclk = $uclk[0];
                                echo "<tr><td>" . $srno++ . "</td><td>" . $uclk['usage_main_catg_desc_' . $lang] . "</td><td>" . $uclk['usage_sub_catg_desc_' . $lang] . "</td><td>" . $uclk['usage_sub_sub_catg_desc_' . $lang] . "</td>";
                                echo "<td style='text-align: center;'>"
//                                    . $this->Form->button('<span class="glyphicon glyphicon-pencil"></span>', array('class' => "btn btn-default", 'onclick' => 'javascript: return formupdate(' . $uclk[$name[0]] . ',' . $uclk[$name[2]] . ',' . $uclk[$name[3]] . ',' . $uclk[$name[4]] . ')'))
                                . $this->Form->button('<span class="glyphicon glyphicon-remove"></span>', array('class' => "btn btn-default", 'onclick' => 'javascript: return formdelete(' . $uclk['usage_cat_id'] . ')'))
                                . "</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>


        </div>
    </div>
    <input type="hidden" name="hid" id="hid">
    <input type="hidden" name="hdnDesc" id="hdnDesc">
    <input type="hidden" name="action" id="actionid" value="<?php echo $actontype; ?>"> 
</div>

<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>
