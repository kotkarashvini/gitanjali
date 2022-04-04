<?php
echo $this->Html->script('bootstrap-datepicker.min');
echo $this->Html->css('bootstrap-datepicker3.min');
//echo $this->element("Helper/jqueryhelper");
//echo $this->element("Citizenentry/property_menu");
?>
<?php
echo $this->element("Helper/jqueryhelper");
?>
<script type="text/javascript">
    
     $(document).ready(function () {
        $('.date').datepicker({
                        format: "dd-mm-yyyy",
           // format: "yyyy-mm-dd",
            todayBtn: "linked",
            calendarWeeks: true,
            autoclose: true,
            todayHighlight: true,
            endDate: 'today'
        });
    });

    function aaa(index_id) {
        //alert(index_id);
        var html = '';
        $.postJSON("<?php echo $this->webroot; ?>Display/view_generalinfo",
                {

                    index_id: index_id,

                },
                function (data, status) {
                    // alert(data);
                    $.each(data, function (index, val) {
                        $.each(val, function (index1, val1) {
                             // alert(val1);
                            // alert(index1); 
                            //s var article=val1;
                            // alert(article);
                            if (index1 == 'article_desc_en')
                            {
                                html = html + '<tr><td>Article</td><td>' + val1 + ' </td></tr>';
                            } else if (index1 == 'office_name_en')
                            {
                                html = html + '<tr><td>Office</td><td>' + val1 + ' </td></tr>';
                            } else if(index1 == 'exec_date')
                            {
                                html = html + '<tr><td>Date</td><td>' + val1 + ' </td></tr>';
                            }

                           //  html = html + '<tr><td>Name</td><td>' + "Amar" + ' </td></tr>';
//                           
                            $.postJSON("<?php echo $this->webroot; ?>Display/view_propattribute",
                                    {

                                        index_id: index_id,

                                    },
                                    function (data, status) {
                                       //  alert(data);
                                        $.each(data, function (index, val) {
                                            $.each(val, function (index1, val2) {
  //alert(val2);
                                                  document.getElementById("hidden_attribute").value = val2;
                                                 // alert(document.getElementById("hidden_attribute").value);
                                       html = html + '<tr><td>Attribute</td><td>' + document.getElementById("hidden_attribute").value + ' </td></tr>';
                                            });
                                        });
                                      
                                        $('#datadisplay').html(html);
                                    });
                                   
                                   
                                    
                                   // $('#datadisplay').html(html);

                        });
                    });

                    $('#datadisplay').html(html);
                });



    }

    ///////////////////////////////////

    function display_generalinfo(index_id) {
        //alert("Hii");
        // document.getElementById("hidden_permission_id").value = permission_id;
        //alert(document.getElementById("hidden_permission_id").value);
        //var csrftoken = <?php //echo $this->Session->read('csrftoken');    ?>;
        var html = '';
        $.postJSON("<?php echo $this->webroot; ?>Display/view_generalinfo",
                {

                    index_id: index_id,
                    // type: flag,
                    // csrftoken: csrftoken,
                    //action: 'remove'
                },
                function (data, status) {
                    // alert(data);
                    $.each(data, function (index, val) {
                        $.each(val, function (index1, val1) {
                            //  alert(val1);
                            // alert(index1); 
                            //s var article=val1;
                            // alert(article);
                            if (index1 == 'article_desc_en')
                            {
                                html = html + '<tr><td>Article</td><td>' + val1 + ' </td></tr>';
                            } else if (index1 == 'office_name_en')
                            {
                                html = html + '<tr><td>Office</td><td>' + val1 + ' </td></tr>';
                            } else
                            {
                                html = html + '<tr><td>Date</td><td>' + val1 + ' </td></tr>';
                            }

                        });
                    });


                    // html = html + "<td>" + val1 + "</td>";
                    //   html="<tr><td> + information + </td></tr>"
                    //  $('#datadisplay').visible;
                    //  visibility: visible;
                    $('#datadisplay').html(html);
                });

    }



    function display_partydetails(index_id) {
        // alert(index_id);
        var html = '';
        $.postJSON("<?php echo $this->webroot; ?>Display/view_partydetails",
                {

                    index_id: index_id,
                },
                function (data, status) {
                    for (var key in data)
                    {
                        var value1 = JSON.stringify(data[key]);
                        var empobj = JSON.parse(value1)


                        for (var key in empobj) {
                            //alert(empobj[key].refund_code);
                            // html = html + "<td>" + val + "</td>";
                            // html=html +'<tr>'"HEllo"'</tr>';
                            html = "<tr>";
                            // html=html +'<td>'HEllo'</td>';

                            $.each(empobj[key], function (index, val) {
                                //alert(index);
                                if (index == 'party_type_desc_en')
                                {
                                    html = html + "<td>" + val + "</td>";
                                } else
                                {
//                                     html = html + "<td>";

                                    if (index == 'party_full_name_en')
                                    {
                                        html = html + "<td>";
                                        html = html + "<b>Party Name-</b>" + val + ",";
                                        // html = html + "<td>" + val + ",";
                                    }
                                    if (index == 'father_full_name_en')
                                    {
                                        html = html + "<b>Father Name-</b>" + val + ",";

                                    }
                                    if (index == 'address_en')
                                    {
                                        html = html + "<b>Address-</b>" + val + ",";
                                        html = html + "</td>";
                                    }

                                    // alert(html);
                                }
                                //alert(html);
                            });
                            html += "</tr>";
                            //alert(html);
                            $('#datadisplay_party').append(html);
                        }
                    }
                });

//                function (data, status) {
//                   html='<tr>';
//                     $.each(data, function (index, val) {
//                      $.each(val, function (index1, val1) {
//                $.each(val, function (index2, val2) {
//                    alert(val2);
//
//                      html=html +'<td>'+val1+'</td>';
//
//                });        
//                });
//                });
//                html=html +'</tr>';
//
//                  $('#datadisplay_party').html(html);  
//                });
        $('#datadisplay_party').empty();

    }

    function display_propertydetails(token_no) {
        //alert(token_no);

        var html = '';
        //////////////////////////////
      
        /////////////////////////
        $.postJSON("<?php echo $this->webroot; ?>Display/view_propertydetails",
                {

                    token_no: token_no,

                },
                function (data, status) {
                    for (var key in data)
                    {
                        var value1 = JSON.stringify(data[key]);
                        var empobj = JSON.parse(value1)


                        for (var key in empobj) {
                            //alert(empobj[key].refund_code);
                            // html = html + "<td>" + val + "</td>";
                            // html=html +'<tr>'"HEllo"'</tr>';
                            html = "<tr>";
                            // html=html +'<td>'HEllo'</td>';

                            $.each(empobj[key], function (index, val) {
                                //alert(index);
                                if (index == 'property_id')
                                {
                                    //html = html + "<td>" + val + "</td>";
                                    //  html = '<tr><td>dsdfsdf</td><td>+val+</td></tr>';
                                    html = html + '<tr><td><b>Property Id</b></td><td>' + val + ' </td></tr>';
                                } else
                                {
                                    if (index == 'village_name_en')
                                    {
                                        //  html = html + '<tr>';
                                        //html = html + '<td>Village-' + val + ',';
                                        //  html = html + '</tr>';

                                        html = html + '<td>Other Description of the Property</td><td> Village-' + val + ',';
                                    }
                                    if (index == 'taluka_name_en')
                                    {
                                        // html = html + 'Village-' + val + ',';
                                        html = html + 'Taluka-' + val + ',';

                                    }
                                    if (index == 'district_name_en')
                                    {
                                        html = html + 'District-' + val + ',';
                                        html = html + '</td>';
                                    }
                                }
                                if (index == 'attribute')
                                {
                                            str=val;
                    str=str.replace('{"', "");
                    str=str.replace('"}', "");
                    str=str.replace('"', "");
                    str=str.replace('"', "");
                                 html = html + '<tr><td>Attribute</td><td>' + str + ' </td></tr>';
                                 }
                                 if (index == 'area')
                                {
                                    
                                                str=val;
                    str=str.replace('{"', "");
                    str=str.replace('"}', "");
                    str=str.replace('"', "");
                    str=str.replace('"', "");
                                 html = html + '<tr><td>Area</td><td>' + str + ' </td></tr>';
                                 }
                                 if (index == 'market_value')
                                 {
                                   //var value=val;
                                   //var value1=ltrim(val,"{");
                                   str=val;
                    str=str.replace('{', "");
                    str=str.replace('}', "");
                    str=str.replace('"', "");
                    str=str.replace('"', "");
                                   //alert(str);
                                 html = html + '<tr><td>market value</td><td>' + str + ' </td></tr>';
                                 }
                            });
                            html += "</tr>";
                           
                            
                            ///////////////////////////
                            //Chnages Added by prasmita on date 05/08/2020
                            //        


                            ///////////////////////////////////
                            $('#datadisplay_prop').append(html);
                        }
                    }
                });

        $('#datadisplay_prop').empty();

    }
    
    
    function display_feedetails(index_id) {
        // alert(index_id);
        var html = '';
        $.postJSON("<?php echo $this->webroot; ?>Display/view_feedetails",
                {

                    index_id: index_id,
                },
                function (data, status) {
                    for (var key in data)
                    {
                        var value1 = JSON.stringify(data[key]);
                        var empobj = JSON.parse(value1)


                        for (var key in empobj) {
                            //alert(empobj[key].refund_code);
                            // html = html + "<td>" + val + "</td>";
                            // html=html +'<tr>'"HEllo"'</tr>';
                            html = "<tr>";
                            // html=html +'<td>'HEllo'</td>';

                            $.each(empobj[key], function (index, val) {
                               // alert(index);alert(val);
                                
                                    //alert(val);
                               //
                               //  html = html + '<tr><td>Registration Fee</td><td>' + val + ' </td></tr>';
                               if(index!="fee_item_desc_en")
                               {
                               html = html + '<tr><td> '+ index +'</td> <td>'+ val+'</td></tr>';
                           }
                            
                                //alert(html);
                            });
                           // html += "</tr>";
                            //alert(html);
                            $('#datadisplay_fee').append(html);
                        }
                    }
                });

//                function (data, status) {
//                   html='<tr>';
//                     $.each(data, function (index, val) {
//                      $.each(val, function (index1, val1) {
//                $.each(val, function (index2, val2) {
//                    alert(val2);
//
//                      html=html +'<td>'+val1+'</td>';
//
//                });        
//                });
//                });
//                html=html +'</tr>';
//
//                  $('#datadisplay_party').html(html);  
//                });
      //  $('#datadisplay_party').empty();

    }

