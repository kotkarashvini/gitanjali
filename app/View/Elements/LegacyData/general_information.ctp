<script>
    $(document).ready(function () {
        $('#tbl_legacy_gen_info').dataTable();
    });
</script>
<div id ='legacy_general_info'>
    <table id="tbl_legacy_gen_info"  style="width:100%" class="table table-striped table-bordered table-condensed">  
        <thead>
            <tr>  
                <th class="center"><?= __('Ref. No.'); ?></th>
                <th class="center"><?= __('Document Reg No.'); ?></th>
                <th class="center"><?= __('Document Reg Date.'); ?></th>
                <th><?= __('Book Code'); ?></th>
                <th class="center"><?= __('Doc Processing Year'); ?></th>
                <th><? echo __('State Id'); ?></th>
                <th><?= __('District Id'); ?></th>
                <th><?= __('Taluka Id'); ?></th>
                <th><?= __('Office Id'); ?></th>
                <th><?= __('Document Entered Office'); ?></th>
                <th><?= __('Article Id'); ?></th>
                <th><?= __('Presentation No'); ?></th>
                <th><?= __('Presentation Date'); ?></th>
                <th><?= __('Document Type'); ?></th>
                <th><?= __('Reference No'); ?></th>
            </tr>  
        </thead>
        <tbody id="tablebody1" >
       <?php                               
       if(!empty($generalInfo)) {                                 
            foreach ($generalInfo as $record) {                               
            ?>
            <tr>
                <td class="tblbigdata"><?= $record['reference_sr_no']; ?></td>
                <td class="tblbigdata"><?= $record['doc_reg_no']; ?></td>
                <td class="tblbigdata"><?= $record['doc_reg_date']; ?></td>
                <td class="tblbigdata"><?= $record['book_code']; ?></td>
                <td class="tblbigdata"><?= $record['doc_processing_year']; ?></td>
                <td class="tblbigdata"><?= $record['state_id']; ?></td>
                <td class="tblbigdata"><?= $record['district_id']; ?></td>
                <td class="tblbigdata"><?= $record['taluka_id']; ?></td>
                <td class="tblbigdata"><?= $record['office_id']; ?></td>
                <td class="tblbigdata"><?= $record['doc_entered_office']; ?></td>
                <td class="tblbigdata"><?= $record['article_id']; ?></td>
                <td class="tblbigdata"><?= $record['presentation_no']; ?></td>
                <td class="tblbigdata"><?= $record['presentation_dt']; ?></td>
                <td class="tblbigdata"><?= $record['doc_type']; ?></td>
                <td class="tblbigdata"><?= $record['reference_no']; ?></td>
            </tr>
        <?php } 
        } ?>  
        </tbody>
    </table>
</div>