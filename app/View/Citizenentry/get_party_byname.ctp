<?php
$language = $this->Session->read("sess_langauge");
if (!empty($partyrecord)) {
    ?>
    <!--<div class="box box-primary">-->
    <div class="form-group">
        <div class="tab-content">
            <div id="home" class="tab-pane fade in active">

                <table id="tablegeninfo" class="table table-striped table-bordered table-hover">  
                    <thead>  
                        <tr> 
                            <th class="center"><?php echo __('lblsrno'); ?></th>
                            <th class="center"><?php echo __('lbltokenno'); ?></th>
                            <th class="center"><?php echo __('lblpartyfullname'); ?></th>
                            <th class="center"><?php echo __('lblpartytype'); ?></th>
                            <th class="center"><?php echo __('lblpartycategory'); ?></th>
                            <th class="center"><?php echo __('lblaction'); ?></th>
                        </tr>  
                    </thead>
                    <?php
                    $i = 1;
                    foreach ($partyrecord as $rec) {
                        ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $rec[0]['token_no']; ?></td>
                            <td><?php echo $rec[0]['party_full_name_' . $language]; ?></td>
                            <td><?php echo $rec[0]['party_type_desc_' . $language]; ?></td>
                            <td><?php echo $rec[0]['category_name_' . $language]; ?></td>
                            <td>
                                <!--<a type="button" href="<?php echo $this->webroot; ?>viewDetails/<?php echo $rec[0]['token_no']; ?>/I" class="btn btn-warning btn-xs pull-left"  data-toggle="modal" data-target="#myModal_rpt">View Details</a>-->
                                <button id="btnview" name="btnview" type="submit" class="btn btn-warning btn-xs pull-left"   onclick="javascript: return formview(('<?php echo $rec[0]['token_no']; ?>'), ('<?php echo $rec[0]['id']; ?>'));">
        <?php echo __('lblviewrecord'); ?> <span class="glyphicon glyphicon-pencil"></span>
                                </button>
                            </td>
                            <!--<td><a type="button" href="<?php echo $this->webroot; ?>viewRegSummary2/<?php echo $rec[0]['token_no']; ?>/I" class="btn btn-warning btn-xs pull-left"  data-toggle="modal" data-target="#myModal_rpt2">View Summery 2</a></td>-->

                        </tr>
    <?php } ?>


                </table> 
            </div>

        </div>
    </div>
    <!--</div>-->
<?php } ?>