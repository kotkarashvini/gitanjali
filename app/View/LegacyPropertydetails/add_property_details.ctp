<table class="table table-bordered" id="prop_details_tbl">
    <thead>
        <tr>
            <th>
                <?php echo __('lblusamaincat'); ?>
            </th>
            <th>
                <?php echo __('lblsubcat'); ?>
            </th>
              <th>
                <?php echo __('lblarea'); ?>
            </th>  
            <th>
                <?php echo __('lblmarketvalue'); ?>
            </th>
             <th>
                 <?php echo __('lblconsiderationamt'); ?>
            </th>
            <th>
                 
            </th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($prop_details as $key => $prop_detail) {
            ?>
            <tr>
                <th>
                    <?php echo $prop_detail['maindesc']; ?>
                </th>
                <th>
                    <?php echo $prop_detail['subdesc']; ?>
                </th>
                  <th>
                    <?php echo $prop_detail['item_value'].$prop_detail['unit_desc']; ?>
                </th>
                  <th>
                    <?php echo $prop_detail['final_value']; ?>
                </th>
               <th>
                    <?php echo $prop_detail['consideration_amt']; ?>
                </th>

                 <th>
                    <input type="button" onclick="remove_property('<?php echo $key; ?>')" value="<?php echo __('lblbtndelete'); ?> ">
                 </th>
                
            </tr>
        <?php } ?>
    </tbody>
</table>
