<?php

class Menu extends AppModel {

    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_menu';

    public function SelectMenu($master_role_id) {
       // pr($master_role_id);exit;
        try {
//            $select_menu = $this->query("select distinct A.role_id,menu_id from ngdrstab_mst_userpermissions A  inner join ngdrstab_mst_userroles C 
//                                     on A.role_id=C.role_id   inner join ngdrstab_mst_user  B  on B.user_id=C.user_id WHERE C.user_id=" . $userid . "");

           
           // $select_menu = $this->query("select distinct role_id,menu_id from ngdrstab_mst_userpermissions where role_id=" . $master_role_id. " order by display_order");
          

$select_menu = $this->query("select distinct (u.menu_id),u.role_id , m.name_en,m.display_order from ngdrstab_mst_menu m,ngdrstab_mst_userpermissions u where m.id = CAST(u.menu_id AS integer) and u.role_id=? order by m.display_order, m.name_en",array($master_role_id));
            return($select_menu);
        } catch (Exception $e) {
            //$this->Session->setFlash($e->getMessage());
            $this->redirect(array('action' => 'error404'));
        }
    }

    public function SelectSubMenu($master_role_id) {
        try {
            //pr($master_role_id);
            //exit;
//            $select_menu = $this->query("select distinct A.role_id,submenu_id from ngdrstab_mst_userpermissions A  inner join ngdrstab_mst_userroles C 
//                                     on A.role_id=C.role_id   inner join ngdrstab_mst_user  B  on B.user_id=C.user_id WHERE C.user_id=" . $userid . "");
//            $select_menu = $this->query("select distinct role_id,submenu_id from ngdrstab_mst_userpermissions where role_id=" . $master_role_id . " order by display_order");
            $select_menu = $this->query("select distinct A.role_id,A.submenu_id,B.name_en,B.display_order 
from ngdrstab_mst_userpermissions A
inner join ngdrstab_mst_submenu B on CAST(A.submenu_id AS integer)= B.id 
where A.role_id=? order by B.display_order",array($master_role_id));
//            pr($select_menu);
            return($select_menu);
        } catch (Exception $e) {
            //$this->Session->setFlash($e->getMessage());
            $this->redirect(array('action' => 'error404'));
        }
    }

//    public function SelectSubSubMenu($master_role_id) {
//        try {
////            $select_menu = $this->query("select distinct A.role_id,subsubmenu_id from ngdrstab_mst_userpermissions A  inner join ngdrstab_mst_userroles C 
////                                     on A.role_id=C.role_id   inner join ngdrstab_mst_user  B  on B.user_id=C.user_id WHERE C.user_id=" . $userid . "");
//
//            $select_menu = $this->query("select distinct role_id,subsubmenu_id from bdrtab_cnt_userpermissions where role_id=" . $master_role_id . "");
//            return($select_menu);
//        } catch (Exception $e) {
//            //$this->Session->setFlash($e->getMessage());
//            $this->redirect(array('action' => 'error404'));
//        }
//    }
    
    
    public function SelectSubSubMenu($master_role_id) {
        try {
//            pr($master_role_id);exit;
//            $select_menu = $this->query("select distinct A.role_id,subsubmenu_id from ngdrstab_mst_userpermissions A  inner join ngdrstab_mst_userroles C 
//                                     on A.role_id=C.role_id   inner join ngdrstab_mst_user  B  on B.user_id=C.user_id WHERE C.user_id=" . $userid . "");

//            $select_menu = $this->query("select distinct role_id,subsubmenu_id from ngdrstab_mst_userpermissions where role_id=" . $master_role_id . "");
             $select_menu = $this->query("select distinct a.role_id,a.subsubmenu_id ,b.name_en from ngdrstab_mst_userpermissions a inner join ngdrstab_mst_subsubmenu b on CAST(a.subsubmenu_id AS integer)= b.id where a.role_id=? order by b.name_en",array($master_role_id));
//            pr($select_menu);

            return($select_menu);
        } catch (Exception $e) {
            //$this->Session->setFlash($e->getMessage());
            $this->redirect(array('action' => 'error404'));
        }
    }

}
