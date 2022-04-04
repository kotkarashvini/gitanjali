<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>

<script type="text/javascript">
    $(document).ready(function () {
        


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
            
        

           // $.post("rategrid", {district: district}, function (data)
           // {
               // $("#divrategrid").html("");
              //  $("#divrategrid").html(data);
            //});

        })

        $('#taluka_id').change(function () {
            var district = $("#district_id option:selected").val();
            var taluka = $("#taluka_id option:selected").val();

            $.post("localgovgrid", {district: district, taluka: taluka}, function (data)
            {
                $("#divrategrid").html(data);
            });
        })
        
        
    });

    function formadd() {
        $("#actiontype").val('1');
        $("#hfaction").val('S');

//        document.gshfactionetElementById("hfaction").value = 'S';
//        document.getElementById("actiontype").value = '1';
    }
    function formcancel() {
        $("#actiontype").val('2');
//        $("#hfaction").val('S');

//        document.gshfactionetElementById("hfaction").value = 'S';
//        document.getElementById("actiontype").value = '1';
    }
    


</script>

<?php echo $this->Form->create('localgovlist', array('id' => 'localgovlist', 'autocomplete' => 'off')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lbllocalgovbodylist'); ?></h3></center>
                <div class="box-tools pull-right">
                    <!--<a  href="<?php //echo $this->webroot;   ?>helpfiles/LocalGoverningBodyList/locgovbodylist_<?php // echo $laug;   ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>-->
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                      
                        <label for="dist_id" class="col-sm-2 control-label"><?php echo __('lbladmdistrict'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">

                            <?php echo $this->Form->input('district_id', array('label' => false, 'id' => 'district_id', 'class' => 'form-control input-sm', 'empty' => '--Select District--', 'options' => array($District))); ?>
                           <span id="district_id_error" class="form-error"><?php // echo $errarr['state_id_error'];  ?></span>
                        </div>
                        
                        <label for="taluka_id" class="col-sm-2 control-label"><?php echo __('lbladmtaluka'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('taluka_id', array('label' => false, 'id' => 'taluka_id', 'class' => 'form-control input-sm', 'empty' => '--Select Taluka--', 'options' => array())); ?>
                            <span id="taluka_id_error" class="form-error"><?php // echo $errarr['taluka_id_error'];  ?></span>
                        </div>
                        <label for="dist_id" class="col-sm-2 control-label"><?php echo __('lbllocalgoberningbody'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('ulb_type_id', array('label' => false, 'id' => 'ulb_type_id', 'class' => 'form-control input-sm', 'empty' => '--Select governing body--', 'options' => array($localgovbody))); ?>
                           <span id="ulb_type_id_error" class="form-error"><?php //echo $errarr['taluka_id_error'];  ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div> <div  class="rowht"></div>
               
                <div  class="rowht"></div> <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <?php
                        //creating dyanamic text boxes using same array of config language
                        foreach ($languagelist as $key => $langcode) {
                            ?>
                            <div class="col-md-3">
                                <label><?php echo __('lbllocalgoberningbody') . "  " . $langcode['mainlanguage']['language_name']; ?>
                                    <span style="color: #ff0000">*</span>
                                </label>    
                                <?php echo $this->Form->input('governingbody_name_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'governingbody_name_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => "100")) ?>
                                <span id="<?php echo 'governingbody_name_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error">
                                    <?php //echo $errarr['governingbody_name_' . $langcode['mainlanguage']['language_code'] . '_error']; ?>
                                </span>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <div  class="rowht"></div> <div  class="rowht"></div>
                <div class="row center">
                    <div class="form-group">
                        <div class="col-sm-12 tdselect">
                            <button id="btnadd" type="submit"name="btnadd" class="btn btn-info "onclick="javascript: return formadd();">
                                <?php echo __('lblbtnAdd'); ?></button>
                         
                            
                            
                            
                            <a <?php echo $this->Html->Link($this->Html->tag('span', 'Cancel', array('class' => 'btn btn-info')), array('action' => 'localgovlist'), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Cancel'))); ?></a>
                        </div>
                    </div>
                </div>
                   <div  class="rowht"></div> <div  class="rowht"></div>
                <br><br>
              

                <div class="rowht"></div>
                <div class="rowht"></div>

                <div id="divrategrid" class="table-responsive">                   
                </div>


            </div>
        </div>

        <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
       <!--    <input type='hidden' value='<?php // echo $hfactionval;  ?>' name='hfaction' id='hfaction'/>-->
        <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
        <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>


    </div>

</div>

<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>




