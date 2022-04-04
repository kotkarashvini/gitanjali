<script language="JavaScript" type="text/javascript">
    $(document).ready(function () {
        
        $('#tablesurvey').dataTable({
    "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    })
    
    
    
      });
      function forupdate(state_id, survey_no_id, district_id, taluka_id, village_id, level1_id, level1_list_id, state_name_en, district_name_en, taluka_name_en, village_name_en, level_1_desc_en, list_1_desc_en, survey_no){
       $("#state_id").val(state_id);
       
            $.getJSON("<?php echo $this->webroot; ?>regdistrict", {state: state_id}, function (data)
            {
                var sc = '<option value="empty">--Select District--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#district_id option").remove();
                $("#district_id").append(sc);
                $("#district_id").val(district_id);
            });
            
            $.getJSON("<?php echo $this->webroot; ?>regtaluka", {district: district_id}, function (data)
            {
                var sc = '<option value="empty">--Select Taluka--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#taluka_id option").remove();
                $("#taluka_id").append(sc);
                $("#taluka_id").val(taluka_id);
            });
           
            
             $.getJSON("<?php echo $this->webroot; ?>get_village_survey", {taluka_id: taluka_id}, function (data)
            {
                var sc1 = '<option value="empty">--select--</option>';
                $.each(data, function (index1, val1) {

                    sc1 += "<option value=" + index1 + ">" + val1 + "</option>";
                });
                $("#village_id option").remove();
                $("#village_id").append(sc1);
                $("#village_id").val(village_id);
            });
            
                         
            $("#level1_id").val(level1_id);
                         
            $.getJSON('<?php echo $this->webroot; ?>get_level1_list1', {level1_list: level1_id}, function (data)
            {
                var sc = '<option>select</option>';
                var sc1 = '<option>select</option>';
                $.each(data.data1, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#level1_list_id").prop("disabled", false);
                $("#level1_list_id option").remove();
                $("#level1_list_id").append(sc);
                 $("#level1_list_id").val(level1_list_id);
            });
   
             $('#hfid').val(survey_no_id);
             $('#survey_no').val(survey_no);
             $('#hfupdateflag').val('Y');
             //alert(survey_no_id);
        return false;
            
    }
</script>
<?php echo $this->Form->create('surveyno_entry', array('id' => 'surveyno_entry')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-body">
                <br><br>
                <div id="divsurveynogrid" class="table-responsive">
                    <table id="tablesurvey" class="table table-striped table-bordered table-hover">  
                        <thead>  
                            <tr>  
                                <th class="center"><?php echo __('Sr.No.'); ?></th>
                                <th class="center"><?php echo __('State'); ?></th>
                                <th class="center"><?php echo __('District'); ?></th>
                                <th class="center"><?php echo __('Taluka'); ?></th>
                                <th class="center"><?php echo __('Village'); ?></th>
                                <th class="center"><?php echo __('Government Body'); ?></th>
                                <th class="center"><?php echo __('Government Body List'); ?></th>
                                <th class="center"><?php echo __('Survey No'); ?></th>
                                <th class="center width15"><?php echo __('lblaction'); ?></th>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php $i = 0; ?>
                            <?php foreach ($result as $result1): ?>
                                <tr>
                                    <?php $i++; ?>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $result1[0]['state_name_en']; ?></td>
                                    <td><?php echo $result1[0]['district_name_en']; ?></td>
                                    <td><?php echo $result1[0]['taluka_name_en']; ?></td>
                                    <td><?php echo $result1[0]['village_name_en']; ?></td>
                                    <td><?php echo $result1[0]['level_1_desc_en']; ?></td>
                                    <td><?php echo $result1[0]['list_1_desc_en']; ?></td>
                                    <td><?php echo $result1[0]['survey_no']; ?></td>
                                    <td>
                                       <button id="btnupdate<?php  echo $result1[0]['survey_no_id']; ?>" name="btnupdate" class="btn btn-default "onclick="javascript: return forupdate(
                                                            '<?php echo $result1[0]['state_id']; ?>',
                                                            '<?php echo $result1[0]['survey_no_id']; ?>',
                                                            '<?php echo $result1[0]['district_id']; ?>',
                                                            '<?php echo $result1[0]['taluka_id']; ?>', 
                                                            '<?php echo $result1[0]['village_id']; ?>', 
                                                            '<?php echo $result1[0]['level1_id']; ?>', 
                                                            '<?php echo $result1[0]['level1_list_id']; ?>',
                                                            '<?php echo $result1[0]['state_name_en']; ?>',
                                                            '<?php echo $result1[0]['district_name_en']; ?>',
                                                            '<?php echo $result1[0]['taluka_name_en']; ?>',
                                                            '<?php echo $result1[0]['village_name_en']; ?>',
                                                            '<?php echo $result1[0]['level_1_desc_en']; ?>',
                                                            '<?php echo $result1[0]['list_1_desc_en']; ?>',
                                                            '<?php echo $result1[0]['survey_no']; ?>');
                                                           ">
                                            <span class="glyphicon glyphicon-pencil"></span></button>
                                    <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'surveyno_delete', $result1[0]['survey_no_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
                         
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php unset($result1); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->Form->end(); ?>
