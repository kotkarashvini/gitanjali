<script>
    $(document).ready(function () {
    
    $('#formlabelid').hide();
    $('#menulabelid').hide();
    $('#submenulabelid').hide();
    $('#minorfunctionlabelid').hide();
   // $('#subsubmenulabelid').hide();
    /*$('#tableFormlabel').dataTable({
    "iDisplayLength": All,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
    */
//   $('#tableFormlabel').dataTable({
//    "iDisplayLength": 50,
//             "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]],
//            "bPaginate": true
//    });
//    $('#tablemenulabel').dataTable({
//    "iDisplayLength": 50,
//             "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
//    });
//    $('#tablesubmenulabel').dataTable({
//   "iDisplayLength": 50,
//           "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]],
//            "bPaginate": true
//    });
//    $('#tableminorfunctionlabel').dataTable({
//   "iDisplayLength": 50,
//            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]],
//            "bPaginate": true
//    });
    /*$("#checkedAll").change(function() {
        if (this.checked) {
            $(".checkSingle").each(function() {
                this.checked=true;
            });
        } else {
            $(".checkSingle").each(function() {
                this.checked=false;
            });
        }
    });*/
    
    });
    
    function firstcheck(checkedornot,val,id){
       // alert(checkedornot);
       // alert(val);
       //alert(id);
        if(checkedornot==true){
            //alert('aafsdf');
            if(val=='NULL' || val==null || val=='' || val==' '){
                alert('Please enter the label value first then activate the flag.');
                this.checked=false;
            }
           
        }
        
    }
   function checkrd(val,val2){
     
       if(val2==true){
            var dispc='checkSingle'+val;
            $("."+dispc).each(function() {
                this.checked=true;
            });
       }
       else{
           var dispc='checkSingle'+val;
            $("."+dispc).each(function() {
                this.checked=false;
            });
       }
   }
   function checkrdmenu(val,val2){
      if(val2==true){
            var dispc='checkSinglemenu'+val;
            $("."+dispc).each(function() {
                this.checked=true;
            });
       }
       else{
           var dispc='checkSinglemenu'+val;
            $("."+dispc).each(function() {
                this.checked=false;
            });
       }
   }
   function checkrdsubmenu(val,val2){
      if(val2==true){
            var dispc='checkSinglesubmenu'+val;
            $("."+dispc).each(function() {
                this.checked=true;
            });
       }
       else{
           var dispc='checkSinglesubmenu'+val;
            $("."+dispc).each(function() {
                this.checked=false;
            });
       }
   }
   
   function checkrdminorfunction(val,val2){
      if(val2==true){
            var dispc='checkSingleminorfunction'+val;
            $("."+dispc).each(function() {
                this.checked=true;
            });
       }
       else{
           var dispc='checkSingleminorfunction'+val;
            $("."+dispc).each(function() {
                this.checked=false;
            });
       }
   }
    function dispradio(valv){
        //alert('aaaaaa');
        //alert(val);
        $('#radioval').val(valv);
        if(valv==1){
            $('#menulabelid').hide();
            $('#submenulabelid').hide();
            $('#formlabelid').show();
            $('#minorfunctionlabelid').hide();
        }else if(valv==2){
            $('#formlabelid').hide();
            $('#submenulabelid').hide();  
            $('#menulabelid').show();
            $('#minorfunctionlabelid').hide();
        }else if(valv==3){
            $('#formlabelid').hide();
            $('#menulabelid').hide();
            $('#submenulabelid').show();
            $('#minorfunctionlabelid').hide();
        }else if(valv==4){
            $('#formlabelid').hide();
            $('#menulabelid').hide();
            $('#submenulabelid').hide();
            $('#minorfunctionlabelid').show();
        }
    }
    function checkflag_func(id,val,flg){
        //alert(id);
       // alert(val);
       // alert(flg);
        //if(flg=='Y')
        var r = confirm("This label is activated; Do you want to Change this label?");
        if (r == true) {
            //document.getElementById(id).focus(); 
           //$( "#id" ).focus();
        }
        else{
            return false;
            
        }

    }
   
</script>
<?php
echo $this->element("Master/language_main_menu");
?> 
<?php echo $this->Form->create('formlabelconfig', array('autocomplete' => 'off', 'id' => 'formlabelconfig')); ?>

