<?php
echo $this->Html->script('bootstrap-datepicker.min');
echo $this->Html->css('bootstrap-datepicker3.min');
echo $this->element("Helper/jqueryhelper");
?>

<script type="text/javascript">
    $(document).ready(function () {
        getPartyFields();
        $('#submitbutton').show();
         $('#repeate_token_div').hide();
        get_old_party();
        var host = '<?php echo $this->webroot; ?>';
        var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
        
         $('#doc_reg_date,.datepicker').datepicker({
            todayBtn: "linked",
            language: "it",
            autoclose: true,
            todayHighlight: true,
            endDate: '+0d',
            format: "dd-mm-yyyy"
        });
        //----------------------------------------------------------------------------------
        if ($('#property_flag').val() == 'N')
        {
            var category = $("#party_catg_id").val();
            $.post(host + 'Citizenentry/get_party_feilds', {category: category, csrftoken: csrftoken}, function (data1)
            {
                if (data1) {
                    $("#partyentry").html(data1);
                    $(document).trigger('_page_ready');
                    show_error_messages();
                    $("#partyentry").show();
                } else {
                    window.location.href = "<?php echo $this->webroot; ?>Citizenentry/party_entry/<?php echo $this->Session->read('csrftoken'); ?>";
                                    }
                                });
                            }
                            else
                            {
                                $('#7_12list').hide();
                                //$('#submitbutton').hide();
                            }

                            //----------------------------------------------------------------------------------------

                            $("#party_type_id").change(function ()
                            {
                                if ($('#property_flag').val() == 'Y') {
                                    $('#7_12list').hide();
                                    //$('#submitbutton').hide();
                                }
                            });
                            //----------------------------------------------------------------------------------------
                            //party category change function
                            $('#party_catg_id').change(function () {
                                party_cat_change();
                            });
                            //----------------------------------------------------------------------

                            //----------------------------------------------------------------------------------------------
<?php if (!empty($party_record)) { ?>
                                $('#tableParty').dataTable({
                                    "iDisplayLength": 10,
                                    "ordering": false,
                                    "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
                                });<?php } ?>
                            $('#prop_list_tbl').dataTable({
                                "iDisplayLength": 5,
                                "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
                            });
                            
                            
                            //for reapete power of attorney

                            $("input:radio[name='data[party_entry][repeate_flag]']").change(function () {
                                if ($(this).val() == 'Y') {
                                    $('#repeate_token_div').show();
                                } else {
                                    $('#repeate_token_div').hide();
                                }
                            });
                        });
                        //-------------------------------------------------------------------/

                        var host = '<?php echo $this->webroot; ?>';
                        var nameformat = '<?php echo $name_format ?>';
                        var lang = '<?php echo $laug ?>';
                        var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
                        function getPartyFields() {
                            var category = $("#party_catg_id").val();
                            $.post(host + 'Citizenentry/get_party_feilds', {category: category, csrftoken: csrftoken}, function (fields)
                            {
                                if (fields) {
                                    $("#partyentry").html(fields);
                                    $(document).trigger('_page_ready');
                                    show_data_messages();
                                    show_error_messages();
                                } else {
                                    window.location.href = "<?php echo $this->webroot; ?>Citizenentry/party_entry/<?php echo $this->Session->read('csrftoken'); ?>";
                                                }
                                            });
                                        }

                                        //---------------------------------------------------//
                                        function formsave() {

                                            document.getElementById("actiontype").value = '1';
                                            document.getElementById("hfaction").value = 'S';
                                            $('#csrftoken').val('<?php echo $this->Session->read('csrftoken'); ?>');

                                            //check percentage

                                            var share = $("#share_percentage").val();
                                            var type = $("#party_type_id").val();
                                            var maincast_id = $("#maincast_id").val();


<?php if ($permission_applicable == 'Y') { ?>

                                                if (maincast_id != 'undefined')
                                                {

                                                    $.post('<?php echo $this->webroot; ?>Citizenentry/check_permission_case', {maincast_id: maincast_id, csrftoken: csrftoken}, function (data)
                                                    {
                                                        if (data == 'Y') {
                                                            if (!$('#permission_case_number').val()) {
                                                                alert('Enter Permission Case Number');
                                                                $('#permission_case_number').focus();
                                                                return false;
                                                            }
                                                        }
                                                    });

                                                }
<?php } ?>


                                            if (share != 'undefined')
                                            {

                                                $.post("<?php echo $this->webroot; ?>Citizenentry/get_share_percentage", {type: type}, function (data)
                                                {

                                                    var total = parseInt(data) + parseInt(share);
                                                    var diff = 100 - parseInt(data);

                                                    if (data > 0 && data < 100) {
                                                        if (share == '') {
                                                            alert('Please Enter Share Percentage');
                                                            $("#share_percentage").focus();
                                                            return false;
                                                        } else {
                                                            submit();
                                                        }
                                                    } else {
                                                        submit();
                                                    }

                                                });
                                            } else {
                                                submit();

                                            }




//          alert($('#csrftoken').val());
//          return false;
                                        }

                                        function submit() {
                                            if ($('#property_flag').val() == 'Y' && $('#propertyid').val() && $('#val_id').val()) {
                                                $('#party_entry').submit();
                                            } else if ($('#property_flag').val() == 'N') {
                                                $('#party_entry').submit();
                                            } else {
                                                // alert($("input[type='partycheck']").val());

                                                if ($('#partycheck').is(":checked")) {

                                                    $('#party_entry').submit();
                                                } else {
                                                    alert('Please Select Property');
                                                    return false;
                                                }

                                            }
                                        }
                                        //------------------------------------------------
                                        //
