<script>
    $(document).ready(function () {
        $('#tablegeninfo').dataTable({
            "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
    });
</script>
<?php echo $this->Form->create('genernal_info', array('id' => 'genernal_info', 'autocomplete' => 'off')); ?>
<?php echo $this->Element('Helpfiles/NewCase'); ?>

<?php //echo $this->element("NewCase/main_menu"); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('Case List'); ?></h3></center>
            </div>
            <div class="box-body">
                <!--<div class="row">-->
                <div class="form-group left">
                    <button type="button" class="btn btn-success" id="btnnewdock" name="btnnewdock" onclick="location.href = '<?php echo $this->Html->url(array('controller' => 'NewCase', 'action' => 'new_session')); ?>';">
                        <span class="glyphicon glyphicon-"></span><?php echo __('New Case admission'); ?>
                    </button>
                    <a class="btn bg-maroon pull-right " data-toggle="modal" data-target="#homehelp"><?php echo __('help??'); ?></a>
                </div>
                <div class="form-group">
                    <div class="tab-content">
                        <div id="home" class="tab-pane fade in active">
                            <table id="tablegeninfo" class="table table-striped table-bordered table-hover">  
                                <thead>  
                                    <tr>  
                                        <!--<th class="center">Select Case</th>-->
                                        <th class="center"><?php echo __('Case Number'); ?></th>
                                        <th class="center"><?php echo __('Case Year'); ?></th>
                                        <!--<th class="center"><?php echo __('Case Belongs to'); ?></th>-->
                                        <th class="center"><?php echo __('Objection Name'); ?></th>
                                        <th class="center"><?php echo __('Case Type Description'); ?></th>
                                        <th class="center"><?php echo __('Status'); ?></th>
                                    </tr>  
                                </thead>
                                <?php
                                foreach ($allresult2 as $status1):
                                    $status = $this->requestAction('/NewCase/case_status/' . $status1[0]['case_id']);
                                    ?>
                                    <tr>
    <!--                                    <td class="width10"><?php
                                        if ($status == 'CASESUB') {
                                            echo $this->Html->link("Select", array('controller' => 'NewCase', 'action' => 'genernalinfoentry', $status1[0]['case_id']));
                                        }
                                        ?>
                                        </td>-->
                                        <td class="width10"><?php echo $status1['0']['case_id']; ?></td>
                                        <td class="width10"><?php echo $status1['0']['case_year']; ?></td>
                                        <!--<td class="width10"><?php echo $status1[0]['office_name_en']; ?></td>-->
                                        <td class="width10"><?php echo $status1[0]['objection_name']; ?></td>
                                        <td class="width10"><?php echo $status1[0]['case_type_desc']; ?></td>
                                        <td  class="width10">
                                            <?php
                                            // pr($this->Session->read("randamkey"));
                                            $newid = $status1[0]['case_id'];
                                            //$this->requestAction(array('controller' => 'NewCase', 'action' => 'encrypt', $status1[0]['case_id'], $this->Session->read("randamkey"),));
                                            if ($status == 'DISP') {
                                                //closed for order,not paid gives payment details
                                                echo "Case Disposed";
                                            } elseif ($status == 'PAID') {
                                                //closed for order,not paid gives payment details
                                                echo $this->Html->link('Case Disposed', array('controller' => 'NewCase', 'action' => 'case_disposal', $status1[0]['case_id']), array('class' => "btn btn-warning"));
                                            } elseif ($status == 'CLORD' || $status == 'NPAID') {
                                                //closed for order,not paid gives payment details
                                                echo $this->Html->link('Payment Details', array('controller' => 'NewCase', 'action' => 'payment', $status1[0]['case_id']), array('class' => "btn btn-warning"));
                                            } elseif ($status == 'FORD' || $status == 'CLHEA') {
                                                //final order,closed hearing gives Judgement Details
                                                echo $this->Html->link('Judgement Details', array('controller' => 'NewCase', 'action' => 'judgement_details', $newid), array('class' => "btn btn-success"));
                                            } elseif ($status == 'ONBOARD') {
//                                                 //Onboard cases(current date cases),Next hearing gives Proceeding phase
                                                echo $this->Html->link('On Board Cases for Proceeding', array('controller' => 'NewCase', 'action' => 'proceeding_details', $newid), array('class' => "btn btn-success"));
                                            } elseif ($status == 'NOT') {
                                                //NOT  gives Generate Notice
                                                echo $this->Html->link('Generate Notice', array('controller' => 'NewCase', 'action' => 'notice_generation', $newid), array('class' => "btn bg-maroon"));
                                            } elseif ($status == 'CASESUB') {
                                                echo $this->Html->link('Respondent entry', array('controller' => 'NewCase', 'action' => 'respondententry', $newid), array('class' => "btn bg-purple"));
//                                                // echo "Only Case details submitted";
//                                            }elseif($status == 'NA'){
//                                             echo $this->Html->link('Case details', array('controller' => 'NewCase', 'action' => 'genernal_info', $status1[0]['case_id']), array('class' => "btn btn-primary"));
////                                                echo $this->Html('Cases Not ON Board', array('class' => "btn btn-warning"));
                                            } elseif ($status == 'PAID') {
                                                echo "Paid";
                                            } elseif ($status == 'NBOARD') {
                                                echo "Not on Board";
                                            } else {
                                                echo $status;
                                            }
                                            ?>
                                        </td>  
                                    </tr>
                                <?php endforeach;
                                ?>
                            </table> 
                        </div>
                        <div id="menu1" class="tab-pane fade">
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





