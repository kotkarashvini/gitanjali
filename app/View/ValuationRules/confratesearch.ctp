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


<?php echo $this->Form->create('confratesearch', array('id' => 'confratesearch', 'autocomplete' => 'off')); ?>

<div class="row">
    <div class="col-md-12">
        <div class="note">
            <?php echo __('lblnote'); ?>  <span style="color: #ff0000">*</span> <?php echo __('lblstarmandatorynote'); ?>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblconfratesearch'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Rate/confratesearch.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 

            </div>

            <div class="box-body">

                <div  class="rowht"></div><div  class="rowht"></div>



                <div class="row">
                    <div class="col-md-12"> 
                        <fieldset class='scheduler-border'>
                            <legend class='scheduler-border'><?php   echo __('lblratesearchcondition') ?></legend>
                            <div class="col-sm-3">
                                <label for="developed_land_types_id" class="control-label"><?php echo __('lbldellandtype'); ?> <span class="star">*</span></label>
                                <?php echo $this->Form->input('developed_land_types_id', array('options' => $developldata, 'empty' => '--select--', 'id' => 'developed_land_types_id', 'class' => 'form-control input-sm ', 'label' => false)); ?>
                                <span class="form-error" id="developed_land_types_id_error"></span>
                            </div>

                            <div class="col-sm-3">
                                <label for="usage_main_catg_id" class="control-label"><?php echo __('lblusamaincat'); ?> <span class="star">*</span></label>
                                <?php echo $this->Form->input('usage_main_cat_id', array('options' => $maincdata, 'empty' => '--select--', 'id' => 'usage_main_cat_id', 'class' => 'form-control input-sm ', 'label' => false)); ?>
                                <span class="form-error" id="usage_main_cat_id_error"></span>
                            </div>

                            <div class="col-md-3">
                                <label>
                                    <?php echo __('lblready_reckoner_rate_flag') ?>
                                    <span style="color: #ff0000">*</span>
                                </label>    
                                <?php
                                $options = array('Y' => __('lblyes'), 'N' => __('lblno'));
                                echo $this->Form->input('ready_reckoner_rate_flag', array('label' => false, 'id' => 'ready_reckoner_rate_flag', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $options)));
                                ?>

                                <span id="ready_reckoner_rate_flag_error" class="form-error">       </span>
                            </div>   
                        </fieldset> 
                    </div>
                </div>



                <div  class="rowht"></div><div  class="rowht"></div>
                <fieldset class='scheduler-border'>
                    <legend class='scheduler-border'><?php   echo __('lblratesearchrule') ?></legend>


                    <div class="row">
                        <div class="col-md-12"> 
                            <div class="col-md-3">
                                <label>
                                    <?php echo __('lblfineyer') ?> 
                                    <span style="color: #ff0000">*</span>
                                </label>    
                                <?php
                                $options = array('Y' => __('lblyes'), 'N' => __('lblno'));
                                echo $this->Form->input('finyear_id', array('label' => false, 'id' => 'finyear_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $options)));
                                ?>

                                <span id="finyear_id_error" class="form-error">       </span>
                            </div>
                            <div class="col-md-3">
                                <label>
                                    <?php echo __('lbladmdivision') ?>
                                   
                                </label>    
                                <?php
                                $options = array('Y' => __('lblyes'), 'N' => __('lblno'));
                                echo $this->Form->input('division_id', array('label' => false, 'id' => 'division_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $options)));
                                ?>

                                <span id="division_id_error" class="form-error">       </span>
                            </div> 



                            <div class="col-md-3">
                                <label>
                                    <?php echo __('lbladmdistrict') ?>
                                     
                                </label>    
                                <?php
                                $options = array('Y' => __('lblyes'), 'N' => __('lblno'));
                                echo $this->Form->input('district_id', array('label' => false, 'id' => 'district_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $options)));
                                ?>

                                <span id="district_id_error" class="form-error">       </span>
                            </div>  
                            <div class="col-md-3">
                                <label>
                                    <?php echo __('lbladmsubdiv') ?>
                                     
                                </label>    
                                <?php
                                $options = array('Y' => __('lblyes'), 'N' => __('lblno'));
                                echo $this->Form->input('subdivision_id', array('label' => false, 'id' => 'subdivision_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $options)));
                                ?>

                                <span id="subdivision_id_error" class="form-error">       </span>
                            </div> 
                            <div class="col-md-3">
                                <label>
                                    <?php echo __('lbladmtaluka') ?>
                                     
                                </label>    
                                <?php
                                $options = array('Y' => __('lblyes'), 'N' => __('lblno'));
                                echo $this->Form->input('taluka_id', array('label' => false, 'id' => 'taluka_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $options)));
                                ?>

                                <span id="taluka_id_error" class="form-error">       </span>
                            </div>  
                            <div class="col-md-3">
                                <label>
                                    <?php echo __('lbladmblockvillage') ?>
                                     
                                </label>    
                                <?php
                                $options = array('Y' => __('lblyes'), 'N' => __('lblno'));
                                echo $this->Form->input('village_id', array('label' => false, 'id' => 'village_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $options)));
                                ?>

                                <span id="village_id_error" class="form-error">       </span>
                            </div>  
                            <div class="col-md-3">
                                <label>
                                    <?php echo __('lblulbtypeid') ?>
                                     
                                </label>    
                                <?php
                                $options = array('Y' => __('lblyes'), 'N' => __('lblno'));
                                echo $this->Form->input('ulb_type_id', array('label' => false, 'id' => 'ulb_type_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $options)));
                                ?>

                                <span id="ulb_type_id_error" class="form-error">       </span>
                            </div>  

                        </div>
                    </div>




                    <div class="row">
                        <div class="col-md-12"> 
                            <div class="col-md-3">
                                <label>
                                    <?php echo __('lblusamaincat') ?>
                                    <span style="color: #ff0000">*</span>
                                </label>    
                                <?php
                                $options = array('Y' => __('lblyes'));
                                echo $this->Form->input('usage_main_catg_id', array('label' => false, 'id' => 'usage_main_catg_id', 'class' => 'form-control input-sm', 'options' => array($options)));
                                ?>

                                <span id="usage_main_catg_id_error" class="form-error">       </span>
                            </div> 


                            <div class="col-md-3">
                                <label>
                                    <?php echo __('lblsubcat') ?>
                                    <span style="color: #ff0000">*</span>
                                </label>    
                                <?php
                                $options = array('Y' => __('lblyes'));
                                echo $this->Form->input('usage_sub_catg_id', array('label' => false, 'id' => 'usage_sub_catg_id', 'class' => 'form-control input-sm', 'options' => array($options)));
                                ?>

                                <span id="usage_sub_catg_id_error" class="form-error">       </span>
                            </div>   


                        </div>
                    </div>


                    <div  class="rowht"></div><div  class="rowht"></div>



                    <div class="row">
                        <div class="col-md-12"> 


                            <div class="col-md-3">
                                <label>
                                    <?php echo __('lblvalzone') ?>
                                     
                                </label>    
                                <?php
                                $options = array('Y' => __('lblyes'), 'N' => __('lblno'));
                                echo $this->Form->input('valutation_zone_id', array('label' => false, 'id' => 'valutation_zone_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $options)));
                                ?>

                                <span id="valutation_zone_id_error" class="form-error">       </span>
                            </div>  
                            <div class="col-md-3">
                                <label>
                                    <?php echo __('lblvalsubzone') ?> 
                                </label>    
                                <?php
                                $options = array('Y' => __('lblyes'), 'N' => __('lblno'));
                                echo $this->Form->input('valutation_subzone_id', array('label' => false, 'id' => 'valutation_subzone_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $options)));
                                ?>

                                <span id="valutation_subzone_id_error" class="form-error">       </span>
                            </div>   


                        </div>
                    </div>






                    <div  class="rowht"></div><div  class="rowht"></div>



                    <div class="row">
                        <div class="col-md-12"> 

                            <div class="col-md-3">
                                <label>
                                    <?php echo __('lblconstuctiontye') ?> 
                                </label>    
                                <?php
                                $options = array('Y' => __('lblyes'), 'N' => __('lblno'));
                                echo $this->Form->input('construction_type_id', array('label' => false, 'id' => 'construction_type_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $options)));
                                ?>

                                <span id="construction_type_id_error" class="form-error">       </span>
                            </div>  
                            <div class="col-md-3">
                                <label>
                                    <?php echo __('lblroadvicinity') ?> 
                                </label>    
                                <?php
                                $options = array('Y' => __('lblyes'), 'N' => __('lblno'));
                                echo $this->Form->input('road_vicinity_id', array('label' => false, 'id' => 'road_vicinity_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $options)));
                                ?>

                                <span id="road_vicinity_id_error" class="form-error">       </span>
                            </div>   


                        </div>
                    </div>





                    <div  class="rowht"></div><div  class="rowht"></div>



                    




                    <div  class="rowht"></div><div  class="rowht"></div>



                    <div class="row">
                        <div class="col-md-12"> 

                            <div class="col-md-3">
                                <label>
                                    <?php echo __('lbluserdefineddependency1') ?>
                                     
                                </label>    
                                <?php
                                $options = array('Y' => __('lblyes'), 'N' => __('lblno'));
                                echo $this->Form->input('user_defined_dependency1_id', array('label' => false, 'id' => 'user_defined_dependency1_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $options)));
                                ?>

                                <span id="user_defined_dependency1_id_error" class="form-error">       </span>
                            </div>  
                            <div class="col-md-3">
                                <label>
                                    <?php echo __('lbluserdefineddependency2') ?>
                                     
                                </label>    
                                <?php
                                $options = array('Y' => __('lblyes'), 'N' => __('lblno'));
                                echo $this->Form->input('user_defined_dependency2_id', array('label' => false, 'id' => 'user_defined_dependency2_id', 'class' => 'form-control input-sm', 'options' => array('empty' => '--Select--', $options)));
                                ?>

                                <span id="user_defined_dependency2_id_error" class="form-error">       </span>
                            </div>   



                        </div>
                    </div>
                </fieldset> 
                <?php echo $this->Form->input('search_id', array('label' => false, 'type' => 'hidden')); ?>
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

                        <a href="<?php echo $this->webroot; ?>ValuationRules/confratesearch" class="btn btn-info "><?php echo __('btncancel'); ?></a>

                    </div>
                </div>
            </div>

        </div>

        <div class="box box-primary">

            <div class="box-body">
                <div id="selectbehavioural">
                    <table id="tablebank" class="table table-striped table-bordered table-hover" >
                        <thead >  
                            <tr>  
                                <th class="center width10"><?php echo __('lbldellandtype'); ?></th>
                                <th class="center width10"><?php echo __('lblusamaincat'); ?></th>
                                <th class="center width10"><?php echo __('lblready_reckoner_rate_flag'); ?></th>
                                <th class="center width10"><?php echo __('lblaction'); ?></th>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php
                            foreach ($result1 as $confratesearch1) {
                                // pr($confratesearch1);
                                //exit;
                                ?>
                                <tr>     

                                    <td><?php echo $confratesearch1[0]['developed_land_types_desc_' . $lang]; ?></td>
                                    <td><?php echo $confratesearch1[0]['usage_main_catg_desc_' . $lang]; ?></td>
                                    <td><?php echo $confratesearch1[0]['ready_reckoner_rate_flag']; ?></td>
                                    <td>
                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-pencil')), array('action' => 'confratesearch', $confratesearch1[0]['search_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Edit'), 'class' => "btn btn-success"), array('Are you sure?')); ?></a>

                                        <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'delete_confratesearch', $confratesearch1[0]['search_id']), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-danger"), array('Are you sure?')); ?></a>
                                    </td>

                                <?php } ?>
                                <?php //unset($confratesearch1);  ?>
                        </tbody>
                    </table>
                    <?php if (!empty($confratesearch)) { ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
                </div>
            </div>
        </div>


    </div>

</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>




