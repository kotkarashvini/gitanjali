

<?php

$result = $this->requestAction(array('controller' => 'NewCase', 'action' => 'major_functions')); ?>
<div class="btn-group btn-group-justified">
    <?php
    foreach ($result as $menu) {
        if ($this->params['action'] == $menu['casemainmenus']['action']) {
            ?> 
            <a href="<?php echo $this->webroot; ?><?php echo $menu['casemainmenus']['controller'] . "/" . $menu['casemainmenus']['action']; ?>" class="btn btn-primary col-md-2"><?php echo $menu['casemainmenus']['function_desc']; ?></a>            
        <?php } else { ?>              
            <a href="<?php echo $this->webroot; ?><?php echo $menu['casemainmenus']['controller'] . "/" . $menu['casemainmenus']['action']; ?>" class="btn btn-primary  col-md-2"><?php echo $menu['casemainmenus']['function_desc']; ?></a>
            <?php
        }
    }
    ?>  
</div>
<div  class="rowht">&nbsp;</div>
<?php  ?>
