<?php
echo $this->element("Helper/jqueryhelper");
?>
<script>
    $(document).ready(function () {
        $('.date').datepicker({
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            calendarWeeks: true,
            autoclose: true,
            todayHighlight: true
        });
        $('#table').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
        // For Edit

<?php if (isset($result)) { ?>
            $.post('<?php echo $this->webroot; ?>Office/get_local_holiday_flag', {holiday_type_id: <?php echo $result['holiday_type_id']; ?>}, function (data)
            {
                if (data == 1) {
                    $("#localholidaydiv").show();
                } else {
                    $("#localholidaydiv").hide();
                    //$('#officelist').html('');
                }

            });

<?php } ?>




        $('#holiday_type_id').change(function () {
            var holiday_type_id = $("#holiday_type_id option:selected").val();

            $.post('<?php echo $this->webroot; ?>Office/get_local_holiday_flag', {holiday_type_id: holiday_type_id}, function (data)
            {
                $("#district_id").val($("#target option:first").val());
                if (data == 1) {
                    $("#localholidaydiv").show();

                } else {
                    $("#localholidaydiv").hide();
                    $('#officelist').html('');

                }

            });
        });


        $('#district_id').change(function () {
            var district_id = $('#district_id').val();

            $.postJSON('<?php echo $this->webroot; ?>Office/get_office_list_by_district', {district_id: district_id}, function (data)
            {
                var html = '<div class="text-success"><?php echo __('lblofclist'); ?></div>';
                $.each(data, function (index, val) {
                    html = html + "<div class='checkbox office_id'><label><input name='data[holiday][office_id][]' type='checkbox' value='" + index + "'  >   <b>" + val + "</b></label></div>";
                });
                html += '<span class="form-error" id="office_id_error"></span>';
                $('#officelist').html(html);

            });
        });
    });</script>


