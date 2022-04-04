<?php
echo $this->Html->script('bootstrap-datepicker.min');
echo $this->Html->css('bootstrap-datepicker3.min');
?>
<script>
    $(document).ready(function () {
        $('#prop_list_tbl').DataTable();
    });
    </script>
<?php
echo $this->Html->css('popup');
$tokenval = $this->Session->read("Selectedtoken");
$csrftoken = $this->Session->read('csrftoken');
$doc_lang = $this->Session->read('doc_lang');
?>


<div class="row">
    <div class="col-lg-12">
       
      
       
        <?php if (!empty($result)) { ?>
            <div class="row" id="propertylist">
                <div class="col-sm-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title headbolder"><?php echo __('lbllistofproperties'); ?></h3>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="prop_list_tbl">
                                    <thead > 
                                        <tr class="table_title_red_brown">
                                            <th class="center"> <?php echo __('lblsrno'); ?></th>
                                            <th class="center"> <?php echo __('lbltokenno'); ?></th>
                                            <th class="center"> <?php echo __('lblregno'); ?></th>
                                             <th class="center"> <?php echo __('lblregdt'); ?></th>
                                            <th class="center"> <?php echo __('lbllocation'); ?></th>
                                         
                                            <th class="center">        <?php echo __('lblpropertydetails'); ?>    </th>
                                          
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i=1; foreach ($result as $key => $property) { ?>
                                            <tr>
                                                 <td class="tblbigdata">
                                                    <?php echo $i; ?>
                                                </td>
                                               
                                                 <td class="tblbigdata">
                                                    <?php echo $property[0]['token_no']; ?>
                                                </td>
                                                 <td class="tblbigdata">
                                                    <?php echo $property[0]['doc_reg_no']; ?>
                                                </td>
                                                <td class="tblbigdata">
                                                    <?php echo $property[0]['doc_reg_date']; ?>
                                                </td>
                                                 <td class="tblbigdata">
                                                    <?php echo $property[0]['village_name_' . $doc_lang]; ?>
                                                </td>
                                                <td class="tblbigdata">
                                                    <?php
                                                    $prop_name = "";
                                                    foreach ($patterns as $key1 => $pattern) {
                                                        if ($property[0]['property_id'] == $pattern[0]['mapping_ref_val']) {
                                                            $prop_name .= "  " . $pattern[0]['pattern_desc_' . $doc_lang] . " : <small>" . $pattern[0]['field_value_' . $doc_lang] . "</small><br>";
                                                        }
                                                    }

                                                    echo substr($prop_name, 1);
                                                    ?>
                                                </td>
                                                
                                            </tr>
                                        <?php $i++; } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> 
            </div> 
            <input type="hidden" name="prop_flag" id="prop_flag" value="1">
        <?php }  ?>
    </div>
</div>

