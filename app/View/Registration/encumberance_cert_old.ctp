<script>
    $(document).ready(function () {
        $('#Doclist').DataTable();
    });
     var host = '<?php echo $this->webroot; ?>';
    function display_details(id)
    {
        $.post(host + 'Registration/display_pro_list', {id: id}, function (data1)
            {
            

               $("#proplist").html(data1);
//                $(document).trigger('_page_ready');
//                show_error_messages();
//                $("#partyentry").show();
            });
    }
    

</script>
<?php echo $this->Form->create('encumberance_cert', array('id' => 'encumberance_cert', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('Encumberance Certificates');  ?></h3></center> 
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table id="Doclist" class="table table-striped table-bordered table-hover">  
                        <thead >  
                            <tr>
                                <th><?php echo __('lblsrno'); ?></th> 
                                <th><?php echo __('lbltokenno'); ?></th>
                                <th><?php echo __('lbldocktype'); ?></th>
                                <th><?php echo __('lblpresentername'); ?></th> 
                                <th><?php echo __('lblaction'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $counter = 0;
                           //pr($alldocuments);
                            foreach ($alldocuments as $documents) {
//                                pr($documents);
                                ?>
                                <tr>
                                    <th scope="row"><?php echo ++$counter; ?></th>
                                    <td><?php echo $documents[0]['token_no']; ?></td>
                                    <td><?php echo $documents[0]['article_desc_en']; ?></td>
                                    <td><?php echo $documents[0]['party_full_name_en'];    ?></td> 
                                    <td> <input type="button" id="btnupdate" name="btnupdate" class="btn btn-default"  value="Encumberance Processing" onclick="javascript: return display_details(('<?php echo $documents[0]['details_id']; ?>'))"><input type="button" id="btnupdate" name="btnupdate" class="btn btn-default"  value="Payment"></td>
                                </tr> 
                            <?php } ?>
                        </tbody>
                    </table> 

                </div>
                
                   
                         <div id="proplist"> </div>
                    
                     
            </div>
        </div>
        <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    </div>
</div>

<?php echo $this->Form->end(); ?>


