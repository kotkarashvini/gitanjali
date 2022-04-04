<?php
if (isset($allrates) && !empty($allrates)) {
    ?>

    <table class="table table-bordered" id="ratetbl">
        <thead>
            <tr>
                <th><?php echo __('lblsrno'); ?></th>
                <th><?php echo __('lbllocation'); ?></th>
                <th><?php echo __('lblusage'); ?></th>
                <th><?php echo __('lblconstuctiontye'); ?></th> 
                 <th><?php echo __('lbluserdependency1'); ?></th>
                <th><?php echo __('lbluserdependency2'); ?></th>           
                <th><?php echo __('lblrate'); ?></th>
                <th><?php echo __('lblunit'); ?></th>
                </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;
            foreach ($allrates as $key => $rates) {
                foreach ($rates as $key1 => $rate) {
                    //     pr($rate);exit;
                    if ($rate['rate']['prop_rate'] != 0 && !is_null($rate['rate']['prop_rate'])) {
                        ?>
                        <tr class=<?php //echo $classname; ?>>
                            <td>
                        <?php echo ++$i; ?> 
                            </td>

                            <td>
                <?php
                if (!empty($rate['location1']['list_1_desc_' . $lang])) {
                    echo $rate['location1']['list_1_desc_' . $lang];
                } else if (!empty($rate['zone']['valuation_zone_desc_' . $lang])) {
                    echo $rate['zone']['valuation_zone_desc_' . $lang] . "(" . $rate['subzone']['from_desc_' . $lang] . " - " . $rate['subzone']['to_desc_' . $lang] . ")";
                } else if (!empty($rate['ulbclass']['class_description_' . $lang])) {
                    echo $rate['ulbclass']['class_description_' . $lang];
                }
                ?>  
                            </td>
                            <td>
                                <?php echo $rate['usage_sub']['usage_sub_catg_desc_' . $lang]; ?>  
                            </td>
                            <td>
                                <?php echo $rate['ctype']['construction_type_desc_' . $lang]; ?>  
                            </td>
                              <td>
                                <?php echo $rate['udep1']['user_defined_dependency1_desc_' . $lang]; ?>  
                            </td>
                            <td> <?php echo $rate['udep2']['user_defined_dependency2_desc_' . $lang]; ?></td>		
                            <td>
                                <?php echo $rate['rate']['prop_rate']; ?>  
                            </td>
                            <td>
                                <?php echo $rate['unit']['unit_desc_' . $lang]; ?>  
                            </td>

                          

                        </tr>

            <?php
            }
        }
    }
    ?>  
        </tbody>
    </table>
    <?php
}?>