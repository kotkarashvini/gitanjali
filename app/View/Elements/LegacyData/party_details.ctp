<script>
    $(document).ready(function () {
        $('#tbl_legacy_parties').dataTable();
    });
</script>

<div id ='legacy_party_detail'>
    <table id="tbl_legacy_parties"  style="width:100%" class="table table-striped table-bordered table-condensed">  
        <thead>
            <tr>  
                <th class="center"><?= __('Doc. Reg. No.'); ?></th>
                <th class="center"><?= __('Party Name.'); ?></th>
                <th class="center"><?= __('Age.'); ?></th>
                <th><?= __('Gender'); ?></th>
                <th><?= __('UID'); ?></th>
                <th><?= __('PAN No.'); ?></th>
                <th><?= __('Father Name'); ?></th>
                <th><?= __('Address'); ?></th>
                <th><?= __('Pin Code'); ?></th>
            </tr>  
        </thead>
        <tbody id="tablebody1" >
   <?php                               
   if(!empty($partyDetail)) {                                 
        foreach ($partyDetail as $record) {
        ?>
            <tr>
                <td class="tblbigdata"><?= $record['doc_reg_no']; ?></td>
                <td class="tblbigdata"><?= $record['party_full_name_en']; ?></td>
                <td class="tblbigdata"><?= $record['age']; ?></td>
                <td class="tblbigdata"><?= $record['gender_id']; ?></td>
                <td class="tblbigdata"><?= $record['uid']; ?></td>
                <td class="tblbigdata"><?= $record['pan_no']; ?></td>
                <td class="tblbigdata"><?= $record['father_full_name_en']; ?></td>
                <td class="tblbigdata"><?= $record['address_en']; ?></td>
                <td class="tblbigdata"><?= $record['pin_code']; ?></td>
            </tr>
    <?php } 
    } ?>  
        </tbody>
    </table>

</div>