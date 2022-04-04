<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>

<script>
    $(document).ready(function () { 
        $('#tablebehavioural').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
    });
</script>



<?php echo $this->Form->create('Behavioural', array('id' => 'Behavioural',  'autocomplete' => 'off')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="note">
            <?php echo __('lblnote'); ?>  <span style="color: #ff0000">*</span> <?php echo __('lblstarmandatorynote'); ?>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblbehavourdescription'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Behaviour/Behavioural_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="col-md-12">

                    <?php foreach ($languagelist as $key => $langcode) { ?>
                        <div class="form-group col-lg-3">
                            <label>
                                <?php echo __('lblbehavourdescription') . "  " . $langcode['mainlanguage']['language_name']; ?>
                                <span style="color: #ff0000">*</span>
                            </label>  

                            <?php
                            echo $this->Form->input('behavioral_desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'behavioral_desc_' . $langcode['mainlanguage']['language_code'],
                                'class' => 'form-control',
                                'type' => 'text',
                                'maxlength' => '200'))
                            ?>
                            <span id="<?php echo 'behavioral_desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" 
                                  class="form-error">
                                      <?php echo $errarr['behavioral_desc_' . $langcode['mainlanguage']['language_code'] . '_error']; ?>
                            </span>
                        </div>
                    <?php } ?>
                    <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
                     <?php echo $this->Form->input('behavioral_id', array('label' => false, 'id' => 'behavioral_id', 'type' => 'hidden', 'class' => 'form-control')); ?>

                </div>
                <div  class="rowht"></div>
                <div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center">
                    <div class="form-group">
                        <div class="col-sm-12 tdselect">
                            <br>
                            <?php if (isset($editflag)) { ?>
                                <button id="btnadd" name="btnadd" class="btn btn-info ">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnupdate'); ?>
                                </button>
                            <?php } else { ?>
                                <button id="btnadd" name="btnadd" class="btn btn-info ">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnsave'); ?>
                                </button>
                            <?php } ?>

                            <a href="<?php echo $this->webroot; ?>PDEMaster/behavioural" class="btn btn-info "><?php echo __('btncancel'); ?></a>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-primary">

            <div class="box-body">
                <div id="selectbehavioural">
                    <table id="tablebehavioural" class="table table-striped table-bordered table-hover" >
                        <thead >  
                            <tr>  
                                <?php foreach ($languagelist as $langcode) { ?>
                                    <th class="center"><?php echo __('lblbehavourdescription') . " (" . $langcode['mainlanguage']['language_name'] . ")"; ?></th>
                                <?php } ?>
                                <th class="center width10"><?php echo __('lblaction'); ?></th>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php foreach ($behaviouralrecord as $behaviouralrecord1): ?>
                                <tr>
                                    <?php foreach ($languagelist as $langcode) { ?>
                                        <td><?php echo $behaviouralrecord1['Behavioural']['behavioral_desc_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                    <?php } ?>
                                    <td> 
                                        <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-pencil')), array('action' => 'behavioural', $behaviouralrecord1['Behavioural']['behavioral_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Edit'), 'class' => "btn-sm btn-success"), array('Are you sure?')); ?>
                                        <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'delete_behavioural', $behaviouralrecord1['Behavioural']['id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn-sm btn-danger"), array('Are you sure?')); ?>
                                    </td>

                                <?php endforeach; ?>
                                <?php unset($behaviouralrecord1); ?>
                        </tbody>
                    </table>
                    <?php if (!empty($behaviouralrecord)) { ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
                </div>
            </div>
        </div>


    </div> 
</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>




