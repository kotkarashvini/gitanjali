<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('Cases Details'); ?></h3></center>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <div class="tab-content">
                        <div id="home" class="">
                            <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
                                <tr>
                                    <th class="center"><h4><b><?php echo __('Case Year'); ?></b></h4></th>
                                    <th class="center"><h4><b><?php echo __('Case Type Desc'); ?></b></h4></th>
                                    <th class="center"><h4><b><?php echo __('Case Belongs to Office'); ?></b></h4></th>
                                    <th class="center"><h4><b><?php echo __('Objection Name'); ?></b></h4></th>
                                    <th class="center"><h4><b><?php echo __('Stamp duty'); ?></b></h4></th>
                                    <th class="center"><h4><b><?php echo __('Case Admitted date'); ?></b></h4></th>
                                    <th class="center"><h4><b><?php echo __('Current Status of Cases'); ?></b></h4></th>
                                </tr>  
                                <?php
                                foreach ($allresult2 as $status1):
                                    $status = $this->requestAction('/NewCase/case_status/' . $status1[0]['case_id']);
                                    ?>
                                <tr>
                                    <td class="width10"><?php echo $status1['0']['case_year']; ?></td>
                                    <td class="width10"><?php echo $status1[0]['case_type_desc']; ?></td>
                                    <td class="width10"><?php echo $status1[0]['office_name_en']; ?></td>
                                    <td class="width10"><?php echo $status1[0]['objection_name']; ?></td>
                                    <td class="width10"><?php echo $status1[0]['stamp_duty']; ?></td>
                                    <td class="width10"><?php echo $status1[0]['case_admited_date']; ?></td>
                                    <td>
                                            <?php
                                            if ($status == 'CLORD' || $status == 'NPAID') {
                                                //closed for order,not paid gives payment details
                                                echo "Payment Details";
                                            } elseif ($status == 'FORD'|| $status == 'CLHEA') {
                                                //final order,closed hearing gives Judgement Details
                                                echo "Judgement Details";
                                            } elseif ($status == 'ONBOARD' || $status == 'HEAR') {
                                                 //Onboard cases(current date cases),Next hearing gives Proceeding phase
                                                echo "On Board Cases for Proceeding";
                                            } elseif ($status == 'NOT') {
                                                //NOT  gives Generate Notice
                                                echo "Generate Notice";
                                            } 
                                             elseif ($status == 'CASESUB') {
                                                //NOT  gives Generate Notice
                                                echo "Respondent entry";
                                            } 
                                              elseif ($status == 'PAID') {
                                                //NOT  gives Generate Notice
                                                echo "Paid";
                                            } 
                                            
                                            else {
                                                echo $status;
                                            }
                                            ?>
                                    </td>  
                                </tr>
                                <?php endforeach;
                                ?>
                            </table> 
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>