<script>
    $(document).ready(function () {
        $('#tablegeninfo').dataTable({
            "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
    });
</script>
<?php echo $this->Form->create('genernal_info', array('id' => 'genernal_info', 'autocomplete' => 'off')); ?>

<?php echo $this->element("ManualRegistration/main_menu"); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblgeneralinfo1'); ?></h3></center>
            </div>
            <div class="box-body">
                <!--<div class="row">-->
                <div class="form-group left">
                    
                    <?php echo $this->Html->link(__('lblnewdocument'),array('controller' => 'ManualReg', 'action' => 'delete_session'),array('class'=>'btn btn-primary')); ?>
                </div>
                <!--</div>-->
                <!--<div class="row">-->
                <div class="form-group">
                    <div class="tab-content">
                        <div id="home" class="tab-pane fade in active">

                            <table id="tablegeninfo" class="table table-striped table-bordered table-hover">  
                                <thead>  
                                    <tr>  
                                        <th class="center"><?php echo __('lblSelect'); ?></th>
                                        <!--<th class="center"><?php // echo __('lbltokenno'); ?></th>-->
                                        <th class="center"><?php echo __('lblregno'); ?></th>
                                        <th class="center"><?php echo __('lblarticlename'); ?></th>
                                        <th class="center"><?php echo __('lbltitlename'); ?></th>
                                        <th class="center"><?php echo __('lbllocation'); ?></th>
                                        <th class="center"><?php echo __('lblusagecategory'); ?></th>
                                        <!--<td ><?php echo __('lblexecutiontype'); ?></td>-->
                                        <th class="center"><?php echo __('lblstatus'); ?></th>
                                        <th class="center"><?php echo __('lblanexture11'); ?></th>
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
                                         <?php if($status1[0]['last_status_id']==1){?> 
                                        <td class="width10"><?php echo $this->Html->link("Select", array('controller' => 'ManualReg', 'action' => 'set_token_session', $status1[0]['token_no'])); ?></td>
                                        <?php } else { ?>
                                        <td class="width10"><?php echo $this->Html->link("Select", array('controller' => 'ManualReg', 'action' => 'genernalinfoentry', $status1[0]['token_no']),array('target'=>'_blank', 'onclick' => 'return false')); ?></td>
                                        <?php } ?>
                                        <!--<td class="width10"><?php // echo $status1[0]['token_no']; ?></td>-->
                                        <td class="tblbigdata"><?php //cho $status1[0]['evalrule_desc_en'];      ?></td>
                                        <td class="tblbigdata"><?php echo $status1[0]['article_desc_en']; ?></td>
                                        <td class="tblbigdata"><?php echo $status1[0]['title_name']; ?></td>
                                        <td class="tblbigdata"><?php echo $status1[0]['village_name_en']; ?></td>
                                        <td class="tblbigdata"><?php echo $status1[0]['evalrule_desc_en']; ?></td>
                                      <!--<td class="tblbigdata"><?php echo $status1[0]['execution_type_en']; ?></td>-->
                                        <td class="width5 tblbigdata"><?php echo $status1[0]['document_status_desc_en']; ?></td>
                                        <td class="width10"><?php echo $this->Html->link('PDF', array('controller' => 'Reports', 'action' => 'pre_registration_docket', $status1[0]['token_no'])); ?></td>
                                    </tr>
                                <?php endforeach;
                                ?>
                                <?php unset($status1); ?>



                            </table> 
                        </div>
                        <div id="menu1" class="tab-pane fade">
                        </div>
                        <div id="menu2" class="tab-pane fade">
                            <div class="btn-group btn-group-justified" id="test">
                                <?php $this->Html->link($this->Form->button('Button'), array('Controller' => 'ManualReg', 'action' => 'genernalinfoentry'), array('escape' => false, 'title' => "Click to view somethin")); ?>
                                <div class="btn-group"><input type="button" class=" btn btn-info" value="A:- General Info" id="general_info"></div>
                                <div class="btn-group"><input type="button" class=" btn btn-info" value="B:- Property Details"></div>
                                <div class="btn-group"><input type="button" class=" btn btn-info" value="C:- valuation"></div>
                                <div class="btn-group"><input type="button" class=" btn btn-info" value="D:- Stamp Duty"></div>
                                <div class="btn-group"><input type="button" class=" btn btn-info" value="E:- Payment"></div>
                                <div class="btn-group"><input type="button" class=" btn btn-info" value="F:- Party"></div>
                                <div class="btn-group"><input type="button" class=" btn btn-info" value="G:- Witness"></div>
                                <div class="btn-group"><input type="button" class=" btn btn-info" value="H:- Slot"></div>
                            </div>
                            <br>
                        </div>
                        <div id="menu3" class="tab-pane fade">
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




