<?php
class leaseandlicense extends AppModel {
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_lease';
    
    function get_leaserecord($language,$tokenval,$user_id)
    {
      return  $this->query("select a.*,b.salutation_desc_" . $language . "  as salutaion_desc
                                                        from ngdrstab_trn_lease a
                                                        left outer join  ngdrstab_mst_salutation b on a.salutation = b.salutation_id 
                                                        where a.token_no= ? and a.user_id=? ",array($tokenval,$user_id));
    }
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