</script>
<?php echo $this->Form->create('report', array('id' => 'report', 'autocomplete' => 'off')); ?>
 <input type="hidden" name="hidden_attribute" id="hidden_attribute" />
<?php echo $this->element("Registration/main_menu"); ?>
<?php
//echo $this->element("Citizenentry/main_menu");

$laug = $this->Session->read("sess_langauge");
?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('Report'); ?></h3></center>
              
            </div>
            
            
            <div class="box-body">
                
                   <div class="form-row">
            <div class="form-group col-md-2">
                <label for=""><b> From Date </b></label>
            </div>
            <div class="form-group col-md-3">
<?php echo $this->Form->input("from_date", array('id' => 'from_date', 'type' => 'text', 'legend' => false, 'class' => 'date form-control', 'label' => false)); ?> 
                <span  id="from_date_error" class="form-error"><?php echo $from_date_error; ?></span>
            </div>
            <div class="form-group col-md-1">
                <label for=""><b> To Date </b></label>
              
            </div>
            <div class="form-group col-md-3">
<?php echo $this->Form->input("to_date", array('id' => 'to_date', 'type' => 'text', 'legend' => false, 'class' => 'date form-control', 'label' => false)); ?> 
                  <span  id="to_date_error" class="form-error"><?php echo $to_date_error; ?></span>
            </div>
            <div class="form-group col-md-2">
                <a href="#" class="pl-3"><button   type="submit"  id="search_bydate"  name="action"  value="search_bydate" class="btn btn-primary" onclick="return dateValidation('from_date', 'to_date');">Search By Date</button></a>
            </div>
        </div>
                

                <div  class="rowht">&nbsp;</div>
            </div>


            <div class="box-body">

                <div class="form-group">
                    <div class="tab-content">
                        <div id="home" class="tab-pane fade in active">

                            <table id="tablegeninfo" class="table table-striped table-bordered table-hover" >  

                                <tbody id="datadisplay1" style="display:none">
                                    <?php
                                    if (!empty($display_data)) {

                                        foreach ($display_data as $display_data1) {
                                            ?>
                                            <tr>

                                                <td class="width5">Article</td><td>Prasmita</td>
                                            </tr> 

                                        <?php }
                                    } else {
                                        ?>
                                        <tr><td colspan="9"><?php echo"No records found! "; ?></td></tr>
<?php } ?>

                                </tbody>
                            </table> 
                        </div>

                    </div>
                </div>
                <div  class="rowht">&nbsp;</div>
            </div>


        </div>

    </div>
