
<script>

    $(document).ready(function () {
        if ($('#hfhidden1').val() == 'Y') {
            $('#tableratedata').dataTable({
                "iDisplayLength": 10,
                "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
            });
        }

        $('#district_id').change(function () {
            var district = $("#district_id option:selected").val();
            $.getJSON("get_taluka_name", {district: district}, function (data)
            {
                var sc = '<option value="">--Select--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#taluka_id option").remove();
                $("#taluka_id").append(sc);
            });

          $("#locationgrid").hide();
          $("#village_id option").remove();
           $("#level_1_id option").remove();



        })

        $('#taluka_id').change(function () {
            var district = $("#district_id option:selected").val();
            var tal = $("#taluka_id option:selected").val();


            $.getJSON("get_village", {tal: tal}, function (data)
            {

                var sc = '<option value="">--Select--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#village_id option").remove();
                $("#village_id").append(sc);
            });

            $("#locationgrid").hide();
             $("#level_1_id option").remove();
        });

        $('#village_id').change(function () {

            var village_id = $("#village_id option:selected").val();

            $.getJSON("get_location", {village_id: village_id}, function (data)
            {

                var sc = '<option value="">--Select--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#level_1_id option").remove();
                $("#level_1_id").append(sc);
            });
              $("#locationgrid").hide();
        })

        $('#level_1_id').change(function () {
            var district = $("#district_id option:selected").val();
            var taluka = $("#taluka_id option:selected").val();
            var village_id = $("#village_id option:selected").val();
            var level_1_id = $("#level_1_id option:selected").val();


            $.post('get_ll_field', {level_1_id: level_1_id}, function (data)
            {
                $('#level_1_desc_ll').val(data);

            });

  $("#locationgrid").show();
            $.post("locationlistgrid", {district: district, taluka: taluka, village_id: village_id, level_1_id: level_1_id}, function (data)
            {
                $("#locationgrid").html(data);
                 $(document).trigger('_page_ready');
            });
        })

       


    });

    function formupdatelocation() {

        var level_name = $('#level_1_desc_ll').val();
        var id = $("#level_1_id option:selected").val();
        $checkflag = valiadte_level1();
        if ($checkflag == 1) {
            $.post('update_location', {id: id, level_name: level_name}, function (data)
            {

                if (data != 'F') {
                    alert('Record Updated Successfully');
                    $('#level_1_desc_ll').val(level_name);
                } else {
                    alert('Error');
                    return false;
                }
            });
        }
    }

    function valiadte_level1() {

//district
 var regex = /^[0-9]+$/;
       // if ($('#district_id').length > 0 && $('#district_id').children('option').length > 1 && $('#district_id').is(":visible")) { // FOR CHECK  DYNAMIC FIELDS  EXIST
            if (!regex.test($('#district_id').val())) {
                $('#district_id_error').html('Selection required');
                $("#district_id").parent().addClass("field-error");
                $("#district_id").focus();
                return false;
            } else {
                $('#district_id_error').html('');
                $("#district_id").parent().removeClass("field-error");
            }
      //  } // END FIELD CHECK
      
 //taluka
   var regex = /^[0-9]+$/;
       // if ($('#taluka_id').length > 0 && $('#designation_id').children('option').length > 1 && $('#designation_id').is(":visible")) { // FOR CHECK  DYNAMIC FIELDS  EXIST
            if (!regex.test($('#taluka_id').val())) {
                $('#taluka_id_error').html('Selection required');
                $("#taluka_id").parent().addClass("field-error");
                $("#taluka_id").focus();
                return false;
            } else {
                $('#taluka_id_error').html('');
                $("#taluka_id").parent().removeClass("field-error");
            }
      //  } // END FIELD CHECK
       
//village
  var regex = /^[0-9]+$/;
       // if ($('#village_id').length > 0 && $('#village_id').children('option').length > 1 && $('#village_id').is(":visible")) { // FOR CHECK  DYNAMIC FIELDS  EXIST
            if (!regex.test($('#village_id').val())) {
                $('#village_id_error').html('Selection required');
                $("#village_id").parent().addClass("field-error");
                $("#village_id").focus();
                return false;
            } else {
                $('#village_id_error').html('');
                $("#village_id").parent().removeClass("field-error");
            }
       // } // END FIELD CHECK
      

//location

        var regex = /^[0-9]+$/;
        //if ($('#level_1_id').length > 0 && $('#level_1_id').children('option').length > 1 && $('#level_1_id').is(":visible")) { // FOR CHECK  DYNAMIC FIELDS  EXIST
            if (!regex.test($('#level_1_id').val())) {
                $('#level_1_id_error').html('Selection required');
                $("#level_1_id").parent().addClass("field-error");
                $("#level_1_id").focus();
                return false;
            } else {
                $('#level_1_id_error').html('');
                $("#level_1_id").parent().removeClass("field-error");
            }
      //  } // END FIELD CHECK
      
        var regex = /^[\u0A00-\u0A7F\s]*$/;
      //  if ($('#level_1_desc_ll').length > 0 && $('#level_1_desc_ll').is(':visible')) { // FOR CHECK  DYNAMIC FIELDS

            if (!regex.test($('#level_1_desc_ll').val())) {
                $('#level_1_desc_ll_error').html('Do not enter any character rather than Punjabi/Gurumukhi');
                $("#level_1_desc_ll").parent().addClass("field-error");
                $("#level_1_desc_ll").focus();
                return false;
            } else {
                $('#level_1_desc_ll_error').html('');
                $("#level_1_desc_ll").parent().removeClass("field-error");
            }
      //  } // END FIELD CHECK

        return 1;
    }

