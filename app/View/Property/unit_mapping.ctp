<script>
    $(document).ready(function () {



        $('#usage_main_catg_id').change(function () {
            var usage_main_catg_id = $("#usage_main_catg_id option:selected").val();
            //   var i;
            $.getJSON('getusagesub', {usage_main_catg_id: usage_main_catg_id}, function (data)
            {
                var sc = '<option value="">select</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#usage_sub_catg_id").prop("disabled", false);
                $("#usage_sub_catg_id option").remove();
                $("#usage_sub_catg_id").append(sc);
            });
        });

        $('#usage_sub_catg_id').change(function () {
          get_unitmapping_records();
        });
         $('#usage_param_id').change(function () {
          get_unitmapping_records();
        });
        
        
    });
//    function get_unitmapping_records() {
//    $.post("Masters/get_unitmapping_records",
//    {
//    usagemain: $('#usage_main_catg_id').val(),
//            usagesub:  $('#usage_sub_catg_id').val()
//    },
//            function (data, status) {
//            alert("Status: " + status);
//            });
//    }
    function get_unitmapping_records() {
        $.post('<?php echo $this->webroot;?>Property/get_unitmapping_records', { usagemain: $('#usage_main_catg_id').val(), usagesub: $('#usage_sub_catg_id').val(),usage_param_id: $('#usage_param_id').val()}, function (response) {
        $('#unitlisttbl').html('');
        $('#tableunit_mapping').dataTable().fnDestroy(); 
        $('#unitlisttbl').html(response);           
         $('#tableunit_mapping').DataTable();
        });
    }

</script>
<script>
    $(document).ready(function () {

        $('#tableunit_mapping').dataTable({
            "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });

    });</script>



<?php echo $this->Form->create('unit_mapping', array('id' => 'unit_mapping', 'autocomplete' => 'off')); ?>
 <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title"><?php echo __('lblunitmapping'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/admin/unit_mapping_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label for="district_id" class="col-sm-2 control-label"><?php echo __('lbladmdistrict'); ?> :</label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('district_id', array('label' => false, 'id' => 'district_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $district))); ?>
                            <span id="developed_land_types_id_error" class="form-error"></span>
                        </div>
                        <label for="usage_main_catg_id" class="col-sm-2 control-label"><?php echo __('lblusamaincat'); ?> :<span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('usage_main_catg_id', array('label' => false, 'id' => 'usage_main_catg_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $usagemain))); ?>
                            <span id="usage_main_catg_id_error" class="form-error"></span>
                        </div>
                        <label for="usage_sub_catg_id" class="col-sm-2 control-label"><?php echo __('lblsubcat'); ?> :<span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('usage_sub_catg_id', array('label' => false, 'id' => 'usage_sub_catg_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $usagesub))); ?>
                            <span id="usage_sub_catg_id_error" class="form-error"></span>
                        </div>


                    </div>
                </div>
                <div  class="rowht"></div>

                <div class="row">
                    <div class="form-group">
                        <label for="usage_param_id" class="col-sm-2 control-label"><?php echo __('lblinputitem'); ?> :</label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('usage_param_id', array('label' => false, 'id' => 'usage_param_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $usagesubsub))); ?>
                            <span id="usage_param_id_error" class="form-error"> </span>
                        </div>

                        <label for="unit_id" class="col-sm-2 control-label"><?php echo __('lblunit'); ?> :<span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('unit_id', array('label' => false, 'id' => 'unit_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $unitdata))); ?>
                            <span id="unit_id_error" class="form-error"><?php echo $errarr['unit_id_error']; ?></span>
                        </div>

                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <label for="sr_no" class="col-sm-2 control-label"><?php echo __('lblDisplayOrder'); ?> :<span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('sr_no', array('label' => false, 'id' => 'sr_no', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span id="sr_no_error" class="form-error"><?php echo $errarr['sr_no_error']; ?></span>
                        </div>
                    </div>
                </div>
                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center" >
                    <div class="form-group" >
                        <button id="btnadd" name="btnadd" class="btn btn-info "  onclick="javascript: return formadd();">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnsave'); ?>
                        </button>
                        <button  id="btncancel" name="btncancel" class="btn btn-info" type="reset">
                            <span class="glyphicon glyphicon-floppy-remove"></span>&nbsp;&nbsp; <?php echo __('btncancel'); ?></button>
                    </div>
                </div>

            </div>
        </div>
        <div class="box box-primary">
            <div class="box-body">
                <table id="tableunit_mapping" class="table table-striped table-bordered table-hover">  
                    <thead >  
                        <tr>  
                            <th class="center"><?php echo __('lbladmdistrict'); ?></th>
                            <th class="center"><?php echo __('lblusamaincat'); ?></th>
                            <th class="center"><?php echo __('lblsubcat'); ?></th>
                            <th class="center width10"><?php echo __('lblinputitem'); ?></th>
                            <th class="center width10"><?php echo __('lblunit'); ?></th>
                            <th class="center width10"><?php echo __('lblsrno'); ?></th>                           
                            <th class="center width10"><?php echo __('lblaction'); ?></th>
                        </tr>  
                    </thead>
                    <tbody id="unitlisttbl">
                      <?php foreach ($unitmappingrecord as $unitrecord1):?>
                            <tr>
                                    <td ><?php echo $unitrecord1[0]['district_name_'. $laug]; ?></td>
                                    <td ><?php echo $unitrecord1[0]['usage_main_catg_desc_'. $laug]; ?></td>
                                    <td ><?php echo $unitrecord1[0]['usage_sub_catg_desc_'. $laug]; ?></td>
                                    <td ><?php echo $unitrecord1[0]['usage_param_desc_'. $laug]; ?></td>
                                    <td ><?php echo $unitrecord1[0]['unit_desc_'. $laug]; ?></td>
                                    <td ><?php echo $unitrecord1[0]['unit_id']; ?></td>
                                    
                                    <td>
                                        <!--<a href="<?php echo $this->webroot; ?>Property/unit_mapping/<?php echo $unitrecord1[0]['mapping_id']; ?>" class="btn-sm btn-default"><span class="fa fa-pencil"></span> </a>-->    
                                            <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-remove')), array('action' => 'unit_mapping_remove', $unitrecord1[0]['mapping_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn-sm btn-danger"), array('Are you sure to delete?')); ?></a>
                                    </td>                                   
                            </tr>
                        <?php endforeach; ?>
                        <?php unset($unitrecord1); ?> 
                        
                    </tbody>
                </table>                
            </div>
        </div>

    </div>
  
</div>
