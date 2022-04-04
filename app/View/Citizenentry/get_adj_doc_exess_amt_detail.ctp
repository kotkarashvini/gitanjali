<div class="col-sm-12" >
    <?php
    if ($doc_payment) {
        ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" border="1" width="100%">
                <thead>
                    <tr>
                        <th class="center"><?php echo __('lblsdamt'); ?></th>
                        <th class="center"><?php echo __('lblpaidamt'); ?></th>
                        <th class="center"><?php echo __('lblexcessamt'); ?></th>
                        <th class="center"><?php echo __('lbladjamt'); ?></th>
                        <th class="center"><?php echo __('lblbalamt'); ?></th>
                    </tr>    
                </thead>
                <tbody>
                    <tr>
                        <td ><?php echo $doc_payment['ApplicationSubmitted']['amt_to_be_paid']; ?></td>
                        <td  ><?php echo $doc_payment['ApplicationSubmitted']['amt_paid']; ?></td>
                        <td  ><?php echo $doc_payment[0]['diff_amt']; ?></td>
                        <td  ><?php echo $adjustedAmount; ?></td>
                        <td  ><?php echo ($doc_payment[0]['diff_amt'] - $adjustedAmount); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php
    }
    if ($adj_detail) {
        ?>
        <h4><b>Adjustment Detail</b></h4>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" border="1"  width="100%">
                <thead>
                    <tr>
                        <th class="center"><?php echo __('lblsrno'); ?></th>
                        <th class="center"><?php echo __('lbltokenno'); ?></th> 
                        <th class="center"><?php echo __('lblamtonline'); ?></th>
                        <th class="center"><?php echo __('lblamtcounter'); ?></th> 
                        <th class="center"><?php echo __('lblTotal'); ?></th> 
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $srno = 1;
                    foreach ($adj_detail as $ad) {
                        $ad = $ad['stamp_duty_adjustment'];
                        $ad['online_adj_amt'] = ($ad['online_adj_amt']) ? $ad['online_adj_amt'] : 0;
                        $ad['counter_adj_amt'] = ($ad['counter_adj_amt']) ? $ad['counter_adj_amt'] : 0;
                        echo "<tr> <td>" . $srno++ . "</td><td align=center>" . $ad['token_no'] . "</td> <td align=center>" . $ad['online_adj_amt'] . "</td><td align=center>" . $ad['counter_adj_amt'] . "</td> <td align=center>" . ($ad['counter_adj_amt'] + $ad['online_adj_amt']) . "</td> </tr>";
                    }
                    ?>
                </tbody>
            </table >
        </div>
        <?php
    }
    ?>
</div>