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
                
            </th>
           
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($prop_attributes as $key => $prop_attribute) {
            ?>
            <tr>
                <th>
                    <?php echo $prop_attribute['para_desc'];
                    ?>
                </th>
                <th>
                    <?php echo $prop_attribute['paramter_value']; ?>
                </th>
                  <th>
                    <?php echo $prop_attribute['paramter_value1']; ?>
                </th>
                  <th>
                    <?php echo $prop_attribute['paramter_value2']; ?>
                </th>
                <th>
                    <input type="button" onclick="remove_attribute('<?php echo $key; ?>')" value="<?php echo __('lblbtndelete'); ?> ">
                </th>
            </tr>
        <?php } ?>
    </tbody>
</table>