</script>

<?php echo $this->Form->create('location', array('id' => 'location')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title" style="font-weight: bolder"><?php echo __('Update Location List'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Property Rate Chart/rate_<?php echo $lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group"><br>
                        <div class="col-sm-2">
                            <label for="district_id" class="control-label"><?php echo __('lbladmdistrict'); ?><span style="color: #ff0000">*</span></label>
<?php echo $this->Form->input('district_id', array('options' => array($districtdata), 'empty' => '--select--', 'id' => 'district_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                            <span id="district_id_error" class="form-error"><?php //echo $errarr['district_id_error'];                   ?></span>


                        </div>

                        <div class="col-sm-2">
                            <label for="taluka_id" class="control-label"><?php echo __('lbladmtaluka'); ?><span style="color: #ff0000">*</span></label>
<?php echo $this->Form->input('taluka_id', array('options' => array($talukadata), 'empty' => '--select--', 'id' => 'taluka_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                            <span id="taluka_id_error" class="form-error"><?php //echo $errarr['taluka_id_error'];                   ?></span>

                        </div>

                        <div class="col-sm-2">
                            <label for="village_id" class="control-label"><?php echo __('lbladmvillage'); ?><span style="color: #ff0000">*</span></label>
<?php echo $this->Form->input('village_id', array('empty' => '--select--', 'id' => 'village_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                            <span id="village_id_error" class="form-error"><?php //echo $errarr['village_id_error'];                   ?></span>
                        </div>
                        <div class="col-sm-2">
                            <label for="level_1_id" class="control-label"><?php echo __('Location'); ?><span style="color: #ff0000">*</span></label>
<?php echo $this->Form->input('level_1_id', array('empty' => '--select--', 'id' => 'level_1_id', 'label' => false, 'class' => 'form-control input-sm')); ?>

                            <span id="level_1_id_error" class="form-error"><?php //echo $errarr['level_1_id_error'];                   ?></span>
                        </div>
                        <div class="col-sm-2">
                            <label for="level_1_id" class="control-label"><?php echo __('Location(Punjabi)'); ?><span style="color: #ff0000">*</span></label>
<?php echo $this->Form->input('level_1_desc_ll', array('label' => false, 'id' => 'level_1_desc_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="level_1_desc_ll_error" class="form-error"><?php //echo $errarr['level_1_desc_ll_error'];                   ?></span>

                        </div>
                        <div class="col-sm-2">
                            <input type='button' name='Save' value='Save'  onclick="javascript: return formupdatelocation();"  >
                        </div>

                    </div>
                </div><br><br>


                <div class="rowht"></div>
                <div class="rowht"></div>

                <div id="locationgrid" class="table-responsive">                   
                </div>
            </div>
        </div>
    </div>

    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
    <!--<input type='hidden' value='<?php // echo $saveflag;         ?>' name='saveflag' id='saveflag'/>
    <input type='hidden' value='<?php // echo $selectflag;         ?>' name='selectflag' id='selectflag'/>
    <input type='hidden' value='<?php // echo $surveyno;         ?>' name='surveyno' id='surveyno'/>
    <input type='hidden' value='<?php // echo $actiontypeval;         ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php // echo $hfvillage;         ?>' name='hfvillage' id='hfvillage'/>-->
</div>

<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>
