<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>

<script>
    $(document).ready(function () {
        if (!navigator.onLine)
        {
            //window.location = '../cterror.html';
        }
        function disableBack() {
            window.history.forward();
        }

        window.onload = disableBack();
        window.onpageshow = function (evt) {
            if (evt.persisted)
                disableBack();
        };
//        $("input[type=text]").keyup(function () {
//            $(this).val($(this).val().toUpperCase());
//        });
//
// $('#level_2_desc_eng').change(function () {
//            var level2_list = $("#level_2_desc_eng option:selected").val();
//            $.getJSON('getlevel2_list1', {level2_list: level2_list}, function (data)
//            {
//                var sc = '<option>select</option>';
//                $.each(data.data1, function (index, val) {
//                    sc += "<option value=" + index + ">" + val + "</option>";
//                });
//                $("#list_2_desc_eng").prop("disabled", false);
//                $("#list_2_desc_eng option").remove();
//                $("#list_2_desc_eng").append(sc);
//            });
//        });
    });


</script>

<?php echo $this->Form->create('formulamaster', array('id' => 'formulamaster', 'autocomplete' => 'off')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-success">
            <div class="panel-heading" style="text-align: center"><b><?php echo __('lblpromashead'); ?></b></div>
            <div class="panel-body">
                <div class="row" style="text-align: center">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <?php foreach ($majorfunction as $mf) { ?>
                                <div class="col-sm-4"> 
                                    <input type="button" id="btn<?php echo $mf['majorfunction']['function_desc']; ?>" class="btn btn-primary " style="width: 130px;" 
                                           value="<?php echo __($mf['formlabels']['labelname']); ?>"  
                                           onclick="location.href = '<?php echo $this->Html->url(array('controller' => 'Property', 'action' => 'propertymaster', "param1" => "val1")); ?>';" />
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                <div class="row" style="text-align: center">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <?php
                            $tempid = NULL;
                            foreach ($majorfunction as $mf) {
                                echo "<div class=col-sm-4>";
                                foreach ($minorfunction as $mf1) {
                                    if ($mf1['major']['major_id'] == $mf['majorfunction']['major_id']) {
                                        ?>
                                        <input type="button" id="btn<?php echo $mf1['minorfunction']['function_desc']; ?>" class="btn btn-primary "  
                                               value="<?php echo __($mf1['formlabels']['labelname']); ?>"  
                                               onclick="location.href = '<?php echo $this->Html->url(array('controller' => 'Property', 'action' => $mf1['mf_forms']['form_name'])); ?>';" />
                                               <?php
                                           }
                                       }
                                       echo "</div>";
                                   }
                                   ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>
