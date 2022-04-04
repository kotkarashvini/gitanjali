

<?php
if ($this->Session->read("user_role_id") == '999901' || $this->Session->read("user_role_id") == '999902' || $this->Session->read("user_role_id") == '999903') {
$result = $this->requestAction(array('controller' => 'ManualReg', 'action' => 'major_functions')); 
$lang=$this->Session->read("sess_langauge");
?>
<div class="btn-group btn-group-justified">

    <?php
    foreach ($result as $menu) {
        if ($this->params['action'] == $menu['majorfunction']['action']) {
            ?> 
            <a href="<?php echo $this->webroot; ?><?php echo $menu['majorfunction']['controller'] . "/" . $menu['majorfunction']['action']; ?>" class="btn btn-primary col-md-2"><?php echo $menu['majorfunction']['function_desc_'.$lang]; ?></a>            
        <?php } else { ?>              
            <a href="<?php echo $this->webroot; ?><?php echo $menu['majorfunction']['controller'] . "/" . $menu['majorfunction']['action']; ?>" class="btn btn-primary  col-md-2"><?php echo $menu['majorfunction']['function_desc_'.$lang]; ?></a>
            <?php
        }
    }
    ?>  
</div>
<div  class="rowht">&nbsp;</div>
<?php } ?>
