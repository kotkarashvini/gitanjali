<?php
$result = $this->requestAction(
        array('controller' => 'ValuationRules', 'action' => 'rule_functions'));
$lang = $this->Session->read("sess_langauge");
?>
<div class="row">
    <div class="col-md-12">

        <div class="btn-arrow">
            
                <?php
                $sr_no = 'A';
                foreach ($result as $menu) {
                    if ($menu['rule_functions']['delete_flag'] == 'N') {

//                        if ($this->Session->read('valuation_rule_id') != NULL && $menu['rule_functions']['action'] == 'rule_items_linkage') {
//                            continue;
//                        } else {
                        if ($this->params['action'] == $menu['rule_functions']['action']) {
                            ?> 
                            <a href="<?php echo $this->webroot; ?><?php echo $menu['rule_functions']['controller'] . "/" . $menu['rule_functions']['action']."/".$this->Session->read('csrftoken'); ?>" class="btn bg-maroon btn-arrow-right"><?php echo $menu['rule_functions']['function_desc_' . $lang]; ?></a>            
                        <?php } else { ?>              
                            <a href="<?php echo $this->webroot; ?><?php echo $menu['rule_functions']['controller'] . "/" . $menu['rule_functions']['action']."/".$this->Session->read('csrftoken'); ?>" class="btn btn-success btn-arrow-right"><?php echo $menu['rule_functions']['function_desc_' . $lang]; ?></a>
                            <?php
                        }
//                        }
                    }
                }
                ?>  
            
        </div>
    </div>
</div>
<div  class="rowht">&nbsp;</div>

