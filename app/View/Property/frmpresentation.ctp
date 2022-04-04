<script>
    $(document).ready(function () {
        if (!navigator.onLine)
        {
           // window.location = '../cterror.html';
        }
        function disableBack() {
            window.history.forward();
        }

        window.onload = disableBack();
        window.onpageshow = function (evt) {
            if (evt.persisted)
                disableBack();
        };

        //calander code
        $('.date').datepicker({
            format: "yyyy-mm-dd",
            todayBtn: "linked",
            calendarWeeks: true,
            //orientation: "top left",
            autoclose: true,
            todayHighlight: true
        });

    });

    function dropdownchange(ddlid) {
        var ddlval = $("#" + ddlid + " option:selected").val();
        //var token = $("#token").val();
        //var i;
        $.getJSON("<?php echo $this->webroot; ?>Property/dropdowndependency", {ddlid: ddlid, ddlval: ddlval}, function (data)
        {
            //alert(data);
            var sc = '<option value="empty">--Select Option--</option>';
            $.each(data, function (index, val) {
                sc += "<option value=" + index + ">" + val + "</option>";
            });
            if (ddlid == 'fldarticle') {
                $("#flddocumenttitle option").remove();
                $("#flddocumenttitle").append(sc);
            }
        });
    }

</script>

<?php echo $this->Form->create('frmpresentation', array('id' => 'frmpresentation', 'autocomplete' => 'off')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-success">
            <div class="panel-heading" style="text-align: center"><b><?php echo __('lblprepresentation'); ?></b></div>
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
                <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <!--                    <div class="row" style="text-align: center;">
                            <?php foreach ($minorfunction as $mf) { ?>
                                                <input type="button" id="btn<?php echo $mf['minorfunction']['function_desc']; ?>" class="btn btn-primary " style="width: 100px;"
                                                value="<?php echo __($mf['formlabels']['labelname']); ?>"  
                                                onclick="location.href = '<?php echo $this->Html->url(array('controller' => 'Property', 'action' => $mf['mf_forms']['form_name'])); ?>';" />
                            <?php } ?>
                                                </div>-->
                            <br>
                            <?php foreach ($formbehaviour as $fb) { ?>
                                <div class="panel panel-info">
                                    <div class="panel-heading" style="text-align: center"><b><?php echo $fb['formbehaviour']['behaviour_desc']; ?></b></div>
                                    <div id="collapseOne" class="panel-collapse collapse in">
                                        <div class="panel-body">
                                            <div class="row">
                                                <?php
                                                foreach ($formfields as $ff) {
                                                    if ($fb['formbehaviour']['id'] == $ff['fieldformlinkage']['behaviour_id']) {
                                                        ?>
                                                        <label for="<?php echo $ff['fieldlist']['fieldname']; ?>" class="col-sm-3 control-label"><?php echo __($ff['formlabels']['labelname']); ?></label>    
                                                        <div class="col-sm-3">
                                                            <?php
                                                            if ($ff['fieldlist']['fieldtype'] == 'Text') {
                                                                echo $this->Form->input($ff['fieldlist']['fieldname'], array('label' => false, 'id' => $ff['fieldlist']['fieldname'], 'type' => 'text', 'class' => 'form-control input-sm'));
                                                            } else if ($ff['fieldlist']['fieldtype'] == 'Dropdown') {
                                                                $options = ClassRegistry::init($ff['fieldlist']['ddltablename'])->find('list', array('fields' => array($ff['fieldlist']['ddlfieldvalue'], $ff['fieldlist']['ddlfielddesc'])));
                                                                echo $this->Form->input($ff['fieldlist']['fieldname'], array('type' => 'select', 'error' => false, 'options' => array('empty' => '--Select Option--', $options), 'id' => $ff['fieldlist']['fieldname'], 'label' => false, 'class' => 'form-control input-sm', 'onchange' => 'dropdownchange("' . $ff['fieldlist']['fieldname'] . '");'));
                                                            } else if ($ff['fieldlist']['fieldtype'] == 'Date') {
                                                                ?>
                                                                <div class="input-group date">
                                                                    <?php echo $this->Form->input($ff['fieldlist']['fieldname'], array('label' => false, 'id' => $ff['fieldlist']['fieldname'], 'type' => 'text', 'class' => 'form-control input-sm', 'readonly' => 'readonly')); ?>
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                </div>
                                                            <?php } ?>
                                                        </div><br>
                                                        <?php
                                                    }
                                                    } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="row" style="text-align: center;">
                                <div class="col-sm-12">
                                    <button type="submit" style="width: 100px;" id="btnCancel" name="btnCancel" class="btn btn-primary"><?php echo __('btncancel'); ?></button>
                                    <button type="submit" style="width: 100px;" id="btnNext" name="btnNext" class="btn btn-primary"><?php echo __('btnnext'); ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $this->Form->end(); ?>