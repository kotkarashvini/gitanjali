<div class="container-fluid">
    <div class="col-md-3">  
        <div class="row">
            <div class="col-md-12">
                <!--//on board cases-->
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <span class="title-home-green"><b> <?php echo __('ON Board cases') ?></b>  </span> 
                    </div>
                    <div class="panel-body">
                        <div class="box-sm-scroll padding-sm">
                               <?php $onboardcases = $this->requestAction('/NewCase/onboard_cases_list'); ?>
                            <ul style="padding-left:0px;list-style:none;">
                                 <?php foreach ($onboardcases as $c) {
                                    ?>
                                <li>
                                    <a href="#">                                       
                                        <span class="fa fa-hand-o-right"></span>
                                           <?php
//                                        $newid = $this->requestAction(
//                                                    array('controller' => 'NewCase', 'action' => 'encrypt', $c[0]['case_id'], $this->Session->read("randamkey"),
//                                            ));
                                          $newid=$c[0]['case_id'];
                                            $casetitle = "";
                                            $casetitle.=$c[0]['office_name_en'];
                                            echo $this->Html->link($casetitle, '/NewCase/proceeding_details/' . $newid);
//                                            echo $this->Html->image('new.gif', array('border' => '0'));
                                            ?>
                                    </a>
                                </li>
                                   <?php } ?>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-footer">
                    </div>
                </div> 
            </div> 
        </div>
    </div>

    <!--registered cases-->
    <div class="col-md-3">  
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <span class="title-home-green"><b> <?php echo __('Registered cases') ?></b>  </span> 
                    </div>
                    <div class="panel-body">
                        <div class="box-sm-scroll padding-sm">
                               <?php     $cases = $this->requestAction('/NewCase/registered_cases_list'); ?>
                            <ul class="list-group">
                                 <?php foreach ($cases as $c) {
                                    ?>
                                <li>
                                    <a href="#">                                       
                                        <span class="fa fa-hand-o-right"></span>
                                           <?php
//                                        $newid = $this->requestAction(
//                                                    array('controller' => 'NewCase', 'action' => 'encrypt', $c[0]['case_id'], $this->Session->read("randamkey"),
//                                            ));
                                          $newid=$c[0]['case_id'];
                                            $casetitle = "";
                                            $casetitle.=$c[0]['case_code']."-".$c[0]['case_year']."-".$c[0]['case_type_desc'];
                                            echo $this->Html->link($casetitle, '/NewCase/genernalinfoentry/' . $newid);
//                                            echo $this->Html->image('new.gif', array('border' => '0'));
                                            ?>
                                    </a>
                                </li>
                                   <?php } ?>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-footer">
                    </div>
                </div> 
            </div> 
        </div>
    </div>

    <!--//Datewise status-->
    <div class="col-md-3">  
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <span class="title-home-green"><b> <?php echo __('Datewise case status') ?></b>  </span> 
                    </div>
                    <div class="panel-body">
                        <div class="box-sm-scroll padding-sm">
                               <?php $datewisecases = $this->requestAction('/NewCase/datewise_cases_list'); ?>
                            <ul style="padding-left:0px;list-style:none;">
                                 <?php foreach ($datewisecases as $c) {
                                //  pr($c) ;?>
                                <li>
                                    <a href="#">                                       
                                        <span class="fa fa-hand-o-right"></span>
                                           <?php
//                                        $newid = $this->requestAction(
//                                                    array('controller' => 'NewCase', 'action' => 'encrypt', $c[0]['case_id'], $this->Session->read("randamkey"),
//                                            ));
                                          $newid=$c[0]['case_id'];
                                            $casetitle = "";
                                            $casetitle.=$c[0]['case_admited_date'];
                                            echo $this->Html->link($casetitle, '/NewCase/proceeding_details/' . $newid);
//                                            echo $this->Html->image('new.gif', array('border' => '0'));
                                            ?>
                                    </a>
                                </li>
                                   <?php } ?>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-footer">
                    </div>
                </div>
            </div> 
        </div>
    </div>
    <!--revenue wise status-->
    <div class="col-md-3">  
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <span class="title-home-green"><b> <?php echo __('Revenue wise cases status') ?></b>  </span> 
                    </div>
                    <div class="panel-body">
                        <div class="box-sm-scroll padding-sm">
                               <?php $onboardcases = $this->requestAction('/NewCase/onboard_cases_list'); ?>
                            <ul style="padding-left:0px;list-style:none;">
                                 <?php foreach ($onboardcases as $c) {
                                    ?>
                                <li>
                                    <a href="#">                                       
                                        <span class="fa fa-hand-o-right"></span>
                                           <?php
//                                        $newid = $this->requestAction(
//                                                    array('controller' => 'NewCase', 'action' => 'encrypt', $c[0]['case_id'], $this->Session->read("randamkey"),
//                                            ));
                                          $newid=$c[0]['case_id'];
                                            $casetitle = "";
                                            $casetitle.=$c[0]['office_name_en'];
                                            echo $this->Html->link($casetitle, '/NewCase/proceeding_details/' . $newid);
//                                            echo $this->Html->image('new.gif', array('border' => '0'));
                                            ?>
                                    </a>
                                </li>
                                   <?php } ?>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-footer">
                    </div>
                </div> 
            </div>
        </div> 
    </div>
    <!--officewise status-->
    <div class="col-md-3">  
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <span class="title-home-green"><b> <?php echo __('officewise cases status') ?></b>  </span> 
                    </div>
                    <div class="panel-body">
                        <div class="box-sm-scroll padding-sm">
                               <?php $officewisecases = $this->requestAction('/NewCase/officewise_cases_list'); ?>
                            <ul style="padding-left:0px;list-style:none;">
                                 <?php foreach ($officewisecases as $c) {
                                    ?>
                                <li>
                                    <a href="#">                                       
                                        <span class="fa fa-hand-o-right"></span>
                                           <?php
//                                        $newid = $this->requestAction(
//                                                    array('controller' => 'NewCase', 'action' => 'encrypt', $c[0]['case_id'], $this->Session->read("randamkey"),
//                                            ));
                                          $newid=$c[0]['case_id'];
                                            $casetitle = "";
                                            $casetitle.=$c[0]['office_name_en'];
                                            echo $this->Html->link($casetitle, '/NewCase/proceeding_details/' . $newid);
//                                            echo $this->Html->image('new.gif', array('border' => '0'));
                                            ?>
                                    </a>
                                </li>
                                   <?php } ?>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-footer">
                    </div>
                </div> 

            </div> 
        </div>
    </div>
</div>

