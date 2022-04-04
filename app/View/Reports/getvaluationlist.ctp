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

<hr style="border: 1px lightblue solid;">
    <table id="vallist" class="table table-striped table-bordered table-hover">  
        <thead >  
            <tr>  
                <th align="center"><b><?php echo __('lblsrno'); ?></b></th>
                <th align="center"><b><?php echo __('lbldate'); ?></b></th>
                <th align="center"><b><?php echo __('lblvaluationno'); ?></b></th>
                <th align="center"><b><?php echo __('lblaction'); ?></b></th>
            </tr>  
        </thead>
        <tbody>
            <?php
            $i = 1;
            foreach ($valuationrecords as $vr):
                $vr = $vr['valuation'];
                ?>
                <tr>
                    <td class='tblbigdata' width='8%'><?php echo $i++ ?></td>
                    <td class='tblbigdata'><?php echo date('d-M-Y', strtotime($vr['created'])); ?></td>
                    <td class='tblbigdata'><?php echo $vr['val_id']; ?></td>
                    <td class='tblbigdata' width='20%'>                        
                        <button id="btnview" name="btnview" class="btn btn-primary" title="View Calculation" style="text-align: center;" onclick="javascript: return formview(('<?php echo base64_encode($vr['val_id']); ?>'));">                            
                            View
                        </button>
                        <?php echo $this->html->link(__('lbldownload'), array('controller' => 'Reports', 'action' => 'rptview', 'P', base64_encode($vr['val_id'])), array('class' => 'btn btn-primary fa fa-download', 'style' => "color:white", 'title' => 'Download PDF')); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php unset($valuationrecords); ?>
        </tbody>
    </table> 
    <?php if (!empty($valuationrecords)) { ?>
        <input type="hidden" value="Y" id="hfhidden1"/>
    <?php } else { ?>
        <input type="hidden" value="N" id="hfhidden1"/>
    <?php } ?>