//form edit
                                        function edit_party(category, id, party_type_id, property_id, repeat_party_id, updateid)
                                        {
                                            if (category === '' && id === '')
                                            {
                                            } else {

                                                $.post(host + 'Citizenentry/get_party_feilds', {category: category, id: id, csrftoken: csrftoken}, function (data)
                                                {

                                                    if (repeat_party_id != '') {

                                                        $("#partycheck").prop('checked', true);
                                                    }
                                                    $('#submitbutton').show();
//                                            $('#hfid').val(id);
                                                    $('#updateid').val(updateid);
                                                    $('#hfid').val(repeat_party_id);

                                                    $('#hfupdateflag').val('Y');
                                                    $('#party_id').val(id);
                                                    $('#party_catg_id').val(category);
                                                    $('#party_type_id').val(party_type_id);
                                                    $('#partyentry').html(data);
                                                    $('#partyentry').show();
                                                    $('#curr_cat').val(category);
                                                    // show_error_messages();
                                                    $.post(host + 'Citizenentry/get_valuation_id', {property_id: property_id, csrftoken: csrftoken}, function (val_id)
                                                    {
                                                        $('#' + property_id).prop('checked', true);
                                                        var edit_flag = 'Y';
                                                        formview(property_id, val_id, edit_flag);
                                                    });
                                                    if ($('#village_id').length && $("#village_id option:selected").val() != '') {
                                                        var village_id = $("#village_id option:selected").val();
                                                        $.post(host + 'Citizenentry/behavioral_patterns', {ref_id: 2, behavioral_id: 2, village_id: village_id, ref_val: id, csrftoken: csrftoken}, function (data1)
                                                        {

                                                            $('.partyaddress').html(data1);
                                                            $(document).trigger('_page_ready');
                                                        });
                                                    }
                                                    $(document).trigger('_page_ready');
                                                });
                                            }
                                        }
                                        //-------------------------------------------//
                                        function forcancel() {
                                            document.getElementById("actiontype").value = '2';
                                            window.location.href = "<?php echo $this->webroot; ?>Citizenentry/party_entry/<?php echo $this->Session->read('csrftoken'); ?>";
                                                }
                                                function formdelete(id, repeat_party_id) {
                                                    var result = confirm("Are you sure you want to delete this record?");
                                                    $('#hfid').val(id);
                                                    $('#hfidnew').val(repeat_party_id);
                                                    if (result) {
                                                        $.post(host + 'Citizenentry/delete_party', {id: id, repeat_party_id: repeat_party_id, csrftoken: csrftoken}, function (data1)
                                                        {
                                                            if (data1.trim() == 1)
                                                            {
                                                                alert('Party deleted successfully');
                                                                window.location.href = "<?php echo $this->webroot; ?>Citizenentry/party_entry/<?php echo $this->Session->read('csrftoken'); ?>";
                                                                                } else
                                                                                {
                                                                                    alert('Error');
                                                                                }
                                                                            });
                                                                        } else {
                                                                            return false;
                                                                        }
                                                                    }
