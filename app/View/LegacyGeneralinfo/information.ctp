
<?php
echo $this->Html->script('bootstrap-datepicker.min');
echo $this->Html->css('bootstrap-datepicker3.min');
//echo $this->element("Helper/jqueryhelper");
echo $this->element("Citizenentry/property_menu");
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
                <center><h3 class="box-title " style="font-weight: bolder"><?php echo __('lblgeneralinfo'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Citizenentry/leg_generalinfo_<?php echo 'en'; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
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


                <?php if ($this->Session->read("manual_flag") == 'Y') { ?>
                    <div class="rowht"></div>
                    <div class="row">
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label"><?php echo __('lblmanualregno'); ?><span style="color: #ff0000">*</span></label> 
                            <div class="col-sm-3" ><?php echo $this->Form->input('manual_reg_no', array('label' => false, 'id' => 'manual_reg_no', 'class' => 'form-control input-sm', 'type' => 'text')); ?>
                                <span id="manual_reg_no_error" class="form-error"><?php echo $errarr['manual_reg_no_error']; ?></span>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label"><?php echo __('lbllocaldataentry'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('local_language_id', array('label' => false, 'id' => 'local_language_id', 'class' => 'form-control input-sm', 'style' => 'cursor: not-allowed;', 'disabled', 'options' => array('@' => '--Select--', $language))); ?>                             
                            <span  id="local_language_id_error" class="form-error"><?php echo $errarr['local_language_id']; ?></span>
                        </div>



                    </div>
                </div>

                <div  class="rowht"></div>
                <div class="row">

                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label"><?php echo __('lblArticle'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('article_id', array('label' => false, 'id' => 'article_id', 'class' => 'form-control input-sm', 'options' => array('@' => '--Select--', $article))); ?> 
                            <span  id="article_id_error" class="form-error"><?php echo $errarr['article_id']; ?></span>
                        </div>



                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">

                    <div class="form-group">

                        <label for="" class="col-sm-3 control-label"><?php echo __('lblexecutiondt'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3 " >
                            <?php echo $this->Form->input('exec_date', array('label' => false, 'class' => 'date form-control', 'data-date-format' => "dd-mm-yyyy", 'id' => 'exec_date', 'placeholder' => '')); ?>
                            <span  id="exec_date_error" class="form-error"><?php echo $errarr['exec_date']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>   
                <div class="row">

                    <div class="form-group">

                        <label for="" class="col-sm-3 control-label"><?php echo __('Serial Number'); ?></label>    
                        <div class="col-sm-3 " >
                            <?php echo $this->Form->input('serial_no', array('label' => false, 'class' => 'form-control', 'id' => 'serial_no', 'placeholder' => '','maxlength'=>"15")); ?>
                            <!-- <span  id="presentation_no_error" class="form-error"><?php // echo $errarr['presentation_no_error']; ?></span> -->
                        </div>
                    </div>
                </div>  

                <div  class="rowht"></div>   
                <div class="row">

                    <div class="form-group">

                        <label for="" class="col-sm-3 control-label"><?php echo __('Volume No'); ?></label>    
                        <div class="col-sm-3 " >
                            <?php echo $this->Form->input('volume_no', array('label' => false, 'class' => 'form-control', 'id' => 'volume_no', 'placeholder' => '','maxlength'=>"15")); ?>
                            <!-- <span  id="presentation_no_error" class="form-error"><?php // echo $errarr['presentation_no_error']; ?></span> -->
                        </div>
                    </div>
                </div>  

                <div  class="rowht"></div>   
                <div class="row">

                    <div class="form-group">

                        <label for="" class="col-sm-3 control-label"><?php echo __('Number Of Pages'); ?></label>    
                        <div class="col-sm-3 " >
                            <?php echo $this->Form->input('page_no', array('label' => false, 'class' => 'form-control', 'id' => 'page_no', 'placeholder' => '','maxlength'=>"15")); ?>
                            <!-- <span  id="presentation_no_error" class="form-error"><?php // echo $errarr['presentation_no_error']; ?></span> -->
                        </div>
                    </div>
                </div> 


                <div  class="rowht"></div>   
                <div class="row">

                    <div class="form-group">

                        <label for="" class="col-sm-3 control-label"><?php echo __('lblpresentationdate'); ?></label>    
                        <div class="col-sm-3 " >
                            <?php echo $this->Form->input('presentation_dt', array('label' => false, 'class' => 'date form-control', 'data-date-format' => "dd-mm-yyyy", 'id' => 'presentation_dt', 'placeholder' => '')); ?>
                            <span  id="presentation_dt_error" class="form-error"><?php echo $errarr['presentation_dt_error']; ?></span>
                        </div>
                    </div>
                </div>     



                <div  class="rowht"></div>
                <div class="row">

                    <div class="form-group">

                        <label for="" class="col-sm-3 control-label"><?php echo __('lblYear'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3 " >
                            <?php echo $this->Form->input('year_for_token', array('label' => false, 'class' => 'form-control', 'id' => 'year_for_token', 'placeholder' => '')); ?>
                            <span  id="year_for_token_error" class="form-error"><?php echo $errarr['year_for_token']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">

                    <div class="form-group">

                        <label for="" class="col-sm-3 control-label"><?php echo __('lbldocktype'); ?></label>    
                        <div class="col-sm-3 " >
                            <?php echo $this->Form->input('doc_type', array('label' => false, 'id' => 'doc_type', 'class' => 'form-control input-sm', 'options' => array('@' => '--Select--', $doc_type))); ?>
                            <span  id="doc_type_error" class="form-error"><?php //echo $errarr['year_for_token'];  ?></span>
                        </div>
                    </div>
                </div>

                <div  class="rowht"></div>
                <div class="row"  id="div_refernce_no" style="display:none">

                    <div class="form-group">

                        <label for="" class="col-sm-3 control-label"><?php echo __('lblrefdocknumber'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3 " >
                            <?php echo $this->Form->input('reference_no', array('label' => false, 'class' => 'form-control', 'id' => 'reference_no', 'class' => 'form-control', 'placeholder' => '','maxlength'=>"15")); ?>
                            <span  id="doc_type_error" class="form-error"><?php //echo $errarr['year_for_token'];  ?></span>
                        </div>
                    </div>
                </div>         
            </div>

        </div>


        <div class="box box-primary">

            <div class="box-body">
                <div class="box-header with-border">
                    <h3 class="box-title headbolder" ><?php echo __('Document Registration Details'); ?></h3>

                    <div class="hr1" style="border: 1px solid black;"></div>

                </div>
                <div  class="rowht"></div>   
                <div class="row">
                    <div class="form-group">
                       <!--  <label for="" class="col-sm-3 control-label"><?php echo __('lblregno'); ?><span style="color: #ff0000">*</span></label>  -->
                        
                    <!-- <div class="col-sm-3 right-border" >
                            <?php //echo $this->Form->input('final_doc_reg_no', array('label' => false, 'class' => 'form-control', 'id' => 'final_doc_reg_no', 'class' => 'form-control', 'placeholder' => '')); ?>
                            <span  id="final_doc_reg_no_error" class="form-error"><?php //echo $errarr['final_doc_reg_no_error']; ?></span>
                        </div> -->
<!--                             <label for="" class="col-sm-3 control-label"><?php echo __('lblregno'); ?><span style="color: #ff0000">*</span></label>   
                        <div class="col-sm-3 right-border" style="display:none >
                            <?php //  echo $this->Form->input('book_serial_number', array('label' => false, 'class' => 'form-control', 'id' => 'book_serial_number', 'class' => 'form-control', 'placeholder' => '')); ?>
                            <span  id="book_serial_number_error" class="form-error"><?php // echo $errarr['book_serial_number_error']; ?></span>
                        </div>
                        -->
                        <label for="" class="col-sm-3 control-label"><?php echo __('Book Number'); ?><span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-3 right-border" >
                            <?php echo $this->Form->input('bookno', array('label' => false ,'class' => 'form-control', 'id' => 'bookno', 'class' => 'form-control', 'placeholder' => '')); ?>
                           <!-- <span  id="deedno_error" class="form-error"><?php echo $errarr['bookno_error']; ?></span> -->
                        </div>

                        <label for="" class="col-sm-3 control-label"><?php echo __('Deed Number'); ?><span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-3 right-border" >
                            <?php echo $this->Form->input('deedno', array('label' => false ,'class' => 'form-control', 'id' => 'deedno', 'class' => 'form-control', 'placeholder' => '')); ?>
                           <!-- <span  id="deedno_error" class="form-error"><?php echo $errarr['deedno_error']; ?></span> -->
                        </div>
 <div  class="rowht"></div>
                        <label for="" class="col-sm-3 control-label"><?php echo __('Deed Year'); ?><span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-3 right-border" >
                            <?php echo $this->Form->input('deedyear', array('label' => false, 'class' => 'form-control', 'id' => 'deedyear', 'class' => 'form-control', 'placeholder' => '')); ?>
                            <!-- <span  id="deedyear_error" class="form-error"><?php echo $errarr['deedyear_error']; ?></span> -->
                        </div>
                                
                        <label for="" class="col-sm-3 control-label"><?php echo __('lblregdt'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3" >
                            <?php echo $this->Form->input('final_stamp_date', array('label' => false, 'class' => 'date form-control', 'id' => 'final_stamp_date', 'placeholder' => '')); ?>
                            <span  id="final_stamp_date_error" class="form-error"><?php echo $errarr['final_stamp_date_error']; ?></span>
                        </div>
                   
                    </div>
                </div>

            </div>
        </div>

        <div class="box box-primary">

            <div class="box-body">
                <div class="box-header with-border">
                    <h3 class="box-title headbolder" ><?php echo __('Document Registered Office Details '); ?><?php echo __('(Main)'); ?></h3>

                    <div class="hr1" style="border: 1px solid black;"></div>

                </div>
                <div  class="rowht"></div>   
                <div class="row">
                    <div class="form-group">

                        <label for="" class="col-sm-3 control-label"><?php echo __('lbladmdistrict'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3 " >
                            <?php echo $this->Form->input('district_id', array('label' => false, 'id' => 'district_id', 'class' => 'form-control input-sm', 'options' => array('@' => '--Select--', $doc_entered_district))); ?> 
                            <span  id="district_id_error" class="form-error"><?php echo $errarr['district_id_error']; ?></span>
                        </div>
<!--                        <label for="" class="col-sm-3 control-label"><?php //echo __('lblTaluka'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3 right-border" >
                            <?php //echo $this->Form->input('taluka_id', array('label' => false, 'id' => 'taluka_id', 'class' => 'form-control input-sm', 'options' => array('@' => '--Select--', $taluka))); ?> 
                            <span  id="taluka_id_error" class="form-error"><?php //echo $errarr['taluka_id_error']; ?></span>
                        </div>-->
                        
                        
                         <label for="" class="col-sm-3 control-label"><?php echo __('lbladmsubdivision'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3 right-border" >
                            <?php echo $this->Form->input('subdivision_id', array('label' => false, 'id' => 'subdivision_id', 'class' => 'form-control input-sm', 'options' => array('@' => '--Select--', $subdivision))); ?> 
                            <span  id="subdivision_id_error" class="form-error"><?php //echo $errarr['subdivision_id_error']; ?></span>
                        </div>

                    </div>
                </div>
                <div  class="rowht"></div>   
                <div class="row">
                    <div class="form-group">

                        <label for="" class="col-sm-3 control-label"><?php echo __('lbloffice1'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3 " >
                            <?php echo $this->Form->input('office_id', array('label' => false, 'id' => 'office_id', 'class' => 'form-control input-sm', 'options' => array('@' => '--Select--', $office))); ?>  
                            <span  id="office_id_error" class="form-error"><?php echo $errarr['office_id']; ?></span>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        <div class="box box-primary">

            <div class="box-body">
                <div class="box-header with-border">
                    <h3 class="box-title headbolder" ><?php echo __('Document Entered Office Details '); ?><?php echo __('(Visit)'); ?></h3>

                    <div class="hr1" style="border: 1px solid black;"></div>

                </div>
                <div  class="rowht"></div>   
                
                
<!--                     <div  class="rowht"></div>   
                <div class="row">
                    <div class="form-group">

                        <label for="" class="col-sm-3 control-label"><?php //echo __('District'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3" >
                            <?php// echo $this->Form->input('doc_entered_district', array('label' => false, 'id' => 'doc_entered_district', 'class' => 'form-control input-sm', 'options' => array('@' => '--Select--', $doc_entered_district))); ?>  
                            <span  id="doc_entered_district_error" class="form-error"><?php //echo $errarr['doc_entered_district_error']; ?></span>
                        </div>

                    </div>-->
                <!--</div>-->

                     
<!--                         <div class="row">-->
<!--                    <div class="form-group">

                        <label for="" class="col-sm-3 control-label"><?php // echo __('Subdivision'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3" >
                            <?php //echo $this->Form->input('doc_entered_subdivision', array('label' => false, 'id' => 'doc_entered_subdivision', 'class' => 'form-control input-sm', 'options' => array('@' => '--Select--', $doc_entered_subdivision))); ?>  
                            <span  id="doc_entered_subdivision_error" class="form-error"><?php //echo $errarr['doc_entered_subdivision_error']; ?></span>
                        </div>

                    </div>
                </div>
-->                <div  class="rowht"></div>   
                <div class="row">
                    <div class="form-group">
                        
                    <label for="" class="col-sm-3 control-label"><?php echo __('District'); ?><span style="color: #ff0000">*</span></label>    
<div class="col-sm-3" >
    <?php echo $this->Form->input('doc_entered_district', array('label' => false, 'id' => 'doc_entered_district', 'class' => 'form-control input-sm', 'options' => array('@' => '--Select--', $doc_entered_district))); ?>  
    <span  id="doc_entered_district_error" class="form-error"><?php// echo $errarr['doc_entered_district_error']; ?></span>
</div>

</div>

<label for="" class="col-sm-3 control-label"><?php echo __('subdivision '); ?><span style="color: #ff0000">*</span></label>    
<div class="col-sm-3" >
    <?php echo $this->Form->input('doc_entered_subdivision', array('label' => false, 'id' => 'doc_entered_subdivision', 'class' => 'form-control input-sm', 'options' => array('@' => '--Select--', $subdivision))); ?>  
    <span  id="doc_entered_subdivision_error" class="form-error"><?php //echo $errarr['doc_entered_subdivision_error']; ?></span>
</div>


                    <div class="form-group">

                        <label for="" class="col-sm-3 control-label"><?php echo __('lbloffice1'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3" >
                            <?php echo $this->Form->input('doc_entered_office', array('label' => false, 'id' => 'doc_entered_office', 'class' => 'form-control input-sm', 'options' => array('@' => '--Select--', $doc_entered_office))); ?>  
                            <span  id="doc_entered_office_error" class="form-error"><?php echo $errarr['doc_entered_office_error']; ?></span>
                        </div>

                    </div>    
                        
                        
                        
                        
                        
                        
<!--
                        <label for="" class="col-sm-3 control-label"><?php //echo __('lbloffice1'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3" >
                            <?php //echo $this->Form->input('doc_entered_office', array('label' => false, 'id' => 'doc_entered_office', 'class' => 'form-control input-sm', 'options' => array('@' => '--Select--', $doc_entered_office))); ?>  
                            <span  id="doc_entered_office_error" class="form-error"><?php// echo $errarr['doc_entered_office_error']; ?></span>
                        </div>
                        -->

                        

                    </div>
                </div>
<!--
            </div>-->
        </div>




        <div class="box box-primary">
            <div class="box-body">
                <div class="row center" >

                    <input type="hidden"  id="continue_flag">
                    <button   type="submit"  id="submit_b"  name="action"  value="submit_b" class="btn btn-primary">Submit</button>
                    <input type="button" id="btnCancel" name="btnCancel" class="btn btn-danger" style="width:155px;" value="<?php echo __('btncancel'); ?>" onclick="javascript: return forcancel();">

                </div>  
            </div>
        </div>

    </div>
</div>
<?php echo $this->Form->end(); ?>