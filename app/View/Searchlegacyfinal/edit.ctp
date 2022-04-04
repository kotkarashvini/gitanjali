<?php
echo $this->Html->script('bootstrap-datepicker.min');
echo $this->Html->css('bootstrap-datepicker3.min');

?>


<script type="text/javascript">
    $(document).ready(function () 
    {

        // $("#usage_main_catg_id").change(function () 
        // {
        //     // alert($("#usage_main_catg_id").val());
        //     var cat= $("#usage_main_catg_id option:selected").val();
        //     if(cat==2)
        //     {
        //         $("#divsubcat").hide();
        //        // $("lblsubcat").hide();
        //     }
        //     else
        //     {
        //         $("#divsubcat").show();
        //     }
        //     //alert(cat);
        //                 // pr($cat);
        //   //  exit();
        // // var districtid = $("#usage_main_catg_id option:selected").val();
        // //                     pr($districtid);
        // //                     exit();
        // } );             

        $("#add_attribute_details").click(function () {
            //alert($("#paramter_id").val());
            
            if($("#paramter_id").val()=='empty')
            {
               // alert("Hii");
                alert('select Attribute');
                document.getElementById(para_id).value = '';
               // return false;
            }
            else if( $("#paramter_value").val()=='')
           //else if(!$.isNumeric($("#paramter_value").val()))
            {
                alert('Enter Attribute Value');
                document.getElementById(para_id).value = '';
                //return false;
            }
            else
            {
               // alert("Hii");
            var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
            $.post("<?php echo $this->webroot; ?>LegacyPropertydetails/add_property_attribute",
                    {
                        paramter_id: $("#paramter_id").val(),
                        para_desc: $("#paramter_id option:selected").text(),
                        paramter_value: $("#paramter_value").val(),

                        paramter_value1: $("#paramter_value1").val(),
                        paramter_value2: $("#paramter_value2").val(),
                        csrftoken: csrftoken
                    },
                    function (data, status) {
                        //alert(data);
                        $("#prop_attribute").html(data);
                        $("#add_attribute").html('Add');
//                        $("#paramter_id").val('');
                        $("#paramter_value").val('');
                        $("#paramter_value1").val('');
                        $("#paramter_value2").val('');
                        //$("#paramter_value1").css("background-color", "white");
                    });
                }

        });

var pattern = /^\d*\.?\d*$/;
        //////////////////////////////////////////////
        $("#add_property_details").click(function () {
        // pr($("#usage_main_catg_id").val());exit();
         if($("#usage_main_catg_id").val()=='empty')
            {
               // alert("Hii");
                alert('select Main Category');
                document.getElementById(para_id).value = '';
               // return false;
            }
            //alert($("#usage_main_catg_id").val());
            if($("#usage_main_catg_id").val()=='1')
        {


             if( $("#usage_sub_catg_id").val()=='--Select--')
            {
                //alert('Select Sub Category');
                document.getElementById(para_id).value = '';
                //return false;
            }
        }
            if(!$.isNumeric($("#item_value").val()))
            {
                 alert('Enter Area');
            }
             if($("#area_unit").val()=='empty')
            {
                alert('Select Unit');
            }
             if(!$.isNumeric($("#final_value").val()))
            {
                alert('Enter Market Value');
            }
             if(!pattern.test($("#consideration_amt").val()))
            {
                alert('Enter COnsideration Amount');
            }
//            else if(!$.isnull($("#consideration_amt").val()))
//            {
//                alert('Enter Consideration Amount');
//            }
else
{
            var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
            $.post("<?php echo $this->webroot; ?>LegacyPropertydetails/add_property_details",
                    {
                        usage_main_catg_id: $("#usage_main_catg_id").val(),
                        maindesc: $("#usage_main_catg_id option:selected").text(),
                        usage_sub_catg_id: $("#usage_sub_catg_id").val(),
                        subdesc: $("#usage_sub_catg_id option:selected").text(),
                        area_unit:$("#area_unit").val(),
                        unit_desc:$("#area_unit option:selected").text(),
                        item_value: $("#item_value").val(),
                        final_value: $("#final_value").val(),
                        consideration_amt: $("#consideration_amt").val(),

                        csrftoken: csrftoken

                    },
                    function (data, status) {
                        // alert(data);
                        $("#prop_details").html(data);
//                        $("#usage_main_catg_id").val('');
//                        $("#usage_sub_catg_id").val('');
//                        
                        $("#item_value").val('');
                        $("#final_value").val('');
                          $("#consideration_amt").val('');


                    });
                }

        });
        /////////////////////////////////////////////


        ////////////////////////////////////////////////////////////////

        $('.date').datepicker({
            format: "dd-mm-yyyy",
            // format: "yyyy-mm-dd",
            todayBtn: "linked",
            calendarWeeks: true,
            autoclose: true,
            todayHighlight: true,
            endDate: 'today'
        });



//        $('#district_id').change(function ()
//        {
//
//            var districtid = $("#district_id option:selected").val();
//            // alert(districtid);
//            $.getJSON('gettaluka', {districtid: districtid}, function (data)
//            {
//                var sc = '<option>--Select Taluka--</option>';
//                $.each(data, function (index, val) {
//                    sc += "<option value=" + index + ">" + val + "</option>";
//                });
//                $("#taluka_id option").remove();
//                $("#taluka_id").append(sc);
//            });
//        });

  $('#district_id').change(function ()
        {

            var districtid = $("#district_id option:selected").val();
           // alert(districtid);
            $.post("<?php echo $this->webroot; ?>LegacyPropertydetails/gettaluka", {districtid: districtid}, function (data)
                {
                 
                    var sc = '<option value="">--select--</option>';
                    $.each(data, function (index, val) {
                        sc += "<option value=" + index + ">" + val + "</option>";
                    });
                   
                    $("#taluka_id option").remove();
                    $("#taluka_id").append(sc);

                }, 'json');
        });



      
///////Chnages on Dated 02 Dec2020

    $('#subdivision_id').change(function ()
        {

            var subdivisionid = $("#subdivision_id option:selected").val();
           //  alert(subdivisionid);
           // $.getJSON('getvillage', {talukaid: talukaid}, function (data)
          $.getJSON('<?php echo $this->webroot; ?>LegacyPropertydetails/get_revenuecircle_for_property', {subdivisionid: subdivisionid}, function (data)
            {
                var sc = '<option>--Select Circle--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#taluka_id option").remove();
                $("#taluka_id").append(sc);
            });
        });


  $('#taluka_id').change(function ()
        {

            var talukaid = $("#taluka_id option:selected").val();
            // alert(talukaid);
           // $.getJSON('getvillage', {talukaid: talukaid}, function (data)
          $.getJSON('<?php echo $this->webroot; ?>LegacyPropertydetails/get_tehsil_for_property', {talukaid: talukaid}, function (data)
            {
                var sc = '<option>--Select Tehsil--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#circle_id option").remove();
                $("#circle_id").append(sc);
            });
        });


  $('#circle_id').change(function ()
        {

            var circleid = $("#circle_id option:selected").val();
            // alert(talukaid);
           // $.getJSON('getvillage', {talukaid: talukaid}, function (data)
          $.getJSON('<?php echo $this->webroot; ?>LegacyPropertydetails/get_village_for_property', {circleid: circleid}, function (data)
            {
                var sc = '<option>--Select Village--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#village_id option").remove();
                $("#village_id").append(sc);
            });
        });



/////////////////////////////
//   $('#usage_main_catg_id').change(function ()
//         {

//             var maincatgid = $("#usage_main_catg_id option:selected").val();
//            var sc=null;
//            // $.getJSON('get_subcatg', {maincatgid: maincatgid}, function (data)
//             $.getJSON('<?php echo $this->webroot; ?>LegacyPropertydetails/get_subcatg', {maincatgid: maincatgid}, function (data)
//             {
//               // alert('fgfgfhg');exit();
//                 $.each(data, function (index, val) {
//                     sc += "<option value=" + index + ">" + val + "</option>";
                    
//                 });
//                 // pr('fgfgfhg');
//                 // pr(sc );exit();
//                // alert(sc);
//                 $("#usage_sub_catg_id option").remove();
//                 $("#usage_sub_catg_id").append(sc);
//             });
//         });



    });


    function remove_property(index_id) {
        // alert(index_id);
        //var csrftoken = <?php //echo $this->Session->read('csrftoken');  ?>;
        $.post("<?php echo $this->webroot; ?>LegacyPropertydetails/add_property_details",
                {

                    index_id: index_id,
                    // type: flag,
                    // csrftoken: csrftoken,
                    action: 'remove'
                },
                function (data, status) {
                    $("#prop_details").html(data);
                });

    }

    function remove_attribute(index_id) {
        // alert(index_id);
        //var csrftoken = <?php //echo $this->Session->read('csrftoken');  ?>;
        $.post("<?php echo $this->webroot; ?>LegacyPropertydetails/add_property_attribute",
                {

                    index_id: index_id,
                    // type: flag,
                    // csrftoken: csrftoken,
                    action: 'remove'
                },
                function (data, status) {
                    $("#prop_attribute").html(data);
                });

    }
    
          function forcancel() {
        window.location.href = "<?php echo $this->webroot; ?>Legacypropertydetails/property/<?php echo $this->Session->read('csrftoken'); ?>";
            }


