<?php

class extinterfacefielddetails extends AppModel {
    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_extinterface_details';
    
    function get_input_attr($state_id)
    {
       return $this->find('all', array('conditions' => array(
                                'extinterfacefielddetails.interface_id ' => 1, 'extinterfacefielddetails.ext_interface_param_inout_type' => 'I', 'extinterfacefielddetails.state_id' => $state_id, 'extinterfacefielddetails.send_flag' => 'Y'), 'order' => array('send_order' => 'ASC')));
    }
    
    function get_output_attr($state_id,$interface_id)
    {
    
       return $this->find('all', array('conditions' => array(
                                        'extinterfacefielddetails.interface_id ' => $interface_id, 'extinterfacefielddetails.ext_interface_param_inout_type' => 'O', 'extinterfacefielddetails.display_flag' => 'Y', 'extinterfacefielddetails.state_id' =>$state_id), 'order' => array('ext_interface_param_id' => 'ASC')));
    }
}
