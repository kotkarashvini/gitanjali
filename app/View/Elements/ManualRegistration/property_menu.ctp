<?php
$result = $this->requestAction(
        array('controller' => 'ManualReg', 'action' => 'minor_functions'));

$rec = $this->requestAction(
        array('controller' => 'Citizenentry', 'action' => 'article_mapping_screen'));
$ekyc = $this->requestAction(
        array('controller' => 'Citizenentry', 'action' => 'is_party_ekyc_done'));

$lang=$this->Session->read("sess_langauge");

?>
<div class="row">
    <div class="col-md-12">

        <div class="btn-group btn-group-justified">
            <div class="btn-group btn-group-justified btn-breadcrumb">
                <?php
                $sr_no = 'A';
                foreach ($result as $menu) {
                    if ($menu['minorfunction']['delete_flag'] == 'N') {
                        if ($menu['minorfunction']['dispaly_flag'] == 'C') {
                            if (($ekyc==1)) {
                                if ($menu['minorfunction']['id'] != 13) {
                                    if ($this->params['action'] == $menu['minorfunction']['action']) {
                                        ?> 
                                        <a href="<?php echo $this->webroot; ?><?php echo $menu['minorfunction']['controller'] . "/" . $menu['minorfunction']['action']; ?>" class="btn-Tab btn-warning"><?php echo $sr_no++ . $menu['minorfunction']['function_desc_'.$lang]; ?></a>            
                                    <?php } else { ?>              
                                        <a href="<?php echo $this->webroot; ?><?php echo $menu['minorfunction']['controller'] . "/" . $menu['minorfunction']['action']; ?>" class="btn-Tab btn-Tabprimary"><?php echo $sr_no++ . $menu['minorfunction']['function_desc_'.$lang]; ?></a>
                                        <?php
                                    }
                                }
                            }
                            else
                            {
                                if ($this->params['action'] == $menu['minorfunction']['action']) {
                            ?> 
                            <a href="<?php echo $this->webroot; ?><?php echo $menu['minorfunction']['controller'] . "/" . $menu['minorfunction']['action']; ?>" class="btn-Tab btn-warning"><?php echo $sr_no++ . $menu['minorfunction']['function_desc_'.$lang]; ?></a>            
                        <?php } else { ?>              
                            <a href="<?php echo $this->webroot; ?><?php echo $menu['minorfunction']['controller'] . "/" . $menu['minorfunction']['action']; ?>" class="btn-Tab btn-Tabprimary"><?php echo $sr_no++ . $menu['minorfunction']['function_desc_'.$lang]; ?></a>
                            <?php
                        }
                            }
                        } else {
                            foreach ($rec as $menu1) {
                                if ($menu['minorfunction']['id'] == $menu1['article_screen_mapping']['minorfun_id']) {

                                    if ($this->params['action'] == $menu['minorfunction']['action']) {
                                        ?> 
                                        <a href="<?php echo $this->webroot; ?><?php echo $menu['minorfunction']['controller'] . "/" . $menu['minorfunction']['action']; ?>" class="btn-Tab btn-warning"><?php echo $sr_no++ . $menu['minorfunction']['function_desc_'.$lang]; ?></a>            
                                    <?php } else { ?>              
                                        <a href="<?php echo $this->webroot; ?><?php echo $menu['minorfunction']['controller'] . "/" . $menu['minorfunction']['action']; ?>" class="btn-Tab btn-Tabprimary"><?php echo $sr_no++ . $menu['minorfunction']['function_desc_'.$lang]; ?></a>
                                        <?php
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
</div>
<div  class="rowht">&nbsp;</div>

<?php
$statusresult = $this->requestAction(array('controller' => 'Citizenentry', 'action' => 'data_entry_status'));
?>
<div class="status-popup" >


    <span class="center"> Status </span>    

    <ul class="status-popup-items">

<?php
$s_no = 'A';
foreach ($result as $menu) {
    foreach ($statusresult as $k => $v) {
        if ($k == $menu['minorfunction']['id']) {
            if ($menu['minorfunction']['status_flag'] == 'Y') {
                if ($statusresult[$menu['minorfunction']['id']] > 0) {
                    ?> 
                            <li>  

                                <input type="checkbox" disabled="true" name="" checked="checked" /> <?php echo $s_no++ . $menu['minorfunction']['function_desc_'.$lang]; ?> 

                            </li>
                <?php } else { ?>              
                            <li>  <input type="checkbox" disabled="true" name=""  /> <?php echo $s_no++ . $menu['minorfunction']['function_desc_'.$lang]; ?> </li>
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
    $(function () {
        $(".status-popup").draggable();
    });
</script>