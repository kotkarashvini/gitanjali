<script>
    $(document).ready(function () { 
    
        $('#numberoflanguages').change(function () {
           var cntv = $("#cntstatelang").val();
           var curcnt=$("#numberoflanguages").val();
           //alert(curcnt);
           if(parseInt(cntv) < parseInt(curcnt))
           {
                alert('Official language count excluding English is '+cntv+', therefore you can not select language count greater than '+cntv);
                $("#numberoflanguages").val('');
                return false;
           }
        });
        
    });
function formadd() {
    
  var cntv = $("#cntstatelang").val();
  var r = confirm('Language count set for this State/ UT excluding English is = '+cntv+'  Do you want to freeze the language count ?');
  
  if (r == true) {
    // submit form
        var r2 = confirm('Are you sure you want to freeze the language count ?');
        if (r2 == true) {
            $( "#select_language" ).submit();
        }
        else{
            return false;
        }
  } else {
     return false;
  }

}

function dispskip(){
    var r = confirm('Do you want to Skip Language Count? This will set only "English" as default language.');
  
    if (r == true) {
        var r2 = confirm('To set any other language count, Please contact administrator');
        if (r2 == true) {
         
            $( "#skipflag" ).val('S');
            $( "#select_language" ).submit();
         
        }
        else{
            return false;
        }
    }else {
     return false;
    }
    
}
function displangstate(){
   // window.open('aa');
   
   $.post("<?php echo $this->webroot; ?>Masters/getstate_lang",
                            {
                             csrftoken: '<?php echo $this->Session->read("csrftoken"); ?>',
                           },
                    function (data, status) {
                       //var data='ssss';
                        $("#land_party_body").html(data);
                        $('#getRecordpartyModal').modal('show');                        
                    });
                    
                    
  
}
</script>
<style>
    .modal-dialog3{
        width:380px;
        max-height: 70%;
        overflow-y: auto;
        position:absolute;
        top:40px;
        right:100px;
        bottom:0;
        left:50%;
    }
    
</style>
<?php
echo $this->element("Master/language_main_menu");
echo $this->Form->create('config_language', array('id' => 'config_language')); ?>


<?php
if($st_coun==0)
{
?>
<br><br>
    <div class="row center">
        State/ UT is not selected, before language configuration Please select State/ UT using given user.
    </div>

<?php

}    
else{
?>
<input type='hidden' value='<?php echo $cntstatelang; ?>' name='cntstatelang' id='cntstatelang'/>
<input type='hidden' name='skipflag' id='skipflag'/>

<div class="row">
    <div class="col-lg-12">
        <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo 'Select Language Count for State/ UT'; ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/ConfigLanguage/config_language_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-1"></div>
                        <label for="language_name" class="col-sm-2 control-label"><?php echo "How many local languages for this State/ UT?"; ?><span style="color: #ff0000">*</span></label>    
                        <div class="col-sm-3">
                            <?php 
                            //pr($skpflag);
                            if($skpflag=='S'){
                                echo $this->Form->input('numberoflanguages', array('label' => false, 'id' => 'numberoflanguages', 'type' => 'text', 'class' => 'form-control', 'value' => $cntcnt ,'readonly' => 'readonly') );
                            }
                            else{
                                if($cntcnt!='')
                                    echo $this->Form->input('numberoflanguages', array('label' => false, 'id' => 'numberoflanguages', 'type' => 'text', 'class' => 'form-control', 'value' => $cntcnt ,'readonly' => 'readonly') );
                                else
                                    echo $this->Form->input('numberoflanguages', array('label' => false, 'id' => 'numberoflanguages', 'type' => 'text', 'class' => 'form-control', 'value' => $cntcnt ) );
                            }
                            ?>
                            <span  id="numberoflanguages_error" class="form-error"><?php //echo $errarr['numberoflanguages_error']; ?></span>
                            <br>
                            <!--(Select language count excluding default language 1 as <b>'English'</b> )-->
                            <font color="blue">Set language count for local languages</font><br>
                            <font color="red"><b>'English'</b> is default language</font><br>
                            <font color="blue">Maximum <?php echo $cntstatelang;?> local languages are allowed for this State/ UT except English
                        </div>
                        
                        
                    </div>
                </div>
                
                <div class="rowht"></div><div class="rowht"></div>
                    <div class="row center">
                    <div class="form-group">
                        <div class="col-sm-12 tdselect">
                            <button id="btnadd"  class="btn btn-info " onclick="javascript: return formadd();">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp; <?php echo 'Save Language Count'; ?></button>
                                &nbsp;&nbsp; &nbsp;&nbsp;       
                                <button id="btndisp"  class="btn btn-info " type="button"  onclick="javascript: return displangstate();">
                                <?php echo 'List of State/ UT wise Languages'; ?></button>
                                <?php if($cntcnt==''){?>
                                 &nbsp;&nbsp; &nbsp;&nbsp;      
                                 <button id="btndisp"  class="btn btn-info"  onclick="javascript: return dispskip();">
                                <?php echo 'Skip Language Count'; ?></button>
                                <?php } ?>
                        </div>
                    </div>
                </div>
                
                
             <div id="getRecordpartyModal" class="modal fade" role="dialog">
            <div class="modal-dialog3">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <!--<h4 class="modal-title">List of State/ UT wise Languages</h4>-->
                    </div>
                    <div class="modal-body" id="land_party_body">
                        <p>Loading ...... Please Wait!</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>  
                
                
            </div>
        </div>
    </div>
</div>
<?php
}
?>