<!--<style>
    .dropdown-menu {
  display: none;
  position: absolute;
  background-color: pink;
}
.dropdown-menu li a {
  color: black;
  padding: 12px 16px;
  font-weight: bold;
  text-decoration: none;
  display: block;
}
</style>-->
<!--<style>
.MyModel100{
    
    width:100%;
}

.MyModel80{
    
    width:80%;
}
.Margin{
    margin-right:-199px;
}
</style>-->
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
            $('#action').val('');
            if (Search == '1') {
//                $('#party_name').val('');
//                $('#father_name').val('');
//                $('#article_id').val('');
//                $('#party_type_id').val('');
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
            $.getJSON('<?php echo $this->webroot; ?>JHSearch/get_party_type', {article_id: article_id}, function (data)
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
            $.getJSON('<?php echo $this->webroot; ?>JHSearch/get_village', {taluka_id: taluka_id}, function (data)
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
            $('#taluka_id').val('');
            $('#village_id').val('');
            $('#khata_no').val('');
            $('#plot_no').val('');
            $('#article_id1').val('');
            $('#deed_no').val('');
            $('#divbyparty').show();
            $('#divbyproperty').hide();
            $('#divbydeed').hide();
        } else if (action == '2') {
            $('#party_name').val('');
            $('#father_name').val('');
            $('#article_id').val('');
            $('#party_type_id').val('');
            $('#deed_no').val('');
            $('#divbyproperty').show();
            $('#divbyparty').hide();
            $('#divbydeed').hide();
        } else if (action == '3') {
            $('#party_name').val('');
            $('#father_name').val('');
            $('#article_id').val('');
            $('#party_type_id').val('');
            $('#taluka_id').val('');
            $('#village_id').val('');
            $('#khata_no').val('');
            $('#plot_no').val('');
            $('#article_id1').val('');
            $('#divbydeed').show();
            $('#divbyparty').hide();
            $('#divbyproperty').hide();
        } else {
            $('#party_name').val('');
            $('#father_name').val('');
            $('#article_id').val('');
            $('#party_type_id').val('');
            $('#taluka_id').val('');
            $('#village_id').val('');
            $('#khata_no').val('');
            $('#plot_no').val('');
            $('#article_id1').val('');
            $('#deed_no').val('');
            $('#divbyparty').hide();
            $('#divbyproperty').hide();
            $('#divbydeed').hide();
        }
        
//                $('#paymentmode_selection1').change(function (e) {
//            var mode = $('#paymentmode_selection1').val();
//            $('#myModal').modal('toggle');
//            if (mode === '')
//            {
//                alert("Please Select Payment Mode");
//                e.preventDefault();
//                retun;
//            } else {
//                $.post('<?php echo $this->webroot; ?>Registration/get_payment_details', {mode: mode, csrftoken:<?php echo $this->Session->read('csrftoken'); ?>}, function (data)
//                {
//                    $(document).unbind('_pay_chart');
//                    $(document).unbind('_pay_event');
//                    
//                    $('#paydetails1').html('');                    
//                    $('#paydetails1').html(data);
//
//                    $(document).trigger('_page_ready');
//                    $(document).trigger('_pay_chart');
//                    $(document).trigger('_pay_event');
//                });
//
//            }
//        });

    });

    function search1() {
        $('#action').val("1");
        $('#searchdefault').submit();
    }
    function search2() {
        $('#action').val("2");
        $('#searchdefault').submit();
    }
    function search3() {
        $('#action').val("3");
        $('#searchdefault').submit();
    }
    function nonemcomb_copy(token_no) {
        var from = $('#hffromyear').val();
        var to = $('#hftoyear').val();
        $.post('<?php echo $this->webroot; ?>JHSearch/rptnonemcomb_copy', {token_no: token_no, from: from, to: to}, function (data)
        {
            $("#rptdetails").html('');
            $("#downdeed").html('');
            $('#btnpay').hide();
            $('#btnverify').hide();
            $("#rptdetails").html(data);

        });
//             $('#myModal').html('');
//             $('#myModal').modal('show');
        return false;
    }

    function details(token_no, flag) {
        var from = $('#hffromyear').val();
        var to = $('#hftoyear').val();
        $.post('<?php echo $this->webroot; ?>JHSearch/rptdetails', {token_no: token_no, from: from, to: to, flag: flag}, function (data)
        {
            $("#rptdetails").html('');
            $("#downdeed").html('');
            $('#btnpay').hide();
            $('#btnverify').hide();
            $("#rptdetails").html(data);

        });
//             $('#myModal').html('');
//             $('#myModal').modal('show');
        return false;
    }

    function Certifiedcopy(token_no) {
        $.post('<?php echo $this->webroot; ?>JHSearch/fee_receipt', {token_no: token_no}, function (data)
        {
            var data = $.parseJSON(data);
            console.log(data);

            var design = data['html_design'];
            var deed = data['deed'];
            console.log(design)
            $("#rptdetails").html('');
            $("#rptdetails").html(design);
            $("#downdeed").html('');
            $("#downdeed").html(deed);
            $('#btnpay').show();
            $('#btnverify').show();

            if (deed != '') {
                $('#btnpay').hide();
                $('#btnverify').hide();
            }


        });
//             $('#myModal').html('');
//             $('#myModal').modal('show');
        return false;
    }

    function inspectcopy() {
        alert("Document Registered but not uploaded any docutment...!!!!");
        return false;
    }

