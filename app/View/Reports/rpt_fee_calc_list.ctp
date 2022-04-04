<?php
echo $this->Html->script('jquery.dataTables');
echo $this->Html->script('dataTables.bootstrap');
?>
<script>
    $(document).ready(function () {

        if ($('#hfhidden1').val() == 'Y')
        {
            $('#vallist').dataTable({
                "iDisplayLength": 10,
                "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
            });
        } else {
            $('#vallist').dataTable({
                "iDisplayLength": 10,
                "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
            });
        }
    });</script>


<div class="table-responsive">
    <table id="vallist" class="table table-striped table-bordered table-hover">  
        <thead >  
            <tr>  
                <th class="center width10"><?php echo __('lblsrno'); ?></th>
                <th class="center width15"><?php echo __('lblfeecalid'); ?></th>
                <th class="center"><?php echo __('lblArticle'); ?></th>
                <th class="center"><?php echo __('lblfeerule'); ?></th>
                <th class="center width15"><?php echo __('lblaction'); ?></th>

            </tr>  
        </thead>
        <tbody>
            <?php
            $i = 1;
            if ($result_records) {
                foreach ($result_records as $vr) {
                    $vra = $vr['fees_calculation'];
                    ?>
                    <tr>
                        <td class='tblbigdata' width='8%'><?php echo $i++ ?></td>
                        <td class='tblbigdata' width='12%'><?php echo $vra['fee_calc_id']; ?></td>
                        <td class='tblbigdata'><?php echo $vr['article']['article_desc_' . $lang]; ?></td>
                        <td class='tblbigdata'><?php echo $vr['rule']['fee_rule_desc_' . $lang]; ?></td>
                        <td class='tblbigdata'  width='20%'>                            
                            <button id="btnview" name="btnview" class="btn btn-primary btn-xs" title="View Calculation"  onclick="javascript: return formview(('<?php echo $vra['fee_calc_id']; ?>'), 'V');">
                                View
                            </button> &nbsp;&nbsp;&nbsp;
                            <?php echo $this->html->link(__('lbldownload'), array('controller' => 'Fees', 'action' => 'view_fee_calculation', $vra['fee_calc_id'], 'D'), array('class' => 'btn btn-xs glyphicon glyphicon-download', 'style' => "color:white", 'title' => 'Download PDF')) ?>
                        </td>
                    </tr>
                    <?php
                }
                unset($result_records);
            }
            ?>
        </tbody>
    </table> 
    <?php if (!empty($result_records)) { ?>
        <input type="hidden" value="Y" id="hfhidden1"/>
    <?php } else { ?>
        <input type="hidden" value="N" id="hfhidden1"/>
    <?php } ?>
</div>
