<?php

class MenusController extends AppController {

    var $name = 'Menus';

    function index() {

        $this->loadModel('Menu');
        $moduleid = $this->Session->read("session_module_id");
        $lang = $this->Session->read("sess_langauge");
        $session_role_id = $this->Session->read("session_role_id");
        // pr($this->Auth->user('role_id'));
        $result1 = substr($session_role_id, 4);
        $session_role_id = substr($result1, 0, -4);
        $menus1 = $this->Menu->SelectMenu($session_role_id);
        $menu_arr1 = array();

        foreach ($menus1 as $menu1) {
            $menuid = $menu1[0]['menu_id'];
            array_push($menu_arr1, $menu1);
        }

        if (isset($this->params['requested']) && $this->params['requested'] == true) {
            $menus = array();
            foreach ($menu_arr1 as $mmenu1) {

                $menuid = $mmenu1[0]['menu_id'];
                $menus1 = $this->Menu->find('all', array('conditions' => array('id' => $menuid)));
                foreach ($menus1 as $menus11) {
                    array_push($menus, $menus11);
                }
            }
            return $menus;
        } else {
            $this->set('menus', $this->Menu->find('all', array('conditions' => array('id' => $menuid),'order by display_order ASC')));
        }
    }

    function index1() {
        $this->loadModel('SubMenu');

        $moduleid = $this->Session->read("session_module_id");

        $session_role_id = $this->Session->read("session_role_id");
        $result1 = substr($session_role_id, 4);
        $session_role_id = substr($result1, 0, -4);

        $menus1 = $this->Menu->SelectSubMenu($session_role_id);
        $menu_arr = array();

        foreach ($menus1 as $menu1) {

            array_push($menu_arr, $menu1);
        }

        if (isset($this->params['requested']) && $this->params['requested'] == true) {
            $submenus = array();
            foreach ($menu_arr as $mmenu) {

                $submenuid = $mmenu[0]['submenu_id'];

                $submenus1 = $this->SubMenu->find('all', array('conditions' => array('id' => $submenuid)));
//                pr($submenus1);exit;

                foreach ($submenus1 as $submenus11) {
                    array_push($submenus, $submenus11);
                }
            }

            return $submenus;
        } else {
            $this->set('submenus', $this->SubMenu->find('all', array('conditions' => array('id' => $submenuid, 'main_menu_id' => $menuid))));
        }
    }

    function index2() {
//        echo 'hi';exit;
        $this->loadModel('Subsubmenu');
        $moduleid = $this->Session->read("session_module_id");
        $session_role_id = $this->Session->read("session_role_id");
        $result1 = substr($session_role_id, 4);
        $session_role_id = substr($result1, 0, -4);
        $menus1 = $this->Menu->SelectSubSubMenu($session_role_id);
        $menu_arr2 = array();
        foreach ($menus1 as $menu1) {

            array_push($menu_arr2, $menu1);
        }

        if (isset($this->params['requested']) && $this->params['requested'] == true) {
            $Subsubmenus = array();
            foreach ($menu_arr2 as $mmenu2) {
                $subsubmenuid = $mmenu2[0]['subsubmenu_id'];

                $Subsubmenus1 = $this->Subsubmenu->find('all', array('conditions' => array('id' => $subsubmenuid)));
                foreach ($Subsubmenus1 as $Subsubmenus11) {
                    array_push($Subsubmenus, $Subsubmenus11);
                }
            }
            return $Subsubmenus;
        } else {
            $this->set('Subsubmenu', $this->Subsubmenu->find('all', array('conditions' => array('id' => $subsubmenuid, 'sub_menu_id' => $submenuid))));
        }
    }

}
