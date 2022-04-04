<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>
<!--<script>
    function PopIt() {
        return "Are you sure you want to leave?";
    }
    function UnPopIt() { /* nothing to return */
    }

    $(document).ready(function () {
        window.onbeforeunload = PopIt;
        $("a").click(function () {
            window.onbeforeunload = UnPopIt;
        });
    });
    
</script>-->
<script>
    $(document).ready(function () {
        
//          alert($('#hfhidden1').val());
//        if ($('#hfhidden1').val() == 'Y') {
//            $('#tableLevel2').dataTable({
//                "iDisplayLength": 5,
//                "aLengthMenu": [[5, 10, -1], [5, 10, "All"]]
//            });
//        }

        if (document.getElementById('hfhidden1').value === 'Y') {

            $('#Showreport1').slideDown(1000);
        }
        else {
            $('#Showreport1').hide();
        }


    });

   

</script>

<?php echo $this->Form->create('emoplyeereportlist', array('type' => 'file', 'class' => 'Level2', 'autocomplete' => 'off', 'id' => 'emoplyeereportlist')); ?>

<div class="panel panel-default">
    <div class="panel-heading" style="text-align: center;"><big><b><?php echo __('lblrpthead'); ?></b></big></div>
    <div class="panel-body">
        <br>
      <?php //pr($username);?>
        <div class="row">
            <div class="col-sm-2"></div>
            <label for="user_name"class="control-label col-sm-2"><?php echo __('lblusrname'); ?></label>
            <div class="col-sm-2" > <label for="user_name"class="control-label col-sm-2"><?php echo $username['username']; ?> </label></div>
            <div class="col-sm-3"></div>
        </div><!--

        <br>

        <br>
        <div class="row" style="text-align: center">
            <button id="btnview" name="btnview" class="btn btn-primary " onclick="javascript: return formview();">view</button>
            <button id="btnexit" name="btnexit" class="btn btn-primary " onclick="javascript: return formexit();">Exit</button>     
        </div>-->

<!--        <div class="row">
            <input type='hidden' value='<?php //echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
            <input type='hidden' value='<?php// echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
            <input type='hidden' value='<?php// echo $hflist_id; ?>' name='list_id1' id='list_id1'/>
        </div>-->
        <br>
        <div id="Showreport1"   class="table-responsive" >
            <table id="tableLevel2" class="table table-striped table-bordered table-condensed">  
                <thead style="background-color: rgb(243, 214, 158);">  
                    <tr>  
<!--                        <td class="tdselect" style="text-align: center; font-weight:bold;">Employee Name</td>-->
                                  <td style="text-align: center; font-weight:bold; "><?php echo __('lblrptempname'); ?></td>
                        <td style="text-align: center; font-weight:bold; "><?php echo __('lbldocdtls'); ?></td>
                         <td style="text-align: center; font-weight:bold; "><?php echo __('lblpropdtls'); ?></td>
                          <td style="text-align: center; font-weight:bold; "><?php echo __('lblofcname'); ?></td>
                    </tr>  
                </thead>
                <tr>
                    <?php foreach ($Showreport as $Showreport1):
                        ?>     
                         <td style="text-align: center;"><?php  echo $Showreport1[0]['reporting_employee'];            ?></td>
                          <td style="text-align: center;"><?php  echo $Showreport1[0]['doc_details'];            ?></td>
                           <td style="text-align: center;"><?php  echo $Showreport1[0]['property_details'];            ?></td>
                            <td style="text-align: center;"><?php  echo $Showreport1[0]['office_name_en'];            ?></td><!--
                                                 ?></td>-->
<!--                        <td style="text-align: center;"><?php //echo $Level2record1['Level2']['list_2_desc_eng']; ?></td>-->
                    </tr>
                <?php endforeach;
                ?>
                <?php unset($Showreport1); ?>
            </table> 
        </div>

        <?php
        if (!empty($Showreport)) {
            ?>
            <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
            <input type="hidden" value="N" id="hfhidden1"/><?php } ?>



    </div>
</div>      
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>