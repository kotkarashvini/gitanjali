<?php
echo $this->Html->script('bootstrap-datepicker.min');
echo $this->Html->css('bootstrap-datepicker3.min');
$doc_lang = $this->Session->read('doc_lang');
?>

<script type="text/javascript">

    $(document).ready(function () {
        $('#party_list_tbl').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
    });
</script>

<?php if (!empty($party_record)) { ?>
            <div class="row" id="propertylist">
                <div class="col-sm-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title headbolder"><?php echo __('Power Of Attorney List'); ?></h3>
                        </div>
                        <div class="box-body">
                            <table class="table table-striped table-bordered table-hover" id="party_list_tbl">
                                <thead > 
                                     <tr> 
                                    <th class="center"> <?php echo __('lblsrno'); ?></th>
                                    <th class="center"><?php echo __('lblpartyname'); ?></th>
                                    <th class="center"><?php echo __('lblpartytypeshow'); ?></th>
                                    <th class="center"><?php echo __('lblpartycategoryshow'); ?> </th>
                                    <th class="center width16"><?php echo __('lblaction'); ?></th>
                                   
                                </tr> 
                                   
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($party_record as $party_record1){ 
                                        
                                       // if($party_record1[0]['party_catg_id']==7){
                                        ?>
                                   
                                        <tr>
                                            <td class="tblbigdata"><?php echo $i; ?></td>
                                            <td class="tblbigdata"><?php echo $party_record1[0]['party_full_name_' . $doc_lang]; ?></td>
                                            <td class="tblbigdata"><?php echo $party_record1[0]['party_type_desc_' . $doc_lang]; ?></td>
                                            <td class="tblbigdata"><?php echo $party_record1[0]['category_name_' . $doc_lang]; ?></td>
                                            <td><input type="radio" class="btn btn-primary" name="power_att_id" value="<?php echo  $party_record1[0]['id']; ?>" id="<?php echo  $party_record1[0]['id']; ?>" name="" onclick="javascript: return set_attorney_details('<?php echo $party_record1[0]['party_catg_id']; ?>','<?php echo  $party_record1[0]['id']; ?>');">
                                            </td>
                                        
                                           
                                           
                                        </tr>
                                        <?php
                                        $i++;
                                    //}
                                    
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> 
            </div> 
            
        <?php }?>




