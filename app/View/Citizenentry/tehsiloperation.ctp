
<script>
    $(document).ready(function () {
//        $('#Doclist').DataTable();
    });
//    function formactive(user_id) {
//        document.getElementById("actiontype").value = '1';
//        $('#hfid').val(user_id);
//        $('#activate_biometric_user').submit();
//    }
//    function formreset(user_id) {
//        document.getElementById("actiontype").value = '2';
//        $('#hfid').val(user_id);
//        $('#activate_biometric_user').submit();
//    }
// 
//    function Save() {
//        document.getElementById("actiontype").value = '3';
//        $('#activate_biometric_user').submit();
//    }

</script>
<?php echo $this->Form->create('tehsiloperation', array('id' => 'tehsiloperation', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title" style="font-weight: bolder">Tehsildar Valuation</h3></center>
            </div>

            <div class="box-body">
                 <?php if(!empty($alldocuments)) { ?>
                <table id="Doclist" class="table table-striped table-bordered table-hover">  
                    <thead >  
                        <tr>
                                <th><?php echo __('lblsrno'); ?></th> 
                                <th><?php echo __('lbltokenno'); ?></th>
                                <th><?php echo __('lblaction'); ?></th>
                            </tr>
                    </thead>
                    <tbody>
                            <?php
                            $counter = 0;
                            foreach ($alldocuments as $documents) {
                                ?>
                            <tr>
                                <th scope="row"><?php echo ++$counter; ?></th>
                                <td><?php echo $documents[0]['token_no']; ?></td>
                                <td> 
                                    <!--<a href="<?php // echo $this->webroot; ?>Registration/document_select/<?php // echo $documents[0]['token_no']; ?>" class="btn btn-primary"><?php echo __('lblSelect'); ?></a>-->
                                     <?php
                                    echo $this->Html->link('Select', array('controller' => 'citizenentry', 'action' => 'way_to_skipvalution', $this->Session->read('csrftoken'), $documents[0]['property_id'], $documents[0]['token_no']), array('confirm' => 'Are you sure you wish to Edit this property?', 'class' => 'btn btn-info')
                                    );  ?>
                                </td>
                            </tr> 
                            <?php } ?>                            
                        </tbody>
                </table> 
                <?php }  else {?>
                <div class="row center">
                <div class="form-group col-sm-12" > 
                    <!--<div class="col-sm-1"></div>-->                   
                    <div class="col-sm-12"><h3>Record not Found...!!!</h3></div>
                    <!--<div class="col-sm-1"></div>-->          
                </div>
            </div>
                 <?php }  ?>
            </div>
        </div>
        <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
        <input type='hidden' value='<?php echo $actiontype; ?>' name='actiontype' id='actiontype'/>
    </div>
</div>

<?php echo $this->Form->end(); ?>


