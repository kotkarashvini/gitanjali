<?php
$lang=$this->Session->read('sess_langauge');
if($this->Session->read("user_role_id")==1)
{
$result = $this->requestAction(array('controller' => 'Citizenentry', 'action' => 'major_functions')); ?>


<div class="btn-group btn-group-justified">

    <?php
    foreach ($result as $menu) {
         if($lang=='ll' && $menu['majorfunction']['major_activation_flag']=='Y')
             $flgset=1;
         else
            $flgset=0;
        if ($this->params['action'] == $menu['majorfunction']['action']) {
            ?> 
            <a href="<?php echo $this->webroot; ?><?php echo $menu['majorfunction']['controller'] . "/" . $menu['majorfunction']['action']; ?>" class="btn btn-primary col-md-2"><?php if($flgset==1) echo $menu['majorfunction']['function_desc_ll']; else echo $menu['majorfunction']['function_desc_en']; ?></a>            
        <?php } else { ?>              
            <a href="<?php echo $this->webroot; ?><?php echo $menu['majorfunction']['controller'] . "/" . $menu['majorfunction']['action']; ?>" class="btn btn-primary  col-md-2"><?php if($flgset==1) echo $menu['majorfunction']['function_desc_ll']; else echo $menu['majorfunction']['function_desc_en']; ?></a>
            <?php
        }
    }
    ?>  
</div>
<div  class="rowht">&nbsp;</div>
<?php } ?>
