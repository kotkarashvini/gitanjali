<script>
    $(document).ready(function () {

       $('#tablecasestatus').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
       


    });
</script> 

<script>
    function formadd() {
        document.getElementById("hfaction").value = 'S';
        document.getElementById("actiontype").value = '1';
    }

    function formupdate(case_status_code, case_status_desc,id) {
        document.getElementById("actiontype").value = '1';
        $('#case_status_code').val(case_status_code);
        $('#case_status_desc').val(case_status_desc);
      
        $('#hfid').val(id);
        $('#btnadd').html('Save');
        $('#hfupdateflag').val('Y');
          return false;
    }


    function formcancel() {
        document.getElementById("actiontype").value = '3';
    }

</script>   


<?php echo $this->Form->create('CaseStatus', array('id' => 'CaseStatus', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title">Case Status</h3></center>
            </div>

            <div class="box-body">
                 <div class="row" style="text-align: center">
                    <div class="form-group">
                        <label for="case_status_code" class="col-sm-3 control-label">Case Code :<span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('case_status_code', array('label' => false, 'id' => 'case_status_code', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                        </div>

                    </div>
                </div>
                  <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
                 <div class="row" style="text-align: center">
                    <div class="form-group">
                        <label for="case_status_desc" class="col-sm-3 control-label">Case Status Description:<span style="color: #ff0000">*</span></label> 
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('case_status_desc', array('label' => false, 'id' => 'case_status_desc', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                        </div>
                    </div>
                </div>
                
                
            </div>
        </div>
         <div class="box box-primary">
               <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
            <div class="row" style="text-align: center">
                <div class="form-group">
                    <button id="btnadd"type="submit" name="btnadd" class="btn btn-info " style="text-align: center;" onclick="javascript: return formadd();">
                        <span class="glyphicon glyphicon-plus"></span><?php echo __('btnsave'); ?>
                    </button>
                    <button id="btncancel" name="btncancel" class="btn btn-info " style="text-align: center;" onclick="javascript: return formcancel();">
                        <?php echo __('btncancel'); ?>
                    </button>
                </div>
            </div>
        </div>
         <div class="box box-primary">

          <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
            <div class="box-body">
                <div class="table-responsive">
                    <table id="tablecasestatus" class="table table-striped table-bordered table-hover">  
                        <thead> 
                            <tr>  
                                <th class="center width10">Case Code</th>
                                <th class="center width10">Case Status Description</th>
                              
                                <th class="center width16"><?php echo __('lblaction'); ?></th>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php foreach ($casestatusrecord as $casestatusrecord1): ?>
                                <tr>
                                    <td ><?php echo $casestatusrecord1['CaseStatus']['case_status_code']; ?></td>
                                    <td ><?php echo $casestatusrecord1['CaseStatus']['case_status_desc']; ?></td>
                                  

                                    <td style="text-align: center;">
                                        <button id="btnupdate" name="btnupdate" class="btn btn-default update" style="text-align: center;" onclick="javascript: return formupdate(
                                                        ('<?php echo $casestatusrecord1['CaseStatus']['case_status_code']; ?>'),
                                                        ('<?php echo $casestatusrecord1['CaseStatus']['case_status_desc']; ?>'),
                                                        ('<?php echo $casestatusrecord1['CaseStatus']['id']; ?>'));">
                                            <span class="glyphicon glyphicon-pencil"></span></button>

                                    <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'delete_casestatus', $casestatusrecord1['CaseStatus']['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
                                    </td>
                                </tr>

                            <?php endforeach; ?>

                            <?php unset($docrecord1); ?>
                        </tbody>
                    </table> 
                    <?php if (!empty($docrecord1)) { ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
                </div>
            </div>

        </div>
    </div>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
</div>