</div>

<div class="modal fade" id="Model_Generalinfo" role="dialog" style=" align-items:center; " >
    <div class="modal-dialog" >

        <!-- Modal content-->
        <div class="modal-content" style="width:800px; vertical-align: central" >
            <div class="modal-header">

                <center><h4 class="modal-title">General Information</h4></center>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <table id="tablegeninfo" class="table table-striped table-bordered table-hover" >  
 <thead><tr>
                            <th class="center" colspan=3><?php echo __('General Information'); ?></th>
                        </tr>  </thead>  
                    <tbody id="datadisplay">
                        <?php
                        if (!empty($display_data)) {

                            foreach ($display_data as $display_data1) {
                                ?>
                                <tr>


                                </tr> 

                            <?php }
                        } else {
                            ?>
                            <tr><td colspan="9"><?php echo"No records found! "; ?></td></tr>
<?php } ?>

                    </tbody>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="Model_propertydetails" role="dialog" style=" align-items:center; " >
    <div class="modal-dialog" >

        <!-- Modal content-->
        <div class="modal-content" style="width:800px; vertical-align: central" >
            <div class="modal-header">

                <center> <h4 class="modal-title">Property Details</h4></center>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <table id="tablegeninfo" class="table table-striped table-bordered table-hover" >  
 <thead><tr>
                            <th class="center" colspan=3><?php echo __('Property Details'); ?></th>
                        </tr>  </thead>  
                    <tbody id="datadisplay_prop">
                        <?php
                        if (!empty($display_data)) {

                            foreach ($display_data as $display_data1) {
                                ?>
                                <tr>


                                </tr> 

                            <?php }
                        } else {
                            ?>
                            <tr><td colspan="9"><?php echo"No records found! "; ?></td></tr>
<?php } ?>

                    </tbody>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>


