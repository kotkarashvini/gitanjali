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
            $('#tabledivisionnew').dataTable({
                   "order":[],
                "iDisplayLength": 10,
                "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
            });
        } else {
            $('#tabledivisionnew').dataTable({
                   "order":[],
                "iDisplayLength": 10,
                "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
            });
        }
        var actiontype = document.getElementById('actiontype').value;
        if (actiontype == '2') {
            $('.tdsave').show();
            $('.tdselect').hide();
            $('#subdivision_name_en').focus();
        }



        $('#division_id').change(function () {

            var division_id = $("#division_id option:selected").val();
            $.getJSON('getdistsubdiv', {division_id: division_id}, function (data)
            {
                var sc = '<option>select</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#district_id option").remove();
                $("#district_id").append(sc);
                $("#lbladmdistrict").show();
            });
        });
    });
</script>
<script>
    function formadd() {

        document.getElementById("actiontype").value = '1';
        document.getElementById("hfaction").value = 'S';
    }



</script> 
<?php echo $this->Form->create('subdivision', array('id' => 'subdivision1', 'autocomplete' => 'off')); ?>
<?php echo $this->element("BlockLevel/main_menu"); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="note">
            <?php echo __('lblnote'); ?>  <span style="color: #ff0000">*</span> <?php echo __('lblstarmandatorynote'); ?>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lbladmsubdiv'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a href="<?php echo $this->webroot; ?>helpfiles/Subdivision/subdivision_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>

            <div class="box-body">

                <div class="row" style="padding-left: 35px;">
                    <?php
                    for ($i = 0; $i < count($configure); $i++) {
                        if ($configure[$i][0]['is_div'] == 'Y') {
                            ?>
                            <div class="col-sm-2">
                                <label for="division_id" class="control-label"><?php echo __('lbladmdivision'); ?> <span class="star">*</span></label>
                                <?php echo $this->Form->input('division_id', array('options' => $divisiondata, 'empty' => '--select--', 'id' => 'division_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                                <span class="form-error" id="division_id_error"></span>
                            </div>
                        <?php } else { ?>
                            <div class="col-sm-2">
                                <label for="district_id" class="control-label"><?php echo __('lbladmdistrict'); ?> <span class="star">*</span></label>
                                <?php echo $this->Form->input('district_id', array('options' => $districtdata, 'empty' => '--select--', 'id' => 'district_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                                <span class="form-error" id="district_id_error"></span>
                            </div>
                            <?php
                        }
                    }
                    ?>

                    <?php
                    for ($i = 0; $i < count($configure); $i++) {
                        if ($configure[$i][0]['is_div'] == 'Y') {
                            ?>
                            <div class="col-sm-2">
                                <label for="district_id" class="control-label"><?php echo __('lbladmdistrict'); ?> <span class="star">*</span></label>
                                <?php echo $this->Form->input('district_id', array('options' => $districtdata, 'empty' => '--select--', 'id' => 'district_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                                <span class="form-error" id="district_id_error"></span>
                            </div>
                            <?php
                        }
                    }
                    ?>

                </div> </div>





            <div class="box-body">
                <div class="col-lg-12">


                    <?php
                    foreach ($languagelist as $key => $langcode) {
                        ?>
                        <div class="col-md-3">
                            <label><?php echo __('lbladmsubdiv') . "  (" . $langcode['mainlanguage']['language_name'] . ")"; ?>
                                <span style="color: #ff0000">*</span>
                            </label>    
                            <?php echo $this->Form->input('subdivision_name_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'subdivision_name_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '100')) ?>
                            <span id="<?php echo 'subdivision_name_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error">
                                <?php echo $errarr['subdivision_name_' . $langcode['mainlanguage']['language_code'] . '_error']; ?>
                            </span>
                        </div>
                    <?php } ?>
                    <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
                </div>

            </div>

            <div class="row" style="padding-left: 32px;">
                <div class="form-group">
                    <div class="col-lg-12">
                        <div class="col-md-3">
                            <label><?php echo __('lblsubdsrocode'); ?>
                                <span style="color: #ff0000">*</span>
                            </label>    
                            <?php echo $this->Form->input('dsro_code', array('label' => false, 'id' => 'dsro_code', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '100')) ?>
                            <span id="<?php echo 'dsro_code_error'; ?>" class="form-error">
                                <?php echo $errarr['dsro_code_error']; ?>
                            </span>
                        </div>


                    </div>


                </div>
            </div>
            <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
            <div class="row center">
                <div class="form-group">
                    <div class="col-sm-12 tdselect">

                        <?php if (isset($editflag)) { ?>
                            <button id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnupdate'); ?>
                            </button>
                        <?php } else { ?>
                            <button id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnsave'); ?>
                            </button>
                        <?php } ?>

                        <a href="<?php echo $this->webroot; ?>BlockLevels/subdivision" class="btn btn-info "><?php echo __('btncancel'); ?></a>


                    </div>
                </div>
            </div>
            <div  class="rowht">&nbsp;</div>
        </div>

        <div class="box box-primary">

            <div class="box-body">

                <table id="tabledivisionnew" class="table table-striped table-bordered table-hover">  
                    <thead >  
                        <tr>  
                             <th class="center"><?php echo __('lblsubdsrocode'); ?></th>
                            <?php foreach ($languagelist as $langcode) { ?>
                                <th class="center"><?php echo __('lbladmsubdiv') . "  (" . $langcode['mainlanguage']['language_name'] . ")"; ?></th>

                            <?php } ?>
                           
<!--                                      <th class="center"><?php //echo 'District Name';    ?></th>-->
                            <th class="center width10"><?php echo __('lblaction'); ?></th>
                        </tr>

                    </thead>
                    <tbody>
                        <?php
                        //pr($subdivisionrecord);
                        foreach ($subdivisionrecord as $subdivisionrecord1):
                            ?>
                            <tr>
                                 <th class="center"><?php echo $subdivisionrecord1['Subdivision']['dsro_code']; ?></th>
                                <?php
                                foreach ($languagelist as $langcode) {
                                    ?>
                                    <td ><?php echo $subdivisionrecord1['Subdivision']['subdivision_name_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                <?php } ?>
                               
    <!--                                          <th class="center"><?php //echo $subdivisionrecord1['subdivision']['district_id'];   ?></th>-->
                                <td >

                                    <?php
                                    $newid = $this->requestAction(
                                            array('controller' => 'BlockLevels', 'action' => 'encrypt', $subdivisionrecord1['Subdivision']['subdivision_id'], $this->Session->read("randamkey"),
                                    ));
                                    ?>
                                    <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-pencil')), array('action' => 'subdivision', $subdivisionrecord1['Subdivision']['subdivision_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Edit'), 'class' => "btn-sm btn-success"), array('Are you sure to Edit?')); ?></a>
    
                                    <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'subdivision_delete', $newid), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn-sm btn-danger"), array('Are you sure to Delete?')); ?></a>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                        <?php unset($districtrecord1); ?>
                    </tbody>
                </table>
                <?php if (!empty($districtrecord)) { ?>
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

