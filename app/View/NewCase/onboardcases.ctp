<script>
    $(document).ready(function () {
        $('#tablegeninfo').dataTable({
            "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
    });
</script>
<?php echo $this->Form->create('document', array('id' => 'document', 'autocomplete' => 'off')); ?>
<?php echo $this->element("NewCase/main_menu"); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('On Board List'); ?></h3></center>
            </div>
            <div class="box-body">
                <!--<div class="row">-->
                <div class="form-group left">
                    <button type="button" class="btn btn-primary" id="btnnewdock" name="btnnewdock" onclick="location.href = '<?php echo $this->Html->url(array('controller' => 'NewCase', 'action' => 'genernalinfoentry')); ?>';">
                        <span class="glyphicon glyphicon-"></span><?php echo __('New Case Entry'); ?>
                    </button>
                </div>
                <!--</div>-->
                <!--<div class="row">-->
                <div class="form-group">
                    <div class="tab-content">
                        <div id="home" class="tab-pane fade in active">
                            <table id="tablegeninfo" class="table table-striped table-bordered table-hover">  
                                <thead>  
                                    <tr>  
                                        <!--<th class="center">Case ID</th>-->
                                        <th class="center"><?php echo __('Case ID'); ?></th>
                                        <th class="center"><?php echo __('Case Year'); ?></th>
                                        <th class="center"><?php echo __('Objection Name'); ?></th>
                                        <th class="center"><?php echo __('Case Type Desc'); ?></th>
                                        <th class="center"><?php echo __('Action'); ?></th>
                                    </tr>  
                                </thead>
                                <?php
//                                pr($allresult2);exit;
                                foreach ($allresult1 as $status1) {
                                    //pr($status1);exit;
                                    ?>
                                    <tr>
                                        <!--<td class="width10"><?php echo $this->Html->link("Select", array('controller' => 'NewCase', 'action' => 'genernalinfoentry', $status1[0]['case_id'])); ?></td>-->
                                        <td class="width10"><?php echo $status1[0]['case_id']; ?></td>
                                        <td class="width10"><?php echo $status1[0]['case_year']; ?></td>
                                        <td class="width10"><?php echo $status1[0]['objection_name']; ?></td>
                                        <td class="width10"><?php echo $status1[0]['case_type_desc']; ?></td>
                                        <td>
                                            <?php
//                                            foreach ($allresult1 as $rs) {
                                            //  pr($allresult1);exit;
                                            //      $status = $this->requestAction('/NewCase/case_status/' . $status1[0]['case_id']);
                                            echo $this->Html->link('ON BOARD CASES', array('controller' => 'Registration', 'action' => 'payment_verification', $status1[0]['case_id']), array('class' => 'btn btn-danger', 'escape' => false));
//                                                echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'proceeding_delete', $rs[0]['case_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('ON BOARD CASES'), 'class' => "btn btn-default"), array('Are you sure?'));
//                                            }
                                            ?>
                                        </td>  
                                    </tr>
                                <?php }
                                ?>
                            </table> 
                        </div>
                        <div id="menu1" class="tab-pane fade">
                        </div>
                        <div id="menu2" class="tab-pane fade">
                            <div class="btn-group btn-group-justified" id="test">
                                <?php $this->Html->link($this->Form->button('Button'), array('Controller' => 'NewCase', 'action' => 'genernalinfoentry'), array('escape' => false, 'title' => "Click to view somethin")); ?>
                                <div class="btn-group"><input type="button" class=" btn btn-info" value="A:- Case General Info" id="general_info"></div>
                                <div class="btn-group"><input type="button" class=" btn btn-info" value="B:- Respondent Details"></div>
                            </div>
                            <br>
                        </div>
                        <div id="menu3" class="tab-pane fade">
                            <div class="btn-group btn-group-justified" id="test">
                                <?php $this->Html->link($this->Form->button('Button'), array('Controller' => 'NewCase', 'action' => 'notice_generation'), array('escape' => false, 'title' => "Click to view somethin")); ?>
                                <div class="btn-group"><input type="button" class=" btn btn-info" value="A:- Case General Info" id="general_info"></div>
                                <div class="btn-group"><input type="button" class=" btn btn-info" value="B:- Respondent Details"></div>
                                <div class="btn-group"><input type="button" class=" btn btn-info" value="C:- Notice Generation"></div>
                            </div>
                            <br>
                        </div>
                        <div id="menu4" class="tab-pane fade">
                            <div class="btn-group btn-group-justified" id="test">
                                <?php $this->Html->link($this->Form->button('Button'), array('Controller' => 'NewCase', 'action' => 'proceeding_details'), array('escape' => false, 'title' => "Click to view somethin")); ?>
                                <div class="btn-group"><input type="button" class=" btn btn-info" value="A:- Case General Info" id="general_info"></div>
                                <div class="btn-group"><input type="button" class=" btn btn-info" value="B:- Respondent Details"></div>
                                <div class="btn-group"><input type="button" class=" btn btn-info" value="C:- Notice Generation"></div>
                                <div class="btn-group"><input type="button" class=" btn btn-info" value="D:- Proceeding Details"></div>
                            </div>
                            <br>
                        </div>
                        <div id="menu5" class="tab-pane fade">
                            <div class="btn-group btn-group-justified" id="test">
                                <?php $this->Html->link($this->Form->button('Button'), array('Controller' => 'NewCase', 'action' => 'judgement_details'), array('escape' => false, 'title' => "Click to view somethin")); ?>
                                <div class="btn-group"><input type="button" class=" btn btn-info" value="A:- Case General Info" id="general_info"></div>
                                <div class="btn-group"><input type="button" class=" btn btn-info" value="B:- Respondent Details"></div>
                                <div class="btn-group"><input type="button" class=" btn btn-info" value="C:- Notice Generation"></div>
                                <div class="btn-group"><input type="button" class=" btn btn-info" value="D:- Proceeding Details"></div>
                                <div class="btn-group"><input type="button" class=" btn btn-info" value="E:- Judgement Details"></div>
                            </div>
                            <br>
                        </div>
                        <div id="menu5" class="tab-pane fade">
                            <div class="btn-group btn-group-justified" id="test">
                                <?php $this->Html->link($this->Form->button('Button'), array('Controller' => 'NewCase', 'action' => 'payment'), array('escape' => false, 'title' => "Click to view somethin")); ?>
                                <div class="btn-group"><input type="button" class=" btn btn-info" value="A:- Case General Info" id="general_info"></div>
                                <div class="btn-group"><input type="button" class=" btn btn-info" value="B:- Respondent Details"></div>
                                <div class="btn-group"><input type="button" class=" btn btn-info" value="C:- Notice Generation"></div>
                                <div class="btn-group"><input type="button" class=" btn btn-info" value="D:- Proceeding Details"></div>
                                <div class="btn-group"><input type="button" class=" btn btn-info" value="E:- Judgement Details"></div>
                                <div class="btn-group"><input type="button" class=" btn btn-info" value="F:- Payment Details"></div>
                            </div>
                            <br>
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


