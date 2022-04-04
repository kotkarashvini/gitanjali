<script>
    $(document).ready(function () {
        $('#tablegeninfo').dataTable({
            "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
    });
</script>
<?php echo $this->Form->create('rpt_rejected_doc', array('id' => 'rpt_rejected_doc', 'autocomplete' => 'off')); ?>

<?php
//echo $this->element("Citizenentry/main_menu");
$laug = $this->Session->read("sess_langauge");
?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('Rejected Document Report'); ?></h3></center>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <div class="tab-content">
                        <div id="home" class="tab-pane fade in active">
                            <?php if (!empty($data)) { ?>
                            <table id="tablegeninfo" class="table table-striped table-bordered table-hover">  
                                <thead>  
                                    <tr>  
                                        <th class="center"><?php echo __('lbltokenno'); ?></th>

                                        <th class="center"><?php echo __('lblArticle'); ?></th>
                                        <th class="center"><?php echo __('Party'); ?></th>
                                        <th class="center"><?php echo __('Summary 1'); ?></th>
                                        <th class="center"><?php echo __('Summary 2'); ?></th>
                                    </tr>  
                                </thead>
                                    <tr>
                                        <td class="width10"><?php echo $data[0][0]['token_no']; ?></td>
                                        <td class="width10"><?php echo $data[0][0]['article_desc_' . $laug]; ?></td>
                                        <td class="width10"><?php echo $data[0][0]['party_full_name_' . $laug]; ?></td>
                                        <td class="width5"><?php echo $this->Html->link('PDF', array('controller' => 'Reports', 'action' => 'rpt_reg_summary1_27', base64_encode($data[0][0]['token_no']), 'D')); ?></td>
                                        <td class="width5"><?php echo $this->Html->link('PDF', array('controller' => 'Reports', 'action' => 'rpt_reg_summary2_27', base64_encode($data[0][0]['token_no']), 'D')); ?></td>
                                    </tr>
                            </table> 
                            <?php } else { ?>
                <div class="row center">
                    <div class="form-group col-sm-12" > 
                        <div class="col-sm-12"><h2 style="color: red">Record Not Found...!!!!!</h2></div>
                    </div>           
                </div>
            <?php } ?>
                        </div>                    
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
            </div>
        </div>
    </div>
</div>


<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>




