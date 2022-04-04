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
        $('#tableusglnkcat').dataTable({
            "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });

        if ($("#addFlag").val() == 'Y') {
            $("#main_id").val('<?php echo $mcat; ?>');
            getsubcat('<?php echo $mcat; ?>', '<?php echo $scat; ?>');
            getsubsubcat('<?php echo $scat; ?>', '<?php echo $sscat; ?>');
            getLinkedItem('<?php echo $mcat; ?>', '<?php echo $scat; ?>', '<?php echo $sscat; ?>');
        }

        $("#btnSave").click(function () {
            $(':input').each(function () {
                $(this).val($.trim($(this).val()));
            });


            try {
                //Block of code to try
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
                }
                else {
//                    $("#hid").val(id);

                    $('#usagelnkitemlist').submit();
                }
            }
            catch (err) {
                // Block of code to handle errors
                alert(err);
                return false;
            }





        });


        $('#main_id').change(function () {

            var main_id = $("#main_id option:selected").val();
            getsubcat(main_id, '');

        });

        // usage sub  categry click dependency
        $('#sub_id').change(function () {
            var sub_id = $("#sub_id option:selected").val();
            getsubsubcat(sub_id, '');
        });

        $("#item_list_id").change(function () {
            $.getJSON('<?php echo $this->webroot; ?>getitemtype', {itemid: $(this).val()}, function (itemtypedata)
            {
                if (itemtypedata == 5) {
                    $(".div_itemtype5").show();
                } else {
                    $(".div_itemtype5").hide();
                }
            });
        });

        $('#main_id1').change(function () {
            $.getJSON('getusagesub1', {main_id: $(this).val()}, function (data)
            {
                var sc = '<option value="">select</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#sub_id1 option").remove();
                $("#sub_id1").append(sc);


            });
        });

        // usage sub  categry click dependency
        $('#sub_id1').change(function () {
            var sub_id = $("#sub_id1 option:selected").val();
            $.getJSON('getusagesubsub1', {sub_id: sub_id}, function (data)
            {
                var sc = '<option value="">select</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#sub_sub_id1 option").remove();
                $("#sub_sub_id1").append(sc);
            });
        });

        $("#sub_sub_id").change(function () {
            getLinkedItem();
        });
    });
