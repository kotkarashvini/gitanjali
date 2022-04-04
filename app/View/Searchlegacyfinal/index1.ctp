
<?php
echo $this->Html->script('bootstrap-datepicker.min');
echo $this->Html->css('bootstrap-datepicker3.min');
//echo $this->element("Helper/jqueryhelper");
//echo $this->element("Citizenentry/property_menu");
?>

<script type="text/javascript">
    $(document).ready(function () {
 
 var ref_no = $("#reference_no").val();
// alert(inp);
if(ref_no!='')
{
     $('#div_refernce_no').show();
}

        $('.date').datepicker({
            format: "dd-mm-yyyy",
            // format: "yyyy-mm-dd",
           // todayBtn: "linked",
            calendarWeeks: true,
            autoclose: true,
            todayHighlight: true,
            //endDate: 'today'
            endDate:'-1d'
          
        });

        $('#exec_date').change(function ()
        {

            var date = $('#exec_date').val().split("-");
            console.log(date, $('#exec_date').val())
            day = date[2];
            month = date[1];
            year = date[0];
            $('#year_for_token').val(day);

        });

        $('#final_doc_reg_no').change(function ()
        {
            // alert("Hii");
            var reg_no = $('#final_doc_reg_no').val();
            //alert(reg_no);
            $.getJSON('get_reg_no', {reg_no: reg_no}, function (data)
            {
                //
                //pr(data);
                if (data == '')
                {

                }
                else if (data != '')
                {
                    alert("This Registration number is already present");
                }

            });

        });
        
          $('#doc_entered_district').change(function ()
        {

            var districtid1 = $("#doc_entered_district option:selected").val();
           // alert(districtid);
            $.post("<?php echo $this->webroot; ?>LegacyGeneralinfo/getsubdivision1", {districtid: districtid1}, function (data)
                {
                 
                    var sc = '<option value="">--select--</option>';
                    $.each(data, function (index, val) {
                        sc += "<option value=" + index + ">" + val + "</option>";
                    });
                   
                    $("#doc_entered_subdivision option").remove();
                    $("#doc_entered_subdivision").append(sc);

                }, 'json');
        });
          $('#doc_entered_subdivision').change(function ()
        {

            var subdivisionid = $("#doc_entered_subdivision option:selected").val();
            //alert(subdivisionid);
            $.post("<?php echo $this->webroot; ?>LegacyGeneralinfo/getoffice1", {subdivisionid: subdivisionid}, function (data)
                {
                 
                var sc = '<option>--Select Office--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#doc_entered_office option").remove();
                $("#doc_entered_office").append(sc);
                
                //  $("#doc_entered_office option").remove();
                // $("#doc_entered_office").append(sc);
                }, 'json');
            
            
        });

        $('#doc_type').change(function ()
        {

            var doc_id = $("#doc_type option:selected").val();

            if (doc_id == 1 || doc_id == 3)
            {
                // alert(doc_id);
                $('#div_refernce_no').show();
            }
            else
            {
                $('#div_refernce_no').hide();
            }

        });




//        $('#district_id').change(function ()
//        {
//
//            var districtid = $("#district_id option:selected").val();
//           // alert(districtid);
//            $.post("<?php //echo $this->webroot; ?>LegacyGeneralinfo/gettaluka", {districtid: districtid}, function (data)
//                {
//                 
//                    var sc = '<option value="">--select--</option>';
//                    $.each(data, function (index, val) {
//                        sc += "<option value=" + index + ">" + val + "</option>";
//                    });
//                   
//                    $("#taluka_id option").remove();
//                    $("#taluka_id").append(sc);
//
//                }, 'json');
//        });




  $('#district_id').change(function ()
        {

            var districtid = $("#district_id option:selected").val();
           // alert(districtid);
            $.post("<?php echo $this->webroot; ?>LegacyGeneralinfo/getsubdivision", {districtid: districtid}, function (data)
                {
                 
                    var sc = '<option value="">--select--</option>';
                    $.each(data, function (index, val) {
                        sc += "<option value=" + index + ">" + val + "</option>";
                    });
                   
                    $("#subdivision_id option").remove();
                    $("#subdivision_id").append(sc);

                }, 'json');
        });


        

//        $('#taluka_id').change(function ()
//        {
//
//            var talukaid = $("#taluka_id option:selected").val();
//            
//            $.post("<?php //echo $this->webroot; ?>LegacyGeneralinfo/getoffice", {talukaid: talukaid}, function (data)
//                {
//                 
//                var sc = '<option>--Select Office--</option>';
//                $.each(data, function (index, val) {
//                    sc += "<option value=" + index + ">" + val + "</option>";
//                });
//                $("#office_id option").remove();
//                $("#office_id").append(sc);
//                
//                 $("#doc_entered_office option").remove();
//                $("#doc_entered_office").append(sc);
//                }, 'json');
//            
//            
//        });





 $('#subdivision_id').change(function ()
        {

            var subdivisionid = $("#subdivision_id option:selected").val();
            //alert(subdivisionid);
            $.post("<?php echo $this->webroot; ?>LegacyGeneralinfo/getoffice", {subdivisionid: subdivisionid}, function (data)
                {
                 
                var sc = '<option>--Select Office--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#office_id option").remove();
                $("#office_id").append(sc);
                
                 $("#doc_entered_office option").remove();
                $("#doc_entered_office").append(sc);
                }, 'json');
            
            
        });



    });
             function forcancel() {
        window.location.href = "<?php echo $this->webroot; ?>LegacyGeneralinfo/information/<?php echo $this->Session->read('csrftoken'); ?>";
            }
  
