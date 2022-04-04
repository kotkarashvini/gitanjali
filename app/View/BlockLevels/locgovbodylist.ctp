<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>

<script type="text/javascript">
    $(document).ready(function () {

        if (document.getElementById('hfhidden1').value == 'Y') {
            $('#divfees_items').slideDown(1000);
        }
        else {
            $('#divfees_items').hide();
        }
        $('#tablefees_items').dataTable({
               "order":[],
            "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
        $('input[type=radio][name=is_boolean]').change(function () {
            if (this.value == 'Y') {
                $('#boolyes').show();
                $('#boolno').hide();
                $('#info_value').val('');
            }
            else if (this.value == 'N') {
                $('#boolno').show();
                $('#boolyes').hide();
                $('input[name="conf_bool_value"]').prop('checked', false);
            }
            else {
                $('#boolyes').hide();
                $('#boolno').hide();
            }
        });
        $('#division_id').change(function () {

            var division_id = $("#division_id option:selected").val();
            $.getJSON('<?php echo $this->webroot; ?>BlockLevels/getdistsubdiv', {division_id: division_id}, function (data)
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
    function formadd() {
        document.getElementById("actiontype").value = '1';
//        var boolval=$("input[name=conf_bool_value]:checked").val();
//        var infoval=$('#info_value').val();
//        alert(boolval); alert(infoval);
//        if($('#hfupdateflag').val=='Y' && boolval!=''){
//            $('#info_value').val('');
//        }
//        if($('#hfupdateflag').val=='Y' && infoval!=''){
//            $('input[name="conf_bool_value"]').prop('checked', false);
//            alert($("input[name=conf_bool_value]:checked").val());
//        }
    }

    function forcancel() {
        document.getElementById("actiontype").value = '2';
    }




</script>

<?php echo $this->Form->create('locgovbodylist', array('id' => 'locgovbodylist', 'autocomplete' => 'off')); ?>
<?php echo $this->element("BlockLevel/main_menu"); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="note">
            <?php echo __('lblnote'); ?>  <span style="color: #ff0000">*</span> <?php echo __('lblstarmandatorynote'); ?>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lbllocalgovbodylist'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/LocalGoverningBodyList/locgovbodylist_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">

                    <?php
                    if ($adminLevelConfig['adminLevelConfig']['is_div'] == 'Y') {
                        ?>
                        <div class="col-sm-2">
                            <label for="division_id" class="control-label"><?php echo __('lbladmdivision'); ?> <span class="star">*</span></label>
                            <?php echo $this->Form->input('division_id', array('options' => $divisiondata, 'empty' => '--select--', 'id' => 'division_id', 'class' => 'form-control input-sm ', 'label' => false)); ?>
                            <span class="form-error" id="division_id_error"></span>
                        </div>
                    <?php } ?>

                    <div class="col-sm-2">
                        <label for="district_id" class="control-label"><?php echo __('lbladmdistrict'); ?> <span class="star">*</span></label>
                        <?php echo $this->Form->input('district_id', array('options' => $districtdata, 'empty' => '--select--', 'id' => 'district_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                        <span class="form-error" id="district_id_error"></span>
                    </div>




                </div>
                <div  class="rowht"></div>
                <div class="row">
                    <div class="col-sm-3">
                        <label for="ulb_type_id" class="control-label " ><?php echo __('lblgovbodyname'); ?><span style="color: #ff0000">*</span></label>  
                        <?php echo $this->Form->input('ulb_type_id', array('label' => false, 'id' => 'ulb_type_id', 'class' => 'form-control input-sm', 'options' => array($corpclassdata), 'empty' => '--Select--')); ?>
                        <span id="ulb_type_id_error" class="form-error"></span>  
                    </div>    
                    <div class="col-sm-3">
                        <label for="class_type" class="control-label"><?php echo __('lblclasstype'); ?><span style="color: #ff0000">*</span></label>  
                        <?php echo $this->Form->input('class_type', array('label' => false, 'id' => 'class_type', 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => "1")); ?>
                        <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
                        <?php echo $this->Form->input('corp_id', array('label' => false, 'id' => 'corp_id', 'class' => 'form-control input-sm', 'type' => 'hidden')); ?>
                        <span id="class_type_error" class="form-error"></span>
                    </div>

                </div>
                <div class="row">
                    <div class="form-group">
                        <?php
                        //creating dyanamic text boxes using same array of config language
                        foreach ($languagelist as $key => $langcode) {
                            ?>
                            <div class="col-md-3">
                                <label><?php echo __('lbllocalgoberningbody') . "  " . $langcode['mainlanguage']['language_name']; ?>
                                    <span style="color: #ff0000">*</span>
                                </label>   

                                <?php echo $this->Form->input('governingbody_name_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'governingbody_name_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => "100")) ?>
                                <span id="<?php echo 'governingbody_name_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error">

                                </span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                

                <div  class="rowht"></div><div  class="rowht"></div><div  class="rowht"></div>
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

                            <a href="<?php echo $this->webroot; ?>BlockLevels/locgovbodylist" class="btn btn-info "><?php echo __('btncancel'); ?></a>

                        </div>
                    </div>
                </div>



            </div>
        </div>




    </div>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
</div>

<div class="row">
    <div class="col-lg-12"> 

        <div class="box box-primary">

            <div class="box-body" id="">

                <table id="tablefees_items" class="table table-striped table-bordered table-hover">  
                    <thead >  
                        <tr>  

                            <?php
                            foreach ($languagelist as $key => $langcode) {
                                //  pr($langcode);
                                ?>
                                <th class="center"><?php echo __('lblgovbodylistname'); ?> [<?php echo $langcode['mainlanguage']['language_name']; ?>]</th>
                            <?php } ?>
                            <th class="center"><?php echo __('lblgovbodyname'); ?></th>
                            <th class="center"><?php echo __('lblclasstype'); ?></th>
                            <th class="center width10"><?php echo __('lblaction'); ?></th>
                        </tr>  
                    </thead>

                    <?php for ($i = 0; $i < count($locgovbodylist); $i++) { ?>
                        <tr>

                            <?php foreach ($languagelist as $key => $langcode) { ?>
                                <td><?php echo $locgovbodylist[$i][0]['governingbody_name_' . $langcode['mainlanguage']['language_code']]; ?></td>
                            <?php } ?>
                            <td ><?php echo $locgovbodylist[$i][0]['class_description_' . $laug]; ?></td>
                            <td ><?php echo $locgovbodylist[$i][0]['class_type']; ?></td>
                            <td >

                                <?php
                                $newid = $this->requestAction(
                                        array('controller' => 'BlockLevels', 'action' => 'encrypt', $locgovbodylist[$i][0]['corp_id'], $this->Session->read("randamkey"),
                                ));
                                ?>
                                <!--<a href="<?php // echo $this->webroot; ?>BlockLevels/locgovbodylist/<?php // echo $locgovbodylist[$i][0]['corp_id']; ?>" class="btn-sm btn-default"><span class="fa fa-pencil"></span> </a>-->    
                                
                                <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'fa fa-pencil')), array('action' => 'locgovbodylist', $locgovbodylist[$i][0]['corp_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Edit'), 'class' => "btn btn-success"), array('Are you sure to Edit?')); ?></a>
                                <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'locgovbodylist_delete', $newid), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-danger"), array('Are you sure to Delete?')); ?></a>

                            </td>
                        </tr>
                    <?php } ?>
                </table> 
                <?php if (!empty($locgovbodylist)) { ?>
                    <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                    <input type="hidden" value="N" id="hfhidden1"/><?php } ?>

            </div>
        </div>

    </div>
</div>

<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>