<?php
if($st_coun==0)
{
?>
<br><br>
    <div class="row center">
        State is not selected, before language configuration Please select state using given user.
    </div>

<?php

}    
else{
?>

<div class="row">
    <div class="col-lg-12">
        
        <div class="pull-left"> <b style="color:red">Note 1 : If label is activated then you can not change the label name; if you want to change the label then first de-activate the label.</b></div><br>
        <br>
<!--<div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>-->
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder" ><?php echo 'Label Display Configuration'; ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/ConfigLanguage/set_label_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
          
            <div class="box-header with-border">
             <input type="radio" class="btn btn-primary" value="1" id="radio_form" name="radio_form" onclick="javascript: return dispradio(this.value);">    
                Form Label 
                &nbsp;&nbsp;
                <input type="radio" class="btn btn-primary" value="2" id="radio_form" name="radio_form" onclick="javascript: return dispradio(this.value);">    
                Menu Label
                &nbsp;&nbsp;
                <input type="radio" class="btn btn-primary"  value="3" id="radio_form" name="radio_form" onclick="javascript: return dispradio(this.value);">    
                Sub-Menu Label
                &nbsp;&nbsp;
                <input type="radio" class="btn btn-primary"  value="4" id="radio_form" name="radio_form" onclick="javascript: return dispradio(this.value);">    
                Minor function Label
                &nbsp;&nbsp;
                <input type="hidden" name="radioval" id="radioval" >
            </div>
        </div>
        <div class="box box-primary">

            <div class="box-body" style="overflow:auto; height:500px;">
                
                
                <div id="selectFormlabel">
                    
                    
                    <div id="formlabelid">
                    <table id="tableFormlabel" class="table table-striped table-bordered table-hover" >
                        <thead >  
                            <tr>  
                                <th class="center">Sr. No</th>
                                <th class="center">Label name</th>
                                <?php
                                //pr($labelrecordc);
                        //  creating dyanamic table header using same array of config language
                                $cntdisp1=1;
                                foreach ($languagelist as $langcode) {
                                    //pr($langcode);
                                    ?>
                                    
                                    <th class="center"><?php echo __('lbllabeldescription') . " (" . $langcode['mainlanguage']['language_name'] . ")";
                                    if($langcode['mainlanguage']['id']==1) echo ' Mandatory - non editable';
                                    if($langcode['mainlanguage']['id']!=1) echo '<br>Language : '.$cntdisp1;
                                    ?></th>
                                    
                                    <?php 
                                        if($langcode['mainlanguage']['id']!=1)
                                        {
                                        ?>
                                        <th align="center">
                                          
                                           <?php echo 'Activate ('.$langcode['mainlanguage']['language_name'] . ")";
                                           $varcheck=$langcode['mainlanguage']['language_code'];
                                           ?>
                                            <br><br>
                                            <input type="checkbox" name="checkedAll" id="checkedAll" onchange="javascript:checkrd('<?php echo $varcheck ?>',this.checked);"/> Check/Clear All
                                        </th>
                                        <?php 
                                        }
                                 
                                    if($langcode['mainlanguage']['id']!=1)     
                                    {
                                        $cntdisp1++;    
                                    }
                                } ?>
                            </tr>  
                        </thead>
                        <tbody>
                                 <?php
                                 $rcnt=1;
                               // pr(sizeof($labelrecordc));
                                 for($aa=0;$aa<sizeof($labelrecordc);$aa++){
                                     
                                    // pr($labelrecordc[$aa]['Formlabel']['label_desc_en']); 
                                     ?>
                                     <tr>
                                            <td align="left"><?php echo $rcnt;$rcnt++;?></td>
                                            <td align="left"><?php echo $labelrecordc[$aa]['Formlabel']['labelname'];?></td>
                                            <?php
                                            
                                            //pr($languagelist);
                                            for($bb=0;$bb<sizeof($languagelist);$bb++){
                                             
                                               // pr($languagelist[$bb]['mainlanguage']['language_code']);
                                                $nn1='label_desc_' . $languagelist[$bb]['mainlanguage']['language_code'];
                                                $nn2=$languagelist[$bb]['mainlanguage']['language_code'].'_activation_flag['.$labelrecordc[$aa]['Formlabel']['id'].']';
                                                $nn3=$languagelist[$bb]['mainlanguage']['language_code'].'_activation_flag';
                                                //$nn4=$labelrecordc[$aa]['Formlabel'][$nn3];
                                                ?>
                                               <td align="left">
                                                <?php
                                                if($languagelist[$bb]['mainlanguage']['id']!=1)
                                                {
                                                    $dclss='form_'.$languagelist[$bb]['mainlanguage']['language_code'].'_'.$labelrecordc[$aa]['Formlabel']['id'];
                                                    // echo $labelrecordc[$aa]['Formlabel'][$nn1];
                                                    ?>
                                                    <!--<input type="text" name="<?php echo $dclss;?>" id="<?php echo $dclss;?>" value="<?php echo $labelrecordc[$aa]['Formlabel'][$nn1];?>" onchange="javascript:checkflag_func(this.id,this.value,'<?php echo $labelrecordc[$aa]['Formlabel'][$nn3]?>');">-->
                                                   <div class="input-field">
                                                   <input type="text" <?php if($labelrecordc[$aa]['Formlabel'][$nn3]=='Y'){?> readonly="readonly" style="background-color: gainsboro;" <?php } ?> name="<?php echo $dclss;?>" id="<?php echo $dclss;?>" value="<?php echo $labelrecordc[$aa]['Formlabel'][$nn1];?>" class="form-control">
                                                   </div>
                                                   <span class="form-error" id="<?php echo $dclss;?>_error"></span>
                                                   
                                                    <?php
                                                    //echo $this->Form->input($dclss, array('value'=>$labelrecordc[$aa]['Formlabel'][$nn1] ,'label' => false, 'id' => $dclss, 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => "255" ));
                                                }
                                                else{
                                                    echo $labelrecordc[$aa]['Formlabel'][$nn1];
                                                }    
                                                ?></td>
                                                <?php 
                                                if($languagelist[$bb]['mainlanguage']['id']!=1)
                                                {
                                                ?>
                                               <td align="center">
                                                  <?php 
                                                  //pr($nn4); 
                                                  // pr($labelrecordc[$aa]['Formlabel'][$nn3]);
                                                  $classnm='checkSingle'.$languagelist[$bb]['mainlanguage']['language_code'];
                                                  ?>
                                                  <input type="checkbox" onchange="javascript:firstcheck(this.checked,'<?php echo $labelrecordc[$aa]['Formlabel'][$nn1];?>',this.id);" class="<?php echo $classnm;?>" name="<?php echo $nn2;?>" id="<?php echo $nn2;?>" value=<?php echo $labelrecordc[$aa]['Formlabel']['id']; ?> <?php if ($labelrecordc[$aa]['Formlabel'][$nn3]=='Y') { ?> checked <?php } ?>/>
                                                   
                                                </td>
                                                <?php
                                                }
                                                
                                            }
                                            ?>
                                    </tr>
                                <?php    
                                 }  
                              ?>
                        </tbody>
                    </table>
                   <div class="rowht"></div><div class="rowht"></div>
                    <div class="row center">
                    <div class="form-group">
                        <div class="col-sm-12 tdselect">
                            <button id="btnadd"  class="btn btn-info "  >
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp; <?php echo __('btnsave'); ?></button>

                        </div>
                    </div>
                </div>
                   
                </div>  
                    
               <!----------  for menu  -------------------------------->
               
                <div id="menulabelid">
                    <table id="tablemenulabel" class="table table-striped table-bordered table-hover" >
                        <thead >  
                            <tr>  
                                <th class="center">Sr. No</th>
                               
                                <?php
                                $cntdisp2=1;
                                //pr($labelrecord);
                        //  creating dyanamic table header using same array of config language
                                foreach ($languagelist as $langcode) {
                                    ?>
                                    <th class="center"><?php echo __('lbllabeldescription') . " (" . $langcode['mainlanguage']['language_name'] . ")";
                                    if($langcode['mainlanguage']['id']==1) echo ' Mandatory - non editable';
                                    if($langcode['mainlanguage']['id']!=1) echo '<br>Language : '.$cntdisp2;
                                    ?></th>
                                    
                                    <?php 
                                        if($langcode['mainlanguage']['id']!=1)
                                        {
                                            $varcheck=$langcode['mainlanguage']['language_code'];
                                        ?>
                                        <th align="center">
                                           
                                           <?php echo 'Activate ('.$langcode['mainlanguage']['language_name'] . ")"; ?>
                                            <br><br>
                                            <input type="checkbox" name="checkedAllmenu" id="checkedAllmenu" onchange="javascript:checkrdmenu('<?php echo $varcheck ?>',this.checked);"/> Check/Clear All
                                        </th>
                                        <?php 
                                        }
                                        
                                    if($langcode['mainlanguage']['id']!=1)     
                                    {
                                        $cntdisp2++;    
                                    }
                                 } ?>
                            </tr>  
                        </thead>
                        <tbody>
                                 <?php 
                                  $rcnt_two=1;
                                    foreach ($labelmenu as $labelmenu1){ 
                                     //pr($labelmenu1);
                                     ?>   
                                    <tr>
                                        <td align="left"><?php echo $rcnt_two;$rcnt_two++;?></td>
                                        
                                            <?php
                                            foreach ($languagelist as $langcodeb) {
                                             
                                            ?>
                                                <!--<td align="left"><?php echo $labelmenu1['Menu']['name_' . $langcodeb['mainlanguage']['language_code']]; ?></td>-->
                                                <td align="left">
                                                <?php
                                                if($langcodeb['mainlanguage']['id']!=1)
                                                {
                                                    $dclss_menu='menu_'.$langcodeb['mainlanguage']['language_code'].'_'.$labelmenu1['Menu']['id'];
                                                    // echo $labelmenu1['Menu']['name_' . $langcodeb['mainlanguage']['language_code']];
                                                    ?>
													<div class="input-field">
                                                    <input type="text" <?php if ($labelmenu1['Menu']['menu_'.$langcodeb['mainlanguage']['language_code'].'_activation_flag'] == 'Y') {?> readonly="readonly" style="background-color: gainsboro;" <?php } ?>  class="form-control" name="<?php echo $dclss_menu;?>" id="<?php echo $dclss_menu;?>" value="<?php echo $labelmenu1['Menu']['name_' . $langcodeb['mainlanguage']['language_code']];?>">
													</div>
<span class="form-error" id="<?php echo $dclss_menu;?>_error"></span>
                                                   
												   <?php
                                                    //echo $this->Form->input($dclss_menu, array('value'=>'2' ,'label' => false, 'id' => $dclss_menu, 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => "255" ));
                                                }
                                                else{
                                                    echo $labelmenu1['Menu']['name_' . $langcodeb['mainlanguage']['language_code']];
                                                }    
                                                ?></td>
                                                
                                                <?php 
                                                if($langcodeb['mainlanguage']['id']!=1)
                                                {
                                                    $classnm='checkSinglemenu'.$langcodeb['mainlanguage']['language_code'];
                                                ?>
                                                <td align="center">
                                                                                                      
                                                    <input type="checkbox" class="<?php echo $classnm;?>" name="menu_<?php echo $langcodeb['mainlanguage']['language_code']; ?>_activation_flag[<?php echo $labelmenu1['Menu']['id']; ?>]" id="menu_<?php echo $langcodeb['mainlanguage']['language_code']; ?>_activation_flag" value=<?php echo $labelmenu1['Menu']['id']; ?> <?php if ($labelmenu1['Menu']['menu_'.$langcodeb['mainlanguage']['language_code'].'_activation_flag'] == 'Y') { ?> checked <?php } ?>/>
                                                    
                                                </td>
                                                <?php 
                                                }
                                                
                                            }
                                            ?>
                                    </tr>
                                 <?php } ?>   
                        </tbody>
                    </table>
                   <div class="rowht"></div><div class="rowht"></div>
                    <div class="row center">
                    <div class="form-group">
                        <div class="col-sm-12 tdselect">
                            <button id="btnadd"  class="btn btn-info "  onclick="javascript: return formadd();">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp; <?php echo __('btnsave'); ?></button>

                        </div>
                    </div>
                </div>
                   
                </div>  
                <!-----------------  for submenu  ------------------------------>
                  
                <div id="submenulabelid">
                    <table id="tablesubmenulabel" class="table table-striped table-bordered table-hover" >
                        <thead >  
                            <tr>  
                                <th class="center">Sr. No</th>
                               
                                <?php
                                $cntdisp3=1;
                                //pr($labelrecord);
                        //  creating dyanamic table header using same array of config language
                                foreach ($languagelist as $langcode) {
                                    ?>
                                    <th class="center"><?php echo __('lbllabeldescription') . " (" . $langcode['mainlanguage']['language_name'] . ")"; 
                                    if($langcode['mainlanguage']['id']==1) echo ' Mandatory - non editable';
                                    if($langcode['mainlanguage']['id']!=1) echo '<br>Language : '.$cntdisp3;
                                    ?></th>
                                    
                                    <?php 
                                        if($langcode['mainlanguage']['id']!=1)
                                        {
                                            $varcheck=$langcode['mainlanguage']['language_code'];
                                        ?>
                                        <th align="center">
                                          
                                           <?php echo 'Activate ('.$langcode['mainlanguage']['language_name'] . ")"; ?>
                                            <br><br>
                                            <input type="checkbox" name="checkedAllsubmenu" id="checkedAllsubmenu" onchange="javascript:checkrdsubmenu('<?php echo $varcheck ?>',this.checked);"/> Check/Clear All
                                        </th>
                                        <?php 
                                        }
                                        
                                    if($langcode['mainlanguage']['id']!=1)     
                                    {
                                        $cntdisp3++;    
                                    }
                                 } ?>
                            </tr>  
                        </thead>
                        <tbody>
                                 <?php
                                  $rcnt_three=1;
                                 foreach ($labelsubmenu as $labelsubmenu1){ 
                                     //pr($labelsubmenu1);
                                     ?>   
                                    <tr>
                                        <td align="left"><?php echo $rcnt_three;$rcnt_three++;?></td>
                                       
                                            <?php
                                            foreach ($languagelist as $langcodeb) {
                                             
                                            ?>
                                                <!--<td align="left"><?php echo $labelsubmenu1['SubMenu']['name_' . $langcodeb['mainlanguage']['language_code']]; ?></td>-->
                                                <td align="left">
                                                <?php
                                                if($langcodeb['mainlanguage']['id']!=1)
                                                {
                                                    $dclss_submenu='submenu_'.$langcodeb['mainlanguage']['language_code'].'_'.$labelsubmenu1['SubMenu']['id'];
                                                   
                                                    ?>
													<div class="input-field">
                                                    <input type="text" <?php if ($labelsubmenu1['SubMenu']['submenu_'.$langcodeb['mainlanguage']['language_code'].'_activation_flag'] == 'Y') {?> readonly="readonly" style="background-color: gainsboro;" <?php } ?> class="form-control" name="<?php echo $dclss_submenu;?>" id="<?php echo $dclss_submenu;?>" value="<?php echo $labelsubmenu1['SubMenu']['name_' . $langcodeb['mainlanguage']['language_code']];?>">
                                                   </div>
<span class="form-error" id="<?php echo $dclss_submenu;?>_error"></span>

												   <?php
                                                    //echo $this->Form->input($dclss_submenu, array('value'=>'2' ,'label' => false, 'id' => $dclss_submenu, 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => "255" ));
                                                }
                                                else{
                                                    echo $labelsubmenu1['SubMenu']['name_' . $langcodeb['mainlanguage']['language_code']];
                                                }    
                                                ?></td>
                                                
                                        
                                                <?php 
                                                if($langcodeb['mainlanguage']['id']!=1)
                                                {
                                                    $classnm='checkSinglesubmenu'.$langcodeb['mainlanguage']['language_code'];
                                                ?>
                                                <td align="center">
                                                                                                      
                                                    <input type="checkbox" class="<?php echo $classnm;?>" name="submenu_<?php echo $langcodeb['mainlanguage']['language_code']; ?>_activation_flag[<?php echo $labelsubmenu1['SubMenu']['id']; ?>]" id="submenu_<?php echo $langcodeb['mainlanguage']['language_code']; ?>_activation_flag" value=<?php echo $labelsubmenu1['SubMenu']['id']; ?> <?php if ($labelsubmenu1['SubMenu']['submenu_'.$langcodeb['mainlanguage']['language_code'].'_activation_flag'] == 'Y') { ?> checked <?php } ?>/>
                                                    
                                                </td>
                                                <?php 
                                                }
                                                
                                            }
                                            ?>
                                    </tr>
                                 <?php } ?>   
                        </tbody>
                    </table>
                   <div class="rowht"></div><div class="rowht"></div>
                    <div class="row center">
                    <div class="form-group">
                        <div class="col-sm-12 tdselect">
                            <button id="btnadd"  class="btn btn-info "  onclick="javascript: return formadd();">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp; <?php echo __('btnsave'); ?></button>

                        </div>
                    </div>
                </div>
                   
                </div>  
              
                
                <!---------------------- for minor function ------------------>
                
                <div id="minorfunctionlabelid">
                    <table id="tableminorfunctionlabel" class="table table-striped table-bordered table-hover" >
                        <thead >  
                            <tr>  
                                <th class="center">Sr. No</th>
                               
                                <?php
                                $cntdisp4=1;
                                //pr($labelrecord);
                        //  creating dyanamic table header using same array of config language
                                foreach ($languagelist as $langcode) {
                                    ?>
                                    <th class="center"><?php echo __('lbllabeldescription') . " (" . $langcode['mainlanguage']['language_name'] . ")"; 
                                    if($langcode['mainlanguage']['id']==1) echo ' Mandatory - non editable';
                                    if($langcode['mainlanguage']['id']!=1) echo '<br>Language : '.$cntdisp4;
                                    ?></th>
                                    
                                    <?php 
                                        if($langcode['mainlanguage']['id']!=1)
                                        {
                                            $varcheck=$langcode['mainlanguage']['language_code'];
                                        ?>
                                        <th align="center">
                                          
                                           <?php echo 'Activate ('.$langcode['mainlanguage']['language_name'] . ")"; ?>
                                            <br><br>
                                            <input type="checkbox" name="checkedAllminorfunction" id="checkedAllminorfunction" onchange="javascript:checkrdminorfunction('<?php echo $varcheck ?>',this.checked);"/> Check/Clear All
                                        </th>
                                        <?php 
                                        }
                                        
                                    if($langcode['mainlanguage']['id']!=1)     
                                    {
                                        $cntdisp4++;    
                                    }
                                 } ?>
                            </tr>  
                        </thead>
                        <tbody>
                                 <?php
                                  $rcnt_four=1;
                                 foreach ($labelminorfunction as $labelminorfunction1){ 
                                     //pr($labelminorfunction1);
                                     ?>   
                                    <tr>
                                        <td align="left"><?php echo $rcnt_four;$rcnt_four++;?></td>
                                       
                                            <?php
                                            foreach ($languagelist as $langcodeb) {
                                             
                                            ?>
                                                <!--<td align="left"><?php echo $labelminorfunction1['minorfunction']['function_desc_' . $langcodeb['mainlanguage']['language_code']]; ?></td>-->
                                        
                                                <td align="left">
                                                <?php
                                                if($langcodeb['mainlanguage']['id']!=1)
                                                {
                                                    $dclss_minor='minor_'.$langcodeb['mainlanguage']['language_code'].'_'.$labelminorfunction1['minorfunction']['id'];
                                                   
                                                    ?>
													<div class="input-field">
                                                    <input type="text" <?php if ($labelminorfunction1['minorfunction']['minor_'.$langcodeb['mainlanguage']['language_code'].'_activation_flag'] == 'Y') {?> readonly="readonly" style="background-color: gainsboro;" <?php } ?> class="form-control" name="<?php echo $dclss_minor;?>" id="<?php echo $dclss_minor;?>" value="<?php echo $labelminorfunction1['minorfunction']['function_desc_' . $langcodeb['mainlanguage']['language_code']];?>">
                                                    </div>
													<span class="form-error" id="<?php echo $dclss_minor;?>_error"></span>
													<?php
                                                    
                                                }
                                                else{
                                                    echo $labelminorfunction1['minorfunction']['function_desc_' . $langcodeb['mainlanguage']['language_code']];
                                                }    
                                                ?></td>
                                                
                                                
                                                <?php 
                                                if($langcodeb['mainlanguage']['id']!=1)
                                                {
                                                    $classnm='checkSingleminorfunction'.$langcodeb['mainlanguage']['language_code'];
                                                ?>
                                                <td align="center">
                                                                                                      
                                                    <input type="checkbox" class="<?php echo $classnm;?>" name="minor_<?php echo $langcodeb['mainlanguage']['language_code']; ?>_activation_flag[<?php echo $labelminorfunction1['minorfunction']['id']; ?>]" id="minor_<?php echo $langcodeb['mainlanguage']['language_code']; ?>_activation_flag" value=<?php echo $labelminorfunction1['minorfunction']['id']; ?> <?php if ($labelminorfunction1['minorfunction']['minor_'.$langcodeb['mainlanguage']['language_code'].'_activation_flag'] == 'Y') { ?> checked <?php } ?>/>
                                                    
                                                </td>
                                                <?php 
                                                }
                                                
                                            }
                                            ?>
                                    </tr>
                                 <?php } ?>   
                        </tbody>
                    </table>
                   <div class="rowht"></div><div class="rowht"></div>
                    <div class="row center">
                    <div class="form-group">
                        <div class="col-sm-12 tdselect">
                            <button id="btnadd"  class="btn btn-info "  onclick="javascript: return formadd();">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp; <?php echo __('btnsave'); ?></button>

                        </div>
                    </div>
                </div>
                   
                </div>  

                
                <!------------------------------------------------------------>
               
                </div>
            </div>
        </div>
    </div>
   
</div>


 <?php echo $this->Form->end(); ?>
<?php
}
?>