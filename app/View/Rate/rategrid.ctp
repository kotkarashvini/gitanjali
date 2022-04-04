
<script>

    $(document).ready(function () {
        if ($('#hfhidden1').val() == 'Y') {
            $('#tableratedata').dataTable({
                "iDisplayLength": 10,
                "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
            });
        }




    });


    function formupdate(id) {

        var prop_rate = $('#prop_rate'+id).val();

         if (!isNaN(prop_rate)) {

            $.post('update_rate', {id: id, prop_rate: prop_rate}, function (data)
            {
                alert('Record Updated Successfully');
                $('#prop_rate'+id).val(prop_rate);
            });

            $('#hfupdateflag').val('Y');
            return false;
       }
       else{
            alert('please enter valid rate');
           return false;
        }
    }
</script>

<?php echo $this->Form->create('rate', array()); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">

            <div class="box-body">
                <br><br>
                <div id="divratedata" class="table-responsive">
                    <table id="tableratedata" class="table table-striped table-bordered table-hover">  
                        <thead >  
                            <tr> 
                                <th class="center" ><?php echo __('Sr. No.'); ?></th>
                                <th class="center" ><?php echo __('Land Type'); ?></th>
                                <th class="center" ><?php echo __('Corporation'); ?></th>

                                <th class="center" ><?php echo __('lbladmvillage'); ?></th>

                                <th class="center"><?php echo __('lblLevel1list'); ?></th>


                                <th class="center"><?php echo __('Usagemain Cat.'); ?></th>
                                <th class="center"><?php echo __('Usage Sub Cat.'); ?></th>
                                <th class="center"><?php echo __('Unit'); ?></th>
                                <th class="center"><?php echo __('Rate'); ?></th>
                                <th class="center width10"><?php echo __('lblaction'); ?> </th>
                            </tr>  
                        </thead>
                        <tbody>

                            <tr>
                                <?php for ($i = 0; $i < count($raterecord); $i++) { ?>
                                    <td class="tblbigdata"><?php echo $i + 1; ?></td>
                                    <td class="tblbigdata"><?php echo $raterecord[$i][0]['developed_land_types_desc_en']; ?></td>

                                    <td class="tblbigdata"><?php echo $raterecord[$i][0]['governingbody_name_en']; ?></td>

                                    <td class="tblbigdata"><?php echo $raterecord[$i][0]['village_name_en']; ?></td>

                                    <td class="tblbigdata"><?php echo $raterecord[$i][0]['list_1_desc_en']; ?></td>

                                    <td class="tblbigdata"><?php echo $raterecord[$i][0]['usage_main_catg_desc_en']; ?></td>
                                    <td class="tblbigdata"><?php echo $raterecord[$i][0]['usage_sub_catg_desc_en']; ?></td>
                                    <td class="tblbigdata"><?php echo $raterecord[$i][0]['unit_desc_en']; ?></td>
                                    <td class="tblbigdata"><?php echo $this->Form->input('prop_rate', array('label' => false, 'id' => 'prop_rate'.$raterecord[$i][0]['id'], 'class' => 'form-control input-sm', 'type' => 'text', 'required' => 'required', 'maxlength' => '10', 'value' => $raterecord[$i][0]['prop_rate'])) ?>
                                    <!--<span id="prop_rate_error" class="form-error"><?php //echo $errarr['prop_rate_error'];  ?></span>-->
                                    </td>

                                    <td >
                                        <input type='submit' name='Save' value='Save'  onclick="javascript: return formupdate(('<?php echo $raterecord[$i][0]['id']; ?>'));"  >
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table> 
                    <?php if (!empty($raterecord)) { ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>