<div class="modal fade" id="Model_partydetails" role="dialog" style=" align-items:center; " >
    <div class="modal-dialog" >

        <!-- Modal content-->
        <div class="modal-content" style="width:800px; vertical-align: central" >
            <div class="modal-header">

                <center><h4 class="modal-title">Party Details</h4></center>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <table id="tablegeninfo" class="table table-striped table-bordered table-hover" >  
                    <thead><tr>
                            <th class="center" colspan=3><?php echo __('Party Details'); ?></th>
                        </tr>  </thead>  

                    <tbody id="datadisplay_party">
                        <?php
                        if (!empty($display_data)) {

                            foreach ($display_data as $display_data1) {
                                ?>
                                <tr>


                                </tr> 

    <?php }
} else {
    ?>
                            <tr><td colspan="9"><?php echo"No records found! "; ?></td></tr>
<?php } ?>

                    </tbody>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
 
 <div class="modal fade" id="Model_feedetails" role="dialog" style=" align-items:center; " >
    <div class="modal-dialog" >

        <!-- Modal content-->
        <div class="modal-content" style="width:800px; vertical-align: central" >
            <div class="modal-header">

                <center>
                    <h4 class="modal-title">Fee Details</h4>
                </center> 
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <table id="tablegeninfo" class="table table-striped table-bordered table-hover" >  
                    <thead><tr>
                            <th class="center" colspan=3><?php echo __('Fee Details'); ?></th>
                        </tr>  </thead>  

                    <tbody id="datadisplay_fee">
                        <?php
                        if (!empty($display_data)) {

                            foreach ($display_data as $display_data1) {
                                ?>
                                <tr>


                                </tr> 

    <?php }
} else {
    ?>
                            <tr><td colspan="2"><?php echo"No records found! "; ?></td></tr>
<?php } ?>

                    </tbody>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>





<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>




