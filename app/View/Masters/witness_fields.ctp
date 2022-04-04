<?php $doc_lang = $this->Session->read('doc_lang'); ?>
<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>

<script>
    $(document).ready(function () {




//        if ($('#hfhidden1').val() === 'Y')
//        {
//            $('#tableconfinfo').dataTable({
//                "iDisplayLength": 10,
//                "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
//            });
//        } else {
//            $('#tableconfinfo').dataTable({
//                "iDisplayLength": 10,
//                "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
//            });
//        }

    });

</script>

<?php echo $this->Form->create('witness_fields', array('id' => 'witness_fields', 'autocomplete' => 'off')); ?>
<?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
<div class="row">
    <div class="col-lg-12">
        <div class=" pull-left"> <b style="color:red">Note: <span style="font-size:18px;">'*'</span> indicates mandatory fields.</b></div><br>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblwitnessfields'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Masters/witness_fields_<?php echo $doc_lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div>
            </div>
            <div class="box-body">
                <div class='rowht'></div><br>
                    <table id="tableconfinfo" class="table table-striped table-bordered table-hover" >
                        <thead >  
                            <tr>  
                                <?php foreach ($languagelist as $langcode) { ?>
                                    <th class="center"><?php echo __('lbldesc'); ?> <?php echo " (" . $langcode['mainlanguage']['language_name'] . ")"; ?></th>
                                <?php } ?>
                                <th class="center width10"><?php echo __('lbldisplayonparty'); ?></th>

                            </tr>  
                        </thead>
                        <tbody>


                            <?php
//  creating dyanamic text boxes using same array of config language

                            foreach ($fields as $fields) {
                                $i = 0;
                                ?>
                                <tr>
                                    <?php
                                    foreach ($languagelist as $langcode) {
                                        ?>
                                        <td class="tblbigdata"><?php echo $fields['witness_fields']['field_name_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                    <?php } ?>
                                    <td class="tblbigdata"><input type="checkbox" name="display_flag<?php echo $fields['witness_fields']['field_id']; ?>" id="display_flag<?php echo $fields['witness_fields']['field_id']; ?>" value=<?php echo $fields['witness_fields']['field_id']; ?> <?php if ($fields['witness_fields']['display_flag'] == 'Y') { ?> checked <?php } ?>/></td>

                                </tr>
                                <?php
                                $i++;
                            }
                            ?>
                        </tbody>
                    </table> 
                    <input type='hidden' value='1' name='actiontype' id='actiontype'/>
                    <div class="rowht"></div><div class="rowht"></div>
                    <div class="row center">
                        <div class="form-group">
                            <div class="col-sm-12 tdselect">
                                <button id="btnadd"  class="btn btn-info "  onclick="javascript: return formadd();">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp; <?php echo __('btnsave'); ?></button>

                            </div>
                        </div>
                    </div>
                    <?php echo $this->Form->end(); ?>
            </div>
        </div> 
    </div>
    <input type='hidden' value='<?php // echo$actiontypeval;                   ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php //echo $hfactionval;                   ?>' name='hfaction' id='hfaction'/>
    <input type='hidden' value='<?php //echo $hfid;                 ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php //echo $hfupdateflag;                ?>' name='hfupdateflag' id='hfupdateflag'/>
</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>




