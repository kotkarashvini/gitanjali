<script>
    $(document).ready(function () {

        $('#test').hide();
        $('.select').click(function () {
            $('#test').show();
        });
    });
</script>
<script>

    function formview(emp_code) {
//        alert(id);
        $.getJSON('<?php echo $this->webroot; ?>Masters/get_selected_data', {emp_code: emp_code}, function (data)
        {
//            alert(data.);
            $('#emp_code').val(data['emp_code']);
            $('#employee_name').val(data['emp_fname']);
            $('#old_designation_name').val(data['desg_desc_en']);
            $('#old_office_name').val(data['office_name_en']);
        });
    }
</script>  

<div class="box-body">

    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                 <th class="center">Emp Code</th>
                <th class="center"><?php echo __('lblempname'); ?> </th>
                <th class="center"><?php echo __('lblpreofcname'); ?> </th>
                <th class="center"><?php echo __('lbldesignation'); ?> </th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
           
            <?php if(!empty($office)){foreach ($office as $office1): ?>
                <tr>
                     <td ><?php echo $office1[0]['emp_code']; ?></td>
                    <td ><?php echo $office1[0]['emp_fname']; ?></td>
                    <td ><?php echo $office1[0]['office_name_en']; ?></td>
                    <td ><?php echo $office1[0]['desg_desc_en']; ?></td>
                    <td> 
                        <input type="button" class="btn btn-primary select" value="<?php echo __('lblSelect'); ?>" onclick="javascript: return formview('<?php echo $office1[0]['emp_code']; ?>');">
                    </td>
                </tr>

            <?php endforeach; } ?>

            <?php unset($office1); ?>
        </tbody>
    </table>
    <div class="rowht"></div><div class="rowht"></div>

    <div id="test" class="row"> 
        <div class="col-sm-12">
        <div class="form-group">
             <label for="employee_name" class="col-sm-2 control-label">Employee Code:<span style="color: #ff0000">*</span></label> 
            <div class="col-sm-2">
                <?php echo $this->Form->input('emp_code', array('label' => false, 'id' => 'emp_code', 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?>
            </div>
            <label for="employee_name" class="col-sm-2 control-label"><?php echo __('lblempselect'); ?> :<span style="color: #ff0000">*</span></label> 
            <div class="col-sm-2">
                <?php echo $this->Form->input('employee_name', array('label' => false, 'id' => 'employee_name', 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?>
            </div>
            <label for="old_office_id" class="col-sm-2 control-label"><?php echo __('lblselofc'); ?> :<span style="color: #ff0000">*</span></label> 
            <div class="col-sm-2">
                <?php echo $this->Form->input('old_office_name', array('label' => false, 'id' => 'old_office_name', 'type' => 'text', 'class' => 'form-control input-sm', 'readonly')); ?>
            </div>
            <label for="old_desi_id" class="col-sm-2 control-label"><?php echo __('lbldesignation'); ?> :<span style="color: #ff0000">*</span></label> 
            <div class="col-sm-2">
                <?php echo $this->Form->input('old_designation_name', array('label' => false, 'id' => 'old_designation_name', 'type' => 'text', 'class' => 'form-control input-sm', 'readonly')); ?>
            </div>
        </div>
            </div>
    </div>
</div>