<?php echo $this->Form->create('holiday', array('id' => 'holiday', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">
         <div class="note">
             <?php echo __('lblnote'); ?>  <span style="color: #ff0000">*</span> <?php echo __('lblstarmandatorynote'); ?>
         </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblholiday'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/admin/holiday_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="col-md-12">
                    <div class="col-sm-2">
                        <label for="holiday_type_id" class="control-label"><?php echo __('lblholidaytype'); ?> <span class="star">*</span> </label>
                        <?php echo $this->Form->input('holiday_type_id', array('options' => $holidaytypedata, 'empty' => '--select--', 'id' => 'holiday_type_id', 'class' => 'form-control input-sm ', 'label' => false)); ?>
                        <span class="form-error" id="holiday_type_id_error"></span>
                    </div>
                </div>
                <div class="col-md-12">           
                    <?php
//  creating dyanamic text boxes using same array of config language
                    foreach ($languagelist as $key => $langcode) {
                        ?>
                        <div class="col-md-3">
                            <label><?php echo __('lblholidaydesc') . "  " . $langcode['mainlanguage']['language_name']; ?><span style="color: #ff0000">*</span></label>    
                            <?php echo $this->Form->input('holiday_desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'holiday_desc_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '100')) ?>
                            <span id="<?php echo 'holiday_desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error"> </span>
                        </div>
                    <?php } ?>
                    <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>

                </div>

                <br>
                <div class="col-md-12">
                    <div class="col-md-3">
                        <label for="holiday_fdate" class="control-label"><?php echo __('lblholidaydate'); ?><span style="color: #ff0000">*</span></label>
                        <?php echo $this->Form->input('holiday_fdate', array('label' => false, 'id' => 'holiday_fdate', 'class' => 'date form-control input-sm', 'type' => 'text')) ?>
                        <span id="holiday_fdate_error" class="form-error"><?php //echo $errarr['holiday_fdate_error'];                            ?></span>

                    </div>
                </div>
                <div  class="rowht"></div>

                <div class="col-md-12"  id="localholidaydiv" hidden="true">
                    <div class="col-md-3">

                        <label for="district_id" class="control-label"><?php echo __('lbladmdistrict'); ?> <span class="star">*</span></label>
                        <?php echo $this->Form->input('district_id', array('options' => $districtdata, 'empty' => '--select--', 'id' => 'district_id', 'class' => 'form-control input-sm ', 'label' => false)); ?>                            
                        <span class="form-error" id="district_id_error"></span>
                    </div>
                    <div class="col-md-3" id="officelist" >
                   
               <?php  if (isset($office)) { ?>
                           <div class="text-success"><?php echo __('lblofclist'); ?></div>   
                  <?php      foreach ($office as $key => $single) {
                               $checked='';
                                foreach ($HolidayMapping as $key1 => $single1) {
                                    if($key==$key1){
                                        $checked='checked=cheked';
                                    }
                                }
                                
                                
                                ?>
                                <div class="checkbox office_id">                                    
                                    <label><input name="data[holiday][office_id][]" type="checkbox" value="<?php echo $key; ?>" <?php echo $checked;?>>   <b><?php echo $single; ?> </b></label>
                                </div>

                                <?php
                            }
                        }
                        ?>



                    </div>


                </div>




                <?php
                echo $this->Form->input('holiday_id', array('label' => false, 'id' => 'holiday_id', 'type' => 'hidden'));
                ?>


                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>


                <div class="row center">
                    <div class="form-group">
                        <div class="col-sm-12 tdselect">
                            
                            <?php if(isset($editflag)){?>
                            <button id="btnadd" name="btnadd" class="btn btn-info ">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnupdate'); ?>
                            </button>
                            <?php }else{ ?>
                                <button id="btnadd" name="btnadd" class="btn btn-info ">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnsave'); ?>
                            </button>
                            <?php } ?>
                            
                            <a href="<?php echo $this->webroot; ?>Office/holiday" class="btn btn-info"><?php echo __('btncancel'); ?></a>
                        </div>
                    </div>
                </div>






                <?php echo $this->Form->end(); ?>



            </div>
        </div>
    </div>
</div>




<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-body">
                <table id="table" class="table table-striped table-bordered table-condensed">  
                    <thead>  

                        <tr> 
                            <?php
                            foreach ($languagelist as $langcode) {
                                ?>
                                <th class="center"><?php echo __('lblholiday') . "  " . $langcode['mainlanguage']['language_name']; ?></th>

                            <?php } ?>

                            <th class="center width10"><?php echo __('lblholidaydate'); ?></th>
                            <th class="center width10"><?php echo __('lblholidaytype'); ?></th>
                            <th class="center width10"><?php echo __('lbladmdistrict'); ?></th>

                            <th class="center width10"><?php echo __('lblaction'); ?></th>

                        </tr>  
                    </thead>
                    <tbody>
                        <!--<tr>--> 


                        <?php
                        foreach ($holidaydata as $holidaydata1) {
                            ?>
                            <tr>
                                <?php
                                //  creating dyanamic table data(coloumns) using same array of config language
                                foreach ($languagelist as $langcode) {
                                    ?>
                                    <td ><?php echo $holidaydata1[0]['holiday_desc_' . $langcode['mainlanguage']['language_code']]; ?></td>


                                <?php } ?>

                                <td><?php echo $holidaydata1[0]['holiday_fdate']; ?></td>
                                <td><?php echo $holidaydata1[0]['holiday_type_' . $laug]; ?></td>
                                <td><?php echo $holidaydata1[0]['district_name_' . $laug]; ?></td>

                                <td>
                                     <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-pencil')), array('action' => 'holiday', $holidaydata1[0]['holiday_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Edit'), 'class' => "btn btn-success"), array('Are you sure?')); ?></a>
                               
                                    <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-remove')), array('action' => 'delete_holiday', $holidaydata1[0]['holiday_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-danger"), array('Are you sure?')); ?></a>
                               
                                </td> 
                            </tr> 
                        <?php } ?>

                    </tbody>

                </table> 
            </div>
        </div>
    </div>
</div>  
