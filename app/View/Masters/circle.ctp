<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>

<script>
    $(document).ready(function () {
    var hfupdateflag = "<?php echo $hfupdateflag; ?>";
            if (hfupdateflag == 'Y')
    {
    $('#btnadd').html('Save');
    }

    if ($('#hfhidden1').val() == 'Y')
    {
    $('#tablecircle').dataTable({
    "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
    }


    });</script>
<script>
            function formadd() {
            document.getElementById("actiontype").value = '1';
                    document.getElementById("hfaction").value = 'S';
            }
    function formupdate(<?php
foreach ($languagelist as $langcode) {
    // This language list consist of code and name of language and we just concate it with construction_type_desc which is field name from database.Means construction_type_desc_en,or ll,or ll1,or ll2,or ll3..
    ?>
    <?php echo 'circle_name_' . $langcode['mainlanguage']['language_code']; ?>,
<?php } ?> id) {
    document.getElementById("actiontype").value = '1';
            document.getElementById("hfupdateflag").value = 'Y';
<?php
foreach ($languagelist as $langcode) {
    // this again assigns value to the text boxes with concatination of languagelist array and construction_type_desc field from database
    ?>
        $('#circle_name_<?php echo $langcode['mainlanguage']['language_code']; ?>').val(circle_name_<?php echo $langcode['mainlanguage']['language_code']; ?>);
<?php } ?>
    $('#hfid').val(id);
            $('#btnadd').html('Save');
    }
</script> 
<?php echo $this->Form->create('circle', array('id' => 'circle', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lbladmcircle'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Circle/circle_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <?php
                        //  creating dyanamic text boxes using same array of config language
                        foreach ($languagelist as $key => $langcode) {
                            ?>
                            <div class="col-md-3">
                                <label><?php echo __('lbladmcircle') . " ( " . $langcode['mainlanguage']['language_name'] . ")"; ?><span style="color: #ff0000">*</span></label>    
                                <?php echo $this->Form->input('circle_name_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'circle_name_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '100')) ?>
                                <span id="<?php echo 'circle_name_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error"><?php echo $errarr['circle_name_' . $langcode['mainlanguage']['language_code'] . '_error']; ?></span>
                            </div>
                        <?php } ?>
                        <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
                    </div>
                </div>
                <div  class="rowht"></div><div  class="rowht"></div> <div  class="rowht"></div>
                <div class="row center">
                    <div class="form-group">
                        <div class="col-sm-12 tdselect">
                            <button id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-primary">

            <div class="box-body">
                
                    <table id="tablecircle" class="table table-striped table-bordered table-hover">  
                        <thead >  
                            <tr>  
                                <?php
                                //  creating dyanamic table header using same array of config language
                                foreach ($languagelist as $langcode) {
                                    ?>
                                    <th class="center"><?php echo __('lbladmcircle') . "  " . $langcode['mainlanguage']['language_name']; ?></th>
                                <?php } ?>
                                <th class="center width10"><?php echo __('lblaction'); ?></th>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php foreach ($circlerecord as $circlerecord1): ?>
                                <tr>
                                    <?php
                                    //  creating dyanamic table data(coloumns) using same array of config language
                                    foreach ($languagelist as $langcode) {
                                        ?>
                                        <td ><?php echo $circlerecord1['circle']['circle_name_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                    <?php } ?>
                                    <td >
                                        <button id="btnupdate" name="btnupdate" type="button" data-toggle="tooltip" title="Edit"  class="btn btn-default "  onclick="javascript: return formupdate(
                                        <?php
                                        //  creating dyanamic parameters  using same array of config language for sending to update function
                                        foreach ($languagelist as $langcode) {
                                            ?>
                                                            ('<?php echo $circlerecord1['circle']['circle_name_' . $langcode['mainlanguage']['language_code']]; ?>'),
                                        <?php } ?>
                                                        ('<?php echo $circlerecord1['circle']['id']; ?>'));">
                                            <span class="glyphicon glyphicon-pencil"></span>
                                        </button>
                                        <?php
                                        $newid = $this->requestAction(
                                                array('controller' => 'Masters', 'action' => 'encrypt', $circlerecord1['circle']['id'], $this->Session->read("randamkey"),
                                        ));
                                        ?>

                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'circle_delete', $newid), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php unset($circlerecord1); ?>
                        </tbody>
                    </table> 
                    <?php if (!empty($circlerecord)) { ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
              
            </div>
        </div>


    </div>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>