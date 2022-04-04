<?php
echo $this->Html->script('bootstrap-datepicker.min');
echo $this->Html->css('bootstrap-datepicker3.min');
?>

<script type="text/javascript">

    $(document).ready(function () {
        $('#name_list_tbl').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
    });
</script>

<?php
$doc_lang = $this->Session->read('doc_lang');
if (isset($output) && !empty($output)) {
    $c = count($attributein);
    ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <center><h3 class="box-title headbolder"><?php echo __('lbllistof712'); ?></h3></center>
                </div>

                <div class="box-body">

                    <table class="table table-striped table-bordered table-hover" id="name_list_tbl">
                        <thead >
                            <tr class="table_title_red_brown">
                                <?php foreach ($attributein as $attributein) { ?>
                                    <th>
                                        <?php echo $attributein['extinterfacefielddetails']['mapping_name'] . ' ' . '-Input'; ?>
                                    </th>
                                <?php } ?>

                                <?php foreach ($attributeout as $attributeout) { ?>
                                    <th>
                                        <?php echo $attributeout['extinterfacefielddetails']['mapping_name'] . ' ' . '-Output'; ?>
                                    </th>
                                <?php } ?>
                                <th>
                                    <?php echo __('lblaction'); ?>
                                </th>
                            </tr>
                        </thead>

                        <tbody>

                            <tr>

                                <?php for ($i = 0; $i < count($output); $i++) { ?>
                                    <td><?php echo $inarray[0][0]['village_name_'.$doc_lang]; ?></td>                                  
                                    <td><?php echo $inarray[0][0]['taluka_name_'.$doc_lang]; ?></td>
                                    <td><?php echo $inarray[0][0]['district_name_'.$doc_lang]; ?></td>
                                    <td><?php echo $output['fname'][$i]; ?></td>
                                    <td><?php echo $output['mname'][$i]; ?></td>
                                    <td><?php echo $output['lname'][$i]; ?></td>
                                    <td><?php echo $output['nave_area'][$i]; ?></td>
                                    <td><?php echo $output['nave_pot'][$i]; ?></td>


                                    <td> <input type="button" class="btn btn-primary" value="<?php echo __('lblSelect'); ?>" onclick="javascript: return setval_for_7_12('<?php echo $output['fname'][$i]; ?>', '<?php echo $output['mname'][$i]; ?>', '<?php echo $output['lname'][$i]; ?>')"></td>
                                </tr>
                            <?php } ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div> 
    </div>
    <input type="hidden" id="flag" value="Y">


    <?php echo $this->Js->writeBuffer(); ?>
<?php } else { ?>
    <input type="hidden" id="flag"  value="N">
<?php } ?>





