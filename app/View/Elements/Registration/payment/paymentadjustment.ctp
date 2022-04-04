<div class="col-md-12">
    <?php
 foreach ($SroAcceptance as $single) {
     if($single[0]['payment_flag']=='Y'){
    $lc = $single[0]['acceptance_id'];
    $html = $this->requestAction(array('controller' => 'Registration', 'action' => 'details_sro_acceptance', $lc,'Y'));
    echo $html;
     }
 }
    ?>
</div>
 
 