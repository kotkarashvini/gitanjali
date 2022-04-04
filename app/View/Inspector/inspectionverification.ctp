<script>
    $(document).ready(function () {
        $('#tablegeninfo').dataTable({
            "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
    });
</script>
<?php echo $this->Form->create('inspectionverification', array('id' => 'inspectionverification', 'autocomplete' => 'off')); ?>

<?php echo $this->element("Citizenentry/main_menu"); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblyourdock'); ?></h3></center>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <div class="tab-content table-responsive">
                        <div id="home" class="tab-pane fade in active">

                            <table id="tablegeninfo" class="table table-striped table-bordered table-hover">  
                                <thead>  
                                    <tr>  
                                        <!--<th class="center"><?php // echo __('lblSelect'); ?></th>-->
                                        <th class="center"><?php echo __('lbltokenno'); ?></th>
                                        <th class="center"><?php echo __('lblregno'); ?></th>
                                        <th class="center"><?php echo __('lblarticlename'); ?></th>
                                        <th class="center"><?php echo __('lbltitlename'); ?></th>
                                        <th class="center"><?php echo __('lbllocation'); ?></th>
                                        <th class="center"><?php echo __('lblusagecategory'); ?></th>
                                        <!--<td ><?php echo __('lblexecutiontype'); ?></td>-->
                                        <th class="center"><?php echo __('lblstatus'); ?></th>
                                        <th class="center"><?php echo __('lblanexture11'); ?></th>
                                        <th class="center"><?php echo __('lblparty'); ?></th>
                                        <th class="center"><?php echo __('lblproperty'); ?></th>
                                    </tr>  
                                </thead>

                                <?php
                                $tmp_token_no = NULL;
                                foreach ($statusrecord as $status1):
                                    if ($tmp_token_no == $status1[0]['token_no'])
                                        continue;
                                    $tmp_token_no = $status1[0]['token_no'];
                                    ?>
                                    <tr>
                                        <!--<td class="width5"><?php // echo $this->Html->link("Select", array('controller' => 'Citizenentry', 'action' => 'genernalinfoentry', $status1[0]['token_no'])); ?></td>-->
                                        <td class="width5"><?php echo $status1[0]['token_no']; ?></td>
                                        <td ><?php //cho $status1[0]['evalrule_desc_en'];      ?></td>
                                        <td ><?php echo $status1[0]['article_desc_en']; ?></td>
                                        <td ><?php echo $status1[0]['title_name']; ?></td>
                                        <td ><?php echo $status1[0]['village_name_en']; ?></td>
                                        <td ><?php echo $status1[0]['evalrule_desc_en']; ?></td>
                                      <!--<td ><?php echo $status1[0]['execution_type_en']; ?></td>-->
                                        <td class="width5"><?php echo $status1[0]['document_status_desc_en']; ?></td>
                                       <td class="width5"><?php echo $this->Html->link('PDF', array('controller' => 'Reports', 'action' => 'pre_registration_docket', base64_encode($status1[0]['token_no']),'D')); ?></td>
                                        <td class="width5"><?php echo $this->Html->link("Show Party", array('controller' => 'Inspector','action'=> 'party', $status1[0]['token_no']), array( 'class' => 'btn btn-warning')); ?></td>
                                        <?php if($status1[0]['inspecation_completed_flag']=='Y') {
                                            $name="Verification Successfully";
                                        }else if($status1[0]['inspecation_completed_flag']=='N'){
                                            $name="Verification Unsuccessfully";
                                        }else{
                                            $name="Verify Property";
                                        } ?>
                                        <td class="width5"><?php echo $this->Html->link($name, array('controller' => 'Inspector','action'=> 'property', $status1[0]['token_no']), array( 'class' => 'btn btn-warning')); ?></td>
                                    </tr>
                                <?php endforeach;
                                ?>
                                <?php unset($status1); ?>



                            </table> 
                        </div>

                    </div>
                </div>
                <!--</div>-->
                <div  class="rowht">&nbsp;</div>
            </div>
        </div>

    </div>
</div>


<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>




