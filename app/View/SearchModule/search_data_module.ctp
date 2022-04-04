<script>
    $(document).ready(function () {
        $('#tableparty').dataTable({
            "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });

        $('#divbyparty').hide();
        $('#divbyproperty').hide();
        $('#divbydeed').hide();
        
        $('#Search').change(function () {
            var Search = $("#Search option:selected").val();
           // alert(Search);
            $('#action').val('');
            if (Search == '1') {
                $('#divbyparty').show();
                $('#divbyproperty').hide();
                $('#divbydeed').hide();
            } else if (Search == '2') {
                $('#divbyproperty').show();
                $('#divbyparty').hide();
                $('#divbydeed').hide();
            } else if (Search == '3') {
                $('#divbydeed').show();
                $('#divbyparty').hide();
                $('#divbyproperty').hide();
            } else {
                $('#divbyparty').hide();
                $('#divbyproperty').hide();
                $('#divbydeed').hide();
            }

        })
        

        $('#article_id').change(function () {
            var article_id = $("#article_id option:selected").val();
            $.getJSON('<?php echo $this->webroot; ?>SearchModule/get_party_type', {article_id: article_id}, function (data)
            {
                var sc = '<option value="">--Select--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#party_type_id option").remove();
                $("#party_type_id").append(sc);
            });
        })

        $('#taluka_id').change(function () {
            var taluka_id = $("#taluka_id option:selected").val();
            $.getJSON('<?php echo $this->webroot; ?>SearchModule/get_village', {taluka_id: taluka_id}, function (data)
            {
                var sc = '<option value="">--Select--</option>';
                $.each(data, function (index, val) {
                    sc += "<option value=" + index + ">" + val + "</option>";
                });
                $("#village_id option").remove();
                $("#village_id").append(sc);
            });
        })

        var action = $('#action').val();
        if (action == '1') {
           
            $('#divbyparty').show();
            $('#divbyproperty').hide();
            $('#divbydeed').hide();
        }
        if (action == '2') {
           
            $('#divbyparty').hide();
            $('#divbyproperty').show();
            $('#divbydeed').hide();
        }
        if (action == '3') {
           
            $('#divbyparty').hide();
            $('#divbyproperty').hide();
            $('#divbydeed').show();
        }
    });

    function search1() {
        $('#action').val("1");
        $('#search_data_module').submit();
    }

    function search2() {
        $('#action').val("2");
        $('#search_data_module').submit();
    }

    function search3() {
        $('#action').val("3");
        $('#search_data_module').submit();
    }

        function details(refno,regno,regdt,procyear) {
        //var from = $('#hffromyear').val();
        //var to = $('#hftoyear').val();
        //alert(refno);
        //alert(regno);
       // alert(regdt);
        //alert(procyear);
        $.post('<?php echo $this->webroot; ?>SearchModule/viewrptdetails', {refno: refno, regno: regno, regdt: regdt, procyear: procyear}, function (data)
        {
            $("#rptdetails").html('');
            //$("#downdeed").html('');
             $('#btnpay').hide();
             // pr(data);
            $("#rptdetails").html(data);

        });
//             $('#myModal').html('');
//             $('#myModal').modal('show');
        return false;
    }

    function Certifiedcopy(doc_reg_no) {
    //alert(doc_reg_no);

        $.post('<?php echo $this->webroot; ?>SearchModule/cer_receipt', {doc_reg_no: doc_reg_no}, function (data)
        {
            var data = $.parseJSON(data);
            console.log(data);

            var design = data['html_design'];
            var deed = data['deed'];
           // alert(design);
           // alert(deed);

            $("#rptdetails").html('');
            $("#rptdetails").html(design);
            $("#downdeed").html('');
            $("#downdeed").html(deed);
           // $('#btnpay').show();


            /*var design = data['html_design'];
            var deed = data['deed'];
            console.log(design)
            $("#rptdetails").html('');
            $("#rptdetails").html(design);
            $("#downdeed").html('');
            $("#downdeed").html(deed);
            //$('#btnpay').show();
            
            if(deed != ''){
                $('#btnpay').hide();
            }
            */
        });
//             $('#myModal').html('');
//             $('#myModal').modal('show');
        return false;

    }


    function nonemcomb_copy(doc_reg_no) {
       // var from = $('#hffromyear').val();
       // var to = $('#hftoyear').val();
        $.post('<?php echo $this->webroot; ?>SearchModule/nonem_comb_cer', {doc_reg_no: doc_reg_no}, function (data)
        {
            $("#rptdetails").html('');
            $("#downdeed").html('');
             $('#btnpay').hide();
            $("#rptdetails").html(data);

        });

        return false;
    }
</script>
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<?php echo $this->Form->create('search_data_module', array('id' => 'search_data_module'));

 ?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title" style="font-weight: bolder">Search</h3></center>
            </div>
            <div class="box-body"><BR>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-3"></div>
                        <label for="district_id" class="col-sm-2 control-label"><?php echo __('Search By'); ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-3">
						
                            <?php echo $this->Form->input('Search', array('options' => array($Search), 'empty' => '--select--', 'id' => 'Search', 'label' => false, 'class' => 'form-control input-sm')); ?>
                            <span  id="Search_error" class="form-error"><?php echo $errarr['Search_error']; ?></span>
                        </div>
                    </div>
                </div>
            </div><br>
        </div>
    </div>
</div>
<div class="row" id="divbyparty">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-body"><BR>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-1"></div>
                        <label for="district_id" class="col-sm-2 control-label"><?php echo __('Name Of Party'); ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('party_name', array('label' => false, 'id' => 'party_name', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span  id="party_name_error" class="form-error"><?php echo $errarr['Search_error']; ?></span>


                        </div>
                        <label for="district_id" class="col-sm-2 control-label"><?php echo __('Father`s/Husband`s Name'); ?></label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('father_name', array('label' => false, 'id' => 'father_name', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span  id="father_name_error" class="form-error"><?php echo $errarr['father_name_error']; ?></span>

                        </div>
                    </div>
                </div>
                <BR>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-1"></div>

                        <label for="district_id" class="col-sm-2 control-label">Search For Years<span style="color: #ff0000">*</span></label>
                        <div class="col-sm-3" style="padding-left: 0px;">
                            <div class="col-sm-5">
                                <?php
                                //pr($year);
                                //echo $this->Form->input('fromyear', array('options' => array($year), 'empty' => '--select--', 'id' => 'fromyear', 'label' => false, 'class' => 'form-control input-sm'));                                 
                                echo $this->Form->input('fromyear', array('label' => false, 'id' => 'fromyear', 'class' => 'form-control input-sm', 'type' => 'text', 'value' => $fromyearset, 'readonly' => 'readonly')) ?>
                                <span  id="fromyear_error" class="form-error"><?php echo $errarr['fromyear_error']; ?></span>

                            </div>
                            <label for="district_id" class="col-sm-2 control-label" style="text-align: center;">To</label>
                            <div class="col-sm-5">
                                <?php 
                                //echo $this->Form->input('toyear', array('options' => array($year), 'empty' => '--select--', 'id' => 'toyear', 'label' => false, 'class' => 'form-control input-sm'));
                                echo $this->Form->input('toyear', array('label' => false, 'id' => 'toyear', 'class' => 'form-control input-sm', 'type' => 'text', 'value' => $toyearset, 'readonly' => 'readonly')) ?>
                                <span  id="toyear_error" class="form-error"><?php echo $errarr['toyear_error']; ?></span>

                            </div>
                        </div>
                        <!--<div class="col-sm-3">
                            <?php echo $this->Html->link('Change Search Years', array('controller' => 'Search', 'action' => 'searchindex'), array('escape' => false)); ?>
                        </div>-->
                    </div>
                </div><br>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-1"></div>
                        <label for="district_id" class="col-sm-2 control-label"><?php echo __('Deed Type'); ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('article_id', array('options' => array($article), 'empty' => '--select--', 'id' => 'article_id', 'label' => false, 'class' => 'form-control input-sm')); ?>

                            <span  id="article_id_error" class="form-error"><?php echo $errarr['article_id_error']; ?></span>
                        </div>
                        <label for="district_id" class="col-sm-2 control-label"><?php echo __('Party Type'); ?></label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('party_type_id', array('options' => array($partytype), 'empty' => 'ALL', 'id' => 'party_type_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                            <!--<span  id="party_type_id_error" class="form-error"><?php //echo $errarr['party_type_id_error'];  ?></span>-->

                        </div>
                    </div>
                </div><br><br>
                <div class="row" style="text-align: center">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <button id="btnsearch1" name="btnsearch1" class="btn btn-info" style="text-align: center;"  onclick="javascript: return search1((''));">
                                Search </button>&nbsp;&nbsp;&nbsp;&nbsp;
                        </div>
                    </div>
                </div>

                <?php
               // pr($partygrid);
               // pr($action);
                if (!empty($partygrid) && ($action == 1)) {
                    
                    ?>
                    <table id="tableparty" class="table table-striped table-bordered table-hover table-responsive">  
                        <thead >  
                            <tr>
                                <th class="width10 center"><?php echo __('lblaction'); ?></th>
                                <th class="center"><?php echo __('Year'); ?></th>
                                <th class="width10 center"><?php echo __('Deed No.'); ?></th>
                                <th class="width10 center"><?php echo __('Party Name'); ?></th>
                                <th class="width10 center"><?php echo __('Father Name'); ?></th>
                                <th class="width10 center"><?php echo __('Resident Address'); ?></th>
                                <th class="width10 center"><?php echo __('Status'); ?></th>
                                <th class="width10 center"><?php echo __('Book No.'); ?></th>
                                <th class="width10 center"><?php echo __('Vol No.'); ?></th>
                                <th class="width10 center"><?php echo __('PFrom'); ?></th>
                                <th class="width10 center"><?php echo __('PTo'); ?></th>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php foreach ($partygrid as $partygrid1):
                               // pr($partygrid1);
                             ?>
                                <tr>
                                    <td class="width10 center">
                                    <div class="dropdown">
                                            <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Select For &nbsp;<span class="caret"></span>
                                            </button> 
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                
                                                    <li><a href="#" data-toggle="modal" data-target="#myModal" onclick="javascript:details('<?php echo $partygrid1[0]['reference_sr_no'];?>','<?php echo $partygrid1[0]['doc_reg_no'];?>','<?php echo $partygrid1[0]['doc_reg_date'];?>','<?php echo $partygrid1[0]['doc_processing_year'];?>');" >Details</a></li> 
                                                <li class="divider"></li>
                                                <li><a href="#" data-toggle="modal" data-target="#myModal" onclick="javascript: return Certifiedcopy('<?php echo $partygrid1[0]['doc_reg_no']; ?>');" >Certified Copy</a></li> 
                                            </ul>
                                    </div>
                                    </td>
                                    <td ><?php echo $partygrid1[0]['doc_processing_year']; ?></td>
                                    <td ><?php echo $partygrid1[0]['doc_reg_no']; ?></td>
                                    <td ><?php echo $partygrid1[0]['party_full_name_en']; ?></td>
                                    <td ><?php echo $partygrid1[0]['father_full_name_en']; ?></td>
                                    <td ><?php  ?></td>
                                    <td ><?php echo $partygrid1[0]['party_type_desc_en']; ?></td>
                                    <td ><?php //echo $partygrid1[0]['book_number']; ?></td>
                                    <td ><?php //echo $partygrid1[0]['volume_number']; ?></td>
                                    <td ><?php //echo $partygrid1[0]['page_number_start']; ?></td>
                                    <td ><?php //echo $partygrid1[0]['page_number_end']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>  
                    </table> 
                <?php } else if (empty($partygrid) && ($action == 1)) { ?>

                    <div class="row center">
                        <div class="form-group col-sm-12" > 
                            <div class="col-sm-12"><h2 style="color: red">Record Not Found...!!!!!</h2></div>
                        </div>           
                    </div>
                <?php } ?>
            </div><BR>
        </div>
    </div>
</div>



<div class="row" id="divbyproperty">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-body"><BR>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-1"></div>
                        <label for="district_id" class="col-sm-2 control-label"><?php echo __('Anchal Name'); ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('taluka_id', array('options' => array($taluka), 'empty' => '--select--', 'id' => 'taluka_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                            <span  id="taluka_id_error" class="form-error"><?php echo $errarr['taluka_id_error']; ?></span>
                        </div>
                        <label for="district_id" class="col-sm-2 control-label"><?php echo __('Mauza/Thana No.'); ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('village_id', array('options' => array($village), 'id' => 'village_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                            <span  id="village_id_error" class="form-error"><?php echo $errarr['village_id_error']; ?></span>

                        </div>
                    </div>
                </div><br>
                <div class="row">
                    <div class="form-group">
                         <div class="col-sm-1"></div>
                        <label for="district_id" class="col-sm-2 control-label"><?php echo __('Plot No.'); ?></label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('plot_no', array('label' => false, 'id' => 'plot_no', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span  id="plot_no_error" class="form-error"><?php echo $errarr['plot_no_error']; ?></span>

                        </div>
                    </div>
                </div>
                <BR>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-1"></div>

                        <label for="district_id" class="col-sm-2 control-label">Search For Years<span style="color: #ff0000">*</span></label>
                        <div class="col-sm-3" style="padding-left: 0px;">
                            <div class="col-sm-5">
                                <?php 
                                 //  pr($year);
                                echo $this->Form->input('fromyear1', array('label' => false, 'id' => 'fromyear1', 'class' => 'form-control input-sm', 'type' => 'text', 'value' => $fromyearset, 'readonly' => 'readonly')); 
                                //echo $this->Form->input('fromyear1', array('options' => array($year), 'empty' => '--select--', 'id' => 'fromyear1', 'label' => false, 'class' => 'form-control input-sm')); ?>
                                <span  id="fromyear1_error" class="form-error"><?php echo $errarr['fromyear1_error']; ?></span>

                            </div>
                            <label for="district_id" class="col-sm-2 control-label" style="text-align: center;">To</label>
                            <div class="col-sm-5">
                                <?php
                                 echo $this->Form->input('toyear1', array('label' => false, 'id' => 'toyear1', 'class' => 'form-control input-sm', 'type' => 'text', 'value' => $toyearset, 'readonly' => 'readonly'));
                               // echo $this->Form->input('toyear1', array('options' => array($year), 'empty' => '--select--', 'id' => 'toyear1', 'label' => false, 'class' => 'form-control input-sm'));
                                 ?>
                                <span  id="toyear1_error" class="form-error"><?php echo $errarr['toyear1_error']; ?></span>
                            </div>
                        </div>
                        
                    </div>
                </div><br>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-1"></div>
                        <label for="district_id" class="col-sm-2 control-label"><?php echo __('Deed Type'); ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('article_id1', array('options' => array($article), 'empty' => '--select--', 'id' => 'article_id1', 'label' => false, 'class' => 'form-control input-sm')); ?>
                            <span  id="article_id1_error" class="form-error"><?php echo $errarr['article_id1_error'];  ?></span>
                        </div>
                    </div>
                </div><br><br>
                <div class="row" style="text-align: center">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <button id="btnsearch2" name="btnsearch2" class="btn btn-info" style="text-align: center;"  onclick="javascript: return search2((''));">
                                Search </button>&nbsp;&nbsp;&nbsp;&nbsp;
                        </div>
                    </div>
                </div>
                <?php
                    if (!empty($propertygrid) && ($action == 2)) {
                    
                    //pr($propertygrid);
                    //pr(array_unique($propertygrid));
                    $pids = array();
                    foreach ($propertygrid as $propertygrid1) {
                        $pids[] = $propertygrid1[0]['property_sr_no'];
                        
                        //$pids[] = $h['pid'];
                    }
                   // pr($pids);
                    $uniquePids = array_unique($pids);
                   // pr($uniquePids);
                    foreach ($uniquePids as $uniquePids_two){
                           // pr($uniquePids_two);
                            
                    }
                    ?>
                    <table id="tableparty" class="table table-striped table-bordered table-hover table-responsive">  
                        <thead >  
                            <tr>
                                <th class="width10 center"><?php echo __('lblaction'); ?></th>
                                <th class="center"><?php echo __('Year'); ?></th>
                                <th class="width10 center"><?php echo __('Deed No.'); ?></th>
                                <th class="width10 center"><?php echo __('Property'); ?></th>
                                <th class="width10 center"><?php echo __('District'); ?></th>
                                <th class="width10 center"><?php echo __('Deed Type'); ?></th>
                                <th class="width10 center"><?php echo __('Registred At'); ?></th>
                                <th class="width10 center"><?php echo __('Book No.'); ?></th>
                                <th class="width10 center"><?php echo __('Vol No.'); ?></th>
                                <th class="width10 center"><?php echo __('PFrom'); ?></th>
                                <th class="width10 center"><?php echo __('PTo'); ?></th>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php foreach ($propertygrid as $propertygrid1):
                              //  pr($propertygrid1);
                           // pr($data_to_disp_second);
//pr(sizeof($data_to_disp_second));
                             ?>
                                <tr>
                                    <td class="width10 center">
                                    <div class="dropdown">
                                            <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Select For &nbsp;<span class="caret"></span>
                                            </button> 
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                
                                                    <li><a href="#" data-toggle="modal" data-target="#myModal" onclick="javascript:details('<?php echo $propertygrid1[0]['reference_sr_no'];?>','<?php echo $propertygrid1[0]['doc_reg_no'];?>','<?php echo $propertygrid1[0]['doc_reg_date'];?>','<?php echo $propertygrid1[0]['doc_processing_year'];?>');" >Details</a></li> 
                                            <li class="divider"></li>
                                                <li><a href="#" data-toggle="modal" data-target="#myModal" onclick="javascript: return nonemcomb_copy('<?php echo $propertygrid1[0]['doc_reg_no']; ?>');" >Non-Encumbrance Certificate</a></li>    
                                             <li class="divider"></li>
                                                <li><a href="#" data-toggle="modal" data-target="#myModal" onclick="javascript: return Certifiedcopy('<?php echo $propertygrid1[0]['doc_reg_no']; ?>');" >Certified Copy</a></li> 
                                            </ul>
                                    </div>
                                    </td>
                                    <td ><?php echo $propertygrid1[0]['doc_processing_year']; ?></td>
                                    <td ><?php echo $propertygrid1[0]['doc_reg_no']; ?></td>
                                    <td ><?php
                                       
                                     
                                     for($a=0;$a<sizeof($data_to_disp_second_arr_docno);$a++)
                                     {
                                            //pr($data_to_disp_second_arr_docno);
                                            for($b=0;$b<sizeof($data_to_disp_second_arr_docno);$b++)
                                            {
                                                if($data_to_disp_second_arr_docno[$a][$b]==$propertygrid1[0]['doc_reg_no'])
                                                    echo $data_to_disp_second_arr_attr_nm[$a][$b].' : '.$data_to_disp_second_arr_para_val[$a][$b].'&nbsp;&nbsp;';
                                                    //echo '<br>';
                                            } 
                                     }
                                    // pr(sizeof($data_to_disp_third_arr_docno));
                                   // pr($data_to_disp_third_arr_docno);
                                     for($c=0;$c<sizeof($data_to_disp_third_arr_docno);$c++){
                                            for($d=0;$d<sizeof($data_to_disp_third_arr_docno);$d++){
                                                //echo 'c:'.$c;
                                                //echo '<br>d:'.$d;
                                                if(isset($data_to_disp_third_arr_docno[$c][$d]))
                                                {
                                                    if($data_to_disp_third_arr_docno[$c][$d]==$propertygrid1[0]['doc_reg_no'])
                                                        echo '<br>Area : '.$data_to_disp_third_item_value[$c][$d].' '.$data_to_disp_third_unit_desc_en[$c][$d];
                                                }
                                            }
                                    }
                                     ?></td>
                                    <td ><?php echo $propertygrid1[0]['district_name_en']; ?></td>
                                    <td ><?php  echo $propertygrid1[0]['article_desc_en'];  ?></td>
                                    <td ><?php echo $propertygrid1[0]['office_name_en']; ?></td>
                                    <td ><?php //echo $propertygrid1[0]['book_number']; ?></td>
                                    <td ><?php //echo $propertygrid1[0]['volume_number']; ?></td>
                                    <td ><?php //echo $propertygrid1[0]['page_number_start']; ?></td>
                                    <td ><?php //echo $propertygrid1[0]['page_number_end']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>  
                        </table>
                <?php
                    }
                ?>
            </div><BR>
        </div>
    </div>
</div>




<div class="row" id="divbydeed">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-body"><BR>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-1"></div>
                        <label for="district_id" class="col-sm-2 control-label"><?php echo __('Deed No.'); ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-3">
                        <?php echo $this->Form->input('deed_no', array('label' => false, 'id' => 'deed_no', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span  id="deed_no_error" class="form-error"><?php echo $errarr['toyear_error']; ?></span>
                        </div>

                        <div class="col-sm-2">
                            <button id="btnsearch3" name="btnsearch3" class="btn btn-info" style="text-align: center;"  onclick="javascript: return search3((''));">
                                Search </button>&nbsp;&nbsp;&nbsp;&nbsp;
                        </div>
                    </div>
                </div>
                <br><br>
               
                
                <?php
                if (!empty($deedgrid) && ($action == 3)) {
                    
                    ?>
                   <table id="tableparty" class="table table-striped table-bordered table-hover table-responsive">  
                        <thead >  
                            <tr>
                                <th class="width10 center"><?php echo __('lblaction'); ?></th>
                                <!--<th class="center">&nbsp;</th>-->
                                <th class="center"><?php echo __('Year'); ?></th>
                                <th class="width10 center"><?php echo __('Deed No.'); ?></th>
                                <th class="width10 center"><?php echo __('Property'); ?></th>
                                <th class="width10 center"><?php echo __('District'); ?></th>
                                <th class="width10 center"><?php echo __('Deed Type'); ?></th>
                                <th class="width10 center"><?php echo __('Registred At'); ?></th>
                                <th class="width10 center"><?php echo __('Book No.'); ?></th>
                                <th class="width10 center"><?php echo __('Vol No.'); ?></th>
                                <th class="width10 center"><?php echo __('PFrom'); ?></th>
                                <th class="width10 center"><?php echo __('PTo'); ?></th>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php foreach ($deedgrid as $deedgrid1):
                              //  pr($deedgrid1);
                           // pr($data_to_disp_second);
//pr(sizeof($data_to_disp_second));
                             ?>
                                <tr>
                                    <td class="width10 center">
                                    <div class="dropdown">
                                            <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Select For &nbsp;<span class="caret"></span>
                                            </button> 
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                
                                                    <li><a href="#" data-toggle="modal" data-target="#myModal" onclick="javascript:details('<?php echo $deedgrid1[0]['reference_sr_no'];?>','<?php echo $deedgrid1[0]['doc_reg_no'];?>','<?php echo $deedgrid1[0]['doc_reg_date'];?>','<?php echo $deedgrid1[0]['doc_processing_year'];?>');" >Details</a></li> 
                                                <li class="divider"></li>
                                                <li><a href="#" data-toggle="modal" data-target="#myModal" onclick="javascript: return Certifiedcopy('<?php echo $deedgrid1[0]['doc_reg_no']; ?>');" >Certified Copy</a></li> 
                                            </ul>
                                    </div>
                                    </td>
                                    <!--<td>
                                    <?php echo $this->Html->link("Create Challan", array('controller' => 'SearchModule', 'action' => 'pay_for_cert', $deedgrid1[0]['doc_reg_no'], $this->Session->read('csrftoken'))); ?>
                                    </td>-->
                                    <td ><?php echo $deedgrid1[0]['doc_processing_year']; ?></td>
                                    <td ><?php echo $deedgrid1[0]['doc_reg_no']; ?></td>
                                    <td ><?php
                                       
                                     
                                     for($a=0;$a<sizeof($data_to_disp_second_arr_docno);$a++)
                                     {
                                            //pr($data_to_disp_second_arr_docno);
                                            for($b=0;$b<sizeof($data_to_disp_second_arr_docno);$b++)
                                            {
                                                if($data_to_disp_second_arr_docno[$a][$b]==$deedgrid1[0]['doc_reg_no'])
                                                    echo $data_to_disp_second_arr_attr_nm[$a][$b].' : '.$data_to_disp_second_arr_para_val[$a][$b].'&nbsp;&nbsp;';
                                                    //echo '<br>';
                                            } 
                                     }
                                    // pr(sizeof($data_to_disp_third_arr_docno));
                                   // pr($data_to_disp_third_arr_docno);
                                     for($c=0;$c<sizeof($data_to_disp_third_arr_docno);$c++){
                                            for($d=0;$d<sizeof($data_to_disp_third_arr_docno);$d++){
                                                //echo 'c:'.$c;
                                                //echo '<br>d:'.$d;
                                                if(isset($data_to_disp_third_arr_docno[$c][$d]))
                                                {
                                                    if($data_to_disp_third_arr_docno[$c][$d]==$deedgrid1[0]['doc_reg_no'])
                                                        echo '<br>Area : '.$data_to_disp_third_item_value[$c][$d].' '.$data_to_disp_third_unit_desc_en[$c][$d];
                                                }
                                            }
                                    }
                                     ?></td>
                                    <td ><?php echo $deedgrid1[0]['district_name_en']; ?></td>
                                    <td ><?php  echo $deedgrid1[0]['article_desc_en'];  ?></td>
                                    <td ><?php echo $deedgrid1[0]['office_name_en']; ?></td>
                                    <td ><?php //echo $deedgrid1[0]['book_number']; ?></td>
                                    <td ><?php //echo $deedgrid1[0]['volume_number']; ?></td>
                                    <td ><?php //echo $deedgrid1[0]['page_number_start']; ?></td>
                                    <td ><?php //echo $deedgrid1[0]['page_number_end']; ?></td>


                                </tr>
                            <?php endforeach; ?>
                        </tbody>  
                        </table>

                <?php
                    } 
                else if (empty($deedgrid) && ($action == 3)) { ?>

                    <div class="row center">
                        <div class="form-group col-sm-12" > 
                            <div class="col-sm-12"><h2 style="color: red">Record Not Found...!!!!!</h2></div>
                        </div>           
                    </div>
<?php } ?>
            </div><BR>
        </div>
    </div>

</div>












<div id="myModal" class="modal fade MyModel100" role="dialog">
    <div class="modal-dialog modal-lg MyModel80">
        <!-- Modal content-->
        <div class="modal-content MyModel100">
            <div class="modal-header MyModel80">
                <button type="button" class="close Margin" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">E-Search</h4>                
                <!--<h5 class="modal-title" style="color: red">Best View in Mozilla Firefox only...!!!</h5>-->
            </div>
            <div class="modal-body MyModel100" id="rptdetails">
                <p>Data Loading...!!!!</p>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12">
                        <div id="downdeed" class="pull-left"></div>
                        <div class="pull-right">
                        <a id="btnpay" type="button" href="" class="btn btn-warning"><?php echo __('Pay'); ?></a>
                        <button type="button" class="btn btn-success" id="printpartydetails"><?php echo __('lblprint'); ?></button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
                        </div></div>
                </div>
            </div>
        </div>

    </div>
</div>


<script type='text/javascript'>
    jQuery(function ($) {
        'use strict';
        $('#printpartydetails').on('click', function () {
//alert('in print function');
            $.print("#rptdetails");
        });
    });
</script>
<input type='hidden' value='<?php echo $action; ?>' name='action' id='action'/>
<?php echo $this->Form->end(); ?>