<?php
$result = $this->requestAction(
        array('controller' => 'NewCase', 'action' => 'minor_functions'));
?>
<div class="row">
    <div class="col-md-12">
        <div class="btn-arrow">
          
                <?php
                $sr_no = 'A';
                foreach ($result as $menu) {
                    if ($this->params['action'] == $menu['casemenus']['action']) {
                        ?> 
                        <a href="<?php echo $this->webroot; ?><?php echo $menu['casemenus']['controller'] . "/" . $menu['casemenus']['action']; ?>" class="btn bg-maroon btn-arrow-right"><?php echo $sr_no++ . $menu['casemenus']['function_desc']; ?></a>            
                    <?php } else { ?>              
                        <a href="<?php echo $this->webroot; ?><?php echo $menu['casemenus']['controller'] . "/" . $menu['casemenus']['action']; ?>" class="btn btn-success btn-arrow-right"><?php echo $sr_no++ . $menu['casemenus']['function_desc']; ?></a>
                        <?php
                    }
                }
                ?>  
          
        </div>
    </div>
</div>
<div  class="rowht">&nbsp;</div>