//    function formupdate(id, mid, sid, ssid, itlist, constr, depre, roadv, userd1, userd2) {
    function formupdate(userd2, userd1, roadv, depre, constr, id, mid, sid, ssid, itlist, mid1, sid1, ssid1) {
//        alert(mid+" - " +sid+" - " +ssid);
        $.getJSON('getusagesub1', {main_id: mid}, function (data)
        {
            var sc = '<option value="">select</option>';
            $.each(data, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $("#sub_id option").remove();
            $("#sub_id").append(sc);
            $("#sub_id").val(sid);
            $.getJSON('getusagesubsub1', {sub_id: sid}, function (data)
            {
                var sc = '<option value="">select</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#sub_sub_id option").remove();
                $("#sub_sub_id").append(sc);
                $("#sub_sub_id").val(ssid);
            });
        });

//
//        if (constr == '') {
//            $("#construction_type_id").prop("disabled", true);
//            $('#constructiontype').hide();
//            $('#constructiontype1').hide();
//        } else {
//
//            $("#construction_type_id").prop("disabled", false);
//            $('#constructiontype').show();
//            $('#constructiontype1').show();
//        }
//        if (depre != '') {
//            $("#depreciation_id").prop("disabled", false);
//            $('#depreciationtype').show();
//            $('#depreciationtype1').show();
//        } else {
//            $("#depreciation_id").prop("disabled", true);
//            $('#depreciationtype').hide();
//            $('#depreciationtype1').hide();
//        }
//        if (roadv != '') {
//            $("#road_vicinity_id").prop("disabled", false);
//            $('#roadvicinity').show();
//            $('#roadvicinity1').show();
//        } else {
//            $("#road_vicinity_id").prop("disabled", true);
//            $('#roadvicinity').hide();
//            $('#roadvicinity1').hide();
//        }
//        if (userd1 != '') {
//            $("#user_defined_dependency1_id").prop("disabled", false);
//            $('#ud1').show();
//            $('#ud11').show();
//        } else {
//            $("#user_defined_dependency1_id").prop("disabled", true);
//            $('#ud1').hide();
//            $('#ud11').hide();
//        }
//        if (userd2 != '') {
//            $("#user_defined_dependency2_id").prop("disabled", false);
//            $('#ud2').show();
//            $('#ud21').show();
//        } else {
//            $("#user_defined_dependency2_id").prop("disabled", true);
//            $('#ud2').hide();
//            $('#ud21').hide();
//        }
        $("#item_list_id").val(itlist);
        $.getJSON('<?php echo $this->webroot; ?>getitemtype', {itemid: itlist}, function (itemtypedata)
        {
            if (itemtypedata == 5) {
                $(".div_itemtype5").show();
                $("#main_id1").val(mid1);
                $.getJSON('getusagesub1', {main_id: mid1}, function (data)
                {
                    var sc = '<option value="">select</option>';
                    $.each(data, function (index, val) {
                        sc += "<option value=" + index + ">" + val + "</option>";
                    });
                    $("#sub_id1 option").remove();
                    $("#sub_id1").append(sc);
                    $("#sub_id1").val(sid1);
                    $.getJSON('getusagesubsub1', {sub_id: sid1}, function (data)
                    {
                        var sc = '<option value="">select</option>';
                        $.each(data, function (index, val) {
                            sc += "<option value=" + index + ">" + val + "</option>";
                        });
                        $("#sub_sub_id1 option").remove();
                        $("#sub_sub_id1").append(sc);
                        $("#sub_sub_id1").val(ssid1);
                    });
                });

            } else {
                $(".div_itemtype5").hide();
            }
        });

        $("#hid").val(id);
        $("#main_id").val(mid);
        $("#sub_id").val(sid);
        $("#sub_sub_id").val(ssid);
        $("#construction_type_id").val(constr);
        $("#depreciation_id").val(depre);
        $("#road_vicinity_id").val(roadv);
        $("#user_defined_dependency1_id").val(userd1);
        $("#user_defined_dependency2_id").val(userd2);
        $("#actionid").val('U');
        window.scrollTo(500, 200);
        return false;
    }
    function getsubcat(main_id, subcat) {
        $.getJSON('getusagesub1', {main_id: main_id}, function (data)
        {
            var sc = '<option value="">select</option>';
            $.each(data, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $("#sub_id option").remove();
            $("#sub_id").append(sc);
            $("#sub_id").val(subcat);
            getLinkedItem();
        });
    }
    function getsubsubcat(sub_id, subsub_id) {
        $.getJSON('getusagesubsub1', {sub_id: sub_id}, function (data)
        {
            var sc = '<option value="">select</option>';
            $.each(data, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            $("#sub_sub_id option").remove();
            $("#sub_sub_id").append(sc);
            $("#sub_sub_id").val(subsub_id);
            getLinkedItem();
        });
    }
    function formdelete(id) {
        $("#hid").val(id);
        $("#actionid").val('D');
//        return false;
    }

    function getLinkedItem() {
        $.getJSON('<?php echo $this->webroot; ?>getLnkItemList', {mcat_id: $("#main_id option:selected").val(), scat_id: $("#sub_id option:selected").val(), sscat_id: $("#sub_sub_id option:selected").val()}, function (data)
        {
            var sc = "";
            sc += '<table  id="tableusglnkcat" class="table table-striped table-bordered table-hover">';
            sc += "<thead ><tr>";
            sc += "<td align=center><b><?php echo __('lblsrno'); ?></b></td>";
            sc += "<td align=center><b><?php echo __('lblusamaincat'); ?></b></td>";
            sc += "<td align=center><b><?php echo __('lblUsagesubcategoryhead'); ?></b></td>";
            sc += "<td align=center><b><?php echo __('lblsubsubcategorydesc'); ?></b></td>";
            sc += "<td align=center><b><?php echo __('lblitemlistname'); ?></b></td>";
            sc += "<td align=center><b><?php echo __('lblaction'); ?></b></td>";
            sc += "</tr></thead><tbody>";
            $i = 1;
            $.each(data, function (key, val) {
                sc += "<tr>";
                sc += "<td align=center width=5%><b>" + $i++ + "</b></td>";
                sc += "<td>" + val[0]['usage_main_catg_desc_en'] + "</td>";
                sc += "<td>" + val[0]['usage_sub_catg_desc_en'] + "</td>";
                sc += "<td>" + val[0]['usage_sub_sub_catg_desc_en'] + "</td>";
                sc += "<td>" + val[0]['usage_param_desc_en'] + "</td>";
                sc += "<td align=center width=8%>" + "<button class='btn btn-default' onClick='return formupdate(" + val[0]['user_defined_dependency2_id'] + "," + val[0]['user_defined_dependency1_id'] + "," + val[0]['road_vicinity_id'] + "," + val[0]['depreciation_id'] + "," + val[0]['construction_type_id'] + "," + val[0]['usage_lnk_id'] + "," + val[0]['usage_main_catg_id'] + "," + val[0]['usage_sub_catg_id'] + "," + val[0]['usage_sub_sub_catg_id'] + "," + val[0]['usage_param_id'] + "," + val[0]['main_cat_id'] + "," + val[0]['sub_cat_id'] + "," + val[0]['sub_sub_cat_id'] + ");'><span class='glyphicon glyphicon-pencil'></span> </button>";
                sc += "<button class='btn btn-default' onClick='return formdelete(" + val[0]['usage_lnk_id'] + ");'><span class='glyphicon glyphicon-remove'></span> </button>";
                sc += "</td></tr>";
            });
            sc += '</tbody></table>';
            $("#linkItemlistdiv").text('');
            $("#linkItemlistdiv").append(sc);
        });
    }

</script>
<?php echo $this->Form->create('usagelistwithitem', array('id' => 'usagelnkitemlist')); ?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading" style="text-align: center"><b><?php echo __('lblusagelinkitemlist'); ?></b></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label"><?php echo __('lblusamaincat'); ?><span style="color: #ff0000">*</span></label> 
                            <label for="" class="col-sm-2 control-label"><?php echo __('lblUsagesubcategoryhead'); ?><span style="color: #ff0000">*</span></label>
                            <label for="" class="col-sm-2 control-label"><?php echo __('lblsubsubcategorydesc'); ?><span style="color: #ff0000">*</span></label>
                            <label for="" class="col-sm-2 control-label" id="constructiontype" hidden="true">Construction Type<span style="color: #ff0000">*</span></label>
                            <label for="" class="col-sm-2 control-label" id="depreciationtype" hidden="true">Depreciation Type<span style="color: #ff0000">*</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="col-sm-2">
                                <?php echo $this->Form->input($name[1], array('options' => $usgmain, 'empty' => '--select--', 'id' => 'main_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                            </div>
                            <div class="col-sm-2">
                                <?php echo $this->Form->input($name[2], array('empty' => '--select--', 'id' => 'sub_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                            </div>
                            <div class="col-sm-2">
                                <?php echo $this->Form->input($name[3], array('empty' => '--select--', 'id' => 'sub_sub_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                            </div>
                            <!--                            <div class="col-sm-2" id="constructiontype1" hidden="true">
                            <?php //echo $this->Form->input($name[10], array('options' => $constructiontype, 'empty' => '--select--', 'id' => 'construction_type_id', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled'));  ?>
                                                        </div>
                                                        <div class="col-sm-2" id="depreciationtype1" hidden="true">
                            <?php //echo $this->Form->input($name[11], array('options' => $depreciationtype, 'empty' => '--select--', 'id' => 'depreciation_id', 'label' => false, 'class' => 'form-control input-sm', 'disabled' => 'disabled'));  ?>
                                                        </div>-->
                        </div>
                    </div>
                </div>
                <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <!--<label for="" class="col-sm-1 control-label">&nbsp;</label>
                            <label for="" class="col-sm-2 control-label" id="roadvicinity" hidden="true">Road Vicinity<span style="color: #ff0000">*</span></label>
                            <label for="" class="col-sm-2 control-label" id="ud1" hidden="true">User Defined Dependency 1<span style="color: #ff0000">*</span></label>
                            <label for="" class="col-sm-2 control-label" id="ud2" hidden="true">User Defined Dependency 2<span style="color: #ff0000">*</span></label>-->
                            <label for="" class="col-sm-2 control-label"><?php echo __('lblitemlistname'); ?><span style="color: #ff0000">*</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <?php // pr($name);   ?>
                            <!--                            <div class="col-sm-2" id="roadvicinity1" hidden="true">
                            <?php //echo $this->Form->input($name[12], array('options' => array($roadvicinity), 'empty' => '--select--', 'id' => 'road_vicinity_id', 'class' => 'form-control input-sm', 'label' => false, 'disabled' => 'disabled'));  ?>
                                                        </div>
                                                        <div class="col-sm-2" id="ud11" hidden="true">
                            <?php //echo $this->Form->input($name[13], array('options' => array($userdependency1), 'empty' => '--select--', 'id' => 'user_defined_dependency1_id', 'class' => 'form-control input-sm', 'label' => false, 'disabled' => 'disabled'));  ?>
                                                        </div>
                                                        <div class="col-sm-2" id="ud21" hidden="true">
                            <?php //echo $this->Form->input($name[14], array('options' => array($userdependency2), 'empty' => '--select--', 'id' => 'user_defined_dependency2_id', 'class' => 'form-control input-sm', 'label' => false, 'disabled' => 'disabled'));  ?>
                                                        </div>-->
                            <div class="col-sm-2">
                                <?php echo $this->Form->input($name[4], array('options' => $usgitem, 'empty' => '--select--', 'id' => 'item_list_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                <div class="row div_itemtype5" hidden="true">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="col-sm-2"></div>
                            <label for="" class="col-sm-2 control-label"><?php echo __('lblusamaincat'); ?><span style="color: #ff0000">*</span></label> 
                            <label for="" class="col-sm-2 control-label"><?php echo __('lblUsagesubcategoryhead'); ?><span style="color: #ff0000">*</span></label>
                            <label for="" class="col-sm-2 control-label"><?php echo __('lblsubsubcategorydesc'); ?><span style="color: #ff0000">*</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                <div class="row div_itemtype5"  hidden="true">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2">
                                <?php echo $this->Form->input($name[16], array('options' => $usgmain, 'empty' => '--select--', 'id' => 'main_id1', 'label' => false, 'class' => 'form-control input-sm')); ?>
                            </div>
                            <div class="col-sm-2">
                                <?php echo $this->Form->input($name[17], array('empty' => '--select--', 'id' => 'sub_id1', 'label' => false, 'class' => 'form-control input-sm')); ?>
                            </div>
                            <div class="col-sm-2">
                                <?php echo $this->Form->input($name[18], array('empty' => '--select--', 'id' => 'sub_sub_id1', 'label' => false, 'class' => 'form-control input-sm')); ?>
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
                <div class="row" id="linkItemlistdiv">
                    <div class="col-lg-12">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="hid" id="hid">
    <input type="hidden" name="affflag" id="addFlag" value="<?php echo $addflag; ?>">
    <input type="hidden" name="action" id="actionid" value="<?php echo $actontype; ?>">  
</div>


<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>
