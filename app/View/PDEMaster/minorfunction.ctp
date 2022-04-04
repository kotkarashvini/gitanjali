<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>
<?php
echo $this->Html->script('dataTables.bootstrap');
echo $this->Html->script('jquery.dataTables');
?>
<script>
    $(document).ready(function () {
        $('#tablebank').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
    });</script>


<?php echo $this->Form->create('minorfunction', array('id' => 'minorfunction', 'autocomplete' => 'off')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="note">
            <?php echo __('lblnote'); ?>  <span style="color: #ff0000">*</span> <?php echo __('lblstarmandatorynote'); ?>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblminorfunctionconfig'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/admin/minorfunction_<?php echo $lang; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 

            </div>
             <?php if (isset($editflag)) { ?>
            <div class="box-body">
                <!--                <div class="row" id="selectminorfunction">
                                    <div class="form-group">
                                        <label for="mf_id" class="col-sm-3 control-label"><?php //echo __('lblselectmajordescription');         ?><span style="color: #ff0000">*</span></label> 
                                        <div class="col-sm-3">
                <?php //echo $this->Form->input('major_id', array('label' => false, 'id' => 'major_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $mfdata))); ?>
                                         <span id="major_id_error" class="form-error"><?php //echo $errarr['major_id_error'];         ?></span>
                                        </div>
                                    </div> 
                                </div>-->
                <div  class="rowht"></div><div  class="rowht"></div>
                <div class="row">
                    <div class="col-md-12">
                        <?php foreach ($languagelist as $key => $langcode) { ?>
                            <div class="col-md-3">
                                <label>
                                    <?php echo __('lblminorfunctiondescription') . " [ " . $langcode['mainlanguage']['language_name']." ]"; ?>
                                    <span style="color: #ff0000">*</span>
                                </label>    
                                <?php
                                echo $this->Form->input('function_desc_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'function_desc_' . $langcode['mainlanguage']['language_code'],
                                    'class' => 'form-control input-sm',
                                    'type' => 'text',
                                    'maxlength' => '200'))
                                ?>
                                <span id="<?php echo 'function_desc_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" 
                                      class="form-error">
                                          <?php echo $errarr['function_desc_' . $langcode['mainlanguage']['language_code'] . '_error']; ?>
                                </span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div  class="rowht"></div><div  class="rowht"></div>
                <div class="row">
                    <div class="col-md-12">                       
                        <!--                        <div class="col-md-3">
                                                    <label>
                        <?php echo __('lblcontroller') ?>
                                                        <span style="color: #ff0000">*</span>
                                                    </label>    
                        <?php
                        echo $this->Form->input('controller', array('label' => false, 'id' => 'controller',
                            'class' => 'form-control input-sm',
                            'type' => 'text',
                            'maxlength' => '200'))
                        ?>
                                                    <span id="<?php echo 'controller' . '_error'; ?>" class="form-error">       </span>
                                                </div>   -->
                        <!--                        <div class="col-md-3">
                                                    <label>
                        <?php echo __('lblaction') ?>
                                                        <span style="color: #ff0000">*</span>
                                                    </label>    
                        <?php
                        echo $this->Form->input('action', array('label' => false, 'id' => 'action',
                            'class' => 'form-control input-sm',
                            'type' => 'text',
                            'maxlength' => '200'))
                        ?>
                                                    <span id="<?php echo 'controller' . '_error'; ?>" class="form-error">       </span>
                                                </div> -->

                        <div class="col-md-3">
                            <label>
                                <?php echo __('lblDisplayOrder') ?>
                                <span style="color: #ff0000">*</span>
                            </label>    
                            <?php
                            echo $this->Form->input('mf_serial', array('label' => false, 'id' => 'mf_serial',
                                'class' => 'form-control input-sm',
                                'type' => 'text',
                                'maxlength' => '20'))
                            ?>
                            <span id="<?php echo 'mf_serial' . '_error'; ?>" class="form-error">       </span>
                        </div> 


                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12"> 
                        <div class="col-md-3">
                            <label>
                                <?php echo __('lbldisplayflag') ?>
                                <span style="color: #ff0000">*</span>
                            </label>    
                            <?php
                            $options = array('O' => __('lbloptional'), 'C' => __('lblcompulsory'));
                            echo $this->Form->input('dispaly_flag', array('label' => false, 'id' => 'dispaly_flag', 'class' => 'form-control input-sm', 'empty' => '--Select--', 'options' => array($options)));
                            ?>

                            <span id="display_flag_error" class="form-error">       </span>
                        </div>   
                        <div class="col-md-3">
                            <label>
                                <?php echo __('lblstatusflag') ?>
                                <span style="color: #ff0000">*</span>
                            </label>    
                            <?php
                            $options = array('Y' => __('lblyes'), 'N' => __('lblno'));
                            echo $this->Form->input('status_flag', array('label' => false, 'id' => 'status_flag', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $options)));
                            ?>

                            <span id="status_flag_error" class="form-error">       </span>
                        </div>   

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12"> 

                        <div class="col-md-3">
                            <label>
                                <?php echo __('lblcitizen_flag') ?>
                                <span style="color: #ff0000">*</span>
                            </label>    
                            <?php
                            $options = array('Y' => __('lblyes'), 'N' => __('lblno'));
                            echo $this->Form->input('citizen_flag', array('label' => false, 'id' => 'citizen_flag', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $options)));
                            ?>

                            <span id="citizen_flag_error" class="form-error">       </span>
                        </div>  
                        <div class="col-md-3">
                            <label>
                                <?php echo __('lblsro_menu_flag') ?>
                                <span style="color: #ff0000">*</span>
                            </label>    
                            <?php
                            $options = array('Y' => __('lblyes'), 'N' => __('lblno'));
                            echo $this->Form->input('sro_menu_flag', array('label' => false, 'id' => 'sro_menu_flag', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $options)));
                            ?>

                            <span id="manual_reg_flag_error" class="form-error">       </span>
                        </div>  
                        <div class="col-md-3">
                            <label>
                                <?php echo __('lblmanual_reg_flag') ?>
                                <span style="color: #ff0000">*</span>
                            </label>    
                            <?php
                            $options = array('Y' => __('lblyes'), 'N' => __('lblno'));
                            echo $this->Form->input('manual_reg_flag', array('label' => false, 'id' => 'manual_reg_flag', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $options)));
                            ?>

                            <span id="manual_reg_flag_error" class="form-error">       </span>
                        </div>   


                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12"> 

                        <div class="col-md-3">
                            <label>
                                <?php echo __('lble_reg_menu') ?>
                                <span style="color: #ff0000">*</span>
                            </label>    
                            <?php
                            $options = array('Y' => __('lblyes'), 'N' => __('lblno'));
                            echo $this->Form->input('e_reg_menu', array('label' => false, 'id' => 'e_reg_menu', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $options)));
                            ?>

                            <span id="citizen_flag_error" class="form-error">       </span>
                        </div>  



                        <div class="col-md-3">
                            <label>
                                <?php echo __('lble_filing_flag') ?>
                                <span style="color: #ff0000">*</span>
                            </label>    
                            <?php
                            $options = array('Y' => __('lblyes'), 'N' => __('lblno'));
                            echo $this->Form->input('e_filing_flag', array('label' => false, 'id' => 'e_filing_flag', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $options)));
                            ?>

                            <span id="manual_reg_flag_error" class="form-error">       </span>
                        </div>  

                        <div class="col-md-3">
                            <label>
                                <?php echo __('lblleave_licence_flag') ?>
                                <span style="color: #ff0000">*</span>
                            </label>    
                            <?php
                            $options = array('Y' => __('lblyes'), 'N' => __('lblno'));
                            echo $this->Form->input('leave_licence_flag', array('label' => false, 'id' => 'leave_licence_flag', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $options)));
                            ?>

                            <span id="manual_reg_flag_error" class="form-error">       </span>
                        </div> 

                    </div>
                </div>



                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-3">
                            <label>
                                <?php echo __('lblcidco_citizen_flag') ?>
                                <span style="color: #ff0000">*</span>
                            </label>    
                            <?php
                            $options = array('Y' => __('lblyes'), 'N' => __('lblno'));
                            echo $this->Form->input('cidco_citizen_flag', array('label' => false, 'id' => 'cidco_citizen_flag', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $options)));
                            ?>

                            <span id="manual_reg_flag_error" class="form-error">       </span>
                        </div> 



                        <div class="col-md-3">
                            <label>
                                <?php echo __('lbldelete_flag') ?>
                                <span style="color: #ff0000">*</span>
                            </label>    
                            <?php
                            $options = array('Y' => __('lblyes'), 'N' => __('lblno'));
                            echo $this->Form->input('delete_flag', array('label' => false, 'id' => 'delete_flag', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $options)));
                            ?>

                            <span id="delete_flag_error" class="form-error">       </span>
                        </div>
                    </div>
                </div>
                 <?php echo $this->Form->input('minor_id', array('label' => false, 'type' => 'hidden')); ?>
                <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
                 
                <div  class="rowht"></div>
                <div  class="rowht"></div><div  class="rowht"></div>
                <div class="row center" >
                    <div class="form-group" >
                        <?php if (isset($editflag)) { ?>
                            <button id="btnadd" name="btnadd" class="btn btn-info ">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnupdate'); ?>
                            </button>
                        <?php } else { ?>
                            <button id="btnadd" name="btnadd" class="btn btn-info ">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('btnsave'); ?>
                            </button>
                        <?php } ?>

                        <a href="<?php echo $this->webroot; ?>PDEMaster/minorfunction" class="btn btn-info "><?php echo __('btncancel'); ?></a>

                    </div>
                </div>
            </div>
             <?php } ?>
        </div>

        <div class="box box-primary">

            <div class="box-body">
                <div id="selectbehavioural">
                    <table id="tablebank" class="table table-striped table-bordered table-hover" >
                        <thead >  
                            <tr>  
                                <th class="center width10"><?php echo __('lblfunctionid'); ?></th>
                                <th class="center width10"><?php echo __('lblfunctions'); ?></th>
                                <?php foreach ($languagelist as $langcode) { ?>
                                    <th class="center"><?php echo __('lblminorfunctiondescription') . " (" . $langcode['mainlanguage']['language_name'] . ")"; ?></th>
                                <?php } ?>
                                <th class="center width10"><?php echo __('lbldelete_flag'); ?></th>   
                                <th class="center width10"><?php echo __('lblaction'); ?></th>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php foreach ($minorfunction as $minorfunction1): ?>
                                <tr>     
                                    <td ><?php echo $minorfunction1['minorfunction']['id']; ?></td>
                                    <td ><?php echo $minorfunction1['minorfunction']['function_desc']; ?></td>
                                    <?php foreach ($languagelist as $langcode) { ?>
                                        <td ><?php echo $minorfunction1['minorfunction']['function_desc_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                    <?php } ?>
                                    <td>
                                        <?php
                                        if ($minorfunction1['minorfunction']['delete_flag'] == 'Y') {
                                            echo __('lblyes');
                                        } else {
                                            echo __('lblno');
                                        }
                                        ?>

                                    </td>
                                    <td>
                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-pencil')), array('action' => 'minorfunction', $minorfunction1['minorfunction']['minor_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Edit'), 'class' => "btn btn-success"), array('Are you sure to Edit?')); ?></a>

                                      <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'delete_minorfunction_dev', $minorfunction1['minorfunction']['minor_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-danger"), array('Are you sure to delete?')); ?></a>
                                    </td>

                                <?php endforeach; ?>
                                <?php unset($minorfunction1); ?>
                        </tbody>
                    </table>
                    <?php if (!empty($minorfunction)) { ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
                </div>
            </div>
        </div>


    </div>

</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>




