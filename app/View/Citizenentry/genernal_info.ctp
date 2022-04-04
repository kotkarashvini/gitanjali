<script>
    $(document).ready(function () {
        $('#tablegeninfo').dataTable({
            "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
    });
</script>
<?php echo $this->Form->create('genernal_info', array('id' => 'genernal_info', 'autocomplete' => 'off')); ?>

<?php echo $this->element("Registration/main_menu"); ?>
<?php
//echo $this->element("Citizenentry/main_menu");
$laug = $this->Session->read("sess_langauge");
?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblgeneralinfo1'); ?></h3></center>
                <div class="box-tools pull-right">
                    <?php echo $this->Html->link(__('lblnewdocument'), array('controller' => 'Citizenentry', 'action' => 'delete_session'), array('class' => 'btn btn-primary')); ?>
                    &nbsp;<a  href="<?php echo $this->webroot; ?>helpfiles/Citizenentry/genernal_info_en.html" class="btn btn-small btn-info pull-right" target="_blank"> <?php echo __('Help??'); ?> </a>
                </div>
            </div>
            <div class="box-body">

                <div class="form-group">
                    <div class="tab-content">
                        <div id="home" class="tab-pane fade in active">

                            <table id="tablegeninfo" class="table table-striped table-bordered table-hover">  
                                <thead>  
                                    <tr>  
                                        <th class="center"><?php echo __('lblSelect'); ?></th>
                                        <th class="center"><?php echo __('lbltokenno'); ?></th>

                                        <th class="center"><?php echo __('lblArticle'); ?></th>
                                        <th class="center"><?php echo __('lbldocumenttitle'); ?></th>
                                        <th class="center"><?php echo __('lbllocation'); ?></th>

  <!--<td ><?php //echo __('lblexecutiontype');    ?></td>-->
                                        <th class="center"><?php echo __('lblstatus'); ?></th>
                                        <th class="center"><?php echo __('lblanexture'); ?></th>
                                        <th class="center"><?php echo __('lblappointment'); ?></th>
                                        <?php if($sro_appr_flag=='Y'){ ?>
                                         <th class="center"><?php echo __('SRO Action'); ?></th>
                                        <th class="center"><?php echo __('SRO Remark'); ?></th>
                                        
                                        <?php } ?>
                                        <th class="center"><?php echo __('lblrevertremark'); ?></th>
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
                                        <?php if ($status1[0]['last_status_id'] == 1 || $status1[0]['appointment_date'] == NULL) { ?> 
                                            <td class="width10"><?php echo $this->Html->link("Select", array('controller' => 'Citizenentry', 'action' => 'set_token_session', $status1[0]['token_no'], $this->Session->read('csrftoken'))); ?></td>
                                        <?php } else { ?>
                                            <td class="width10"><?php echo $this->Html->link("Select", array('controller' => 'Citizenentry', 'action' => 'genernalinfoentry', $status1[0]['token_no'], $this->Session->read('csrftoken')), array('target' => '_blank', 'onclick' => 'return false')); ?></td>
                                        <?php } ?>
                                        <td class="width10"><?php echo $status1[0]['token_no']; ?></td>

                                        <td class="width10"><?php echo $status1[0]['article_desc_' . $laug]; ?></td>
                                        <td class="width10"><?php echo $status1[0]['articledescription_' . $laug]; ?></td>
                                        <td class="width10"><?php
                                            if ($status1[0]['village_name_' . $laug] != '') {
                                                echo $status1[0]['district_name_' . $laug] . ' , ' . $status1[0]['taluka_name_' . $laug] . ' , ' . $status1[0]['village_name_' . $laug];
                                            }
                                            ?></td>

            <!--<td class="tblbigdata"><?php echo $status1[0]['execution_type_' . $laug]; ?></td>-->
                                        <td class="width5 "><?php echo $status1[0]['document_status_desc_' . $laug]; ?></td>

                                        <td class="width5"><?php echo $this->Html->link('PDF', array('controller' => 'Reports', 'action' => 'pre_registration_docket', base64_encode($status1[0]['token_no']), 'D')); ?></td>

                                     <td class="width5"><?php
                                            if ($status1[0]['appointment_date'] != '') {
                                                echo date("d-m-Y", strtotime($status1[0]['appointment_date']));
                                                if($status1[0]['flag']=='N' && $status1[0]['last_status_id'] !=3){
                                                     $app_date = date('Y-m-d', strtotime($status1[0]['appointment_date']));
                                                     $today = date('Y-m-d');
                                                     if($app_date<=$today){
                                                ?><br>
                                                <?php echo $this->Html->link("Reschedule Appointment", array('controller' => 'Citizenentry', 'action' => 'reschedule',$status1[0]['token_no'],$this->Session->read('csrftoken'))); ?>
                                                
                                                     <?php } } }
                                            ?></td>
                                      <?php if($sro_appr_flag=='Y'){ ?>
                                     <td><?php if($status1[0]['sro_action_flag']=='R'){ echo 'Rejected';}else if($status1[0]['sro_action_flag']=='A'){ echo 'Accepted';}else{echo 'No Action';} ?></td>
                                     <td> <?php echo $status1[0]['sro_remark'];?> </td>
                                    
                                      <?php } ?>
                                      <td><?php echo $status1[0]['document_entry_remark']; ?></td>
                                     
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
                                <?php $this->Html->link($this->Form->button('Button'), array('Controller' => 'citizenentry', 'action' => 'genernalinfoentry', $this->Session->read('csrftoken')), array('escape' => false, 'title' => "Click to view somethin")); ?>
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
                <div  class="rowht">&nbsp;</div>
            </div>
        </div>

    </div>
</div>


<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>




