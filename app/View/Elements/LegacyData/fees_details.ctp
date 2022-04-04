<script>
    $(document).ready(function () {
        $('#tbl_legacy_fees').dataTable();
    });
</script>
<div id ='legacy_fees_detail'>
    <table id="tbl_legacy_fees"  style="width:100%" class="table table-striped table-bordered table-condensed">  
        <thead>
            <tr>  
                <th class="center"><?= __('Doc. Reg. No.'); ?></th>                
                <th><?= __('Fee Type'); ?></th>
                <th><?= __('Amount'); ?></th>
            </tr>  
        </thead>
        <tbody id="tablebody1" >
   <?php                               
   if(!empty($feesDetail)) {                                 
        foreach ($feesDetail as $record) {
        ?>
            <tr>
                <td class="tblbigdata"><?= $record['doc_reg_no']; ?></td>
                <td class="tblbigdata"><?= $record['fee_item_id']; ?></td>
                <td class="tblbigdata"><?= $record['final_value']; ?></td>                
            </tr>
    <?php } 
    } ?>  
        </tbody>
    </table>

</div>