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
        var hfupdateflag = "<?php echo $hfupdateflag; ?>";
        if (hfupdateflag === 'Y')
        {
            $('#btnadd').html('Save');
        }
        if ($('#hfhidden1').val() === 'Y')
        {
            $('#tableUsagemainmain').dataTable({
                "iDisplayLength": 10,
                "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
            });
        }

    });
</script>
<script>
    function formadd() {
        $(':input').each(function () {
            $(this).val($.trim($(this).val()));
        });
        var usage_main_catg_desc_en = $('#usage_main_catg_desc_en').val();
        //var usage_main_catg_desc_ll = $('#usage_main_catg_desc_ll').val();
        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        var numbers = /^[0-9]+$/;
        var Alphanum = /^(?=.*?[a-zA-Z])[0-9a-zA-Z]+$/;
        var Alphanumdot = /^(?=.*?[a-zA-Z])[0-9a-zA-Z.]+$/;
        var password = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[#,@]).{8,}/;
        var alphbets = /^[a-z A-Z ]+$/;
        var alphbetscity = /^[ A-Za-z-() ]*$/;
        var alphanumnotspace = /^[0-9a-zA-Z]+$/;
        var alphanumsapcedot = /^(?=.*?[a-zA-Z])[0-9 a-zA-Z,.\-_]+$/;

        if (usage_main_catg_desc_en === '') {

            alert('Please enter category description!!!');
            $('#usage_main_catg_desc_en').focus();
            return false;
        }
        //$('#usage_main_catg_desc_en').val(usage_main_catg_desc_en.trim());
        //$('#usage_main_catg_desc_ll').val(usage_main_catg_desc_ll.trim());
        document.getElementById("actiontype").value = '1';
        document.getElementById("hfaction").value = 'S';
    }

    function formupdate(usage_main_catg_desc_en, usage_main_catg_desc_ll, dolr_usgaecode, id) {
        document.getElementById("actiontype").value = '1';
        $('#usage_main_catg_desc_en').val(usage_main_catg_desc_en);
        $('#usage_main_catg_desc_ll').val(usage_main_catg_desc_ll);
        $('#dolr_usgaecode').val(dolr_usgaecode);
        $('#hfupdateflag').val('Y');
        $('#hfid').val(id);
        $('#btnadd').html('Save');
        return false;
    }

    function formdelete(id) {
        document.getElementById("actiontype").value = '3';
        document.getElementById("hfid").value = id;
    }
</script> 

<?php echo $this->Form->create('usagemain', array('id' => 'usagemain', 'autocomplete' => 'off')); ?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading" style="text-align: center"><b><?php echo __('lblusagemaincategoryhead'); ?></b></div>
            <div class="panel-body">
                <div class="row" id="selectUsagemainmain">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="col-sm-1">&nbsp;</div>
                            <label for="usage_main_catg_desc_en" class="col-sm-3 control-label"><?php echo __('lblusagemaincategoryname'); ?><span style="color: #ff0000">*</span></label>    
                            <div class="col-sm-3">
                                <?php echo $this->Form->input('usage_main_catg_desc_en', array('label' => false, 'id' => 'usage_main_catg_desc_en', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            </div>
                            <div class="col-sm-3">
                                <?php echo $this->Form->input('usage_main_catg_desc_ll', array('label' => false, 'id' => 'usage_main_catg_desc_ll', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12" style="height: 5px;">&nbsp;</div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="col-sm-1"></div>
                            <label for="dolr_usgaecode" class="col-sm-3 control-label"><?php echo __('lbldolrusagecode'); ?><span style="color: #ff0000">*</span></label> 
                            <div class="col-sm-3">
                                <?php echo $this->Form->input('dolr_usgaecode', array('label' => false, 'id' => 'dolr_usgaecode', 'class' => 'form-control input-sm', 'type' => 'text')) ?>
                            </div>
                            <div class="col-sm-1 tdselect">
                            </div>  
                        </div>
                    </div>
                </div>
                <div class="col-sm-12" style="height: 10px;">&nbsp;</div>
                <div class="row">
                    <div class="col-lg-12 tdselect" style="text-align: center">
                        <div class="form-group">
                            <button id="btnadd" name="btnadd" class="btn btn-primary " style="text-align: center;" onclick="javascript: return formadd();">
                                <span class="glyphicon glyphicon-plus"></span><?php echo __('lblbtnAdd'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-heading" style="text-align: center"><b><?php echo __('lblusagemaincategoryhead'); ?></b></div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="tableUsagemainmain">
                        <thead >
                            <tr>  
                                <td style="text-align: center; width: 10%;"><?php echo __('lbladmstate'); ?></td>
                                <?php if ($this->Session->read("sess_langauge") == 'en') { ?>
                                    <td style="text-align: center;"><?php echo __('lblusagemaincategoryname'); ?></td>
                                    <td style="text-align: center;"><?php echo __('lbldlrusagecode'); ?></td>
                                    <td style="text-align: center;"><?php echo __('lblusagemaincategoryname_ll'); ?></td>
                                <?php } else { ?>
                                    <td style="text-align: center;"><?php echo __('lblusagemaincategoryname'); ?></td>
                                    <td style="text-align: center;"><?php echo __('lbldlrusagecode'); ?></td>
                                    <td style="text-align: center;"><?php echo __('lblusagemaincategoryname_ll'); ?></td>
                                <?php } ?>
                                <td style="text-align: center; width: 10%;"><?php echo __('lblaction'); ?></td>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php foreach ($usagemainrecord as $usagemainrecord1): ?>
                                <tr>
                                    <td style="text-align: center"><?php echo $state; ?></td>
                                    <?php if ($this->Session->read("sess_langauge") == 'en') { ?>
                                        <td style="text-align: center;"><?php echo $usagemainrecord1['Usagemainmain']['usage_main_catg_desc_en']; ?></td>
                                        <td style="text-align: center;"><?php echo $usagemainrecord1['Usagemainmain']['dolr_usgaecode']; ?></td>
                                        <td style="text-align: center;"><?php echo $usagemainrecord1['Usagemainmain']['usage_main_catg_desc_ll']; ?></td>
                                        <td style="text-align: center;">
                                            <button id="btnupdate" name="btnupdate" class="btn btn-default " style="text-align: center;" 
                                                    onclick="javascript: return formupdate(
                                                                    ('<?php echo $usagemainrecord1['Usagemainmain']['usage_main_catg_desc_en']; ?>'),
                                                                    ('<?php echo $usagemainrecord1['Usagemainmain']['usage_main_catg_desc_ll']; ?>'),
                                                                    ('<?php echo $usagemainrecord1['Usagemainmain']['dolr_usgaecode']; ?>'),
                                                                    ('<?php echo $usagemainrecord1['Usagemainmain']['id']; ?>'));">
                                                <span class="glyphicon glyphicon-pencil"></span>
                                            </button>
                                            <button id="btndelete" name="btndelete" class="btn btn-default " style="text-align: center;" 
                                                    onclick="javascript: return formdelete(('<?php echo $usagemainrecord1['Usagemainmain']['id']; ?>'));">
                                                <span class="glyphicon glyphicon-remove"></span>
                                            </button>
                                        </td>
                                    <?php } else { ?>
                                        <td style="text-align: center;"><?php echo $usagemainrecord1['Usagemainmain']['usage_main_catg_desc_ll']; ?></td>
                                        <td style="text-align: center;"><?php echo $usagemainrecord1['Usagemainmain']['dolr_usgaecode']; ?></td>
                                        <td style="text-align: center;"><?php echo $usagemainrecord1['Usagemainmain']['usage_main_catg_desc_en']; ?></td>
                                        <td style="text-align: center;">
                                            <button id="btnupdate" name="btnupdate" class="btn btn-default " style="text-align: center;" 
                                                    onclick="javascript: return formupdate(
                                                                    ('<?php echo $usagemainrecord1['Usagemainmain']['usage_main_catg_desc_ll']; ?>'),
                                                                    ('<?php echo $usagemainrecord1['Usagemainmain']['usage_main_catg_desc_en']; ?>'),
                                                                    ('<?php echo $usagemainrecord1['Usagemainmain']['dolr_usgaecode']; ?>'),
                                                                    ('<?php echo $usagemainrecord1['Usagemainmain']['id']; ?>'));">
                                                <span class="glyphicon glyphicon-pencil"></span>
                                            </button>
                                            <button id="btndelete" name="btndelete" class="btn btn-default " style="text-align: center;" 
                                                    onclick="javascript: return formdelete(('<?php echo $usagemainrecord1['Usagemainmain']['id']; ?>'));">
                                                <span class="glyphicon glyphicon-remove"></span>
                                            </button>
                                        </td>
                                    <?php } ?>


                                </tr>
                            <?php endforeach; ?>
                            <?php unset($usagemainrecord1); ?>
                        </tbody>
                    </table>
                    <?php if (!empty($usagemainrecord)) { ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
                </div>
            </div>
        </div>
    </div>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
</div>

<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>




