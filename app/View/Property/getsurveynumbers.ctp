<?php
echo $this->Html->script('jquery.dataTables');
echo $this->Html->script('dataTables.bootstrap');
?>

<table class="table table-bordered" id="surveytbl">
    <thead>
        <tr>
            <th><?php echo __('lblsrno'); ?></th>
             <th><?php echo __('lblattributetype'); ?></th>
            <th><?php echo __('lblattributevalue'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        foreach ($results as $result) {
            ?>
            <tr>
                <td>
    <?php echo $i++; ?> 
                </td>
                 <td>
    <?php echo $result['attribute']['eri_attribute_name_en']; ?> 
                </td>
                <td>
    <?php echo $result['Surveyno']['survey_no']; ?>  
                </td>
            </tr>
<?php } ?>  
    </tbody>
</table>