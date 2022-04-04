

<script>

    $(document).ready(function () {

        $('#tabledivisionnew').dataTable({
            "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });

        $('#date_of_death').datepicker({
            format: "yyyy-mm-dd",
            todayBtn: "linked",
            calendarWeeks: true,
            //orientation: "top left",
            autoclose: true,
            todayHighlight: true
        });

        $('#actual_exec_date').datepicker({
            format: "yyyy-mm-dd",
            todayBtn: "linked",
            calendarWeeks: true,
            //orientation: "top left",
            autoclose: true,
            todayHighlight: true
        });
    });

    function formlock(id, token_no) {

        $('#hftokenno').val(token_no);
        $('#hfid').val(id);
        $('#hfupdateflag').val('Y');
        var conf = confirm('Are You Sure to Lock this Records');
        if (!conf) {
            return false;
        } else {

            $('#willexecution').submit();
        }
    }

    function formupdate(id, token_no, date_of_death, actual_exec_date, doc_reg_no, exec_date) {
//             alert('id');
//alert(id);

        $('#hfid').val(id);
        $('#hfdateofdeath').val(date_of_death);
        $('#hfactualexecdate').val(actual_exec_date);

        $('#date_of_death').val(date_of_death);
        $('#actual_exec_date').val(actual_exec_date);
        $('#hfdocumentnumber').val(doc_reg_no);
        $('#hfexecutiondate').val(exec_date);
        $('#hftokenno').val(token_no);

//        $('#hfupdateflag').val('Y');
        $('#btnadd').html('Save');
//        $('#willexecution').submit();
        return false;
    }





</script>


<?php echo $this->Form->create('willexecution', array('id' => 'willexecution', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title" style="font-weight: bolder">Will Execution</h3></center>
            </div>
        </div>

        <div class="box box-primary">
            <br>
            <div id="date">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-3"></div>
                        <label for="From_Date" class="col-sm-2">Date Of Death</label>   
                        <div class="input-group date col-sm-2">
                            <?php echo $this->Form->input('date_of_death', array('label' => false, 'id' => 'date_of_death', 'type' => 'text', 'name' => 'date_of_death', 'class' => 'form-control input-sm', 'readonly' => 'readonly', 'value' => date('Y-m-d'))); ?> 
                            <span class="input-group-addon glyphicon glyphicon-calendar"></span>
                            <span id="date_of_death_error" class="form-error"><?php //echo $errarr['date_of_death_error'];  ?></span>
                        </div>
                    </div> 
                </div>
                <div  class="rowht">&nbsp;</div>
                <div class="row" >
                    <div class="form-group">
                        <div class="col-sm-3"></div>
                        <label for="TO Date" class="col-sm-2" >Actual Execute Date</label> 
                        <div class="input-group date col-sm-2">
                            <?php echo $this->Form->input('actual_exec_date', array('label' => false, 'id' => 'actual_exec_date', 'type' => 'text', 'name' => 'actual_exec_date', 'class' => 'form-control input-sm', 'readonly' => 'readonly', 'value' => date('Y-m-d'))); ?>
                            <span class="input-group-addon glyphicon glyphicon-calendar"></span>
                            <span id="actual_exec_date_error" class="form-error"><?php //echo $errarr['actual_exec_date_error'];  ?></span>
                        </div>
                    </div>
                </div>
                <div class="row center" >
                    <div class="form-group">
                        <button id="btnSave" name="btnSave" class="btn btn-info " onclick = "javascript: return EncryptSHA1();">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?></button>
                    </div>

                </div>
            </div>  

            <div class="box-body">
                <table id="tabledivisionnew" class="table table-striped table-bordered table-hover">  
                    <thead >  
                        <tr>  
                            <th class="center">ID</th>
                            <th class="center">Token No</th>
                            <th class="center">Document Reg No</th>
                            <th class="center">Execution Date</th>
                            <th class="center">Article Name</th>

                            <th class="center">Date Of Death</th>
                            <th class="center">Actual Execute Date</th>
                            <th class="width10 center"><?php echo __('lblaction'); ?></th>
                            <th class="width10 center">Lock</th>
                        </tr>  
                    </thead>
                    <tbody>
                        <?php $srNo = 1;
                        foreach ($result as $result1): ?>
                            <tr>

                                <td ><?php echo $srNo++; ?></td>
                                <td ><?php echo $result1[0]['token_no']; ?></td>
                                <td ><?php echo $result1[0]['doc_reg_no']; ?></td>
                                <td ><?php echo $result1[0]['exec_date']; ?></td>
                                <td ><?php echo $result1[0]['article_desc_en']; ?></td>
                                <td ><?php echo $result1[0]['date_of_death']; //echo $this->Form->input('date_of_death', array('label' => false, 'id' => 'date_of_death', 'type' => 'text', 'name' => 'date_of_death', 'class' => 'form-control input-sm', 'readonly' => 'readonly', 'value' => $result1[0]['date_of_death']));   ?> </td>
                                <td ><?php echo $result1[0]['actual_exec_date']; //echo $this->Form->input('actual_exec_date', array('label' => false, 'id' => 'actual_exec_date', 'type' => 'text', 'name' => 'actual_exec_date', 'class' => 'form-control input-sm', 'readonly' => 'readonly', 'value' => date('Y-m-d')));   ?> </td>
                                <td class="width10 center">
                                    <button id="btnupdate" name="btnupdate" type="button" data-toggle="tooltip" title="Edit" class="btn btn-default "   onclick="javascript: return formupdate(
                                                    ('<?php echo $result1[0]['id']; ?>'),
                                                    ('<?php echo $result1[0]['token_no']; ?>'),
                                                    ('<?php echo $result1[0]['date_of_death']; ?>'),
                                                    ('<?php echo $result1[0]['actual_exec_date']; ?>'),
                                                    ('<?php echo $result1[0]['doc_reg_no']; ?>'),
                                                    ('<?php echo $result1[0]['exec_date']; ?>'));">
                                        <span class="glyphicon glyphicon-pencil"></span>
                                    </button>

                                </td>
                                <td>
                                    <button id="btnlock" name="btnlock" type="button" data-toggle="tooltip" title="Lock" class="btn btn-default "   onclick="javascript: return formlock(
                                                    ('<?php echo $result1[0]['id']; ?>'),
                                                    ('<?php echo $result1[0]['token_no']; ?>')

                                                    );">
                                        <span class="glyphicon glyphicon-lock"></span>
                                    </button>

                                </td>
                            </tr> 

                        <?php endforeach; ?>
<?php unset($result1); ?>
                    </tbody>
                </table> 
<?php if (!empty($result1)) { ?>
                    <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                    <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
            </div>
        </div>
        <input type='hidden' value='<?php // echo $actiontypeval;    ?>' name='actiontype' id='actiontype'/>
        <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
        <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
        <input type='hidden' value='<?php echo $hfdateofdeath; ?>' name='hfdateofdeath' id='hfdateofdeath'/>
        <input type='hidden' value='<?php echo $hfactualexecdate; ?>' name='hfactualexecdate' id='hfactualexecdate'/>
        <input type='hidden' value='<?php echo $hfdocumentnumber; ?>' name='hfdocumentnumber' id='hfdocumentnumber'/>
        <input type='hidden' value='<?php echo $hfexecutiondate; ?>' name='hfexecutiondate' id='hfexecutiondate'/>
        <input type='hidden' value='<?php echo $hftokenno; ?>' name='hftokenno' id='hftokenno'/>

    </div>

</div>

<?php echo $this->Form->end(); ?>


