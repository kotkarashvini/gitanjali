<div class="box box-primary">
    <div class="box-header with-border">
        <center><h3 class="box-title" style="font-weight: bolder"><?php echo __('Update Estamp Lock Details'); ?></h3></center>
    </div>
    <div class="box-body">
        <div class="col-md-4">

            <?php echo $this->Form->create('estamp', array('id' => 'estamp', 'autocomplete' => 'off')); ?>
            <div class="form-group">
                <label>Enter Token Number</label>
                <?php
                echo $this->Form->input('token_no', array('type' => 'text', 'name' => 'token_no', 'id' => 'token_no', 'label' => FALSE, 'class' => 'form-control'));
                ?> 
            </div>
            <div class="form-group">
                <?php
                echo $this->Form->input('Search', array('type' => 'submit', 'name' => 'submit', 'id' => 'token_no', 'label' => FALSE, 'class' => 'btn btn-primary'));
                ?> 
            </div>
            <?php echo $this->Form->end(); ?>
        </div>  

    </div>
</div>



<div class="box box-primary">
    <div class="box-header with-border">
        <center><h3 class="box-title" style="font-weight: bolder"><?php echo __('List Of  Estamp '); ?></h3></center>
    </div>
    <div class="box-body">
        <table class="table table-striped table-bordered table-hover" id="Doclist">
            <thead>
                <tr>
                    <th style="text-align: center;"><?php echo __('lblsrno'); ?></th> 
                     <th style="text-align: center;"><?php echo __('lbltokenno'); ?></th> 
                    <th style="text-align: center;"><?php echo __('Certificate Number'); ?></th>
                    <th style="text-align: center;"><?php echo __('Estamp Issue Date'); ?></th>
                    <th style="text-align: center;"><?php echo __('Party Name'); ?></th>
                    <th style="text-align: center;"><?php echo __('Action'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $counter = 0;
                if (isset($results)) {
                    // pr($results);exit;
                    foreach ($results as $result) {
                        ?>
                        <tr >
                            <td scope="row" style="text-align: center; font-weight:bold;"><?php echo ++$counter; ?></td>
                            <td style="text-align: center;"><?php echo $result['payment']['token_no']; ?></td>
                            <td style="text-align: center;"><?php echo $result['payment']['certificate_no']; ?></td>
                            <td style="text-align: center;"><?php echo $result['payment']['estamp_issue_date']; ?></td>  
                            <td style="text-align: center;"><?php echo $result['payment']['payee_fname_en']; ?></td> 
                            <td style="text-align: center;"><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal<?php echo $counter; ?>">Update</button></td> 

                        </tr>
                        <?php
                    }
                }
                ?>  
            </tbody>
        </table>

    </div>
</div>



<?php
$counter = 0;
if (isset($results)) {
    foreach ($results as $result) {
        $counter++;
        ?>
        <div id="myModal<?php echo $counter; ?>" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Manually Update e-stamp lock details</h4>
                    </div>
                    <?php echo $this->Form->create('update_estamp_lock', array('id' => 'update_estamp_lock'.$counter, 'autocomplete' => 'off')); ?>

                    <div class="modal-body">
                        <table class="table table-striped table-bordered table-hover" id="Doclist">
                            <thead>
                                <tr>
                                    <th style="text-align: center;"><?php echo __('Certificate Number'); ?></th>
                                    <td style="text-align: center;"><?php echo $result['payment']['certificate_no']; ?></td>

                                </tr>
                                <tr>  
                                    <th style="text-align: center;"><?php echo __('Estamp Issue Date'); ?></th>
                                    <td style="text-align: center;"><?php echo $result['payment']['estamp_issue_date']; ?></td>  
                                </tr>
                                <tr> 
                                    <th style="text-align: center;"><?php echo __('Party Name'); ?></th>
                                    <td style="text-align: center;"><?php echo $result['payment']['payee_fname_en']; ?></td>
                                </tr>

                            </thead>
                        </table>

                        <div class="form-group">
                            <label>Remark</label>
                            <?php
                            echo $this->Form->input('manually_lock_remark', array('type' => 'text', 'id' => 'manually_lock_remark'.$counter, 'label' => FALSE, 'class' => 'form-control'));
                            echo $this->Form->input('certificate_no', array('type' => 'hidden', 'id' => 'certificate_no'.$counter, 'label' => FALSE, 'class' => 'form-control', 'value' => $result['payment']['certificate_no']));
                            ?> 
                            <span id="<?php echo 'manually_lock_remark'.$counter; ?>_error" class="form-error" ></span>
                        </div>
                        <div class="form-group">
                            <label>Lock Date</label>
                            <?php
                            echo $this->Form->input('lock_date', array('type' => 'text', 'id' => 'lock_date'.$counter, 'label' => FALSE, 'class' => 'form-control lock_date'));
                            ?>                              
                            <span id="<?php echo 'lock_date'.$counter; ?>_error" class="form-error" ></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" >Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                    <?php echo $this->Form->end(); ?>
                </div>

            </div>
        </div>
        <?php
    }
}
?> 


<script>
    $(document).ready(function () {
        $('.lock_date').datepicker({
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            calendarWeeks: true,
            autoclose: true,
            todayHighlight: true
        });
    });
</script>