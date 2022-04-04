<table class="table table-bordered" id="prop_attribute_tbl">
    <thead>
        <tr>
            <th>
                <?php echo __('lblattriname'); ?>
            </th>
            <th>
                <?php echo __('lblattrivalue'); ?>
            </th>
            <th>
                <?php echo __('lblattrivalue_part1'); ?>
            </th>  
            <th>
                <?php echo __('lblattrivalue_part2'); ?>
            </th>  
            <th>
                <?php echo __('lblaction'); ?>
            </th>

        </tr>
    </thead>
    <tbody>
        <?php
         
        foreach ($prop_attributes as $key => $prop_attribute) {
            ?>
            <tr>
                <th>
                    <?php echo $attributes[$prop_attribute['attribute_id']];
                    ?>
                </th>
                <th>
                    <?php echo $prop_attribute['attribute_value']; ?>
                </th>
                <th>
                    <?php echo $prop_attribute['attribute_value1']; ?>
                </th>
                <th>
                    <?php echo $prop_attribute['attribute_value2']; ?>
                </th>
                <th>
                    <button type="button" class="btn btn-info" onclick="return attribute_remove('<?php echo $key; ?>', 'S');">Remove</button>
                </th>
            </tr>
        <?php        
          
        } ?>
    </tbody>
</table>
