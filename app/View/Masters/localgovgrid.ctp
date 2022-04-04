
<script>

    $(document).ready(function () {
        if ($('#hfhidden1').val() == 'Y') {
            $('#tableratedata').dataTable({
                "iDisplayLength": 10,
                "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
            });
        }




    });

    function formupdate(
        
      <?php foreach ($languagelist as $langcode) { ?>
    <?php echo 'governingbody_name_' . $langcode['mainlanguage']['language_code']; ?>,
<?php } ?> district_id,taluka_id,ulb_type_id,id)
    
    {
           $("#actiontype").val('1');
           
           <?php foreach ($languagelist as $langcode) { ?>
        $('#governingbody_name_<?php echo $langcode['mainlanguage']['language_code']; ?>').val(governingbody_name_<?php echo $langcode['mainlanguage']['language_code']; ?>);
        <?php } ?>
    
      
        
        var district = district_id;
        var token = $("#token").val();
        var i1;
        $.getJSON("<?php echo $this->webroot; ?>regtaluka", {district: district, token: token}, function (data1)
        {
            var sc1 = taluka_id;
            $.each(data1, function (index1, val1) {
                sc1 += "<option value=" + index1 + ">" + val1 + "</option>";
            });
            $("#taluka_id option").remove();
            $("#taluka_id").append(sc1);
            $('#taluka_id').val(taluka_id);
        });
        
     
        
       
    
            
             $('#district_id').val(district_id);
              $('#taluka_id').val(taluka_id);
            $('#ulb_type_id').val(ulb_type_id);
            
            $('#hfid').val(id);
            $('#hfupdateflag').val('Y');
            $('#btnadd').html('Save');
            return false;
    }

</script>

<?php echo $this->Form->create('localgovlist', array()); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">

            <div class="box-body">
                <br><br>
                <div id="divratedata" class="table-responsive">
                    <table id="tableratedata" class="table table-striped table-bordered table-hover">  
                        <thead >  
                            <tr>  
                            
                            <th class="center"> <?php echo __('lbladmdistrict'); ?> </th>
                            <th class="center"> <?php echo __('lbladmtaluka'); ?> </th>
                            <th class="center"> <?php echo __('lbllocalgoberningbody'); ?> </th>                           
                            
                            <?php foreach ($languagelist as $langcode) { ?>
                                    <th class="center"><?php echo __('lbllocalgoberningbody') . " (" . $langcode['mainlanguage']['language_name'] . ")"; ?></th>
                                <?php } ?>
                         <th class="center" style="width:20px;"> <?php echo __('lblaction'); ?> </th>
                        </tr>  
                        </thead>
                        <tbody>
                             <?php foreach ($localgovrecord as $result1): ?>
                            <tr>
                                
                                <td ><?php echo $result1[0]['district_name_'.$lang]; ?></td>
                                <td ><?php echo $result1[0]['taluka_name_'.$lang]; ?></td>
                                <td ><?php echo $result1[0]['class_description_'.$lang]; ?></td>
                                                                

                                  <?php
                                //  creating dyanamic table data(coloumns) using same array of config language
                                foreach ($languagelist as $langcode) {
                                    ?>
                                    <td ><?php echo $result1[0]['governingbody_name_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                <?php } ?>

                                            <td class="width15 center">
                                  
                                        <button id="btnupdate" name="btnupdate" type="button" data-toggle="tooltip" title="Edit" class="btn btn-default " onclick="javascript: return formupdate(
                                         <?php           
                                                              foreach ($languagelist as $langcode) {
                                            ?>
                                                        ('<?php echo $result1[0]['governingbody_name_' . $langcode['mainlanguage']['language_code']]; ?>'),
                                        <?php } ?>
                                                                    
                                                                    ('<?php echo $result1[0]['district_id']; ?>'),
                                                                    ('<?php echo $result1[0]['taluka_id']; ?>'),
                                                                    ('<?php echo $result1[0]['ulb_type_id']; ?>'),
                                                                    ('<?php echo $result1[0]['id']; ?>'));">
                                            <span class="glyphicon glyphicon-pencil"></span>
                                        </button>


                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'localgov_delete', $result1[0]['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
                                   

                                </td>
                            </tr>
                           <?php endforeach; ?>
                        </tbody>
                    </table> 
                    <?php if (!empty($localgovrecord)) { ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>