</script>





















<?php $doc_lang = $this->Session->read('doc_lang');?>

<?php echo $this->Form->create('searchlegacyfinal', array('id' => 'searchlegacyfinal', 'class'=>'edit','autocomplete' => 'off')); ?>

<?php echo ('Edit Record'); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
               <center><h3 class="box-title " style="font-weight: bolder">
                    <?php echo __('Edit Record'); ?>
                </h3> 

                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Citizenentry/leg_property_details_en<?php ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div>    
            </div>

           

            <div class="box-body">
              
              <div class="rowht"></div>
              <div class="hr1" style="border: 1px solid black;"></div>
              <div class="rowht"></div>


              <div class="row">
                  <div class="form-group">
                      <div class="col-sm-7">
                          <label for="" class="col-sm-3 control-label"><?php echo __('District'); ?><span style="color: #ff0000">*</span></label> 
                          <?php echo $this->Form->input('district_id', array('label' => false, 'id' => 'district_id', 'class' => 'form-control input-sm', 'style' => 'cursor: not-allowed;', 'disabled', 'options' => array('empty' => '--Select--', $district_id))); ?> 
                          <span  id="district_id_error" class="form-error"><?php //echo $errarr['district_id_error']; ?></span>
                      </div>

                      <div class="col-sm-7" >
                          <label for="" class="col-sm-7 control-label"><?php echo __('Enter Circle/anchal Code'); ?></label> 
                          <?php echo $this->Form->input('taluka_code', array('label' => false, 'id' => 'taluka_code', 'class' => 'form-control input-sm')); ?>                              
                          <span  id="taluka_code_error" class="form-error"><?php // echo $errarr['taluka_code']; ?></span>
                      </div>
  
                      
                      <div class="col-sm-7">
                          <label for="" class="col-sm-7 control-label" ><?php echo __('Enter Circle/anchal Name'); ?><span style="color: #ff0000">*</span></label> 
                          <?php echo $this->Form->input('taluka_name_en', array('label' => false, 'id' => 'taluka_name_en', 'class' => 'form-control input-sm')); ?>    
                          <span  id="taluka_name_en_error" class="form-error"><?php //echo $errarr['taluka_name_en_error']; ?></span>

                      </div>

                      <?php //echo $this->form->end(__('Submit'));?>
                  </div>
              </div> 

             

<BR>
<br>
</center>  
<CENTER>
<a href="#" class="pl-3"><button   type="submit"  id="submit"  name="action"  value="submit" class="btn btn-primary"><?php echo('Submit'); ?></button></a>
     
</center>   
    <?php echo $this->Form->end(); ?>
 
