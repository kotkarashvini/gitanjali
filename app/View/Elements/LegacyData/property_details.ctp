<div id ='legacy_property_detail'>
    <table id="tbl_legacy_properties"  style="width:100%" class="table table-striped table-bordered table-condensed">  
        <thead>
            <tr>  
                <th class="center"><?= __('Doc. Reg. No.'); ?></th>
                <th class="center"><?= __('Property Serial No.'); ?></th>
                <th class="center"><?= __('Unique Property No.'); ?></th>
                <th><?= __('Dev Land Type'); ?></th>
                <th><?= __('District'); ?></th>
                <th><?= __('Sub-division'); ?></th>
                <th><?= __('Taluka'); ?></th>
                <th><?= __('Circle'); ?></th>
                <th><?= __('Village'); ?></th>
                <th><?= __('Boundries - East'); ?></th>
                <th><?= __('Boundries - West'); ?></th>
                <th><?= __('Boundries - South'); ?></th>
                <th><?= __('Boundries - North'); ?></th>
                <th><?= __('Additional Info.'); ?></th>
            </tr>  
        </thead>
        <tbody id="tablebody1" >
   <?php                               
   if(!empty($propertyDetail)) {                                 
        foreach ($propertyDetail as $record) {
        ?>
            <tr>
                <td class="tblbigdata"><?= $record['doc_reg_no']; ?></td>
                <td class="tblbigdata"><?= $record['property_serial_no']; ?></td>
                <td class="tblbigdata"><?= $record['unique_property_no_en']; ?></td>
                <td class="tblbigdata"><?= $record['developed_land_types_id']; ?></td>
                <td class="tblbigdata"><?= $record['district_id']; ?></td>
                <td class="tblbigdata"><?= $record['subdivision_id']; ?></td>
                <td class="tblbigdata"><?= $record['taluka_id']; ?></td>
                <td class="tblbigdata"><?= $record['circle_id']; ?></td>
                <td class="tblbigdata"><?= $record['village_id']; ?></td>
                <td class="tblbigdata"><?= $record['boundries_east_en']; ?></td>
                <td class="tblbigdata"><?= $record['boundries_west_en']; ?></td>
                <td class="tblbigdata"><?= $record['boundries_south_en']; ?></td>
                <td class="tblbigdata"><?= $record['boundries_north_en']; ?></td>
                <td class="tblbigdata"><?= $record['additional_information_en']; ?></td>
            </tr>
    <?php } 
    } ?>  
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function () {
        $('#tbl_legacy_properties').dataTable();
    });
</script>