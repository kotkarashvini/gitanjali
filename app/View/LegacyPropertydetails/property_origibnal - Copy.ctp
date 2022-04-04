<?php
echo $this->Html->script('bootstrap-datepicker.min');
echo $this->Html->css('bootstrap-datepicker3.min');
//echo $this->element("Helper/jqueryhelper");
echo $this->element("Citizenentry/property_menu");
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

<style type="text/css">
    .mycontent-left {
        border-right: 1px dashed #333;
    }

</style>

<?php $doc_lang = $this->Session->read('doc_lang');?>
<?php echo $this->Form->create('property_locationctp', array('id' => 'property_locationctp', 'autocomplete' => 'off')); ?>
<?php //pr($this->request->data); ?> 
<?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>

<div class="row">

    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title " style="font-weight: bolder"><?php ?><?php echo __('lblpropertydetails'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Citizenentry/leg_property_details_en<?php ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="col-sm-4">
                                <?php if ($this->Session->read("Leg_Selectedtoken") != '') { ?>

                                    <div class="row">
                                        <div class="form-group">
                                            <label for="" class="col-sm-5 control-label"><b><?php echo __('lbltokenno'); ?> :-</b><span style="color: #ff0000"></span></label>   
                                            <div class="col-sm-7">
                                                <?php echo $this->Form->input('', array('label' => false, 'id' => '', 'value' => $this->Session->read("Leg_Selectedtoken"), 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="rowht"></div>
                <div class="hr1" style="border: 1px solid black;"></div>


                <div class="rowht"></div>


                <div class="row">
                    <div class="form-group">

                        <div class="col-sm-3">
                            <label for="" class="col-sm-3 control-label"><?php echo __('lbladmdistrict'); ?><span style="color: #ff0000">*</span></label> 
                            <?php echo $this->Form->input('district_id', array('label' => false, 'id' => 'district_id', 'class' => 'form-control input-sm', 'style' => 'cursor: not-allowed;', 'disabled', 'options' => array('empty' => '--Select--', $district_id))); ?> 
                            <span  id="district_id_error" class="form-error"><?php echo $errarr['district_id_error']; ?></span>
                        </div>

                        <div class="col-sm-3">
                            <label for="" class="col-sm-6 control-label"><?php echo __('Sub Division'); ?><span style="color: #ff0000">*</span></label> 
                            <?php echo $this->Form->input('subdivision_id', array('label' => false, 'id' => 'subdivision_id', 'class' => 'form-control input-sm', 'style' => 'cursor: not-allowed;', 'options' => array('empty' => '--Select--', $subdivision))); ?> 
                            <span  id="subdivision_id_error" class="form-error"><?php echo $errarr['subdivision_id_error']; ?></span>
                        </div>
                        
                        
                        
                        <div class="col-sm-3">
                            <label for="" class="col-sm-4 control-label" ><?php echo __('Area Type'); ?><span style="color: #ff0000">*</span></label> 
                            <?php echo $this->Form->input('developed_land_types_id', array('label' => false, 'id' => 'developed_land_types_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $developed_land_types_id))); ?>    
                            <span  id="developed_land_types_id_error" class="form-error"><?php echo $errarr['developed_land_types_id_error']; ?></span>

                        </div>
                        <div class="col-sm-3" >
                            <label for="" class="col-sm-7 control-label"><?php echo __('lbltaluka'); ?><span style="color: #ff0000">*</span></label> 
                            <?php echo $this->Form->input('taluka_id', array('label' => false, 'id' => 'taluka_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $revenue_circle))); ?>                              
                            <span  id="taluka_id_error" class="form-error"><?php echo $errarr['taluka_id']; ?></span>
                        </div>
                        

                    </div>

                </div> 

                <div class="rowht"></div>
                <div class="rowht"></div>

                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-3" >
                            <label for="" class="col-sm-3 control-label"><?php echo __('Tehsil'); ?></label> 
                            <?php echo $this->Form->input('circle_id', array('label' => false, 'id' => 'circle_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $tehsil,'type' => 'select'))); ?>
                            <span  id="circle_id_error" class="form-error"><?php echo $errarr['circle_id_error']; ?></span>
                        </div>
<div class="col-sm-3" >
                            <label for="" class="col-sm-3 control-label"><?php echo __('lbladmvillage'); ?></label> 
                            <?php echo $this->Form->input('village_id', array('label' => false, 'id' => 'village_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $village_id,'type' => 'select'))); ?>
                            <span  id="village_id_error" class="form-error"><?php echo $errarr['village_id_error']; ?></span>
                        </div>
<!--                        <div class="col-sm-2">
                            <label for="" class="col-sm-3 control-label"><?php //echo __('lbllocation'); ?></label> 

                        </div> -->
                        <?php
                            if (!empty($doc_lang) and $doc_lang != 'en') { ?>
                        <div class="col-sm-3">
                            <label for="" class="col-sm-3 control-label"><?php echo __('lbllocation'); ?></label>
                            <?php echo $this->Form->input('location2_ll', array('label' => false, 'class' => 'form-control', 'id' => 'location2_ll', 'class' => 'form-control', 'placeholder' => 'Location')); ?>       
                            <span  id="location2_ll_error" class="form-error"><?php echo $errarr['location2_ll_error']; ?></span>

                        </div>
                          <?php } ?>
                        <div class="col-sm-3" id="attribute_id2_div">
                            <label for="" class="col-sm-3 control-label"><?php echo __('lbllocation'); ?></label>
                            <?php echo $this->Form->input('location1_en', array('label' => false, 'class' => 'form-control', 'id' => 'location1_en', 'class' => 'form-control', 'placeholder' => 'Location')); ?>
                            <span  id="location1_en_error" class="form-error"><?php echo $errarr['location1_en_error']; ?></span>

                        </div>

                    </div>

                </div> 

            </div>

            <div class="box-body">
                <div class="rowht"></div>
                <div class="hr1" style="border: 1px solid black;"></div>

                <div class="box-header with-border">


                    <h3 class="box-title headbolder"><?php //echo __('lblpropertyattribute');  ?>Property Details</h3>

                </div>


                <div  class="rowht"></div>   
                <div class="row">
                    <div class="form-group">
                        <?php
                            if (!empty($doc_lang) and $doc_lang != 'en') { ?>
                         <label for="" class="col-sm-3 control-label"><?php echo __('lbluniquepropnu'); ?></label>    
                        <div class="col-sm-3 " >
                            <?php echo $this->Form->input('unique_property_no_ll', array('label' => false, 'class' => 'form-control', 'id' => 'unique_property_no_ll', 'class' => 'form-control', 'placeholder' => '')); ?>
                            <span  id="unique_property_no_ll_error" class="form-error"><?php echo $errarr['unique_property_no_ll_error']; ?></span>
                        </div>
                        <?php } ?>
                        <label for="" class="col-sm-3 control-label"><?php echo __('lbluniquepropnu'); ?>[ENGLISH]:</label>    
                        <div class="col-sm-3 " >
                            <?php echo $this->Form->input('unique_property_no_en', array('label' => false, 'class' => 'form-control', 'id' => 'unique_property_no_en', 'class' => 'form-control', 'placeholder' => '')); ?>
                            <span  id="unique_property_no_en_error" class="form-error"><?php echo $errarr['unique_property_no_en_error']; ?></span>
                        </div>

                    </div>
                </div>

                <div  class="rowht"></div>   
                <div class="row">
                    <div class="form-group">
                         <?php if (!empty($doc_lang) and $doc_lang != 'en') { ?>
                        <label for="" class="col-sm-3 control-label"><?php echo __('lblboundryeast'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3 ">
                            <?php echo $this->Form->input('boundries_east_ll', array('label' => false, 'class' => 'form-control', 'id' => 'boundries_east_ll', 'class' => 'form-control', 'placeholder' => '')); ?>
                            <span  id="boundries_east_ll_error" class="form-error"><?php echo $errarr['boundries_east_ll_error']; ?></span>
                        </div>
                        <?php } ?>
                        <label for="" class="col-sm-3 control-label"><?php echo __('lblboundryeast'); ?>[ENGLISH]:<span style="color: #ff0000">*</span></label>  
                        <div class="col-sm-3 ">
                            <?php echo $this->Form->input('boundries_east_en', array('label' => false, 'class' => 'form-control', 'id' => 'boundries_east_en', 'class' => 'form-control', 'placeholder' => '')); ?>
                            <span  id="boundries_east_en_error" class="form-error"><?php echo $errarr['boundries_east_en_error']; ?></span>
                        </div>

                    </div>
                </div>

                <div  class="rowht"></div>   
                <div class="row">
                    <div class="form-group">
  <?php if (!empty($doc_lang) and $doc_lang != 'en') { ?>
                        <label for="" class="col-sm-3 control-label"><?php echo __('lblboundrywest'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3" >
                            <?php echo $this->Form->input('boundries_west_ll', array('label' => false, 'class' => 'form-control', 'id' => 'boundries_west_ll', 'placeholder' => '')); ?>
                            <span  id="boundries_west_ll_error" class="form-error"><?php echo $errarr['boundries_west_ll_error']; ?></span><span style="color: #ff0000">*</span>
                        </div>
                        <?php } ?>
                        <label for="" class="col-sm-3 control-label"><?php echo __('lblboundrywest'); ?>[ENGLISH]:<span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3" >
                            <?php echo $this->Form->input('boundries_west_en', array('label' => false, 'class' => 'form-control', 'id' => 'boundries_west_en', 'placeholder' => '')); ?>
                            <span  id="boundries_west_en_error" class="form-error"><?php echo $errarr['boundries_west_en_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>   
                <div class="row">
                    <div class="form-group">
                        <?php if (!empty($doc_lang) and $doc_lang != 'en') { ?>
                        <label for="" class="col-sm-3 control-label"><?php echo __('lblboundriessouth'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3 " >
                            <?php echo $this->Form->input('boundries_south_ll', array('label' => false, 'class' => 'form-control', 'id' => 'boundries_south_ll', 'class' => 'form-control', 'placeholder' => '')); ?>
                            <span  id="boundries_south_ll_error" class="form-error"><?php echo $errarr['boundries_south_ll_error']; ?></span>
                        </div>
                          <?php } ?>
                        <label for="" class="col-sm-3 control-label"><?php echo __('lblboundriessouth'); ?>[ENGLISH]:<span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3 " >
                            <?php echo $this->Form->input('boundries_south_en', array('label' => false, 'class' => 'form-control', 'id' => 'boundries_south_en', 'class' => 'form-control', 'placeholder' => '')); ?>
                            <span  id="boundries_south_en_error" class="form-error"><?php echo $errarr['boundries_south_en_error']; ?></span>
                        </div>

                    </div>
                </div>
                <div  class="rowht"></div>   
                <div class="row">
                    <div class="form-group">
<?php if (!empty($doc_lang) and $doc_lang != 'en') { ?>
                 <label for="" class="col-sm-3 control-label"><?php echo __('lblboundriesnorth'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3" >
                            <?php echo $this->Form->input('boundries_north_ll', array('label' => false, 'class' => 'form-control', 'id' => 'boundries_north_ll', 'placeholder' => '')); ?>
                            <span  id="boundries_north_ll_error" class="form-error"><?php echo $errarr['boundries_north_ll_error']; ?></span>
                        </div>       
                 <?php } ?>        
                        <label for="" class="col-sm-3 control-label"><?php echo __('lblboundriesnorth'); ?>[ENGLISH]:<span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3" >
                            <?php echo $this->Form->input('boundries_north_en', array('label' => false, 'class' => 'form-control', 'id' => 'boundries_north_en', 'placeholder' => '')); ?>
                            <span  id="boundries_north_en_error" class="form-error"><?php echo $errarr['boundries_north_en_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>   
                <div class="row">
                    <div class="form-group">
                        <?php if (!empty($doc_lang) and $doc_lang != 'en') { ?>
                        <label for="" class="col-sm-3 control-label"><?php echo __('lbladditionalinfo'); ?></label> 
                        <div class="col-sm-3" >
                            <?php echo $this->Form->input('additional_information_ll', array('label' => false, 'class' => 'form-control', 'id' => 'additional_information_ll', 'class' => 'form-control', 'placeholder' => '')); ?>
                            <span  id="additional_information_ll_error" class="form-error"><?php echo $errarr['additional_information_ll_error']; ?></span>
                        </div>
                         <?php } ?>  
                        <label for="" class="col-sm-3 control-label"><?php echo __('lbladditionalinfo'); ?>[ENGLISH]:</label> 
                        <div class="col-sm-3" >
                            <?php echo $this->Form->input('additional_information_en', array('label' => false, 'class' => 'form-control', 'id' => 'additional_information_en', 'class' => 'form-control', 'placeholder' => '')); ?>
                            <span  id="additional_information_en_error" class="form-error"><?php echo $errarr['additional_information_en_error']; ?></span>
                        </div>

                    </div>
                </div>
                <br>
                <!--                   <div class="row">
                                    <div class="form-group">
                                      
                                        <label for="" class="col-sm-3 control-label"><?php //echo __('lblrefregdocdate');  ?></label>    
                                        <div class="col-sm-3" >
                                         <a href="#" class="pl-3"><button   type="submit"  id="test"  name="action"  value="test" class="btn btn-primary">Save</button></a>
                    
                                        </div>
                                    </div>
                                </div>  -->
            </div>




            <div class="box-body">
                <div class="rowht"></div>
                <div class="hr1" style="border: 1px solid black;"></div>

                <div class="box-header with-border">


                    <h3 class="box-title headbolder"><?php ?>Other Property Details</h3>

                </div>
                <div class="box-body">
                    <div class="rowht"></div>
                    <div class="row">
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label"><?php echo __('lblusamaincat'); ?><span style="color: #ff0000">*</span></label>    
                            <div class="col-sm-3">
                                <?php echo $this->Form->input('usage_main_catg_id', array('label' => false, 'id' => 'usage_main_catg_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $main_category))); ?>                              
<!--                                <span  id="usage_main_catg_id_error" class="form-error"><?php //echo $errarr['usage_main_catg_id_error']; ?></span>-->
                            </div>
                          

                        </div>
                    </div>
                    <div class="rowht"></div>
                   <div class="row">
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label"><?php echo __('lblsubcat'); ?><span style="color: #ff0000">*</span></label>    
                           <div class="col-sm-3">
                            <select  id="usage_sub_catg_id" class="form-control">
                            <option value="empty">--Select-- </option>
                             <?php foreach ($sub_category1 as $sub_category1) {
                                    ?>
                            <option  value='<?php echo $sub_category1[0]['usage_sub_catg_id']; ?>'><?php  echo $sub_category1[0]['usage_sub_catg_desc_en']; ?></option>                            
                            <?php } ?>
                                </select>
                               <span  id="usage_sub_catg_id_error" class="form-error"></span>
                        </div> 
                        </div><!-- 
                    </div>-->
                    <!-- <div class="row" id='divsubcat'> 
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label"><?php// echo __('lblsubcat'); ?><span style="color: #ff0000">*</span></label>    
                           <div class="col-sm-3">
                           <?php // echo $this->Form->input('usage_sub_catg_id', array('label' => false, 'id' => 'usage_sub_catg_id', 'class' => 'form-control input-sm', 'options' => array($sub_category1))); ?> 
                               <span  id="usage_sub_catg_id_error" class="form-error"><?php //echo $errarr['usage_sub_catg_id_error']; ?></span>
                        </div>   
                        </div>
                    </div> -->
                    
                    
                    <div  class="rowht"></div>   
                    <div class="row">
                        <div class="form-group">

                            <label for="" class="col-sm-3 control-label"><?php echo __('lblarea'); ?><span style="color: #ff0000">*</span></label>    
                            <div class="col-sm-3" >
                                <?php echo $this->Form->input('item_value', array('label' => false, 'id' => 'item_value', 'class' => 'form-control input-sm','maxlength'=>"10")); ?> 
                                <span  id="item_value_error" class="form-error"><?php //echo $errarr['item_value_error']; ?></span>
                            </div>

                        </div>
                    </div>
                    <div  class="rowht"></div>   
                    <div class="row">
                        <div class="form-group">

                            <label for="" class="col-sm-3 control-label"><?php echo __('lblareaunit'); ?><span style="color: #ff0000">*</span></label>    
                            <div class="col-sm-3" >
                                <?php echo $this->Form->input('area_unit', array('label' => false, 'id' => 'area_unit', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $unit))); ?>                           </div>
<!--                            <span  id="area_unit_error" class="form-error"><?php //echo $errarr['area_unit_error']; ?></span>-->

                        </div>
                    </div>
                    <div  class="rowht"></div>   
                    <div class="row">
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label"><?php echo __('lblmarketvalue'); ?><span style="color: #ff0000">*</span></label>    
                            <div class="col-sm-3" >
                                <?php echo $this->Form->input('final_value', array('label' => false, 'id' => 'final_value', 'class' => 'form-control input-sm','maxlength'=>"15")); ?> 
                                 <span  id="final_value_error" class="form-error"><?php //echo $errarr['final_value_error']; ?></span>
                            </div>

                        </div>
                    </div>
                    
                     <div  class="rowht"></div>   
                    <div class="row">
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label"><?php echo __('lblconsiderationamt'); ?><span style="color: #ff0000">*</span></label>    
                            <div class="col-sm-3" >
                                <?php echo $this->Form->input('consideration_amt', array('label' => false, 'id' => 'consideration_amt', 'class' => 'form-control input-sm','maxlength'=>"15")); ?> 
                                 <span  id="consideration_amt_error" class="form-error"><?php echo $errarr['consideration_amt_error']; ?></span>
                            </div>

                        </div>
                    </div>

                    <br>
                    <div class="row">
                        <div class="form-group">

                            <label for="" class="col-sm-3 control-label"><?php //echo __('lblrefregdocdate');  ?></label>    
                            <div class="col-sm-3" >
                                <a href="#" class="pl-3"><button   type="button"  id="add_property_details"  name="action"  value="add_property_details" class="btn btn-primary">Add</button></a>

                            </div>
                        </div>
                    </div>   

                    <br>

                    <div  id="prop_details">

                        <div class="form-group">
                            <table class="table table-bordered" id="prop_details_tbl">
                                <thead>
                                    <tr>
                                        <th class="center">
                                            <?php echo __('lblusamaincat'); ?> 
                                        </th>
                                        <th class="center">
                                            <?php echo __('lblsubcat'); ?> 
                                        </th>
                                        <th>
                                            <?php echo __('lblarea'); ?>
                                        </th>  
                                        <th>
                                            <?php echo __('lblmarketvalue'); ?>
                                        </th>

                                        <th>
                                             <?php echo __('lblconsiderationamt'); ?>
                                        </th>
                                       <th>
                
                                       </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    //pr($prop_details_seller);
                                    if (isset($prop_details_seller)) {
                                        foreach ($prop_details_seller as $key => $prop_details) {
                                            ?>
                                            <tr>
                                                <th>
                                                    <?php echo $prop_details['maindesc']; ?>
                                                </th>
                                                <th>
                                                    <?php 
                                                    if($prop_details['subdesc']=='--Select--')
                                                        echo 'NULL'; 
                                                    else
                                                        echo $prop_details['subdesc'];    
                                                    ?>
                                                </th>
                                                <th>
                                                    <?php echo $prop_details['item_value'].$prop_details['unit_desc']; ?>
                                                </th>
                                                <th>
                                                    <?php echo $prop_details['final_value']; ?>
                                                </th>
                                                 <th>
                                                    <?php echo $prop_details['consideration_amt']; ?>
                                                </th>
                                                <th>
                                                    <input type="button" onclick="remove_property('<?php echo $key; ?>')" value="<?php echo __('lblbtndelete'); ?> ">
                                                </th>


                                            </tr>
                                        <?php }
                                    } else { ?>
                                        <tr><td colspan="5"><?php echo"No records found! "; ?></td></tr>
<?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>   






                </div>


            </div>


















            <div class="box-body">


                <div class="hr1" style="border: 1px solid black;"></div>

                <div class="row">
                    <div class="col-sm-12">

                        <div class="box-header with-border">


                            <h3 class="box-title headbolder"><?php //echo __('lblpropertyattribute');  ?>Property Attributes</h3>

                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-sm-2">
<?php echo $this->Form->input('paramter_id', array('label' => false, 'id' => 'paramter_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $paramter_id))); ?> 
<!--                                         <span  id="paramter_id_error" class="form-error"><?php //echo $errarr['paramter_id_error']; ?></span>-->

                                    </div> 
                                    <div class="col-sm-3">
<?php echo $this->Form->input('paramter_value', array('label' => false, 'class' => 'form-control', 'type' => 'text', 'id' => 'paramter_value', 'placeholder' => __('lblattrivalue'))); ?>
<!--                                        <span id="paramter_value_error" class="form-error"></span>-->
                                    </div>
                                    <!-- <div class="col-sm-3" id="attribute_id1_div">
<?php echo $this->Form->input('paramter_value1', array('label' => false, 'class' => 'form-control', 'type' => 'text', 'id' => 'paramter_value1', 'placeholder' => __('lblattrivalue_part1'))); ?>

                                    </div>

                                    <div class="col-sm-3" id="attribute_id2_div">
<?php echo $this->Form->input('paramter_value2', array('label' => false, 'class' => 'form-control', 'type' => 'text', 'id' => 'paramter_value2', 'placeholder' => __('lblattrivalue_part2'))); ?>

                                    </div> -->
                                    <div class="col-sm-1">
                                        <a href="#" class="pl-3"><button   type="button"  id="add_attribute_details"  name="action"  value="add_attribute_details" class="btn btn-primary" >Add</button></a>
                                    </div> 
                                </div>

                            </div>
                            <br>
                            <div  id="prop_attribute">

                                <div class="form-group">
                                    <table class="table table-bordered" id="prop_attribute_tbl">
                                        <thead>
                                            <tr>
                                                <th class="center">
<?php echo __('lblattriname');  ?> 
                                                </th>
                                                <th class="center">
<?php echo __('lblattrivalue');  ?> 
                                                </th>
                                                <th>
<?php echo __('lblattrivalue_part1');  ?>
                                                </th>  
                                                <th>
<?php echo __('lblattrivalue_part2');  ?>
                                                </th>
                                                <th>

                                                </th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (isset($prop_attributes_seller)) {
                                                foreach ($prop_attributes_seller as $key => $prop_attribute) {
                                                   // pr($prop_attributes_seller);exit();
                                                    ?>
                                                    <tr>

                                                        <th>
                                                            <?php //echo $attributes[$prop_attributes['paramter_id']]; ?>
                                                            <?php echo $prop_attribute['para_desc']; ?>
                                                        </th>
                                                        <th>
                                                            <?php echo $prop_attribute['paramter_value']; ?>
                                                        </th>
                                                        <th>
                                                            <?php echo $prop_attribute['paramter_value1']; ?>
                                                        </th>
                                                        <th>
                                                            <?php echo $prop_attribute['paramter_value2']; ?>
                                                        </th>
                                                        <th>
                                                            <input type="button" onclick="remove_attribute('<?php echo $key; ?>')" value="<?php echo __('lblbtndelete'); ?> ">
                                                        </th>


                                                    </tr>
                                                <?php }
                                            } else { ?>
                                                <tr><td colspan="5"><?php echo"No records found! "; ?></td></tr>
<?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>





                        </div>
                    </div> 

                </div>
            </div>

            <div class="box box-primary">
                <div class="box-body">
                    <div class="row center" >

                        <input type="hidden"  id="continue_flag">
                        <a href="#" class="pl-3"><button   type="submit"  id="Final_submit"  name="action"  value="Final_submit" class="btn btn-primary"><?php echo __('btnsubmit'); ?></button></a>
                        <input type="button" id="btnCancel" name="btnCancel" class="btn btn-danger" style="width:155px;" value="<?php echo __('btncancel'); ?>" onclick="javascript: return forcancel();">

                    </div>  
                </div>
            </div>
            <div class="box-body">


                <div class="hr1" style="border: 1px solid black;"></div>

                <div class="row">
                    <div class="col-sm-12">

                        <div class="box-header with-border">


                            <h3 class="box-title headbolder"><?php //echo __('lblpropertyattribute');  ?>Property Final Details</h3>

                        </div>
                        <div class="box-body">

                            <br>
                            <div  id="prop_attribute34243">

                                <div class="form-group">
                                    <table class="table table-bordered" id="prop_attribute_tbl">
                                        <thead>
                                            <tr>
                                                <th class="center">
<?php echo __('lblproid');  ?> 
                                                </th>
                                                <th class="center">
<?php echo __('lbladdressotherdetails');  ?> 
                                                </th>
                                                <th>
<?php echo __('lbltaluka');  ?>
                                                </th>  
                                                <th>
<?php echo __('lbllocation');  ?>
                                                </th>
                                                <th>
<?php echo __('lblaction');  ?>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!empty($Prop_detail_info)) {

                                                foreach ($Prop_detail_info as $Prop_detail_info1) {
                                                    ?>
                                                    <tr>

                                                        <th>
                                                            <?php echo $Prop_detail_info1[0]['property_id']; ?>
                                                        </th>
                                                        <?php if ($doc_lang != 'en') { ?>
                                                        <th>
                                                         <?php echo $Prop_detail_info1[0]['additional_information_ll']; ?>
                                                             </th>
                                                        <?php } else {?>
                                                        <th>
                                                            <?php echo $Prop_detail_info1[0]['additional_information_en']; ?>
                                                        </th>
                                                          <?php } ?>
                                                        <?php if ($doc_lang != 'en') { ?>
                                                        <th>
                                                         <?php echo $Prop_detail_info1[0]['taluka_name_ll']; ?>
                                                             </th>
                                                        <?php } else {?>
                                                        <th>
                                                            <?php echo $Prop_detail_info1[0]['taluka_name_en']; ?>
                                                        </th>
                                                          <?php } ?>
                                                        <?php if ($doc_lang != 'en') { ?>
                                                        <th>
                                                         <?php echo $Prop_detail_info1[0]['location2_ll']; ?>
                                                             </th>
                                                        <?php } else {?>
                                                        <th>
                                                            <?php echo $Prop_detail_info1[0]['location1_en']; ?>
                                                        </th>
                                                         <?php } ?>
                                                        <th>
                                                            <a <?php echo $this->Html->Link("Edit", array('action' => 'property', $this->Session->read('csrftoken'), $Prop_detail_info1[0]['property_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Edit')), array('Are you sure to Edit?')); ?></a>
                                                            <a <?php echo $this->Html->Link("Delete", array('action' => 'delete_property_details', $this->Session->read('csrftoken'), $Prop_detail_info1[0]['property_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete')), array('Are you sure to delete?')); ?></a>
                                                        </th> 

                                                    </tr>
                                                <?php }
                                            } else { ?>
                                                <tr><td colspan="5"><?php echo"No records found! "; ?></td></tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>


                        </div>
                    </div> 

                </div>
            </div>






        </div>
    </div>
    <?php echo $this->Form->end(); ?>

