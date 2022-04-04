<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>


<script>
    $(document).ready(function () {
         $("#census_code_changedate").datepicker();
        var hfupdateflag = "<?php echo $hfupdateflag; ?>";

        if (hfupdateflag === 'Y')
        {
            $('#btnadd').html('Save');
        }

        if ($('#hfhidden1').val() === 'Y')
        {
            $('#tablevillagemapping1').dataTable({
                "iDisplayLength": 10,
                "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
            });
        } else {
            $('#tablevillagemapping1').dataTable({
                "iDisplayLength": 10,
                "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
            });
        }
        var actiontype = document.getElementById('actiontype').value;
        if (actiontype == '2') {
            $('.tdsave').show();
            $('.tdselect').hide();
            $('#village_name_en').focus();
        }
    });

    function formadd() {

        var division_id = $('#division_id').val();
        var state_id = $('#state_id').val();
        var taluka_id = $('#taluka_id').val();
        var subdivision_id = $('#subdivision_id').val();
        var circle_id = $('#circle_id').val();
        var ulb_type_id = $('#ulb_type_id').val();
        var village_name_en = $('#village_name_en').val();
        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        var numbers = /^[0-9]+$/;
        var Alphanum = /^(?=.*?[a-zA-Z])[0-9a-zA-Z]+$/;
        var Alphanumdot = /^(?=.*?[a-zA-Z])[0-9a-zA-Z.]+$/;
        var password = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[#,@]).{8,}/;
        var alphbets = /^[a-z A-Z ]+$/;
        var alphbetscity = /^[ A-Za-z-() ]*$/;
        var alphanumnotspace = /^[0-9a-zA-Z]+$/;
        var alphanumsapcedot = /^(?=.*?[a-zA-Z])[0-9 a-zA-Z,.\-_]+$/;

        if (division_id == '') {

            $('#division_id').focus();
            alert('Please Select Division');
            return false;
        }

        if (state_id == '') {

            $('#state_id').focus();
            alert('Please Select District');
            return false;
        }

        if (taluka_id == '') {

            $('#taluka_id').focus();
            alert('Please Select Taluka');
            return false;
        }

        if (subdivision_id == '') {

            $('#subdivision_id').focus();
            alert('Please Select Zilla Parishad');
            return false;
        }

        if (circle_id == '') {

            $('#circle_id').focus();
            alert('Please Select Block');
            return false;
        }

//        if (ulb_type_id == '') {
//
//            $('#ulb_type_id').focus();
//            alert('Please Select Coporation Class');
//            return false;
//        }

        if (!village_name_en.match(alphanumsapcedot) || village_name_en.length > 100)
        {
            $('#village_name_en').focus();
            alert('Only Alphabets and Number with max length 100 are allowed in Village Name');
            return false;
        }

        document.getElementById("actiontype").value = '1';
        document.getElementById("hfaction").value = 'S';
    }

    function formupdate(id, division_id, state_id, taluka_id, taluka_name_en, subdivision_id, circle_id, ulb_type_id, corp_id, developed_land_types_desc_id, census_code, village_name_en) {
        document.getElementById("actiontype").value = '2';
        $('#id1').val(id);
        $('#name1').val(taluka_name_en);
        $('#division_id').val(division_id);
        $('#state_id').val(state_id);
        $('#taluka_id').val(taluka_id);
        $('#subdivision_id').val(subdivision_id);
        $('#circle_id').val(circle_id);
        $('#class_description_en').val(ulb_type_id);
        $('#governingbody_name_en').val(corp_id);
        $('#developed_land_types_desc_en').val(developed_land_types_desc_id);
        $('#census_code').val(census_code);
        $('#village_name_en').val(village_name_en);
        $('#hfupdateflag').val('Y');
        $('#hfid').val(id);
        $('#btnadd').html('Save');
        return false;
    }
    function formsave() {

        var division_id = $('#division_id').val();
        var state_id = $('#state_id').val();
        var taluka_id = $('#taluka_id').val();
        var subdivision_id = $('#subdivision_id').val();
        var circle_id = $('#circle_id').val();
        var ulb_type_id = $('#ulb_type_id').val();
        var village_name_en = $('#village_name_en').val();
        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        var numbers = /^[0-9]+$/;
        var Alphanum = /^(?=.*?[a-zA-Z])[0-9a-zA-Z]+$/;
        var Alphanumdot = /^(?=.*?[a-zA-Z])[0-9a-zA-Z.]+$/;
        var password = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[#,@]).{8,}/;
        var alphbets = /^[a-z A-Z ]+$/;
        var alphbetscity = /^[ A-Za-z-() ]*$/;
        var alphanumnotspace = /^[0-9a-zA-Z]+$/;
        var alphanumsapcedot = /^(?=.*?[a-zA-Z])[0-9 a-zA-Z,.\-_]+$/;

        if (division_id == '') {

            $('#division_id').focus();
            alert('Please Select Division');
            return false;
        }

        if (state_id == '') {

            $('#state_id').focus();
            alert('Please Select District');
            return false;
        }

        if (taluka_id == '') {

            $('#taluka_id').focus();
            alert('Please Select Taluka');
            return false;
        }

        if (subdivision_id == '') {

            $('#subdivision_id').focus();
            alert('Please Select Zilla Parishad');
            return false;
        }

        if (circle_id == '') {

            $('#circle_id').focus();
            alert('Please Select Block');
            return false;
        }

//        if (ulb_type_id == '') {
//
//            $('#ulb_type_id').focus();
//            alert('Please Select Coporation Class');
//            return false;
//        }

        if (!village_name_en.match(alphanumsapcedot) || village_name_en.length > 100)
        {
            $('#village_name_en').focus();
            alert('Only Alphabets and Number with max length 100 are allowed in Village Name');
            return false;
        }
        document.getElementById("actiontype").value = '3';
    }

    function formdelete(id) {
        var result = confirm("Are you sure you want to delete this record?");
        if (result) {
            document.getElementById("actiontype").value = '4';
            document.getElementById("hfid").value = id;
            $('#id1').val(id);
        } else {
            return false;
        }
    }
</script> 


<?php echo $this->Form->create('villagemapping', array('type' => 'file', 'class' => 'villagemapping', 'autocomplete' => 'off', 'id' => 'villagemapping')); ?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblvillagemapping'); ?></h3></center>
            <div class="box-tools pull-right">
                        <a  href="<?php echo $this->webroot;?>helpfiles/Villagemapping/villagemapping_<?php echo $language; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                    </div> 
            </div>
            <div class="box-body">
                <div class="row" id="selectvillagemapping">
                    <div class="col-lg-12">
                        <table id="tablevillagemapping" class="table table-striped table-bordered table-hover">  
                            <thead >  
                                <tr>  
                                    <?php for ($i = 0; $i < count($configure); $i++) { ?>
                                        <?php if ($configure[$i][0]['is_state'] == 'Y') { ?>
                                            <th class="center"><?php echo __('lbladmstate'); ?></th><?php } ?>
                                        <?php if ($configure[$i][0]['is_div'] == 'Y') { ?>
                                            <th class="center"><?php echo __('lbladmdivision'); ?></th><?php } ?>
                                        <?php if ($configure[$i][0]['is_dist'] == 'Y') { ?>
                                            <th class="center"><?php echo __('lbladmdistrict'); ?></th><?php } ?>
                                        <?php if ($configure[$i][0]['is_zp'] == 'Y') { ?>
                                            <th class="center"><?php echo __('lblSubDivision'); ?> </th><?php } ?>
                                        <?php if ($configure[$i][0]['is_taluka'] == 'Y') { ?>
                                            <th class="center"><?php echo __('lbladmtaluka'); ?></th><?php } ?>
                                        <?php if ($configure[$i][0]['is_block'] == 'Y') { ?>
                                            <th class="center"><?php echo __('lblCircle'); ?> </th><?php } ?>
                                        <th class="center"><?php echo __('lblCorporationClass'); ?> </th>
                                        <th class="center"><?php echo __('lblcorpcouncillist'); ?> </th>
                                        <th class="center"><?php echo __('lbldellandtype'); ?></th>
                                        <th class="center"><?php echo __('lblCensusCode'); ?></th>
                                          <th class="center"><?php echo __('lbloldcencuscode'); ?></th>
                                           <th class="center"><?php echo __('lblcensuscodechagedate'); ?></th>
                                        <th class="center"><?php echo __('lbladmvillage'); ?> </th>
                                        <th class="center width10"><?php echo __('lblaction'); ?></th>
                                    <?php } ?>
                                </tr>  
                            </thead>
                            <tbody>
                                <tr>
                                    <?php for ($i = 0; $i < count($configure); $i++) { ?>
                                        <?php if ($configure[$i][0]['is_state'] == 'Y') { ?>
                                    <td class="tblbigdata"><?php echo $state; ?></td><?php } ?>
                                        <?php if ($configure[$i][0]['is_div'] == 'Y') { ?>
                                            <td class="tblbigdata"><?php echo $this->Form->input('division_id', array('options' => array($divisiondata), 'empty' => '--select--', 'id' => 'division_id', 'class' => 'form-control input-sm', 'label' => false)); ?> 
                                              <span id="division_id_error" class="form-error"><?php echo $errarr['division_id_error']; ?></span></td><?php } ?>
                                             
                                        <?php if ($configure[$i][0]['is_dist'] == 'Y') { ?>
                                            <td class="tblbigdata"><?php echo $this->Form->input('district_id', array('options' => array($districtdata), 'empty' => '--select--', 'id' => 'district_id', 'class' => 'form-control input-sm', 'label' => false)); ?> 
                                             <span id="district_id_error" class="form-error"><?php echo $errarr['district_id_error']; ?></span></td><?php } ?>
                                        
                                        <?php if ($configure[$i][0]['is_zp'] == 'Y') { ?>
                                            <td class="tblbigdata"><?php echo $this->Form->input('subdivision_id', array('options' => array($subdivisiondata), 'empty' => '--select--', 'id' => 'subdivision_id', 'class' => 'form-control input-sm', 'label' => false)); ?> 
                                            <span id="subdivision_id_error" class="form-error"><?php echo $errarr['subdivision_id_error']; ?></span></td><?php } ?>
                                                  
                                        <?php if ($configure[$i][0]['is_taluka'] == 'Y') { ?>
                                            <td class="tblbigdata"><?php echo $this->Form->input('taluka_id', array('options' => $taluka, 'empty' => '--select--', 'id' => 'taluka_id', 'class' => 'form-control input-sm', 'label' => false)); ?> 
                                            <span id="taluka_id_error" class="form-error"><?php echo $errarr['taluka_id_error']; ?></span></td><?php } ?>
                                        <?php if ($configure[$i][0]['is_block'] == 'Y') { ?>
                                            <td class="tblbigdata"><?php echo $this->Form->input('circle_id', array('options' => array($blockdata), 'empty' => '--select--', 'id' => 'circle_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                                            <span id="circle_id_error" class="form-error"><?php echo $errarr['circle_id_error']; ?></span></td><?php } ?>
                                        <td class="tblbigdata"><?php echo $this->Form->input('ulb_type_id', array('options' => array($corpclassdata), 'empty' => '--select--', 'id' => 'ulb_type_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                                        <span id="ulb_type_id_error" class="form-error"><?php echo $errarr['ulb_type_id_error']; ?></span></td>
                                        <td class="tblbigdata"><?php echo $this->Form->input('corp_id', array('options' => array($corpclasslist), 'empty' => '--select--', 'id' => 'corp_id', 'class' => 'form-control input-sm', 'label' => false)); ?>
                                        <span id="corp_id_error" class="form-error"><?php echo $errarr['corp_id_error']; ?></span></td>
                                        <td class="tblbigdata"><?php echo $this->Form->input('developed_land_types_id', array('options' => array($Developedland), 'empty' => '--select--', 'id' => 'developed_land_types_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                                        <span id="developed_land_types_id_error" class="form-error"><?php echo $errarr['developed_land_types_id_error']; ?></span></td>
                                        <td class="tblbigdata"><?php echo $this->Form->input('census_code', array('label' => false, 'id' => 'census_code', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                        <span id="census_code_error" class="form-error"><?php echo $errarr['census_code_error']; ?></span>
                                        </td>
                                         <td class="tblbigdata"><?php echo $this->Form->input('old_census_code', array('label' => false, 'id' => 'old_census_code', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                         <span id="old_census_code_error" class="form-error"><?php echo $errarr['old_census_code_error']; ?></span></td>
                                          <td class="tblbigdata"><?php echo $this->Form->input('census_code_changedate', array('label' => false, 'id' => 'census_code_changedate', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                           <span id="census_code_changedate_error" class="form-error"><?php echo $errarr['census_code_changedate_error']; ?></span></td>
                                        <td class="tblbigdata"><?php echo $this->Form->input('village_name_en', array('label' => false, 'id' => 'village_name_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                         <span id="village_name_en_error" class="form-error"><?php echo $errarr['village_name_en_error']; ?></span></td>
                                        <td class="tdselect" >
                                            <button id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?></button>
                                        </td>
                                        <td class="tdsave" hidden="true">
                                            <button id="btnadd" name="btnadd" class="btn btn-primary " onclick="javascript: return formsave();">
                                                <span class="glyphicon glyphicon-floppy-saved"></span>&nbsp;&nbsp;<?php echo __('btnsave'); ?></button>
                                        </td>
                                    <?php } ?>
                                </tr>
                            </tbody>
                        </table> 
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-primary">

            <div class="box-body">
                <div id="selectvillagemapping" class="table-responsive">
                    <table id="tablevillagemapping1" class="table table-striped table-bordered table-hover">  
                        <thead >  
                            <tr>  
                                <?php for ($i = 0; $i < count($configure); $i++) { ?>
                                    <?php if ($configure[$i][0]['is_state'] == 'Y') { ?>
                                        <th class="center"><?php echo __('lbladmstate'); ?></th><?php } ?>
                                    <?php if ($configure[$i][0]['is_div'] == 'Y') { ?>
                                        <th class="center"><?php echo __('lbladmdivision'); ?></th><?php } ?>
                                    <?php if ($configure[$i][0]['is_dist'] == 'Y') { ?>
                                        <th class="center"><?php echo __('lbladmdistrict'); ?></th><?php } ?>
                                    <?php if ($configure[$i][0]['is_zp'] == 'Y') { ?>
                                        <th class="center"><?php echo __('lblSubDivision'); ?> </th><?php } ?>
                                    <?php if ($configure[$i][0]['is_taluka'] == 'Y') { ?>
                                        <th class="center"><?php echo __('lbladmtaluka'); ?></th><?php } ?>
                                    <?php if ($configure[$i][0]['is_block'] == 'Y') { ?>
                                        <th class="center"><?php echo __('lblCircle'); ?> </th><?php } ?>
                                    <th class="center"><?php echo __('lblCorporationClass'); ?> </th>
                                    <th class="center"><?php echo __('lblcorpcouncillist'); ?>  </th>
                                    <th class="center"><?php echo __('lbldellandtype'); ?></th>
                                    <th class="center width5"><?php echo __('lblCensusCode'); ?></th>
                                     <th class="center width5"><?php echo __('lbloldcencuscode'); ?></th>
                                      <th class="center width5"><?php echo __('lblcensuscodechagedate'); ?></th>
                                    
                                    
                                    <th class="center"><?php echo __('lbladmvillage'); ?> </th> 
                                    <th class="center width10"><?php echo __('lblaction'); ?> </th>
                                <?php } ?>
                            </tr>  
                        </thead>
                        <tbody>
                            <tr>
                                <?php  for ($i = 0; $i < count($talukarecord); $i++) { ?>
                                    <?php if ($talukarecord[$i][0]['is_state'] == 'Y') { ?>
                                        <td ><?php echo $state; ?></td><?php } ?>
                                    <?php if ($talukarecord[$i][0]['is_div'] == 'Y') { ?>
                                        <td class="tblbigdata"><?php echo $talukarecord[$i][0]['division_name_en']; ?></td><?php } ?>
                                    <?php if ($talukarecord[$i][0]['is_dist'] == 'Y') { ?>
                                        <td class="tblbigdata"><?php echo $talukarecord[$i][0]['district_name_en']; ?></td><?php } ?>
                                    <?php if ($talukarecord[$i][0]['is_zp'] == 'Y') { ?>
                                        <td class="tblbigdata"><?php echo $talukarecord[$i][0]['subdivision_name_en']; ?></td><?php } ?>
                                    <?php if ($talukarecord[$i][0]['is_taluka'] == 'Y') { ?>
                                        <td class="tblbigdata"><?php echo $talukarecord[$i][0]['taluka_name_en']; ?></td><?php } ?>
                                    <?php if ($talukarecord[$i][0]['is_block'] == 'Y') { ?>
                                        <td class="tblbigdata"><?php echo $talukarecord[$i][0]['circle_name_en']; ?></td><?php } ?>
                                    <td class="tblbigdata"><?php echo $talukarecord[$i][0]['class_description_en']; ?></td>
                                    <td class="tblbigdata"><?php echo $talukarecord[$i][0]['governingbody_name_en']; ?></td>
                                    <td class="tblbigdata"><?php echo $talukarecord[$i][0]['developed_land_types_desc_en']; ?></td>
                                    <td class="tblbigdata"><?php echo $talukarecord[$i][0]['census_code']; ?></td>
                                     <td class="tblbigdata"><?php echo $talukarecord[$i][0]['old_census_code']; ?></td>
                                      <td class="tblbigdata"><?php echo $talukarecord[$i][0]['census_code_changedate']; ?></td>
                                    <td class="tblbigdata"><?php echo $talukarecord[$i][0]['village_name_en']; ?></td>
                                    <td >
                                        <button id="btnupdate<?php echo $talukarecord[$i][0]['id']; ?>" name="btnupdate" class="btn btn-default "onclick="javascript: return formupdate('<?php echo $talukarecord[$i][0]['id']; ?>', '<?php echo $talukarecord[$i][0]['division_id']; ?>', '<?php echo $talukarecord[$i][0]['state_id']; ?>',
                                                        '<?php echo $talukarecord[$i][0]['taluka_id']; ?>', '<?php echo $talukarecord[$i][0]['taluka_name_en']; ?>', '<?php echo $talukarecord[$i][0]['subdivision_id']; ?>', '<?php echo $talukarecord[$i][0]['circle_id']; ?>', '<?php echo $talukarecord[$i][0]['ulb_type_id']; ?>', '<?php echo $talukarecord[$i][0]['corp_id']; ?>', '<?php echo $talukarecord[$i][0]['developed_land_types_id']; ?>', '<?php echo $talukarecord[$i][0]['census_code']; ?>', '<?php echo $talukarecord[$i][0]['village_name_en']; ?>');">
                                            <span class="glyphicon glyphicon-pencil"></span></button>
                                        <button id="btndelete" name="btndelete" class="btn btn-default "    onclick="javascript: return formdelete(('<?php echo $talukarecord[$i][0]['id']; ?>'));">
                                            <span class="glyphicon glyphicon-remove"></span></button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table> 
                    <?php if (!empty($talukarecord)) { ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
                </div>
            </div>
        </div>


    </div>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
</div>

<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>