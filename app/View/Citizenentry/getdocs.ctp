<table id="tableconfinfo" class="table table-striped table-bordered table-hover" >
<thead>  
    <tr>  
        <th class="center"><?php echo "Sr No."; ?></th>
        <th class="center"><?php echo "Document Name"; ?></th>
    </tr>  
</thead>
<tbody>
    <?php
        $i=1;
        for ($j = 0; $j < count($upload_file1); $j++) {
    ?>
        <tr>
            <td class="tblbigdata" align="right"><?php echo $i; $i++; ?></td>
            <td class="tblbigdata" align="left"><?php echo $upload_file1[$j]['upload_document']['document_name_en']; ?></td>
        </tr>
    <?php
        }
    ?>
</tbody>
</table>