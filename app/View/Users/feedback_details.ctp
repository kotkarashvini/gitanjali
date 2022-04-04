<script type="text/javascript">
    $(document).ready(function () {
        $('#feedback_details').dataTable();
        $('.date').datepicker({
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            calendarWeeks: true,
            //orientation: "top left",
            autoclose: true,
            todayHighlight: true
        });

    });
</script> 
<div class="box box-primary">

    <div class="box-header with-border">
        <center><h3 class="box-title" style="font-weight: bolder"><label><?php echo __('Feedback Details'); ?></label></h3></center>
    </div>
    <div class="box-body">
    <?php echo $this->Form->create('feedback_detail',array('id' => 'feedback_detail', 'class' => 'form-vertical')); ?>
        <div class="row">
            <div class="col-sm-3"></div>
            <div class="col-sm-3"><?php echo $this->Form->input('from', array('label' => false, 'type' => 'text', 'id' => 'from','placeholder'=>__('lblfromdate'), 'class' => 'date form-control')); ?></div>
            <div class="col-sm-3"><?php echo $this->Form->input('to', array('label' => false, 'type' => 'text', 'id' => 'to', 'placeholder'=>__('lbltodate'),'class' => 'date form-control')); ?></div>
            <div class="col-sm-3"><button type="submit" class="btn btn-primary"><?php echo __('submit'); ?></button> </div>
        </div>
    <?php echo $this->form->end(); ?>
        <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
        <div class="box-body">
            <table  id="feedback_details" class="table table-bordred table-striped">
                <thead>
                    <tr>
                        <th><?= __('lbldate')?></th>
                        <th><?= __('lblname')?></th>
                        <th><?= __('lblmobileno')?></th>
                        <th><?= __('lblemailid')?></th>
                        <th><?= __('lbldesc')?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        foreach ($feedbackDetails as $fd) {
                            echo "<tr>"
                                . "<td width = '7%'>".date("d-M-Y", strtotime($fd['created_date'])). "</td>"
                                . "<td>".$fd['applicantname']. "</td>"
                                . "<td>".$fd['mobile_no']. "</td>"
                                . "<td>".$fd['email_id']. "</td>"
                                . "<td>".$fd['message']. "</td>"
                                    . "<tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>