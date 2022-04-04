

<script>

    $(document).ready(function () {

        if ($('#hfhidden1').val() === 'Y')
        {
            $('#tblofccharge').dataTable({
                "iDisplayLength": 10,
                "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
            });
        } else {
            $('#tblofccharge').dataTable({
                "iDisplayLength": 10,
                "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
            });
        }

        $("#jurisdiction_date").datepicker();

        $("#btnSave").click(function () {
            $("#designation_office_link").submit();
        });


    });

    function formdelete(emp_id)
    {

        $.post('<?php echo $this->webroot; ?>Masters/delete_office_emplink', {emp_id: emp_id}, function (data)
        {

            if (data == 1)
            {
                alert('Record Deleted Successfully');
                location.reload();
            }
            else
            {

                alert('Error!');
            }

        });
    }

    function formupdate(emp_id, jdate)
    {


        $.post('<?php echo $this->webroot; ?>Masters/get_officeid', {emp_id: emp_id}, function (data)
        {


            $('input[type=checkbox]').attr('checked', false);
            $("#officeincharge_link")[0].reset();
            $('#emp_id').val(emp_id);
            $('#jurisdiction_date').val(jdate);
            var temp = new Array();
            temp = data.split(",");

            for (var i = 0; i < temp.length; i++) {
                $("input[type=checkbox][value=" + temp[i] + "]").attr("checked", "true");
            }

        });
        return false;
    }

</script>
<?php echo $this->Form->create('officeincharge_link', array('id' => 'officeincharge_link')); ?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblofcemplinkage'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Employee office Linkage/employee_office_incharge_link_<?php echo $language; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-3"></div>
                        <label for="office_id" class="col-sm-2 control-label"><?php echo __('lblempselect'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('emp_id', array('label' => false, 'id' => 'emp_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $emp))); ?>
                       <span id="emp_id_error" class="form-error"><?php //echo $errarr[emp_id_error'];     ?></span>
                        </div>

                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-3"></div>
                        <label for="office_id" class="col-sm-2 control-label"><?php echo __('lblofficename'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3" style="overflow-y: scroll;border: 2px">

                            <?php echo $this->Form->input('office_id', array('label' => false, 'id' => 'office_id', 'class' => 'form-control input-sm', 'options' => array($office), 'multiple' => 'checkbox')); ?>
                        <span id="office_id_error" class="form-error"><?php //echo $errarr[office_id_error'];     ?></span>
                        
                        </div>

                    </div>
                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-3"></div>
                        <label for="office_id" class="col-sm-2 control-label"><?php echo __('lbldate'); ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3" >

                            <?php echo $this->Form->input('jurisdiction_date', array('label' => false, 'id' => 'jurisdiction_date', 'class' => 'form-control input-sm')); ?>
                         <span id="jurisdiction_date_error" class="form-error"><?php //echo $errarr[jurisdiction_date_error'];     ?></span>
                        </div>

                    </div>
                </div>
                <div  class="rowht"></div>
                <div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center">
                    <div class="col-sm-12">
                        <button type="submit" id="btnSave" class="btn btn-info">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?></button>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-primary">

            <div class="box-body">
               
                    <table id="tblofccharge" class="table table-striped table-bordered table-hover">  
                        <thead >  
                            <tr>  
                                <?php if ($this->Session->read("sess_langauge") == 'en') { ?>
                                    <th class="center"><?php echo __('lblemployee'); ?></th>
                                <?php } else { ?>
                                    <th class="center"><?php echo __('lblemployee'); ?>&nbsp;<?php echo __('lbllevelname'); ?></th>
                                <?php } ?>
                                <th class="center width10"><?php echo __('lblaction'); ?></th>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php foreach ($office_link as $office_link): ?>
                                <tr>
                                    <td ><?php echo $office_link[0]['emp_fname'] . ' ' . $office_link[0]['emp_mname'] . ' ' . $office_link[0]['emp_lname']; ?></td>
                                    <td >
                                        <button id="btnupdate" name="btnupdate" class="btn btn-default "  onclick="javascript: return formupdate(('<?php echo $office_link[0]['emp_id']; ?>'), ('<?php echo date('d-m-Y', strtotime(str_replace('-', '/', $office_link[0]['jurisdiction_date']))); ?>'));">
                                            <span class="glyphicon glyphicon-pencil"></span></button>
                                        <span id="btndelete" name="btndelete" class="btn btn-default "  onclick="javascript: return formdelete(('<?php echo $office_link[0]['emp_id']; ?>'));">
                                            <span class="glyphicon glyphicon-remove"></span></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php unset($office_link); ?> 
                        </tbody>

                    </table> 
                    <?php if (!empty($office_link)) { ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
               
            </div>
        </div>

    </div>
</div>