<?php
$lang=$this->Session->read('sess_langauge');

$result = $this->requestAction(
        array('controller' => 'Citizenentry', 'action' => 'minor_functions'));

$rec = $this->requestAction(
        array('controller' => 'Citizenentry', 'action' => 'article_mapping_screen'));
$ekyc = $this->requestAction(
        array('controller' => 'Citizenentry', 'action' => 'is_party_ekyc_done'));
//pr($result);exit;
?>
<div class="row">
    <div class="col-md-12">

        <div class="btn-arrow">
           
                <?php
                $sr_no = 'A';
                foreach ($result as $menu) {
                    if ($menu['minorfunction']['delete_flag'] == 'N') {
                        if ($menu['minorfunction']['dispaly_flag'] == 'C') {
                         // pr($ekyc);
                            if (($ekyc==1)) {
                                if ($menu['minorfunction']['id'] != 13) {
//                                     pr($menu['minorfunction']['action']);
                                    if ($this->params['action'] == $menu['minorfunction']['action']) {
                                        ?> 
                                        <a href="<?php echo $this->webroot; ?><?php echo $menu['minorfunction']['controller'] . "/" . $menu['minorfunction']['action'].'/'.$this->Session->read('csrftoken'); ?>" class="btn bg-maroon btn-arrow-right"><?php echo $sr_no++ .' - '. $menu['minorfunction']['function_desc_'.$lang]; ?></a>            
                                    <?php } else { ?>              
                                        <a href="<?php echo $this->webroot; ?><?php echo $menu['minorfunction']['controller'] . "/" . $menu['minorfunction']['action'].'/'.$this->Session->read('csrftoken'); ?>" class="btn btn-successmenu btn-arrow-right"><?php echo $sr_no++ .' - '. $menu['minorfunction']['function_desc_'.$lang]; ?></a>
                                        <?php
                                    }
                                }
                            }
                            else
                            {
                               // echo 'ddd';
//                                pr($menu['minorfunction']['action']);
                                if ($this->params['action'] == $menu['minorfunction']['action']) {
                            ?> 
                            <a href="<?php echo $this->webroot; ?><?php echo $menu['minorfunction']['controller'] . "/" . $menu['minorfunction']['action'].'/'.$this->Session->read('csrftoken'); ?>" class="btn bg-maroon btn-arrow-right"><?php echo $sr_no++ .' - '. $menu['minorfunction']['function_desc_'.$lang]; ?></a>            
                        <?php } else { ?>              
                            <a href="<?php echo $this->webroot; ?><?php echo $menu['minorfunction']['controller'] . "/" . $menu['minorfunction']['action'].'/'.$this->Session->read('csrftoken'); ?>" class="btn btn-successmenu btn-arrow-right"><?php echo $sr_no++ .' - '. $menu['minorfunction']['function_desc_'.$lang]; ?></a>
                            <?php
                        }
                            }
                        } else {
                            
                            foreach ($rec as $menu1) {
                               
                                if($this->Session->read('prop_applicable')=='N'){
                              
                                if ($menu['minorfunction']['id'] == $menu1['article_screen_mapping']['minorfun_id']) {
                                    if ($menu['minorfunction']['id'] != 2) {
                                    if ($this->params['action'] == $menu['minorfunction']['action']) {
                                        ?> 
                                        <a href="<?php echo $this->webroot; ?><?php echo $menu['minorfunction']['controller'] . "/" . $menu['minorfunction']['action'].'/'.$this->Session->read('csrftoken'); ?>" class="btn bg-maroon btn-arrow-right"><?php echo $sr_no++ .' - '. $menu['minorfunction']['function_desc_'.$lang]; ?></a>            
                                    <?php } else { ?>              
                                        <a href="<?php echo $this->webroot; ?><?php echo $menu['minorfunction']['controller'] . "/" . $menu['minorfunction']['action'].'/'.$this->Session->read('csrftoken'); ?>" class="btn btn-successmenu btn-arrow-right"><?php echo $sr_no++ .' - '. $menu['minorfunction']['function_desc_'.$lang]; ?></a>
                                        <?php
                                    }
                                    }
                                }
                 
                                }else{
                               
                                if ($menu['minorfunction']['id'] == $menu1['article_screen_mapping']['minorfun_id']) {

                                    if ($this->params['action'] == $menu['minorfunction']['action']) {
                                        ?> 
                                        <a href="<?php echo $this->webroot; ?><?php echo $menu['minorfunction']['controller'] . "/" . $menu['minorfunction']['action'].'/'.$this->Session->read('csrftoken'); ?>" class="btn bg-maroon btn-arrow-right"><?php echo $sr_no++ .' - '. $menu['minorfunction']['function_desc_'.$lang]; ?></a>            
                                    <?php } else { ?>              
                                        <a href="<?php echo $this->webroot; ?><?php echo $menu['minorfunction']['controller'] . "/" . $menu['minorfunction']['action'].'/'.$this->Session->read('csrftoken'); ?>" class="btn btn-successmenu btn-arrow-right"><?php echo $sr_no++ .' - '. $menu['minorfunction']['function_desc_'.$lang]; ?></a>
                                        <?php
                                    }
                                }
                                }
                            }
                        }
                    }
                }
                ?>  
           
        </div>
    </div>
</div>
<div  class="rowht">&nbsp;</div>

<?php
$statusresult = $this->requestAction(array('controller' => 'Citizenentry', 'action' => 'data_entry_status'));
?>
<div class="back-to-top_popup" >


    <span class="center"> Status </span>    

    <ul>

<?php
$s_no = 'A';

foreach ($result as $menu) {
    foreach ($statusresult as $k => $v) {
        if ($k == $menu['minorfunction']['id']) {
            if ($menu['minorfunction']['status_flag'] == 'Y') {
                if ($statusresult[$menu['minorfunction']['id']] > 0) {
                    ?> 
                            <li>  

                                <input type="checkbox" disabled="true" name="" checked="checked" /> <?php echo $s_no++ .' - '. $menu['minorfunction']['function_desc_'.$lang]; ?> 

                            </li>
                <?php } else { ?>              
                            <li>  <input type="checkbox" disabled="true" name=""  /> <?php echo $s_no++ .' - '. $menu['minorfunction']['function_desc_'.$lang]; ?> </li>
                            <?php
                        }
                    } else {
                        $s_no++;
                    }
                }
            }
        }
        ?>
    </ul>          

</div>  
<script>
    $(document).ready(function () {
        $("#header-img").hide();
    });
    $(function () {
        $(".back-to-top_popup").draggable();
    });
</script>