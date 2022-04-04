<script>
    $(document).ready(function () {
        $('#Doclist').DataTable();
    });
    function formprint(details_id){
            $('#hfid').val(details_id);
            $('#certified_copy_print').submit();
    }

</script>
<?php echo $this->Form->create('certified_copy_print', array('id' => 'certified_copy_print', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">
         <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblcertifiedcopyprint');  ?></h3></center> 
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table id="Doclist" class="table table-striped table-bordered table-hover">  
                        <thead >  
                            <tr>
                                <th><?php echo __('lblsrno'); ?></th> 
                                <th><?php echo __('lbltokenno'); ?></th>
                                <th><?php echo __('lbldocktype'); ?></th>
                                <th><?php echo __('lblpresentername'); ?></th> 
                                <th><?php echo __('lblaction'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $counter = 0;
                         //pr($alldocuments);exit;
                            foreach ($alldocuments as $documents) {
                               // pr($documents);
                                ?>
                                <tr>
                                    <th scope="row"><?php echo ++$counter; ?></th>
                                    <td><?php echo $documents[0]['token']; ?></td>
                                    <td><?php echo $documents[0]['article_desc_en']; ?></td>
                                    <td><?php echo $documents[0]['party_full_name_en'];    ?></td> 
                                    <td>
                                        <?php if($documents[0]['payment_accept_flag']=='N' && $documents[0]['issue_flag']=='N' ){?>
                                        <a class="btn btn-primary" href="<?php echo $this->webroot;?>Registration/certified_copy_payment/<?php echo $documents[0]['doc_reg_no']; ?>/<?php echo $documents[0]['details_id']; ?>">Select</a>
                            <?php }  if($documents[0]['payment_accept_flag']=='Y' && $documents[0]['issue_flag']=='N' ){ 
                                        echo $this->Html->link('PDF', array('controller' => 'Reports', 'action' => 'pre_registration_docket', base64_encode($documents[0]['token'])), array('class' => 'btn btn-danger', 'escape' => false));
                                 ?> 
                                        <!--<button type="button"  id='btncap' class="btn btn-success" onclick="javascript: return formprint(('<?php echo $documents[0]['details_id']; ?>'));">Print</button>-->
                                  
                           <?php  } ?>
                                        </td>
                                </tr> 
                            <?php } ?>
                        </tbody>
                    </table> 

                </div>
            </div>
        </div>
        <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    </div>
</div>

<?php echo $this->Form->end(); ?>