//--------------------------------------------------------------------//
//Formview

                                                                    function formview(id, val_id, edit_flag)
                                                                    {

                                                                        var party = $("#party_type_id").val();
                                                                        var category = $("#party_catg_id").val();
                                                                        $('#propertyid').val(id);
                                                                        $('#val_id').val(val_id);
                                                                        $.post(host + 'Citizenentry/get_valuation_amt', {val_id: val_id, csrftoken: csrftoken}, function (amount) {

                                                                            $('#valuation_amt').val(amount);
                                                                        });
                                                                        if (category == 1)
                                                                        {
                                                                            $.post(host + 'Citizenentry/check_land_record_fetching', {party: party, csrftoken: csrftoken}, function (data1)
                                                                            {

                                                                                if (data1.trim() == 'Y')
                                                                                {

                                                                                    //$('#partyentry').hide();
                                                                                    $('#submitbutton').hide();
                                                                                    if (nameformat == 'Y') {
                                                                                        //$('#party_fname_en,#party_mname_en,#party_lname_en').val('');
                                                                                        $('#party_fname_en,#party_mname_en').attr('readonly', false);
                                                                                    } else
                                                                                    {
                                                                                        // $('#party_full_name_en').val(' ');
                                                                                        $('#party_full_name_en').attr('readonly', false);
                                                                                    }


                                                                                    $.post(host + 'Citizenentry/get_7_12_record', {id: id, party: party, csrftoken: csrftoken}, function (data2)
                                                                                    {

                                                                                        if (data2.trim() == 1)//data not found on LR
                                                                                        {
                                                                                            $.post(host + 'Citizenentry/check_7_12_compulsary', {party: party, csrftoken: csrftoken}, function (data3)
                                                                                            {

                                                                                                if (data3.trim() == 'Y')
                                                                                                {
                                                                                                    alert('Data not found on land record! Sorry You are unable to proceed');
                                                                                                    // $('#partyentry').hide();
                                                                                                    $('#submitbutton').hide();
                                                                                                } else if (data3.trim() == 'N')
                                                                                                {
                                                                                                    alert('Data not found on land record! You can enter details');
                                                                                                    $('#submitbutton').show();
                                                                                                    // getPartyFields();
                                                                                                }

                                                                                            });
                                                                                            return true;
                                                                                        } else if (data2.trim() == 'o')
                                                                                        {
                                                                                            alert('Enter correct area value');
                                                                                            window.location.href = host + "Citizenentry/property_details";
                                                                                        } else // record found on LR
                                                                                        {

                                                                                            if (data2.trim() != 1)
                                                                                            {
                                                                                                $("#7_12list").html(data2);
                                                                                                $('#7_12list').show();
                                                                                                $.post(host + 'Citizenentry/check_7_12_compulsary', {party: party, csrftoken: csrftoken}, function (data3)
                                                                                                {

                                                                                                    if (data3.trim() == 'Y')
                                                                                                    {

                                                                                                        // $('#partyentry').hide();
                                                                                                        $('#submitbutton').hide();
                                                                                                        if (nameformat == 'Y') {
                                                                                                            if (edit_flag != 'Y') {
                                                                                                                $('#party_fname_en,#party_mname_en,#party_lname_en').val('');
                                                                                                            }
                                                                                                            $('#party_fname_en,#party_mname_en').attr('readonly', true);
                                                                                                        } else {
                                                                                                            if (edit_flag != 'Y') {
                                                                                                                $('#party_full_name_en').val(' ');
                                                                                                            }
                                                                                                            $('#party_full_name_en').attr('readonly', true);
                                                                                                        }
                                                                                                    } else
                                                                                                    {
                                                                                                        $('#submitbutton').show();
                                                                                                        //  getPartyFields();
                                                                                                        if (nameformat == 'Y') {
                                                                                                            $('#party_fname_en,#party_mname_en').attr('readonly', false);
                                                                                                        } else {
                                                                                                            //  $('#party_full_name_en').val(' ');
                                                                                                            $('#party_full_name_en').attr('readonly', false);
                                                                                                        }
                                                                                                    }
                                                                                                });
                                                                                            }
                                                                                        }

                                                                                    });
                                                                                } else
                                                                                {
                                                                                    //  getPartyFields();
                                                                                    $('#submitbutton').show();
                                                                                    if (nameformat == 'Y') {
                                                                                        // $('#party_fname_en,#party_mname_en,#party_lname_en').val('');
                                                                                        $('#party_fname_en,#party_mname_en').attr('readonly', false);
                                                                                    } else {
                                                                                        // $('#party_full_name_en').val(' ');
                                                                                        $('#party_full_name_en').attr('readonly', false);
                                                                                    }
                                                                                }

                                                                            });
                                                                        } else
                                                                        {//category other than 1
                                                                            //getPartyFields();
                                                                            $('#submitbutton').show();
                                                                            $('#7_12list').hide();
                                                                        }
                                                                    }

                                                                    //-----------------------------------------//
                                                                    function setval(id, token_no, category_id)
                                                                    {
                                                                        var category = $("#party_catg_id").val();
                                                                        if (category_id == category) {
                                                                            $.post(host + 'Citizenentry/get_party_feilds', {category: category_id, id: id, token_no: token_no, csrftoken: csrftoken}, function (data)
                                                                            {

                                                                                $('#partyentry').html(data);
                                                                                $('#partyentry').show();
                                                                                if ($('#village_id').length && $("#village_id option:selected").val() != '') {
                                                                                    var village_id = $("#village_id option:selected").val();
                                                                                    $.post(host + 'Citizenentry/behavioral_patterns', {ref_id: 2, behavioral_id: 2, village_id: village_id, ref_val: id, csrftoken: csrftoken}, function (data1)
                                                                                    {

                                                                                        $('.partyaddress').html(data1);
                                                                                        $(document).trigger('_page_ready');
                                                                                        show_data_messages();
                                                                                        show_error_messages();
                                                                                    });
                                                                                } else {
                                                                                    $(document).trigger('_page_ready');
                                                                                    show_data_messages();
                                                                                    show_error_messages();
                                                                                }

                                                                            });
                                                                            $('#submitbutton').show();
                                                                        }

                                                                    }

                                                                    function setval_for_7_12(fname, mname, lname)
                                                                    {


                                                                        if (check_field($('#party_fname_ll').val())) {
                                                                            $('#party_fname_ll').val(fname);
                                                                        }
                                                                        if (check_field($('#party_mname_ll').val())) {
                                                                            $('#party_mname_ll').val(mname);
                                                                        }
                                                                        if (check_field($('#party_lname_ll').val())) {
                                                                            $('#party_lname_ll').val(lname);
                                                                        }
                                                                        if (check_field($('#party_full_name_ll').val())) {
                                                                            $('#party_full_name_ll').val(fname + ' ' + mname + ' ' + lname);
                                                                        }


                                                                    }

                                                                    function check_field(field) {


                                                                        if (field == undefined) {
                                                                            return false;
                                                                        }
                                                                        else {
                                                                            return true;
                                                                        }
                                                                    }

