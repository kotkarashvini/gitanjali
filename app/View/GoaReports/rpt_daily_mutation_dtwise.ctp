<script type="text/javascript">
    $(document).ready(function () {

        $('#from').datepicker({
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            calendarWeeks: true,
            autoclose: true,
            todayHighlight: true
        });

        $('#to').datepicker({
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            calendarWeeks: true,
            autoclose: true,
            todayHighlight: true
        });

        $('#tabledoc').dataTable({
            "bPaginate": false,
            "ordering": false
        });

    });

</script>
<style>
    .table-responsive
    {
        overflow-y:auto;
        height:400px;
    }
</style>

<?php
echo $this->Form->create('rpt_daily_mutation_dtwise', array('id' => 'rpt_daily_mutation_dtwise', 'autocomplete' => 'off'));
?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class = "box-header with-border" style="color: #8B0000">
                <center><h3 class="box-title headbolder"> <?php echo __('Mutation Status Details'); ?> </h3></center>
            </div>
            <div class="box-body">
                <div  class="rowht"></div>  <div  class="rowht"></div> 
                <div class="row">
                    <div class="col-sm-2"></div>
                    <label for="TAX No" class="control-label col-sm-2"><?php echo __('Get Record By Date'); ?></label>        
                    <div class="col-sm-2"><?php echo $this->Form->input("from", array('id' => 'from', 'legend' => false, 'class' => 'date form-control', 'label' => false, 'placeholder' => 'From Date')); ?>
                        <span id="from_error" class="form-error"><?php //echo $errarr['from_error'];                               ?></span>
                    </div>
                    <div class="col-sm-2"><?php echo $this->Form->input("to", array('id' => 'to', 'legend' => false, 'class' => 'date form-control', 'label' => false, 'placeholder' => 'To Date')); ?>
                        <span id="to_error" class="form-error"><?php //echo $errarr['to_error'];                                     ?></span>
                    </div>

                    <div class="col-sm-2"><button id="go" class="btn btn-primary" type="submit"> <?php echo __('lblsearch'); ?> </button></div>
                </div> 
                <div  class="rowht"></div>  <div  class="rowht"></div> 

            </div>
        </div>
        <?php
        if (!empty($dcount)) {
            //pr($dcount);exit;
            ?>
            <div class="box box-primary">
                <div class="box-body">
                    <div id="selectdocument" class="table-responsive">
                        <table id="tabledoc" class="table table-striped table-bordered table-hover">
                            <thead class="center">  
                                <tr > 
                                    <th><?php echo __('Mutation Date'); ?></th>
                                    <th><?php echo __('Mutation Status Count'); ?></th>
                                </tr>  

                            </thead>
                            <tbody>
                                <?php
                                foreach ($dcount as $rec):
                                    ?>
                                    <tr>
                                        <td ><?php echo $rec[0]['mutation_date']; ?></td>
                                        <td ><?php
                                            $c = $rec[0]['ttl_reg'];
                                            echo $c;
                                            $r[] = $c;
                                            ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tr> 
                                <th><?php echo __('<b>Total</b>'); ?></th>
                                <td ><?php echo '<b>' .array_sum($r). '</b>'; ?></td>
                            </tr>


                        </table>

                    </div>

                </div>
            </div>

        <?php } ?>

    </div>
</div>

<?php echo $this->Form->end(); ?>
