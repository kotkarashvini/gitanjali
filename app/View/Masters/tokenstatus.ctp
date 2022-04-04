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
<?php
echo $this->Html->script('jquery.dataTables');
echo $this->Html->script('dataTables.bootstrap');
?>
<script>

    $(document).ready(function () {
        // alert($("#hfhidden1").val());
        $('#tabledivisionnew').dataTable({
            "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });

        if (document.getElementById('hfhidden1').value == 'Y') {
            $('#divtokenstatus').slideDown(1000);
        }
        else {
            $('#divtokenstatus').hide();
        }
        if (document.getElementById('hfhidden2').value == 'Y') {
            $('#divparty').slideDown(1000);
        }
        else {
            $('#divparty').hide();
        }


        var actiontype = document.getElementById('actiontype').value;
        if (actiontype == '2') {
            $('.tdsave').show();
            $('.tdselect').hide();
            $('#village_name_en').focus();
        }
    });


    function formselect(party_id) {
//        alert('hii');
        document.getElementById("actiontype").value = '1';
        $('#hfid').val(party_id);
    }

    function formverify(party_id) {
        document.getElementById("actiontype").value = '2';

        $('#hfid').val(party_id);

    }


</script> 

<?php echo $this->Form->create('tokenstatus', array('id' => 'tokenstatus', 'autocomplete' => 'off')); ?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading" style="text-align: center"><b><?php echo __('lblTokenStauts'); ?></b></div>
            <div class="panel-body">
                <div class="table-responsive" id="divtokenstatus">
                    <table id="tabledivisionnew" class="table table-striped table-bordered table-hover">  
                        <thead >  
                            <tr>  
                                <td style="text-align: center; font-weight:bold;"><?php echo __('lblsrno') ?></td>
                                <td style="text-align: center; font-weight:bold;"><?php echo __('lblpartyid') ?></td>
                                <td style="text-align: center; font-weight:bold;"><?php echo __('lbltokenno') ?></td>
                                <td style="text-align: center; font-weight:bold;"><?php echo __('lblpartydetails') ?></td>
                                <td style="text-align: center; font-weight:bold;"><?php echo __('lblaction'); ?></td>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php for ($i = 0; $i < count($tokenstatusrecord); $i++) { ?>
                                <tr>
                                    <td style="text-align: center;"><?php echo $tokenstatusrecord[$i][0]['id']; ?></td>
                                    <td style="text-align: center;"><?php echo $tokenstatusrecord[$i][0]['party_id']; ?></td>
                                    <td style="text-align: center;"><?php echo $tokenstatusrecord[$i][0]['tokan_no']; ?></td>
                                    <td style="text-align: center;"><?php echo $tokenstatusrecord[$i][0]['party_name']; ?></td>
                                    <td style="text-align: center;">
                                        <button id="btnselect" name="btndelete" class="btn btn-primary " style="text-align: center;" onclick="javascript: return formselect(('<?php echo $tokenstatusrecord[$i][0]['party_id']; ?>'));"><?php echo __('lblSelect'); ?></button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table> 
                    <?php if (!empty($tokenstatusrecord)) { ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
                </div>
                <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                <div class="row" id="divparty" hidden="true">
                    <div class="col-sm-12" >
                        <div class="row">
                            <div class="col-sm-3" >&nbsp;</div>
                            <div class="col-sm-8" >
                                <div class="form-group">
                                    <?php for ($i = 0; $i < count($partyrecord); $i++) { ?>
                                        <div class="row">
                                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblpartyid') ?></label>
                                            <div class="col-sm-3"><?php echo $partyrecord[$i][0]['party_id']; ?></div>
                                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblpartyname') ?></label>
                                            <div class="col-sm-3"><?php echo $partyrecord[$i][0]['party_name']; ?></div>
                                        </div>
                                        <div class="row">
                                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblcity') ?></label>
                                            <div class="col-sm-3"><?php echo $partyrecord[$i][0]['city']; ?></div>
                                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblmobileno') ?></label>
                                            <div class="col-sm-3"><?php echo $partyrecord[$i][0]['mobile_no']; ?></div>
                                        </div>
                                        <div class="row">
                                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblpancardno') ?></label>
                                            <div class="col-sm-3"><?php echo $partyrecord[$i][0]['pan_card_no']; ?></div>
                                            <label for="party_id" class="col-sm-2 control-label"><?php echo __('lblemailid') ?></label>
                                            <div class="col-sm-3"><?php echo $partyrecord[$i][0]['email_id']; ?></div>
                                        </div>

                                    <?php } ?>
                                </div>
                            </div>
                            <div class="col-sm-1" >&nbsp;</div>
                        </div>
                        <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                        <div class="row" style="text-align: center;">
                            <div class="col-sm-12" >
                                <div class="form-group">
                                    <button id="btnselect" name="btndelete" class="btn btn-primary " style="text-align: center;" 
                                            onclick="javascript: return formverify(('<?php echo $tokenstatusrecord[$i][0]['party_id']; ?>'));">
                                                <?php echo __('lblVerify'); ?>
                                    </button>
                                    <button id="btnselect" name="btndelete" class="btn btn-primary " style="text-align: center;" 
                                            onclick="location.href = '<?php echo $this->Html->url(array('controller' => 'Masters', 'action' => 'tokenstatus')); ?>';">
                                                <?php echo __('btncancel'); ?>
                                    </button>
                                </div>
                            </div>
                            <?php if (!empty($partyrecord)) { ?>
                                <input type="hidden" value="Y" id="hfhidden2"/><?php } else { ?>
                                <input type="hidden" value="N" id="hfhidden2"/><?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
</div>


<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>