//-----------------------------------------------/
                                                                    function ispresenter(id)
                                                                    {
                                                                        var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;
                                                                        $.post('<?php echo $this->webroot; ?>Citizenentry/check_presenter', {id: id, csrftoken: csrftoken}, function (data)
                                                                        {
                                                                            if (data.trim() > 0)
                                                                            {
                                                                                var result = confirm("Presenter already selected Are you sure you want to change Presenter?");
                                                                                if (result) {
                                                                                    $.post('<?php echo $this->webroot; ?>Citizenentry/set_presenter', {id: id, csrftoken: csrftoken}, function (data1)
                                                                                    {
                                                                                        alert('Party successfully set as presenter');
                                                                                        window.location.href = "<?php echo $this->webroot; ?>Citizenentry/party_entry/<?php echo $this->Session->read('csrftoken'); ?>";
                                                                                                                //location.reload();
                                                                                                                return false;
                                                                                                            });
                                                                                                        }
                                                                                                    } else
                                                                                                    {
                                                                                                        $.post(host + 'Citizenentry/set_presenter', {id: id, csrftoken: csrftoken}, function (data1)
                                                                                                        {
                                                                                                            if (data1.trim() == 1)
                                                                                                            {
                                                                                                                alert('Party successfully set as presenter');
                                                                                                                window.location.href = "<?php echo $this->webroot; ?>Citizenentry/party_entry/<?php echo $this->Session->read('csrftoken'); ?>";
                                                                                                                                        return false;
                                                                                                                                    }

                                                                                                                                });
                                                                                                                            }
                                                                                                                        });
                                                                                                                    }

                                                                                                                    //---------------------------------------------//
                                                                                                                    function party_cat_change()
                                                                                                                    {
                                                                                                                        var current_party = $('#party_id').val();
                                                                                                                        var category = $("#party_catg_id").val();
                                                                                                                        var curr_cat = $('#curr_cat').val();
                                                                                                                        var party = $("#party_type_id").val();
                                                                                                                        if ($('#property_flag').val() == "N")
                                                                                                                        {
                                                                                                                            if (category == 1)
                                                                                                                            {
                                                                                                                                get_old_party();
                                                                                                                                $('#7_12list').show();
                                                                                                                            } else {
                                                                                                                                $('#7_12list').hide();
                                                                                                                            }
                                                                                                                            var praty_id = $("#hfid").val();
                                                                                                                            $.post(host + 'Citizenentry/get_party_feilds', {category: category, party_id: praty_id, csrftoken: csrftoken}, function (data1)
                                                                                                                            {
                                                                                                                                $("#partyentry").html(data1);
                                                                                                                                // $('#7_12list').hide();
                                                                                                                                $(document).trigger('_page_ready');
                                                                                                                                show_error_messages();
                                                                                                                            });
                                                                                                                        } else if ($('#property_flag').val() == "Y")
                                                                                                                        {
                                                                                                                            var intRegex = /^\d+$/;
                                                                                                                            if ($('#hfupdateflag').val() == 'Y' && intRegex.test($('#party_id').val()))
                                                                                                                            {

                                                                                                                                var prop_id = $('input[name=prop_id]:checked', '#party_entry').val();
                                                                                                                                if (curr_cat == category)
                                                                                                                                {
                                                                                                                                    edit_party(category, current_party, party, prop_id);
                                                                                                                                } else {
                                                                                                                                    getPartyFields();
                                                                                                                                }
                                                                                                                            }
                                                                                                                            else
                                                                                                                            {
                                                                                                                                getPartyFields();
                                                                                                                            }
                                                                                                                        } else {
                                                                                                                            getPartyFields();
                                                                                                                            $('#7_12list').hide();
                                                                                                                        }

                                                                                                                    }


//-------------------------------------------------//

//---------------------------get old party------------------------//
                                                                                                                    function get_old_party()
                                                                                                                    {
                                                                                                                        var host = '<?php echo $this->webroot; ?>';
                                                                                                                        $.post(host + 'Citizenentry/get_record_old_party', {csrftoken: csrftoken}, function (data2)
                                                                                                                        {

                                                                                                                            $("#oldparty").html(data2);
                                                                                                                        });
                                                                                                                    }

                                                                                                                    function show_data_messages() {
<?php
if (isset($fromdata)) {
    ?>
    <?php
    foreach ($fromdata as $keyfield => $message) {
        ?>
                                                                                                                                $("#<?php echo $keyfield ?>").val("<?php echo $message ?>");
    <?php } ?>

<?php }
?>
                                                                                                                    }
                                                                                                                    //-----------------------------------------------------------


                                                                                                                    function checkvalidation(id) {

                                                                                                                        var file1 = $('#fileupload' + id).val();
                                                                                                                        var reg = /^[0-9a-zA-Z_\-\.]*$/;
                                                                                                                        var a = file1.split(".");
                                                                                                                        var fname1 = a[0].split("\\");
                                                                                                                        if (!reg.test(fname1[fname1.length - 1])) {
                                                                                                                            $('#flag').val('N');
                                                                                                                            $('#fileupload' + id).val('');
                                                                                                                            alert('Please select file with alphanumeric name(- and _ are allowed, space is not allowed)');
                                                                                                                            return false;
                                                                                                                        }

                                                                                                                        var fsize = ($("#fileupload" + id))[0].files[0].size;
                                                                                                                        var size = (fsize / 1000000);
                                                                                                                        $.post('<?php echo $this->webroot; ?>Citizenentry/check_filevalidation', {file: file1}, function (data)
                                                                                                                        {

                                                                                                                            if (data == 'false')
                                                                                                                            {
                                                                                                                                $('#flag').val('N');
                                                                                                                                $('#fileupload' + id).val('');
                                                                                                                                alert('Format not supported');
                                                                                                                                return false;
                                                                                                                            }
                                                                                                                            else
                                                                                                                            {
                                                                                                                                if (data < size)
                                                                                                                                {
                                                                                                                                    $('#flag').val('N');
                                                                                                                                    $('#fileupload' + id).val('');
                                                                                                                                    alert('please upload file with maximum size ' + data + 'MB');
                                                                                                                                    return false;
                                                                                                                                }
                                                                                                                                else
                                                                                                                                {

                                                                                                                                    $('#flag').val('Y');
                                                                                                                                }

                                                                                                                            }
                                                                                                                        });
                                                                                                                    }

                                                                                                                    function verifyuid(id) {
                                                                                                                        $.post('<?php echo $this->webroot; ?>Citizenentry/verify_uid', {id: id}, function (data)
                                                                                                                        {
                                                                                                                            alert('UID verified');
                                                                                                                        });
                                                                                                                    }

                                                                                                                    function verifypan(id) {
                                                                                                                        $.post('<?php echo $this->webroot; ?>Citizenentry/verifypan', {id: id}, function (data)
                                                                                                                        {
                                                                                                                            alert('PAN verified');
                                                                                                                        });
                                                                                                                    }
                                                                                                                    
                                                                                                                    
                                                                                                                    function display_attorny_list() {

                                                                                                                        var doc_reg_no = $('#doc_reg_no').val();
                                                                                                                        var doc_reg_date = $('#doc_reg_date').val();
                                                                                                                        var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;

                                                                                                                        $.post('<?php echo $this->webroot; ?>Citizenentry/getpow_attorny_list', {doc_reg_no: doc_reg_no,doc_reg_date:doc_reg_date, csrftoken: csrftoken}, function (list)
                                                                                                                        {
                                                                                                                            $("#attorney_list").html(list);

                                                                                                                        });

                                                                                                                    }
                                                                                                                    function  set_attorney_details(category, id) {

                                                                                                                        var csrftoken = <?php echo $this->Session->read('csrftoken'); ?>;

                                                                                                                        $.post('<?php echo $this->webroot; ?>Citizenentry/get_party_feilds', {category: category, party_id: id, csrftoken: csrftoken, power_att: 'Y'}, function (data)
                                                                                                                        {

                                                                                                                            $('#partyentry').html(data);
                                                                                                                            $('#partyentry').show();
                                                                                                                            $('#party_catg_id').val(category);

                                                                                                                            if ($('#village_id').length && $("#village_id option:selected").val() != '') {
                                                                                                                                var village_id = $("#village_id option:selected").val();
                                                                                                                                $.post('<?php echo $this->webroot; ?>Citizenentry/behavioral_patterns', {ref_id: 2, behavioral_id: 2, village_id: village_id, ref_val: id, csrftoken: csrftoken}, function (data1)
                                                                                                                                {

                                                                                                                                    $('.partyaddress').html(data1);
                                                                                                                                    $(document).trigger('_page_ready');
                                                                                                                                });
                                                                                                                            }
                                                                                                                            $(document).trigger('_page_ready');

                                                                                                                        });

                                                                                                                    }

