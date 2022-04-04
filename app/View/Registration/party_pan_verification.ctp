        
<?php
if (isset($pandetails)) {
    // pr($pandetails);
    ?>
    <?php echo $this->Form->create('panparty', array('url' => array('controller' => 'Registration', 'action' => 'party'), 'id' => 'party', 'autocomplete' => 'off')); ?>
    <?php
    echo $this->Form->input('party_id', array('label' => false, 'id' => 'panparty_id', 'type' => 'hidden', 'value' => $party_id));
    ?>
    <?php
    echo $this->Form->input('pan_no', array('label' => false, 'id' => 'pan_no', 'type' => 'hidden', 'value' => @$pandetails['pan']));
    ?>
    <?php
    echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken")));
    ?>
    <table id="doclist" class="table table-bordred table-striped">             
        <tbody>
            <tr>
                <th>Pan Number</th> <td><?php echo @$pandetails['pan']; ?></td>
            </tr>    
            <tr>
                <th>Type</th> <td><?php echo @$pandetails['type']; ?></td>
            </tr>    
            <tr>
                <th>Name</th> <td><?php echo @$pandetails['salutation'] . " " . @$pandetails['firstname'] . " " . @$pandetails['middlename'] . " " . @$pandetails['lastname']; ?></td>
            </tr>    
            <tr>
                <th>Last Modified</th> <td><?php echo @$pandetails['lastmodified']; ?></td>
            </tr>
        </tbody>
    </table>
    <center><button type="submit" class="btn btn-primary">Accept</button></center>
    <?php echo $this->Form->end(); ?>
<?php } ?>
<?php
if (isset($panerror)) {
    echo $panerror;
}
?>