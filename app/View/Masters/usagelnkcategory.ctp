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
echo $this->Html->script('jquery.dataTables.min');
?>
<script>

    $(document).ready(function () {

        var host = "<?php echo $this->webroot; ?>";
        $('#usage_main_catg_id').change(function () {
            var usage_main_catg_id = $("#usage_main_catg_id").val();
            $.getJSON(host + "getsubcategory", {usage_main_catg_id: usage_main_catg_id}, function (data)
            {
                var sc = '<option>--Select--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#usage_sub_catg_id option").remove();
                $("#usage_sub_catg_id").append(sc);
            });
        });

        $('#usage_sub_catg_id').change(function () {
            var usage_main_catg_id = $("#usage_main_catg_id").val();
            var usage_sub_catg_id = $("#usage_sub_catg_id").val();
            $.getJSON(host + "getsubsubcategory", {usage_main_catg_id: usage_main_catg_id, usage_sub_catg_id: usage_sub_catg_id}, function (data)
            {
                var sc = '<option> --Select-- </option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#usage_sub_sub_catg_id option").remove();
                $("#usage_sub_sub_catg_id").append(sc);
            });
        });

        $('#usage_sub_sub_catg_id').change(function () {
            var usage_main_catg_id = $("#usage_main_catg_id").val();
            var usage_sub_catg_id = $("#usage_sub_catg_id").val();
            var usage_sub_sub_catg_id = $("#usage_sub_sub_catg_id").val();
            $.getJSON(host + "getparamlist", {usage_main_catg_id: usage_main_catg_id, usage_sub_catg_id: usage_sub_catg_id, usage_sub_sub_catg_id: usage_sub_sub_catg_id}, function (data)
            {
                var sc = "<li>";
                $.each(data, function (index, val) {
                    sc += "<ul>" + index + " : " + val + "</ul>";
                });
                sc += "</li>"
                $("#parameterlist").html();
                $("#parameterlist").html(sc);
            });

            $.getJSON(host + "getcdrflags", {usage_sub_sub_catg_id: usage_sub_sub_catg_id}, function (data)
            {
                var drflag = 'Y';
                if (data['contsruction_type_flag'] == 'Y') {
                    drflag = 'N';
                    $("#construction_type").show();
                    $("#lblconstructiontype").show();
                }
                else {
                    $("#construction_type").val('');
                    $("#construction_type").hide();
                    $("#lblconstructiontype").hide();
                }
                if (data['depreciation_flag'] == 'Y') {
                    drflag = 'N';
                    $("#depreciation").show();
                    $("#lbldepreciation").show();
                }
                else {
                    $("#depreciation").hide();
                    $("#depreciation").val('');
                    $("#lbldepreciation").hide();
                }
                if (data['road_vicinity_flag'] == 'Y') {
                    drflag = 'N';
                    $("#roadvicinity").show();
                    $("#lblroadvicinity").show();
                }
                else {
                    $("#roadvicinity").val('');
                    $("#roadvicinity").hide();
                    $("#lblroadvicinity").hide();
                }
                if (drflag === 'N') {
                    $("#eval_id option").remove();
                }
                else {
                    var ctype = $("#construction_type").val();
                    var dtype = $("#depreciation").val();
                    var rctype = $("#roadvicinity").val();
                    $.getJSON(host + "getrulebycdrv", {constuction_id: ctype, deprecition_id: dtype, rvicinity_id: rctype}, function (data)
                    {
                        var sc = '<option> --Select-- </option>';
                        $.each(data, function (index, val) {
                            sc += "<option value=" + index + ">" + val + "</option>";
                        });
                        $("#eval_id option").remove();
                        $("#eval_id").append(sc);
                    });
                }
            });
        });
        $("#construction_type,#depreciation,roadvicinity").change(function () {
            var ctype = $("#construction_type").val();
            var dtype = $("#depreciation").val();
            var rctype = $("#roadvicinity").val();
            $.getJSON(host + "getrulebycdrv", {constuction_id: ctype, deprecition_id: dtype, rvicinity_id: rctype}, function (data)
            {
                var sc = '<option> --Select-- </option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#eval_id option").remove();
                $("#eval_id").append(sc);
            });
        });
        $("#btnCancel").click(function () {
            $(":checkbox").attr("checked", false);
        });

        $("#btnSave").click(function () {
            $(':input').each(function () {
                $(this).val($.trim($(this).val()));
            });
            $('#formid').submit();
        });

    });



