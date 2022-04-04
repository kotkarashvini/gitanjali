
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

        var level_name = $('#list_1_desc_ll' + id).val();
        $checkflag = valiadte_level1list(id);
        if ($checkflag == 1) {

            $.post('update_location_list', {id: id, level_name: level_name}, function (data)
            {

                if (data != 'F') {
                    alert('Record Updated Successfully');
                    $('#list_1_desc_ll' + id).val(level_name);
                    // location.reload();
                } else {
                    alert('Error');
                    return false;
                }
            });

            $('#hfupdateflag').val('Y');
            return false;
        }
    }
    function listfieldkeyup(id){
         $('#list_1_desc_ll'+id).on('change keyup paste', function (event) {
            var regex = /^[\u0A00-\u0A7F\s]*$/; // GET MATCHING RULE PATTERN                    
            if (!regex.test($('#list_1_desc_ll'+id).val())) {
                $('#list_1_desc_ll'+id+'_error').html('Do not enter any character rather than Punjabi/Gurumukhi');
                $("#list_1_desc_lllist_1_desc_ll"+id).parent().addClass("field-error");
                $("#list_1_desc_lllist_1_desc_ll"+id).focus();
                return false;
            } else {
                $('#list_1_desc_ll'+id+'_error').html('');
                $("#list_1_desc_lllist_1_desc_ll"+id).parent().removeClass("field-error");
            }
        }); // END JS EVENTyyyy
    }
    function valiadte_level1list(id) {

        var regex = /^[\u0A00-\u0A7F\s]*$/;
        //  if ($('#level_1_desc_ll').length > 0 && $('#level_1_desc_ll').is(':visible')) { // FOR CHECK  DYNAMIC FIELDS

        if (!regex.test($('#list_1_desc_ll' + id).val())) {
            $('#list_1_desc_ll' + id + '_error').html('Do not enter any character rather than Punjabi/Gurumukhi');
            $('#list_1_desc_ll' + id).parent().addClass("field-error");
            $('#list_1_desc_ll' + id).focus();
            return false;
        } else {
            $('#list_1_desc_ll' + id + '_error').html('');
            $('#list_1_desc_ll' + id).parent().removeClass("field-error");
        }
        //  } // END FIELD CHECK

        return 1;
    }

</script>

<?php echo $this->Form->create('location', array()); ?>
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


                                <th class="center"><?php echo __('Location List'); ?></th>


                                <th class="center"><?php echo __('Enter Punjabi Name'); ?></th>

                                <th class="center width10"><?php echo __('lblaction'); ?> </th>
                            </tr>  
                        </thead>
                        <tbody>

                            <tr>
<?php for ($i = 0; $i < count($record); $i++) { ?>
                                    <td class="tblbigdata"><?php echo $i + 1; ?></td>


                                    <td class="tblbigdata"><?php echo $record[$i][0]['list_1_desc_en']; ?></td>

                                    <td class="tblbigdata"><?php echo $this->Form->input('list_1_desc_ll', array('label' => false,'onkeyup'=>'listfieldkeyup("'.$record[$i][0]['prop_level1_list_id'].'");', 'id' => 'list_1_desc_ll' . $record[$i][0]['prop_level1_list_id'], 'class' => 'form-control input-sm', 'type' => 'text', 'value' => $record[$i][0]['list_1_desc_ll'])) ?>
                                        <span id="list_1_desc_ll<?php echo $record[$i][0]['prop_level1_list_id']; ?>_error" class="form-error"><?php //echo $errarr['district_id_error'];                    ?></span>
                                    </td>

                                    <td >
                                        <input type='button' name='Save' value='Save'  onclick="javascript: return formupdate(('<?php echo $record[$i][0]['prop_level1_list_id']; ?>'));"  >
                                    </td>
                                </tr>
<?php } ?>
                        </tbody>
                    </table> 
<?php if (!empty($record)) { ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>
