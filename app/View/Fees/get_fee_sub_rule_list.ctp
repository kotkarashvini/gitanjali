<?php
echo $this->Html->script('jquery.dataTables');
echo $this->Html->script('dataTables.bootstrap');
?>
<script>
    $(document).ready(function () {
        $("#eff_date").datepicker({maxDate: new Date, dateFormat: 'dd-mm-yy'}).datepicker("setDate", new Date());
        $('#tblFeeSubRule').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, -1], [5, 10, 15, "All"]]
        });
    });
</script>

<h4 align="center"><?php echo __('lblsubruledetails'); ?></h4>
<table  id="tblFeeSubRule" class="table table-striped table-bordered table-hover" width="100%">
    <thead>
        <tr>
            <th class="width10 center"><?php echo __('lblid'); ?></th>
            <th class="center"><?php echo __('lbloutputitem'); ?></th>
            <th ><?php echo __('lblgovbodytype'); ?></th>
            <th class="width10 center"><?php echo __('lblorder'); ?></th>

            <th class="width10 center"><?php echo __('lblismax'); ?></th>
            <th class=" width10 center"><?php echo __('lblaction'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        foreach ($subruledata as $sbd) {
            $sb = $sbd['article_fee_subrule'];
            echo "<tr id=subrule_" . $sb['fee_subrule_id'] . ">"
            . "<td>" . $sb['fee_subrule_id'] . "</td>"
            . "<td>" . $sbd[0]['output_item'] . "</td>"
            . "<td>" . $sbd[0]['ulb_type_desc'] . "</td>"
            . "<td>" . $sb['fee_output_item_order'] . "</td>"
            . "<td>" . $sb['max_value_condition_flag'] . "</td>"
            . "<td>" . "<button class = 'btn btn-default' onClick = 'return setsubruleData(" . $sb['fee_rule_id'] . "," . $sb['fee_subrule_id'] . ");'><span class = 'glyphicon glyphicon-pencil'></span> </button>"
            . "<button class = 'btn btn-default' onClick = 'return  deletesubrule(" . $sb['fee_rule_id'] . "," . $sb['fee_subrule_id'] . ");'><span class = 'glyphicon glyphicon-remove'></span> </button>"
            . "</td></tr>";
        }
        ?>
    </tbody></table>