</script>
<?php echo $this->Form->create('searchdefault', array('id' => 'searchdefault')); ?>

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

                            <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>
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
                        <div class="col-sm-4" style="padding-left: 0px;">
                            <div class="col-sm-5">
                                <?php echo $this->Form->input('fromyear', array('label' => false, 'id' => 'fromyear', 'class' => 'form-control input-sm', 'type' => 'text', 'value' => $fromyear, 'readonly' => 'readonly')) ?>
                                <span  id="fromyear_error" class="form-error"><?php echo $errarr['fromyear_error']; ?></span>

                            </div>
                            <label for="district_id" class="col-sm-2 control-label" style="text-align: center;">To</label>
                            <div class="col-sm-5">
                                <?php echo $this->Form->input('toyear', array('label' => false, 'id' => 'toyear', 'class' => 'form-control input-sm', 'type' => 'text', 'value' => $toyear, 'readonly' => 'readonly')) ?>
                                <span  id="toyear_error" class="form-error"><?php echo $errarr['toyear_error']; ?></span>

                            </div>
                        </div>
                        <div class="col-sm-3">
                            <?php echo $this->Html->link('Change Search Years', array('controller' => 'JHSearch', 'action' => 'searchindex'), array('escape' => false)); ?>


                        </div>
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
                            <!--<span  id="party_type_id_error" class="form-error"><?php //echo $errarr['party_type_id_error'];   ?></span>-->

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
                if (!empty($partygrid) && ($action == 1)) {
                    ;
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
                            <?php foreach ($partygrid as $partygrid1): ?>
                                <tr>
                                    <td class="width10 center">
                                        <div class="dropdown">
                                            <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Select For &nbsp;<span class="caret"></span>
                                            </button> 
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <!--<button type="button" class="btn btn-warning " data-toggle="modal" data-target="#modelRegSummary2partial"><span class="glyphicon glyphicon-print img-circle"></span> <?php // echo __('lblsummary2partialview');         ?></button>-->
                                                <li><a href="#" data-toggle="modal" data-target="#myModal" onclick="javascript: return details('<?php echo $partygrid1[0]['token_no']; ?>', 'P');" >Details</a></li> 
                                                <?php if($info_value == 'Y') { ?>
                                                <li class="divider"></li>
                                                <li> <?php
                                                    $scan_upload = $this->requestAction(array('controller' => 'JHSearch', 'action' => 'getscandata', $partygrid1[0]['token_no']));
                                                    if (!empty($scan_upload)) {
                                                        echo $this->Html->link(
                                                                __('Inspection Copy'), array(
                                                            'disabled' => TRUE,
                                                            'controller' => 'JHSearch', // controller name
                                                            'action' => 'downloadfile', //action name
                                                            'full_base' => true, $scan_upload['scan_upload']['scan_name'], 'Scanning', $partygrid1[0]['token_no'], 'I'), array('target' => '_blank')
                                                        );
                                                    } else {
                                                        ?>
                                                        <a onclick="javascript: return inspectcopy();" >Inspection Copy</a>
                                                    <?php } ?> </li>
                                                <li class="divider"></li>
                                                <li><a href="#" data-toggle="modal" data-target="#myModal" onclick="javascript: return Certifiedcopy('<?php echo $partygrid1[0]['token_no']; ?>');" >Certified Copy</a></li> 
                                                <?php } ?>
                                            </ul>
                                        </div>
                                        <!--                                        <button id="btnupdate" name="btnupdate" type="button" data-toggle="tooltip" title="Edit" class="btn btn-default "   onclick="javascript: return formupdate(
                                                                                                    ('<?php // echo $partygrid1[0]['token_no'];         ?>'));">
                                                                                    <span class="glyphicon glyphicon-pencil"></span>
                                                                                </button>-->
                                    </td>
                                    <td ><?php echo $partygrid1[0]['year']; ?></td>
                                    <td ><?php echo $partygrid1[0]['final_doc_reg_no']; ?></td>
                                    <td ><?php echo $partygrid1[0]['party_full_name_en']; ?></td>
                                    <td ><?php echo $partygrid1[0]['father_full_name_en']; ?></td>
                                    <td ><?php echo $partygrid1[0]['address_en']; ?></td>
                                    <td ><?php echo $partygrid1[0]['party_type_desc_en']; ?></td>
                                    <td ><?php echo $partygrid1[0]['book_number']; ?></td>
                                    <td ><?php echo $partygrid1[0]['volume_number']; ?></td>
                                    <td ><?php echo $partygrid1[0]['page_number_start']; ?></td>
                                    <td ><?php echo $partygrid1[0]['page_number_end']; ?></td>
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
                        <label for="district_id" class="col-sm-2 control-label"><?php echo __('Taluka Name'); ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('taluka_id', array('options' => array($taluka), 'empty' => '--select--', 'id' => 'taluka_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                            <span  id="taluka_id_error" class="form-error"><?php echo $errarr['taluka_id_error']; ?></span>
                        </div>
                        <label for="district_id" class="col-sm-2 control-label"><?php echo __('Village Name'); ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('village_id', array('options' => array($village), 'id' => 'village_id', 'label' => false, 'class' => 'form-control input-sm')); ?>
                            <span  id="village_id_error" class="form-error"><?php echo $errarr['village_id_error']; ?></span>

                        </div>
                    </div>
                </div><br>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-1"></div>
                        <label for="district_id" class="col-sm-2 control-label"><?php echo __('Khata No.'); ?></label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('khata_no', array('label' => false, 'id' => 'khata_no', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            <span  id="khata_no_error" class="form-error"><?php echo $errarr['khata_no_error']; ?></span>

                        </div>
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
                                <?php echo $this->Form->input('fromyear1', array('label' => false, 'id' => 'fromyear1', 'class' => 'form-control input-sm', 'type' => 'text', 'value' => $fromyear, 'readonly' => 'readonly')) ?>
                                <span  id="fromyear1_error" class="form-error"><?php echo $errarr['fromyear1_error']; ?></span>

                            </div>
                            <label for="district_id" class="col-sm-2 control-label" style="text-align: center;">To</label>
                            <div class="col-sm-5">
                                <?php echo $this->Form->input('toyear1', array('label' => false, 'id' => 'toyear1', 'class' => 'form-control input-sm', 'type' => 'text', 'value' => $toyear, 'readonly' => 'readonly')) ?>
                                <span  id="toyear1_error" class="form-error"><?php echo $errarr['toyear1_error']; ?></span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <?php echo $this->Html->link('Change Search Years', array('controller' => 'JHSearch', 'action' => 'searchindex'), array('escape' => false)); ?>
                        </div>
                    </div>
                </div><br>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-1"></div>
                        <label for="district_id" class="col-sm-2 control-label"><?php echo __('Deed Type'); ?></label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('article_id1', array('options' => array($article), 'empty' => '--select--', 'id' => 'article_id1', 'label' => false, 'class' => 'form-control input-sm')); ?>
                            <!--<span  id="article_id1_error" class="form-error"><?php //echo $errarr['article_id1_error'];   ?></span>-->
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
                    ;
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
                            <?php foreach ($propertygrid as $propertygrid1): ?>
                                <tr>
                                    <td class="width10 center">
                                        <div class="dropdown">
                                            <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Select For &nbsp;<span class="caret"></span>
                                            </button> 
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <li><a href="#" data-toggle="modal" data-target="#myModal" onclick="javascript: return details('<?php echo $propertygrid1[0]['token_no']; ?>', 'R');" >Details</a></li> 
                                                  <?php if($info_value == 'Y') { ?>
                                                <li class="divider"></li>
                                                <li><a href="#" data-toggle="modal" data-target="#myModal" onclick="javascript: return nonemcomb_copy('<?php echo $propertygrid1[0]['token_no'];  ?>');" >Non-Encumbrance Certificate</a></li> 
                                                
                                                <li class="divider"></li>
                                                <li> <?php
                                                    $scan_upload = $this->requestAction(array('controller' => 'JHSearch', 'action' => 'getscandata', $propertygrid1[0]['token_no']));
                                                    if (!empty($scan_upload)) {
                                                        echo $this->Html->link(
                                                                __('Inspection Copy'), array(
                                                            'disabled' => TRUE,
                                                            'controller' => 'JHSearch', // controller name
                                                            'action' => 'downloadfile', //action name
                                                            'full_base' => true, $scan_upload['scan_upload']['scan_name'], 'Scanning', $propertygrid1[0]['token_no'], 'I'), array('target' => '_blank')
                                                        );
                                                    } else {
                                                        ?>
                                                        <a onclick="javascript: return inspectcopy();" >Inspection Copy</a>
                                                    <?php } ?> </li>
                                                <li class="divider"></li>
                                                <li><a href="#" data-toggle="modal" data-target="#myModal" onclick="javascript: return Certifiedcopy('<?php echo $propertygrid1[0]['token_no']; ?>');" >Certified Copy</a></li> 
<!--                                                <li class="divider"></li>
                                                <li><a id="btnverify" type="button" href="#" data-toggle="modal" data-target="#myModal1" class="btn btn-warning">Verify Payment</a></li> -->
                                             <?php } ?> 
                                            </ul>
                                        </div>
                                        <!--                                        <button id="btnupdate" name="btnupdate" type="button" data-toggle="tooltip" title="Edit" class="btn btn-default "   onclick="javascript: return formupdate(
                                                                                                    ('<?php // echo $propertygrid1[0]['token_no'];         ?>'));">
                                                                                    <span class="glyphicon glyphicon-pencil"></span>
                                                                                </button>-->
                                    </td>
                                    <td ><?php echo $propertygrid1[0]['year']; ?></td>
                                    <td ><?php echo $propertygrid1[0]['final_doc_reg_no']; ?></td>
                                    <td >  <?php
                                        $result = $this->requestAction(array('controller' => 'JHSearch', 'action' => 'propertydata', $propertygrid1[0]['token_no']));
                                        echo $result;
                                        ?> </td>
                                    <td ><?php echo $propertygrid1[0]['district_name_en']; ?></td>
                                    <td ><?php echo $propertygrid1[0]['article_desc_en']; ?></td>
                                    <td ><?php echo $propertygrid1[0]['office_name_en']; ?></td>
                                    <td ><?php echo $propertygrid1[0]['book_number']; ?></td>
                                    <td ><?php echo $propertygrid1[0]['volume_number']; ?></td>
                                    <td ><?php echo $propertygrid1[0]['page_number_start']; ?></td>
                                    <td ><?php echo $propertygrid1[0]['page_number_end']; ?></td>
                                <?php endforeach; ?>
                        </tbody>
                    </table> 
                <?php } else if (empty($propertygrid) && ($action == 2)) { ?>

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
<!--                        <label for="district_id" class="col-sm-2 control-label"><?php echo __('Year'); ?><span style="color: #ff0000">*</span></label>
                        <div class="col-sm-2">
                        <?php // echo $this->Form->input('year', array('label' => false, 'id' => 'year', 'class' => 'form-control input-sm', 'type' => 'text'))     ?>
                        </div>-->
                        <div class="col-sm-2">
                            <button id="btnsearch3" name="btnsearch3" class="btn btn-info" style="text-align: center;"  onclick="javascript: return search3((''));">
                                Search </button>&nbsp;&nbsp;&nbsp;&nbsp;
                        </div>
                    </div>
                </div>
                <br><br>
                <!--                <div class="row">
                                    <div class="form-group">
                                        <div class="col-sm-1"></div>
                                        <label for="district_id" class="col-sm-2 control-label"><?php echo __('Deed Type'); ?></label>
                                        <div class="col-sm-2">
                <?php // echo $this->Form->input('article_id2', array('options' => array($article), 'empty' => '--select--', 'id' => 'article_id2', 'label' => false, 'class' => 'form-control input-sm'));     ?>
                                        </div>
                                    </div>
                                </div><br><br>-->
                <!--                <div class="row" style="text-align: center">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <button id="btnsearch3" name="btnsearch3" class="btn btn-info" style="text-align: center;"  onclick="javascript: return search3((''));">
                                                Search </button>&nbsp;&nbsp;&nbsp;&nbsp;
                                        </div>
                                    </div>
                                </div>-->
                <?php
                if (!empty($deedgrid) && ($action == 3)) {
                    ;
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
                            <?php foreach ($deedgrid as $deedgrid1): ?>
                                <tr>
                                    <td class="width10 center">
                                        <div class="dropdown">
                                            <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Select For &nbsp;<span class="caret"></span>
                                            </button> 
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <li><a href="#" data-toggle="modal" data-target="#myModal" onclick="javascript: return details('<?php echo $deedgrid1[0]['token_no']; ?>', 'D');" >Details</a></li> 
                                                <?php if($info_value == 'Y') { ?>
                                                <li class="divider"></li>
                                                <li> <?php
                                                    $scan_upload = $this->requestAction(array('controller' => 'JHSearch', 'action' => 'getscandata', $deedgrid1[0]['token_no']));
                                                    if (!empty($scan_upload)) {
                                                        echo $this->Html->link(
                                                                __('Inspection Copy'), array(
                                                            'disabled' => TRUE,
                                                            'controller' => 'JHSearch', // controller name
                                                            'action' => 'downloadfile', //action name
                                                            'full_base' => true, $scan_upload['scan_upload']['scan_name'], 'Scanning', $deedgrid1[0]['token_no'], 'I'), array('target' => '_blank')
                                                        );
                                                    } else {
                                                        ?>
                                                        <a onclick="javascript: return inspectcopy();" >Inspection Copy</a>
                                                    <?php } ?> </li>
                                                <li class="divider"></li>
                                                <li><a href="#" data-toggle="modal" data-target="#myModal" onclick="javascript: return Certifiedcopy('<?php echo $deedgrid1[0]['token_no']; ?>');" >Certified Copy</a></li> 
                                           <?php } ?>
                                            </ul>
                                        </div>
                                        <!--                                        <button id="btnupdate" name="btnupdate" type="button" data-toggle="tooltip" title="Edit" class="btn btn-default "   onclick="javascript: return formupdate(
                                                                                                    ('<?php echo $deedgrid1[0]['token_no']; ?>'));">
                                                                                    <span class="glyphicon glyphicon-pencil"></span>
                                                                                </button>-->
                                    </td>
                                    <td ><?php echo $deedgrid1[0]['year']; ?></td>
                                    <td ><?php echo $deedgrid1[0]['final_doc_reg_no']; ?></td>
                                    <td >  <?php
                                        $result = $this->requestAction(array('controller' => 'JHSearch', 'action' => 'propertydata', $deedgrid1[0]['token_no']));
                                        echo $result;
                                        ?> </td>
                                    <td ><?php echo $deedgrid1[0]['district_name_en']; ?></td>
                                    <td ><?php echo $deedgrid1[0]['article_desc_en']; ?></td>
                                    <td ><?php echo $deedgrid1[0]['office_name_en']; ?></td>
                                    <td ><?php echo $deedgrid1[0]['book_number']; ?></td>
                                    <td ><?php echo $deedgrid1[0]['volume_number']; ?></td>
                                    <td ><?php echo $deedgrid1[0]['page_number_start']; ?></td>
                                    <td ><?php echo $deedgrid1[0]['page_number_end']; ?></td>
                                <?php endforeach; ?>
                        </tbody>
                    </table> 
                <?php } else if (empty($deedgrid) && ($action == 3)) { ?>

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
<!--                            <a id="btnpay" type="button" href="" class="btn btn-warning">Make Payment</a>
                            <a id="btnverify" type="button" href="#" data-toggle="modal" data-target="#myModal1" class="btn btn-warning">Verify Payment</a>-->
                            <button type="button" class="btn btn-success" id="printpartydetails"><?php echo __('lblprint'); ?></button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
                        </div></div>
                </div>
            </div>
        </div>

    </div>
</div>
<!--<div id="myModal1" class="modal fade MyModel100" role="dialog">
    <div class="modal-dialog modal-lg MyModel80">
         Modal content
        <div class="modal-content MyModel100">
            <div class="modal-header MyModel80">
                <button type="button" class="close Margin" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">E-Search</h4>                
                <h5 class="modal-title" style="color: red">Best View in Mozilla Firefox only...!!!</h5>
            </div>
            <div class="modal-body MyModel100" id="rptdetails1">


                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="col-md-10" >
                            <label for="" class="control-label"><?php echo __('lblselectpaymode'); ?><span style="color: #ff0000">*</span></label>    
                            <?php echo $this->Form->input('paymentmode_id', array('label' => false, 'id' => 'paymentmode_selection1', 'class' => 'form-control input-sm paymentmode_id', 'type' => 'select', 'options' => array('empty' => '--Select--', $payment_mode_online))) ?>                         
                        </div> 
                        <div class="col-md-2">
                            <a  href="<?php echo $this->webroot; ?>helpfiles/Payment/payment_varification_<?php echo $lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                        </div>
                        <div class="col-md-10" id="paydetails1"> 
                        </div>        
                    </div>
                    </div>
                </div> 
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12">
                        <div id="downdeed" class="pull-left"></div>
                        <div class="pull-right">
                                                    <a id="btnpay" type="button" href="" class="btn btn-warning">Make Payment</a>
                                                    <a id="btnverify" type="button" href="" class="btn btn-warning">Verify Payment</a>
                                                    <button type="button" class="btn btn-success" id="printpartydetails"><?php echo __('lblprint'); ?></button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>-->


<script type='text/javascript'>
    jQuery(function ($) {
        'use strict';
        $('#printpartydetails').on('click', function () {
            $.print("#rptdetails");
        });
    });
</script>
<input type='hidden' value='<?php echo $action; ?>' name='action' id='action'/>
<input type='hidden' value='<?php echo $hffromyear; ?>' name='hffromyear' id='hffromyear'/>
<input type='hidden' value='<?php echo $hftoyear; ?>' name='hftoyear' id='hftoyear'/>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>