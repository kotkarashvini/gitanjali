<?php
echo $this->element("Helper/jqueryhelper");
?>
<script>
    $(document).ready(function () {

        $('#table').dataTable({
               "order":[],
            "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
        //---------------Division->District filteration
    
        //---------------------------------    
        //---------------District->Subdivision filteration
        $('#district_id').change(function () {
            var district_id = $('#district_id').val();

<?php if ($configure[0][0]['is_subdiv'] == 'Y') { ?>
                $.postJSON('<?php echo $this->webroot; ?>BlockLevels/getsubdiv', {district_id: district_id}, function (data)
                {
                    var sc = '<option value="">--select--</option>';
                    $.each(data, function (index, val) {

                        sc += "<option value=" + index + ">" + val + "</option>";
                    });
                    $("#subdivision_id option").remove();
                    $("#subdivision_id").append(sc);
                });
<?php } else { ?>
                var district_id = $('#district_id').val();

                $.postJSON('<?php echo $this->webroot; ?>BlockLevels/gettaluka', {district_id: district_id}, function (data)
                {
                    var sc = '<option value="">--select--</option>';
                    $.each(data, function (index, val) {

                        sc += "<option value=" + index + ">" + val + "</option>";
                    });
                    $("#taluka_id option").remove();
                    $("#taluka_id").append(sc);
                });
<?php } ?>

        });
      
     function formadd() { 
         pr('in formadd');
         exit;
         alert('in formadd');
         exit;
        $.post('<?php echo $this->webroot; ?>Searchlegacyfinal/upload_excel_to_tbl_data', function (data)
        {
pr('after coming');
            if (data == 'false')
            {
                $('#commonflag').val('N');
                $('#commonfileupload' + id).val('');
                alert('data saved sucessfully');
                return false;

            }
        }
        //---------------------------------    
        $('#subdivision_id').change(function () {
            var subdivision_id = $('#subdivision_id').val();
            $.postJSON('<?php echo $this->webroot; ?>BlockLevels/gettaluka', {subdivision_id: subdivision_id}, function (data)
            {
                var sc = '<option value="">--select--</option>';
                $.each(data, function (index, val) {

                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#taluka_id option").remove();
                $("#taluka_id").append(sc);
            });
        });




    });</script>


<?php echo $this->Form->create('upload_excel_to_tbl_data', array('id' => 'upload_excel_to_tbl_data', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">
       
    <div class="box box-primary">
            <div class="box-header with-border">
                        <label for="district_id" class="control-label"><?php echo __('District'); ?> <span class="star">*</span></label>
                        <?php echo $this->Form->input('district_id', array('options' => $distdata, 'empty' => '--select--', 'id' => 'district_id', 'class' => 'form-control input-sm', 'label' => false)); ?>                            
                        <span class="form-error" id="district_id_error"></span>
            </div>
    </div>

        <div class="box box-primary">
            <div class="box-header with-border">
            <label for="circle_code" class="control-label"><?php echo __('Enter Circle Code'); ?> <span class="star">*</span></label>
            <?php echo $this->Form->input("circle_code", array('label' => false, 'id' => 'circle_code', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '100')) ?>
                            <span id="circle_code_error" class="form-error"></span>
                </div> 
            </div>

            <div class="box box-primary">
            <div class="box-header with-border">
            <label for="circle_code" class="control-label"><?php echo __('Enter Circle Name'); ?> <span class="star">*</span></label>  
                                      <?php echo $this->Form->input("circle_name", array('label' => false, 'id' => 'circle_name', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '100')) ?>

           
            </div> 
            </div>

                <div class="col-md-12">
                <button id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('Add'); ?>
                                </button>
                           
                                <button id="btnupdate" name="btnadd" class="btn btn-info " onclick="javascript: return btnupdate();">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnupdate'); ?>
                                </button>

                                <button id="btncancel" name="btnadd" class="btn btn-info " onclick="javascript: return forcancel();">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btncancel'); ?>
                                </button>

                </div>

</div>
</div>
</form>
              