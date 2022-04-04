<script>
    $(document).ready(function () {
        $('#tbl_legacy_withness').dataTable();
    });
</script>
<div id ='legacy_withness_detail'>
    <table id="tbl_legacy_withness"  style="width:100%" class="table table-striped table-bordered table-condensed">  
        <thead>
            <tr>  
                <th class="center"><?= __('Doc. Reg. No.'); ?></th>
                <th class="center"><?= __('Withness Name'); ?></th>
                <th class="center"><?= __('Age'); ?></th>
                <th><?= __('Gender'); ?></th>
                <th><?= __('Father Name'); ?></th>
                <th><?= __('District'); ?></th>
                <th><?= __('Taluka'); ?></th>
                <th><?= __('Village'); ?></th>
                <th><?= __('Address'); ?></th>
            </tr>  
        </thead>
        <tbody id="tablebody1" >
   <?php                               
   if(!empty($withnessDetail)) {                                 
        foreach ($withnessDetail as $record) {
        ?>
            <tr>
                <td class="tblbigdata"><?= $record['doc_reg_no']; ?></td>
                <td class="tblbigdata"><?= $record['salutation'].' '.$record['witness_full_name_en']; ?></td>
                <td class="tblbigdata"><?= $record['age']; ?></td>
                <td class="tblbigdata"><?= $record['gender_id']; ?></td>
                <td class="tblbigdata"><?= $record['father_full_name_en']; ?></td>
                <td class="tblbigdata"><?= $record['district_id']; ?></td>
                <td class="tblbigdata"><?= $record['taluka_id']; ?></td>
                <td class="tblbigdata"><?= $record['village_id']; ?></td>
                <td class="tblbigdata"><?= $record['address_en']; ?></td>
            </tr>
    <?php } 
    } ?>  
        </tbody>
    </table>

</div>