</script>



<?php
echo $this->Html->css('popup');
$tokenval = $this->Session->read("Selectedtoken");
$csrftoken = $this->Session->read('csrftoken');
$doc_lang = $this->Session->read('doc_lang');
?>
<?php
//if (isset($errarr)) {
//    echo "<ul>";
//    foreach ($errarr as $key => $arr) {
//        if ($arr != '') {
//            echo "<li>" . $key . "-" . $arr . "</li>";
//        }
//    }
//    echo "</ul>";
//}
?>
<?php echo $this->Form->create('party_entry', array('id' => 'party_entry', 'class' => 'form-vertical', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">
        <?php
        echo $this->element("Registration/main_menu");
        if ($this->Session->read('sroparty') == 'N') {
            echo $this->element("Citizenentry/property_menu");
        }
        ?>

        <div class="row">
            <div class="col-sm-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <center><h3 class="box-title headbolder"><?php echo __('lblparty'); ?></h3></center>
                        <div class="box-tools pull-right">
                            <a  href="<?php echo $this->webroot; ?>helpfiles/Citizenentry/party_entry_<?php echo $doc_lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>

                            <?php if ($this->Session->read('sroparty') == 'Y') { ?>
                                <a href="<?php echo $this->webroot; ?>Registration/party" class="btn btn-small btn-info" >  <?php echo __('BACK'); ?> </a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="" class="col-sm-3 control-label"><b><?php echo __('lbltokenno'); ?> :-</b><span style="color: #ff0000"></span></label>   
                                                <div class="col-sm-3">
                                                    <?php echo $this->Form->input('', array('label' => false, 'id' => '', 'value' => $Selectedtoken, 'class' => 'form-control input-sm', 'type' => 'text', 'readonly')) ?>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <p style="color: red;"><b><?php echo __('lblnote'); ?>1:&nbsp;</b><?php echo __('lblengdatarequired'); ?></br>
                                                        <b><?php echo __('lblnote'); ?>2:&nbsp;</b><?php echo __('lblsellerandpurrequired'); ?>
                                                        <?php if ($trible == 'Y') { ?>
                                                            <b><?php echo __('lblnote'); ?>3:&nbsp;</b><?php echo __('If one party selected as tribal then select all parties as tribal. '); ?>
                                                        <?php } ?>
                                                        <?php if ($stateid == 3) { ?>
                                                            <b><?php echo __('lblnote'); ?>4:&nbsp;</b><?php echo __('As Identifire preference should be given to Nambardar or Public representative or Advocate or Retired and Serving employee. '); ?><br>
                                                            <b><?php echo __('lblnote'); ?>5:&nbsp;</b><?php echo __('Writer of the deed cannot be the identifire'); ?>
                                                        <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group">
                                <label for="party_type_id" class="col-sm-2 control-label"><?php echo __('lblpartytype'); ?></label> 
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('party_type_id', array('label' => false, 'id' => 'party_type_id', 'class' => 'form-control input-sm', 'options' => array($partytype))); ?>
                                    <span id="party_type_id_error" class="form-error"><?php //echo $errarr['party_type_id_error'];             ?></span>
                                </div>

                                <label for="party_catg_id" class="col-sm-3 control-label"><?php echo __('lblpartycategory'); ?>:</label> 
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('party_catg_id', array('label' => false, 'id' => 'party_catg_id', 'class' => 'form-control input-sm', 'options' => array($party_category))); ?>
                                    <span id="party_catg_id_error" class="form-error"><?php //echo $errarr['party_catg_id_error'];             ?></span>
                                    <?php echo $this->Form->input('display_flag', array('label' => false, 'id' => 'display_flag', 'class' => 'form-control input-sm', 'type' => 'hidden', 'value' => 'Y')); ?>

                                </div>
                            </div>
                        </div>              
                    </div>
                </div>
            </div>
        </div>

        <?php echo $this->Form->input('property_flag', array('id' => 'property_flag', 'type' => 'hidden', 'value' => (isset($property) && $property ) ? 'Y' : 'N')); ?>
        <?php if (!empty($property)) { ?>
            <div class="row" id="propertylist">
                <div class="col-sm-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title headbolder"><?php echo __('lbllistofproperties'); ?></h3>
                        </div>
                        <div class="box-body">
                            <table class="table table-striped table-bordered table-hover" id="prop_list_tbl">
                                <thead > 
                                    <tr class="table_title_red_brown">
                                        <th class="center"> <?php echo __('lblsrno'); ?></th>
                                        <th class="center"> <?php echo __('lbllocation'); ?></th>
                                        <th class="center">  <?php echo __('lblusage'); ?>    </th>
                                        <th class="center">        <?php echo __('lblpropertydetails'); ?>    </th>
                                        <th class="center width10"> <?php echo __('lblaction'); ?>     </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($property_list as $key => $property) {
                                        ?>
                                        <tr>
                                            <td class="tblbigdata">
                                                <?php echo $i; ?>
                                            </td>
                                            <td class="tblbigdata">
                                                <?php echo $property[0]['village_name_' . $doc_lang]; ?>
                                            </td>
                                            <td class="tblbigdata">
                                                <?php echo $property[0]['evalrule_desc_' . $doc_lang]; ?>
                                            </td>
                                            <td class="tblbigdata">
                                                <?php
                                                $prop_name = "";
                                                foreach ($property_pattern as $key1 => $pattern) {
                                                    if ($property[0]['property_id'] == $pattern[0]['mapping_ref_val']) {
                                                        $prop_name .= "  " . $pattern[0]['pattern_desc_' . $doc_lang] . " : <small>" . $pattern[0]['field_value_' . $doc_lang] . "</small><br>";
                                                    }
                                                }

                                                echo substr($prop_name, 1);
                                                ?>
                                            </td>
                                            <td>
                                                <input type="radio" class="btn btn-primary" name="prop_id" value="<?php echo $property[0]['property_id']; ?>" id="<?php echo $property[0]['property_id']; ?>" name="" onclick="javascript: return formview('<?php echo $property[0]['property_id']; ?>', '<?php echo $property[0]['val_id']; ?>');">
                                            </td>
                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> 
            </div> 
            <input type="hidden" name="prop_flag" id="prop_flag" value="1">
        <?php } else { ?>
            <input type="hidden" name="prop_flag" id="prop_flag" value="2">
        <?php } ?>


            <!--for power of attorny-->
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group">
                                <label for="repete_pow_att" class="col-sm-2 control-label"><?php echo __('Do you want to repeat Power of Attorney'); ?></label> 
                                <div class="col-sm-2">
                                    <div class="col-sm-12"><?php echo $this->Form->input('repeate_flag', array('type' => 'radio', 'options' => array('Y' => '&nbsp;Yes&nbsp;&nbsp;', 'N' => '&nbsp;No&nbsp;&nbsp'), 'value' => 'N', 'legend' => false, 'div' => false, 'class' => 'select', 'id' => 'repeate_flag')); ?></div>
                                    <span id="repeate_flag_error" class="form-error"><?php //echo $errarr['party_type_id_error'];              ?></span>

                                </div>
                                <div id="repeate_token_div">
                                        <div class="col-sm-2">
                                    <label for="" ><b><?php echo __('Enter Reg. no.'); ?> :-</b><span style="color: #ff0000"></span></label>   
                                
                                        <?php echo $this->Form->input('doc_reg_no', array('label' => false, 'id' => 'doc_reg_no', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                        <span id="repeate_flag_error" class="form-error"><?php //echo $errarr['party_type_id_error'];              ?></span>
                                    </div>
                                     <div class="col-sm-2">
                                     <label for="" ><b><?php echo __('Enter Reg. Date'); ?> :-</b><span style="color: #ff0000"></span></label>   
                                   
                                        <?php echo $this->Form->input('doc_reg_date', array('label' => false, 'id' => 'doc_reg_date', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                                        <span id="repeate_flag_error" class="form-error"><?php //echo $errarr['party_type_id_error'];              ?></span>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="button"  class="btn btn-info" value="<?php echo __('Submit'); ?>" name="btnpan" class="btn btn-default "  
                                               onclick="javascript: return display_attorny_list();">
                                    </div>
                                </div>
                            </div>
                        </div>              
                    </div>
                </div>
            </div>
        </div>
        <div id="attorney_list">
        </div> 
            
            
        <div  id="oldparty">
        </div>
        <div  id="7_12list">
        </div>
        <div id="partyentry">
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="row center"  id="submitbutton">

                            <?php
                            //pr($regconfig['regconfig']['conf_bool_value']);
                            if ($regconfig['regconfig']['conf_bool_value'] == 'Y') {
                                if ($same_prop_flag == 'Y') {
                                    ?> 
                                    <div class="col-sm-6">


                                        <div class="col-sm-2">
                                            <?php echo $this->Form->input('partycheck', array('label' => false, 'type' => 'checkbox', 'class' => 'mycheck', 'id' => 'partycheck')); ?> 
                                        </div>
                                        <!--<div class="col-sm-10" style="text-align: left;">--> 
                                        <label for="party_type_id" class="col-sm-10 control-label" style="text-align: left;"><b><?php echo __('Same Partys For all Property'); ?></b></label> 
                                        <!--</div>-->
                                    </div>
                                    <?php
                                }
                            }
                            ?> 
                            <div class="col-sm-6">

<?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
                                <div class="col-sm-12" style="text-align: left;">
                                    <button type="button"  id="btnNext" name="btnNext" class="btn btn-info" onclick="javascript: return formsave();"><?php echo __('btnsave'); ?></button>
                                    <button type="button"  id="btnCancel" name="btnCancel" class="btn btn-info" onclick="javascript: return forcancel();"><?php echo __('btncancel'); ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
        <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
        <input type='hidden' value='<?php echo $hfidnew; ?>' name='hfidnew' id='hfidnew'/>
        <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
        <input type='hidden' value='<?php echo $updateid; ?>' name='updateid' id='updateid'/>
        <input type='hidden' value='' name='party_id' id='party_id'/>
        <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
        <input type='hidden' value='' name='propertyid' id='propertyid'/>
        <input type='hidden' value='' name='curr_cat' id='curr_cat'/>
        <input type='hidden' value='' name='valuation_amt' id='valuation_amt'/>
        <input type='hidden' value='' name='val_id' id='val_id'/>
<?php echo $this->Form->end(); ?>    
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title headbolder"><?php echo __('lbllistofsavedparties'); ?></h3>
                    </div>
                    <div class="box-body">

                        <table id="tableParty" class="table table-striped table-bordered table-condensed">  
                            <thead>  
                                <tr> 
                                    <th class="center"> <?php echo __('lblsrno'); ?></th>
                                    <th class="center"><?php echo __('lblpartyname'); ?></th>
                                    <th class="center"><?php echo __('Power Of Attorney'); ?></th>
                                    <th class="center"><?php echo __('lblpartytypeshow'); ?></th>
                                    <th class="center"><?php echo __('lblpartycategoryshow'); ?> </th>
                                    <th class="center width16"><?php echo __('lblaction'); ?></th>
                                    <?php if ($party_upload_flag == 'Y') { ?>
                                        <th class="center width16"><?php echo __('lblform60'); ?></th>
<?php } ?>
                                </tr>  
                            </thead>
                            <tbody>

                                <?php
                                $j = 1;
                                foreach ($party_record as $party_record1):

                                    if ($party_record1[0]['repeat_party_id'] == NULL) {
                                        ?>
                                        <tr>
                                            <td class="tblbigdata"><?php echo $j; ?></td>
                                            <td class="tblbigdata"><?php echo $party_record1[0]['party_full_name_' . $doc_lang]; ?></td>
                                            <td class="tblbigdata"><?php echo $party_record1[0]['poa']; ?></td>
                                            <td class="tblbigdata"><?php echo $party_record1[0]['party_type_desc_' . $doc_lang]; ?></td>
                                            <td class="tblbigdata"><?php echo $party_record1[0]['category_name_' . $doc_lang]; ?></td>
                                            <td class="tblbigdata">
                                                <?php
                                                if ($party_record1[0]['presenter_flag'] == 'Y') {

                                                    if ($party_record1[0]['is_presenter'] == 'Y') {
                                                        ?>
                                                        <input type="button" id="btnpren" name="btnpren" class="btn btn-info "  
                                                               onclick="javascript: return ispresenter(('<?php echo $party_record1[0]['id']; ?>'));"                                  
                                                               value="<?php echo __('Presenter'); ?>" />
            <?php } else { ?>
                                                        <input type="button" id="btnpren" name="btnpren" class="btn btn-info "  
                                                               onclick="javascript: return ispresenter(('<?php echo $party_record1[0]['id']; ?>'));"                                  
                                                               value="<?php echo __('Set as Presenter'); ?>" />
            <?php }
        } ?>
                                                <input type="button" class="btn btn-info" value="<?php echo __('lblbtnedit'); ?>" onclick="edit_party('<?php echo $party_record1[0]['party_catg_id']; ?>', '<?php echo $party_record1[0]['id']; ?>', '<?php echo $party_record1[0]['party_type_id']; ?>', '<?php echo $party_record1[0]['property_id']; ?>', '<?php echo $party_record1[0]['repeat_party_id']; ?>', '<?php echo $party_record1[0]['id']; ?>');"> 
                                                <input type="button" id="btndelete" class="btn btn-info" value="Delete" name="btndelete" class="btn btn-default "  
                                                       onclick="javascript: return formdelete('<?php echo $party_record1[0]['id']; ?>', '<?php echo $party_record1[0]['repeat_party_id']; ?>');">

                                                       <?php if ($uid_compulsary == 'Y') { ?>
                                                    <input type="button"  class="btn btn-info" value="<?php echo __('lblverifyuid'); ?>" name="btnuid" class="btn btn-default "  
                                                           onclick="javascript: return verifyuid(('<?php echo $party_record1[0]['id']; ?>'));">
                                                       <?php } ?>
                                                       <?php if ($pan_verify == 'Y') { ?>
                                                    <input type="button"  class="btn btn-info" value="<?php echo __('lblverifypan'); ?>" name="btnpan" class="btn btn-default "  
                                                           onclick="javascript: return verifypan(('<?php echo $party_record1[0]['id']; ?>'));">
                                                <?php } ?>
                                            <?php if ($form60_61 == 'Y') { ?>
                                                    <h4><?php echo $this->html->link('Generate Form60/61', array('controller' => 'Citizenentry', 'action' => 'generate_form60', $party_record1[0]['id'])); ?></h4>
                                                <?php } ?>
                                            </td>
                                                <?php if ($party_upload_flag == 'Y') { ?>
                                                <td>
            <?php if ($party_record1[0]['pan_no'] == NULL && $party_record1[0]['pan_form_list'] != NULL) { ?>
                <?php echo $this->Form->create('party_upload', array('url' => '/Citizenentry/party_upload', 'type' => 'file', 'id' => 'party_upload' . $party_record1[0]['party_id'], 'class' => 'form-vertical')); ?>
                <?php echo $this->Form->input("upload_file", array("label" => false, "class" => "Cntrl1", "id" => "fileupload" . $party_record1[0]['party_id'], "type" => "file")); ?>


                                                        <input type='hidden'  name='flag' id='flag'/>
                                                        <input type='hidden'  name='party_id' value="<?php echo $party_record1[0]['party_id']; ?>"/>
                                                        <input type='hidden'  name='id' value="<?php echo $party_record1[0]['id']; ?>"/>

                                                        <input type="submit" name="upload" class="btn btn-info" id="filesubmit<?php echo $party_record1[0]['party_id']; ?>"  value="Upload/Update" class="btn btn-warning"/>
                                                        <?php if ($party_record1[0]['uploaded_file'] != '') { ?> <?php
                                                            echo $this->Html->link(
                                                                    'Download', array(
                                                                'disabled' => TRUE,
                                                                'controller' => 'Citizenentry', // controller name
                                                                'action' => 'downloadpartyfile', //action name
                                                                'full_base' => true, $party_record1[0]['uploaded_file'], $party_record1[0]['party_id'])
                                                            );
                                                            ?>

                                                            <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'delete_partyfile', $this->Session->read('csrftoken'), $party_record1[0]['party_id'], $party_record1[0]['uploaded_file']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete File'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
                                                        <?php } ?>
                                                    <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?> 
                                                    <?php echo $this->Form->end(); ?>
                                            <?php } ?>
                                                </td>
                                        <?php } ?>
                                        </tr>
                                        <?php
                                    } else {


                                        if ($party_record1[0]['id'] == $party_record1[0]['repeat_party_id']) {
                                            ?>
                                            <tr>
                                                <td class="tblbigdata"><?php echo $j; ?></td>
                                                <td class="tblbigdata"><?php echo $party_record1[0]['party_full_name_' . $doc_lang]; ?></td>
                                                <td class="tblbigdata"><?php echo $party_record1[0]['poa']; ?></td>
                                                <td class="tblbigdata"><?php echo $party_record1[0]['party_type_desc_' . $doc_lang]; ?></td>
                                                <td class="tblbigdata"><?php echo $party_record1[0]['category_name_' . $doc_lang]; ?></td>
                                                <td class="tblbigdata">
                                                    <?php
                                                    if ($party_record1[0]['presenter_flag'] == 'Y') {

                                                        if ($party_record1[0]['is_presenter'] == 'Y') {
                                                            ?>
                                                            <input type="button" id="btnpren" name="btnpren" class="btn btn-info "  
                                                                   onclick="javascript: return ispresenter(('<?php echo $party_record1[0]['id']; ?>'));"                                  
                                                                   value="<?php echo __('Presenter'); ?>" />
                                                               <?php } else { ?>
                                                            <input type="button" id="btnpren" name="btnpren" class="btn btn-info "  
                                                                   onclick="javascript: return ispresenter(('<?php echo $party_record1[0]['id']; ?>'));"                                  
                                                                   value="<?php echo __('Set as Presenter'); ?>" />
                <?php
                }
            }
            ?>
                                                    <input type="button" class="btn btn-info" value="<?php echo __('lblbtnedit'); ?>" onclick="edit_party('<?php echo $party_record1[0]['party_catg_id']; ?>', '<?php echo $party_record1[0]['id']; ?>', '<?php echo $party_record1[0]['party_type_id']; ?>', '<?php echo $party_record1[0]['property_id']; ?>', '<?php echo $party_record1[0]['repeat_party_id']; ?>', '<?php echo $party_record1[0]['id']; ?>');"> 
                                                    <input type="button" id="btndelete" class="btn btn-info" value="Delete" name="btndelete" class="btn btn-default "  
                                                           onclick="javascript: return formdelete('<?php echo $party_record1[0]['id']; ?>', '<?php echo $party_record1[0]['repeat_party_id']; ?>');">

                                                           <?php if ($uid_compulsary == 'Y') { ?>
                                                        <input type="button"  class="btn btn-info" value="<?php echo __('lblverifyuid'); ?>" name="btnuid" class="btn btn-default "  
                                                               onclick="javascript: return verifyuid(('<?php echo $party_record1[0]['id']; ?>'));">
                                                           <?php } ?>
            <?php if ($pan_verify == 'Y') { ?>
                                                        <input type="button"  class="btn btn-info" value="<?php echo __('lblverifypan'); ?>" name="btnpan" class="btn btn-default "  
                                                               onclick="javascript: return verifypan(('<?php echo $party_record1[0]['id']; ?>'));">
                                                    <?php } ?>

                                                </td>
            <?php if ($party_upload_flag == 'Y') { ?>
                                                    <td>
                <?php echo $this->Form->create('party_upload', array('url' => '/Citizenentry/party_upload', 'type' => 'file', 'id' => 'party_upload' . $party_record1[0]['party_id'], 'class' => 'form-vertical')); ?>
                <?php echo $this->Form->input("upload_file", array("label" => false, "class" => "Cntrl1", "id" => "fileupload" . $party_record1[0]['party_id'], "type" => "file")); ?>


                                                        <input type='hidden'  name='flag' id='flag'/>
                                                        <input type='hidden'  name='party_id' value="<?php echo $party_record1[0]['party_id']; ?>"/>
                                                        <input type='hidden'  name='id' value="<?php echo $party_record1[0]['id']; ?>"/>

                                                        <input type="submit" name="upload" class="btn btn-info" id="filesubmit<?php echo $party_record1[0]['party_id']; ?>"  value="Upload/Update" class="btn btn-warning"/>
                                                        <?php if ($party_record1[0]['uploaded_file'] != '') { ?> <?php
                                                            echo $this->Html->link(
                                                                    'Download', array(
                                                                'disabled' => TRUE,
                                                                'controller' => 'Citizenentry', // controller name
                                                                'action' => 'downloadpartyfile', //action name
                                                                'full_base' => true, $party_record1[0]['uploaded_file'], $party_record1[0]['party_id'])
                                                            );
                                                            ?>

                                                            <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'delete_partyfile', $this->Session->read('csrftoken'), $party_record1[0]['party_id'], $party_record1[0]['uploaded_file']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete File'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
                                                    <?php } ?>
                <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?> 
                                                <?php echo $this->Form->end(); ?>
                                                    </td>
                                            <?php } ?>
                                            </tr>

                                        <?php
                                        }
                                    }
                                    $j++;
                                endforeach;
                                ?>
<?php unset($party_record1); ?>
                            </tbody>
                        </table> 
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>


<?php echo $this->Js->writeBuffer(); ?>

