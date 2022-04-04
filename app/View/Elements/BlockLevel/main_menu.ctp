




   <div class="btn-arrow">
                    <?php
                      $data = $this->requestAction(array('controller' => 'BlockLevels', 'action' => 'block_levels_main_menu'));   
                        
                      $data = @$data['adminLevelConfig'];
                //pr($data);
                    if (!is_null($data)) {
                        ?>

                        <?php if ($data['is_state'] == 'Y') { ?>
                            <a class="btn bg-maroon btn-arrow-right"><?php echo $data['statename_' . $laug]; ?></a>
                        <?php } ?>

                        <?php if ($data['is_div'] == 'Y') { ?>
                            <a class="btn bg-maroon btn-arrow-right" href="<?php echo $this->webroot;?>BlockLevels/division_new"><?php echo $data['divisionname_' . $laug]; ?></a>
                        <?php } ?>

                        <?php if ($data['is_dist'] == 'Y') { ?>
                            <a class="btn bg-maroon btn-arrow-right" href="<?php echo $this->webroot;?>BlockLevels/district_new"><?php echo $data['districtname_' . $laug]; ?></a>
                        <?php } ?>
                        <?php if ($data['is_subdiv'] == 'Y') { ?>
                            <a class="btn bg-maroon btn-arrow-right" href="<?php echo $this->webroot;?>BlockLevels/subdivision"><?php echo $data['subdivname_' . $laug]; ?></a>
                        <?php } ?>
                        <?php if ($data['is_taluka'] == 'Y') { ?>
                            <a class="btn bg-maroon btn-arrow-right" href="<?php echo $this->webroot;?>BlockLevels/taluka"><?php echo $data['talukaname_' . $laug]; ?></a>
                        <?php } ?>

                        <?php if ($data['is_circle'] == 'Y') { ?>
                            <a class="btn bg-maroon btn-arrow-right" href="<?php echo $this->webroot;?>BlockLevels/circle"><?php echo $data['circlename_' . $laug]; ?></a>
                        <?php } ?>
                              <a class="btn bg-maroon btn-arrow-right" href="<?php echo $this->webroot;?>BlockLevels/local_governing_body"><?php echo __('lbllocalgoberningbody'); ?></a>
                              <a class="btn bg-maroon btn-arrow-right" href="<?php echo $this->webroot;?>BlockLevels/locgovbodylist"><?php echo __('lbllocalgovbodylist'); ?></a>
                              <a class="btn bg-maroon btn-arrow-right" href="<?php echo $this->webroot;?>BlockLevels/developltype"><?php echo __('lbldellandtype'); ?></a>
                        
                              
                        <?php if ($data['is_village'] == 'Y') { ?>
                            <a class="btn bg-maroon btn-arrow-right" href="<?php echo $this->webroot;?>BlockLevels/village"><?php echo $data['villagename_' . $laug]; ?></a>
    <?php } ?>


                    <?php }
                    ?>
                </div>  