</script>

<script>
    $(document).ready(function () {
        $('#tableevalrule').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, -1], [5, 10, "All"]]
        });
    });
</script>
<?php
echo $this->Form->create('usagelnkcategory', array('id' => 'formid', 'class' => 'form-vertical'));
?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
<div class="panel-body">
    <div class="panel-group" id="accordion">
        <div class="panel panel-default">
            <div class="panel-heading" style="text-align: center;"><big><b><?php echo __('lblusagelinkcategory'); ?></b></big></div>
            <div id="collapseOne" class="panel-collapse collapse in">
                
                <div class="panel-body">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-3"></div>
                            <label for="usage_main_catg_id" class="control-label col-sm-2"><?php echo __('lblusamaincat'); ?></label>
                            <label for="usage_main_catg_id" class="control-label col-sm-2"><?php echo __('lblUsagesubcategoryhead'); ?></label>
                            <label for="usage_main_catg_id" class="control-label col-sm-2"><?php echo __('lblsubsubcategorydesc'); ?></label>

                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-2" ><?php echo $this->Form->input($name[1], array('options' => $maincat_id, 'empty' => '--Select Option--', 'multiple' => false, 'id' => 'usage_main_catg_id', 'label' => false, 'class' => 'form-control input-sm')); ?></div>
                            <div class="col-sm-2" ><?php echo $this->Form->input($name[2], array('type' => 'select', 'empty' => '--Select Option--', 'id' => 'usage_sub_catg_id', 'label' => false, 'class' => 'form-control input-sm')); ?></div>
                            <div class="col-sm-2" ><?php echo $this->Form->input($name[3], array('type' => 'select', 'empty' => '--Select Option--', 'id' => 'usage_sub_sub_catg_id', 'label' => false, 'class' => 'form-control input-sm')); ?></div>
                        </div>
                    </div>
                    <div class="row"><br>
                        <div class="col-sm-3"></div>
                        <div id="parameterlist"></div>
                    </div>
                    
                    <br>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-3"></div>
                            <label for="usage_main_catg_id" class="control-label col-sm-2" id="lblconstructiontype"><?php echo __('lblconstuctiontypehead'); ?></label>
                            <label for="usage_main_catg_id" class="control-label col-sm-2" id="lbldepreciation"><?php echo __('lbldepreciation'); ?></label>
                            <label for="usage_main_catg_id" class="control-label col-sm-2" id="lblroadvicinity"><?php echo __('lblroadvicinity'); ?></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">   
                            <div class="col-sm-3"></div>
                            <div class="col-sm-2" ><?php echo $this->Form->input($name[10], array('options' => $constructiontype, 'empty' => '--Select Option--', 'id' => 'construction_type', 'label' => false, 'class' => 'form-control input-sm')); ?></div>           
                            <div class="col-sm-2" ><?php echo $this->Form->input($name[11], array('options' => $depreciation, 'empty' => '--Select Option--', 'id' => 'depreciation', 'label' => false, 'class' => 'form-control input-sm')); ?></div>
                            <div class="col-sm-2" ><?php echo $this->Form->input($name[12], array('options' => $roadvicinitylist, 'empty' => '--Select Option--', 'id' => 'roadvicinity', 'label' => false, 'class' => 'form-control input-sm')); ?></div>
                        </div>
                    </div><br>                   
                    <div class="row">
                        <div class="form-group"> 
                            <div class="col-sm-3"></div>
                            <label for="eval rule" class="control-label col-sm-2" id="lblconstructiontype"><?php echo __('lblevalrule'); ?></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">  
                            <div class="col-sm-3"></div>
                            <div class="col-sm-4"><?php echo $this->Form->input($name[8], array('options' => $evalrule, 'empty' => '--select--', 'id' => 'eval_id', 'label' => false, 'class' => 'form-control input-sm')); ?></div>
                        </div>
                    </div>
                    <br/>
                    <div class="row" align="center">
                        <input type="button" value="Save" id="btnSave"  class="btn btn-primary">
                        <input type="reset" value="Cancel" id="btnCancel" class="btn btn-primary">
                        <input type="button" value="Exit" id="btnExit" class="btn btn-primary">
                        <?php echo $this->form->end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

