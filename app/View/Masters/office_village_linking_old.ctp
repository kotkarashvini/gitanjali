<script>
    $(document).ready(function () {

        $('#district_id').change(function () {

            var dist = $("#district_id option:selected").val();
            $.getJSON('<?php echo $this->webroot; ?>Property/get_corp_list_new', {district: dist}, function (data)
            {
                var sc = '<option>--select--</option>';
                $.each(data.corp, function (index, val) {

                    sc += "<option value=" + index + ">" + val + "</option>";
                });

                $("#corp_id option").remove();
                $("#corp_id").append(sc);
                getTaluka(dist);

            });

        });
        // Circle
        $('#taluka_id').change(function () {
            var tal = $("#taluka_id option:selected").val();

            $.getJSON('<?php echo $this->webroot; ?>Property/taluka_change_event_new', {tal: tal}, function (data)
            {
                var sc = '<option>--select--</option>';
                $.each(data.village, function (index, val) {

                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#village_id option").remove();
                $("#village_id").append(sc);
                $("#lblvillage_id").show();
                $("#lblcitytown").hide();

                $.getJSON('<?php echo $this->webroot; ?>Property/get_corp_list_new', {taluka: tal}, function (data)
                {
                    var sc = '<option>--select--</option>';
                    $.each(data.corp, function (index, val) {
                        sc += "<option value=" + index + ">" + val + "</option>";
                    });
                    $("#corp_id option").remove();
                    $("#corp_id").append(sc);
                });
            });
        });

        $('#corp_id').change(function () {
            var corp = $("#corp_id option:selected").val();
            $.getJSON('<?php echo $this->webroot; ?>Property/corp_change_event_new', {corp: corp}, function (data)
            {
                var sc2 = '<option>--select--</option>';
                $.each(data.village, function (index, val) {
                    sc2 += "<option value=" + index + ">" + val + "</option>";
                });
                $("#village_id").prop("disabled", false);
                $("#village_id option").remove();
                $("#village_id").append(sc2);
                $("#lblvillage_id").hide();
                $("#lblcitytown").show();
                $("#villagetable").show();
                //display checkbox
                $("#checkboxes").html('');
                var html = '';
                var f = 0;
                $.each(data.village, function (index, val) {
                    if (f == 0)
                    {
                        f++;
                        html = html + "<tr><th><input name='village_id[]' type='checkbox' value='" + index + "' > &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <label>" + val + "</label></th>";
                    } else if (f == 1)
                    {
                        f++;
                        html = html + "<th><input name='village_id[]' type='checkbox' value='" + index + "' > &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <label>" + val + "</label> </th>";
                    } else {
                        html = html + "<th><input name='village_id[]' type='checkbox' value='" + index + "' > &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <label>" + val + "</label> </th></tr>";
                        f = 0;
                    }

                });
                if (f == 1)
                {
                    html = html + "<td></td><td></td></tr>";
                } else if (f == 2)
                {
                    html = html + "<td></td></tr>";
                }

                $('#checkboxestbl').DataTable().destroy();
                $("#checkboxes").html(html);
                $('#checkboxestbl').show();
                $('#listbox').show();
                $('#checkboxestbl').dataTable({
                    "iDisplayLength": 5,
                    "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
                });

            });

            $('#office_id').change(function () {
                var office = $("#office_id option:selected").val();

                $.getJSON('<?php echo $this->webroot; ?>Masters/get_office_data', {office: office}, function (data)
                {

                    $("input:checkbox").each(function () {
                        $(this).prop('checked', false);
                    });


                    $('#aaaa').find('option:not(:first)').remove();

                    $.each(data, function (index, val) {
                        $("input:checkbox").each(function () {
                            var cval = $(this).val();
                            if (val == cval)
                            {
                                $(this).prop('checked', true);
                                $('#aaaa').append($('<option>', {
                                    value: index,
                                    text: $(this).next('label').text()
                                }));

                            }
                        });
                    });

                });
            });

        });


        //------------------------------------------------office change function ---------------------------------------------


//        $('#office_id').change(function () {
//            var office = $("#office_id option:selected").val();
//            $.getJSON('<?php echo $this->webroot; ?>Property/office_change_event', {office: office}, function (data)
//            {
//                $.each(data, function (index, val) {
//                    $("input:checkbox").each(function () {
//                        var cval = $(this).val();
//                        if (val == cval)
//                        {
//                            $(this).prop('checked', true);
//                            $('#aaaa').append($('<option>', {
//                                value: index,
//                                text: $(this).next('label').text()
//                            }));
//
//                        }
//                    });
//                });
//
//            });
//
//
//
//
//
//
//        });
//
//







        $('#taluka_id').change(function () {
            var tal = $("#taluka_id option:selected").val();
            $.getJSON('<?php echo $this->webroot; ?>Property/taluka_change_event_new', {tal: tal}, function (data)
            {
                var sc2 = '<option>--select--</option>';
                $.each(data.village, function (index, val) {
                    sc2 += "<option value=" + index + ">" + val + "</option>";
                });
                $("#village_id").prop("disabled", false);
                $("#village_id option").remove();
                $("#village_id").append(sc2);
                $("#lblvillage_id").hide();
                $("#lblcitytown").show();

                $("#villagetable").show();
                //display checkbox
                $("#checkboxes").html('');
                var html = '';
                var f = 0;
                $.each(data.village, function (index, val) {
                    if (f == 0)
                    {
                        f++;
//                            alert(f);
                        html = html + "<tr><th><input name='village_id[]' type='checkbox' value=' " + index + "' > &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <label>" + val + "</label> </th>";
                    } else if (f == 1)
                    {
                        f++;
//                            alert(f);
                        html = html + "<th><input name='village_id[]' type='checkbox' value='" + index + "' > &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <label>" + val + "</label> </th>";
                    } else {
                        html = html + "<th><input name='village_id[]' type='checkbox' value='" + index + "' > &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <label>" + val + "</label> </th></tr>";
                        f = 0;
                    }
//                 alert(html);
                });
                if (f == 1)
                {
                    html = html + "<td></td><td></td></tr>";
                } else if (f == 2)
                {
                    html = html + "<td></td></tr>";
                }

                $('#checkboxestbl').DataTable().destroy();
                $("#checkboxes").html(html);
                $('#checkboxestbl').show();
//            checkboxestbl        $('#checkboxestbl').destroy();

                $('#checkboxestbl').dataTable({
                    "iDisplayLength": 5,
                    "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
                });

            });

            $('#office_id').change(function () {
                var office = $("#office_id option:selected").val();
         
                $.getJSON('<?php echo $this->webroot; ?>Masters/get_office_data', {office: office}, function (data)
                {

                    $("input:checkbox").each(function () {
                        $(this).prop('checked', false);
                    });


                    $('#aaaa').find('option:not(:first)').remove();

                    $.each(data, function (index, val) {
                        $("input:checkbox").each(function () {
                            var cval = $(this).val();
                            if (val == cval)
                            {
                                $(this).prop('checked', true);
                                $('#aaaa').append($('<option>', {
                                    value: index,
                                    text: $(this).next('label').text()
                                }));

                            }
                        });
                    });

                });
            });
            
              $("#selectall").click(function () {

                $('#checkboxestbl input[type="checkbox"]').each(function () {

                    $(this).prop('checked', true);
                });
            });

        });

        function getTaluka(dist) {
            //alert(dist);
            $.getJSON("<?php echo $this->webroot; ?>Property/district_change_event_new", {dist: dist}, function (data)
            {
                var sc = '<option>--select--</option>';
                $.each(data.taluka, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#taluka_id").prop("disabled", false);
                $("#taluka_id option").remove();
                $("#taluka_id").append(sc);



            });
        }
    });

    function formadd() {
        document.getElementById("actiontype").value = '1';
        document.getElementById("hfaction").value = 'S';
    }

    function forcancel() {
        document.getElementById("actiontype").value = '2';
    }


</script>
<?php echo $this->Form->create('office_village_linking', array('id' => 'office_village_linking')); ?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblofclvljlink'); ?></h3></center>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label"><?php echo __('lbladmdistrict'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('district_id', array('options' => array($districtdata), 'empty' => '--select--', 'id' => 'district_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                            <span id="district_id_error" class="form-error"><?php echo $errarr['district_id_error']; ?></span>
                        </div>
                        <label for="" class="col-sm-3 control-label"><?php echo __('lbladmtaluka'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('taluka_id', array('options' => $taluka, 'empty' => '--select--', 'id' => 'taluka_id', 'class' => 'form-control input-sm chosen-select', 'label' => false)); ?>
                       <span id="taluka_id_error" class="form-error"><?php echo $errarr['taluka_id_error']; ?></span>
                        </div>

                    </div>
                </div>
                <div  class="rowht"></div> 
                <div class="row">
                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label"><?php echo __('lblcorporation'); ?><span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('corp_id', array('options' => array($corpclasslist), 'empty' => '--select--', 'id' => 'corp_id', 'class' => 'form-control input-sm chosen-select', 'label' => false)); ?>
                       <!--<span id="corp_id_error" class="form-error"><?php //echo $errarr['corp_id_error']; ?></span>-->
                        </div>
                        <label for="" class="col-sm-3 control-label"><?php echo __('lblselofc'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('office_id', array('options' => array($officedata), 'empty' => '--select--', 'id' => 'office_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                        <span id="office_id_error" class="form-error"><?php echo $errarr['office_id_error']; ?></span>
                        </div>

                    </div>
                </div>

                <div  class="rowht"></div> 
                
                <div class="row" id="temp">
                    <div id="checkboxestemp">


                    </div>
                    <div class="col-sm-8 ">
                        <table class="table" id="checkboxestbl" hidden="true">
                            <thead>
                                <tr>
                                    <th id="villagetable" width="30%" ><?php echo __('lblselvillage'); ?></th>
                                   <th  id="selectall" width="30%">Select All

                                    </th>
                                    <th  width="30%"></th>
                                </tr>
                            </thead>
                            <tbody id="checkboxes">
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr> 
                            </tbody>

                        </table>

                    </div>
                    <div class="col-sm-4" id="listbox" hidden="true">
                        <?php echo $this->Form->input('zazzz', array('type' => 'select', 'id' => 'aaaa', 'class' => 'form-control input-sm', 'label' => false, 'multiple' => 'multiple')); ?>

                    </div> 
                </div>
                <div class="row center">
                    <div class="form-group">
                        <div class="col-sm-12 tdselect">
                            <button id="btnadd" name="btnadd" class="btn btn-info "  onclick="javascript: return formadd();">
                                <span class="glyphicon glyphicon-plus"></span><?php echo __('btnsave'); ?></button>
                            <button id="btnadd" name="btncancel" class="btn btn-info "  onclick="javascript: return forcancel();">
                                <span class="glyphicon glyphicon-floppy-remove"></span>&nbsp;&nbsp; <?php echo __('btncancel'); ?></button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
    <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>

</div>