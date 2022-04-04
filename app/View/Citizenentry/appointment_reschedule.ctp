<script>

    $(document).ready(function () {
        $('#reschedule_date,#appointment_date').datepicker({
            todayBtn: "linked",
            language: "it",
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy"
        });
        
         <?php if (!empty($reschedule)) { ?>
                    $('#tableapp').dataTable({
                    "iDisplayLength": 10,
                            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
                    });<?php } ?>
    });
    });



    function formsave() {

        var retVal = confirm("Are you sure do you want to change appointment date ?");
        if (retVal == true)
        {
            $('#appointment').submit();
            
        } else{
            return false;
        }



    }



</script> 
<?php
$doc_lang = $this->Session->read('doc_lang');
echo $this->element("Registration/main_menu");
?>

<?php echo $this->Form->create('appointment', array('id' => 'appointment')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('Reschedule Appointment') ?></h3></center>

            </div>

        </div>


        <div class="box box-primary">
            <div class="box-body">

                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-3"></div>
                        <label for="appointment_date" class="col-sm-2 control-label"><?php echo __('Select appointment Date'); ?><span style="color: #ff0000">*</span></label>   
                        <div class="col-sm-3">
<?php echo $this->Form->input('appointment_date', array('label' => false, 'id' => 'appointment_date', 'type' => 'text', 'class' => 'form-control input-sm')); ?>
                        </div>

                    </div>

                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-3"></div>
                        <label for="appointment_date" class="col-sm-2 control-label"><?php echo __('Reschedule To Date'); ?><span style="color: #ff0000">*</span></label>   
                        <div class="col-sm-3">
<?php echo $this->Form->input('reschedule_date', array('label' => false, 'id' => 'appointment_date', 'type' => 'text', 'class' => 'form-control input-sm')); ?>
                        </div>

                    </div>

                </div>
                <div  class="rowht"></div>

                <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
                <div class="row center" >
                    <div class="col-sm-12">
<?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
                        <button type="submit" id="btnCancel" name="btnCancel" class="btn btn-info" onclick="javascript: return formsave();"><?php echo __('btnsave'); ?></button>
                        <button type="submit"  id="btnNext" name="btnNext" class="btn btn-info" onclick="javascript: return forcancel();"><?php echo __('btncancel'); ?></button>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>

            </div>
        </div>
    </div>
</div>

<div class="box box-primary" id="divwitness">
            <div class="box-body">
                <table id="tableapp" class="table table-striped table-bordered table-hover">  
                    <thead >  
                        <tr>  
                            <th class="center"><?php echo __('Original Appointment Date'); ?></th>

                            <th class="center width10"><?php echo __('Schedule Date'); ?></th>
                        </tr>  
                    </thead>
                    <tbody>
                        <?php if(!empty($reschedule)){foreach($reschedule as $rec){
                           
                            ?>
                        <tr>
                            <td><?php echo date('d-m-Y', strtotime($rec['appointment']['original_date'])); ?> </td>
                            <td><?php echo date('d-m-Y', strtotime($rec['appointment']['appointment_date'])); ?></td>
                        
                        </tr>
                        
                        
                        <?php }} ?>
                     
                    </tbody>
                </table> 
               
            </div>
        </div>

<?php echo $this->Form->end(); ?>