</script>

<style type="text/css">
    .mycontent-left {
        border-right: 1px dashed #333;
    }

</style>

<?php echo $this->Form->create('General_infoctp', array('id' => 'General_infoctp', 'autocomplete' => 'off')); ?>
<div class="row">

    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title " style="font-weight: bolder"><?php echo __('Land Registration'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Citizenentry/leg_generalinfo_<?php echo 'en'; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="col-sm-4">
                               


                <div  class="rowht"></div>
                <div class="row">

                    <div class="form-group">
                        <label for="" class="col-sm-6 control-label"><?php echo __('Objection id'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-6">
                            <?php echo $this->Form->input('objection_id', array('label' => false, 'id' => 'objection_id', 'class' => 'form-control input-sm')); ?> 
                        
                            <span  id="objection_id_error" class="form-error"><?php //echo $errarr['nameofobjector']; ?></span>
                        </div>



                    </div>
                </div>
          
               

                <div  class="rowht"></div>
                <div class="row">

                    <div class="form-group">
                        <label for="" class="col-sm-6 control-label"><?php echo __('Name Of Objector'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-6">
                            <?php echo $this->Form->input('nameofobjector', array('label' => false, 'id' => 'nameofobjector', 'class' => 'form-control input-sm')); ?> 
                        
                            <span  id="nameofobjector_error" class="form-error"><?php //echo $errarr['nameofobjector']; ?></span>
                        </div>



                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">

                <div class="form-group">
                        <label for="" class="col-sm-6 control-label"><?php echo __('Location'); ?></label>    
                        <div class="col-sm-6">
                            <?php echo $this->Form->input('location', array('label' => false, 'id' => 'location', 'class' => 'form-control input-sm')); ?> 
                        
                            <span  id="location_error" class="form-error"><?php //echo $errarr['location']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>   
                <div class="row">

                    <div class="form-group">

                        <label for="" class="col-sm-6 control-label"><?php echo __('Additional Info'); ?></label>    
                        <div class="col-sm-6 " >
                            <?php echo $this->Form->input('additonal_info', array('label' => false, 'class' => 'form-control', 'id' => 'additonal_info', 'placeholder' => '','maxlength'=>"15")); ?>
                            <!-- <span  id="presentation_no_error" class="form-error"><?php // echo $errarr['presentation_no_error']; ?></span> -->
                        </div>
                    </div>
                </div>  

                <div  class="rowht"></div>   
                <div class="row">

                    <div class="form-group">

                        <label for="" class="col-sm-6 control-label"><?php echo __('File Info'); ?></label>    
                        <div class="col-sm-6 " >
                            <?php echo $this->Form->input('fileno', array('label' => false, 'class' => 'form-control', 'id' => 'fileno', 'placeholder' => '','maxlength'=>"15")); ?>
                            <!-- <span  id="presentation_no_error" class="form-error"><?php // echo $errarr['presentation_no_error']; ?></span> -->
                        </div>
                    </div>
                </div>  

                <div  class="rowht"></div>   
                <div class="row">

                    <div class="form-group">

                        <label for="" class="col-sm-6 control-label"><?php echo __('Name Of Objected Person'); ?></label>    
                        <div class="col-sm-6 " >
                            <?php echo $this->Form->input('nameofobjectedperson', array('label' => false, 'class' => 'form-control', 'id' => 'nameofobjectedperson', 'placeholder' => '','maxlength'=>"15")); ?>
                            <!-- <span  id="presentation_no_error" class="form-error"><?php // echo $errarr['presentation_no_error']; ?></span> -->
                        </div>
                    </div>
                </div> 


                <div  class="rowht"></div>   
                <div class="row">

                <div class="form-group">

<label for="" class="col-sm-6 control-label"><?php echo __('Office Code'); ?></label>    
<div class="col-sm-6 " >
    <?php echo $this->Form->input('officecode', array('label' => false, 'class' => 'form-control', 'id' => 'officecode', 'placeholder' => '','maxlength'=>"15")); ?>
    <!-- <span  id="presentation_no_error" class="form-error"><?php // echo $errarr['presentation_no_error']; ?></span> -->
</div>
                    </div>
                </div>    
                
                <div  class="rowht"></div>   
                <div class="row">

                    <div class="form-group">

                        <label for="" class="col-sm-6 control-label"><?php echo __('Mobile No of Objector '); ?></label>    
                        <div class="col-sm-6 " >
                            <?php echo $this->Form->input('mobile_no_of_objector ', array('label' => false, 'class' => 'form-control', 'id' => 'mobile_no_of_objector ', 'placeholder' => '','maxlength'=>"15")); ?>
                            <!-- <span  id="presentation_no_error" class="form-error"><?php // echo $errarr['presentation_no_error']; ?></span> -->
                        </div>
                    </div>
                </div> 

                <div  class="rowht"></div>   
                <div class="row">

                    <div class="form-group">

                        <label for="" class="col-sm-6 control-label"><?php echo __('Edit Remarks'); ?></label>    
                        <div class="col-sm-6 " >
                            <?php echo $this->Form->input('editremarks', array('label' => false, 'class' => 'form-control', 'id' => 'editremarks', 'placeholder' => '','maxlength'=>"15")); ?>
                            <!-- <span  id="presentation_no_error" class="form-error"><?php // echo $errarr['presentation_no_error']; ?></span> -->
                        </div>
                    </div>
                </div> 



               
            </div>
        </div>

       
    </div>
</div>

<?php echo $this->Form->end(); ?>