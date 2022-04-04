
<?php echo $this->element("NewCase/main_menu"); ?>

<div class="row">
    <div class="col-md-4">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h4 class="box-title"><b><?php echo __('ON Board cases') ?></b></h4>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                    <?php $onboardcases = $this->requestAction('/NewCase/onboard_cases_list'); ?>
                <ul class="products-list product-list-in-box product_scroll">
                      <?php foreach ($onboardcases as $c) {  ?>
                    <li class="item">
                        <div class="product-img">
                            <i class="fa fa-fw fa-hand-o-right"></i> 
                        </div>
                        <div class="product-info-one">
                            <a href="#" class="product-title">
                            <?php
                           // pr($c);exit;
                            
                                    $newid=$c[0]['case_id'];
                                     $case_code=$c[0]['case_code'];
                                      $case_year=$c[0]['case_year'];
                                    $casetitle = "";
                                    $casetitle.=$c[0]['case_type_desc']."-".$case_code."-".$case_year;
                                    echo $casetitle;
                                    //    echo $this->Html->link($casetitle, '/NewCase/proceeding_details/' . $newid);
                             ?>
                            </a>
                        </div>
                    </li> 
                    <?php } ?>
                </ul>
            </div>
            <div class="box-footer text-center">
                <a href="javascript:void(0)" class="uppercase">View All On Board Cases</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><b><?php echo __('Registered cases') ?></b></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                    <?php     $cases = $this->requestAction('/NewCase/registered_cases_list'); ?>
                <ul class="products-list product-list-in-box product_scroll">
                      <?php foreach ($cases as $c) { ?>
                    <li class="item">
                        <div class="product-img">
                            <i class="fa fa-fw fa-hand-o-right"></i> 
                        </div>
                        <div class="product-info-one">
                            <a href="#" class="product-title">
                                    <?php
//                                    pr($c);exit;
//                                     $case_type_id = $this->requestAction(
//                                        array('controller' => 'NewCase', 'action' => 'encrypt', $c[0]['case_type_id'], $this->Session->read("randamkey"),
//                                ));
                                         $case_type_id=$c[0]['case_type_id'];
                                            $casetitle = "";
                                            $casetitle.=$c[0]['case_type_desc'];
                                            echo $this->Html->link($casetitle, '/NewCase/status_info/case/' . $case_type_id);
                                    ?>
                                <span class="label label-warning pull-right"><?php echo $c[0]['count'];?></span>
                            </a>
                        </div>
                    </li>
                     <?php } ?>
                </ul>
            </div>
            <div class="box-footer text-center">
                <a href="javascript:void(0)" class="uppercase">View All Registered Cases</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><b><?php echo __('Datewise case status') ?></b></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                    <?php $datewisecases = $this->requestAction('/NewCase/datewise_cases_list'); ?>
                <ul class="products-list product-list-in-box product_scroll">
                      <?php foreach ($datewisecases as $c) {  ?>
                    <li class="item">
                        <div class="product-img">
                            <i class="fa fa-fw fa-hand-o-right"></i> 
                        </div>
                        <div class="product-info-one">
                            <a href="#" class="product-title">
                            <?php
//                              $newid = $this->requestAction(
//                                        array('controller' => 'NewCase', 'action' => 'encrypt', $c[0]['case_id'], $this->Session->read("randamkey"),
//                                ));
                                  $newid=$c[0]['case_id'];
                                            $casetitle = "";
                                            $casetitle.=$c[0]['case_admited_date'];
                                            echo $this->Html->link($casetitle, '/NewCase/proceeding_details/' . $newid);
                             ?>
                            </a>
                        </div>
                    </li> 
                    <?php } ?>
                </ul>
            </div>
            <div class="box-footer text-center">
                <a href="javascript:void(0)" class="uppercase">View All Date Wise Cases</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><b><?php echo __('Revenue wise cases status') ?></b></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                    <?php $onboardcases1 = $this->requestAction('/NewCase/revenue_cases_list');
                    //pr($onboardcases1);exit;?>
                <ul class="products-list product-list-in-box product_scroll">
                      <?php foreach ($onboardcases1 as $c) { 
                         // pr($c);exit;?>
                    <li class="item">
                        <div class="product-img">
                            <i class="fa fa-fw fa-hand-o-right"></i> 
                        </div>
                        <div class="product-info-one">
                            <a href="#" class="product-title">
                            <?php
//                                            $newid=$c[0]['case_id'];
//                                            $casetitle = "";
                                            $casetitle=$c[0]['office_name_en'];
                                            echo $casetitle;
                             ?>
                            </a>
                        </div>
                    </li> 
                    <?php } ?>
                </ul>
            </div>
            <div class="box-footer text-center">
                <a href="javascript:void(0)" class="uppercase">View All Revenue Wise Cases</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><b><?php echo __('officewise cases status') ?></b></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                    <?php $officewisecases = $this->requestAction('/NewCase/officewise_cases_list'); ?>
                <ul class="products-list product-list-in-box product_scroll">
                      <?php foreach ($officewisecases as $c) {  ?>
                    <li class="item">
                        <div class="product-img">
                            <i class="fa fa-fw fa-hand-o-right"></i> 
                        </div>
                        <div class="product-info-one">
                            <a href="#" class="product-title">
                            <?php
                                           $office_id=$c[0]['office_id'];
                                            $casetitle = "";
                                            $casetitle.=$c[0]['office_name_en'];
                                            echo $this->Html->link($casetitle, '/NewCase/status_info/office/' . $office_id);
                             ?>
                                <span class="label label-warning pull-right"><?php echo $c[0]['count'];?></span>
                            </a>
                        </div>
                    </li> 
                    <?php } ?>
                </ul>
            </div>
            <div class="box-footer text-center">
                <a href="javascript:void(0)" class="uppercase">View All Office Wise Cases</a>
            </div>
        </div>
    </div>
</div>


