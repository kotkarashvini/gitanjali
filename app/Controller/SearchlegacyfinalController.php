<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of StudController
 *
 * @author anjalibh
 */
//App::uses('AppController', 'Controller');

class SearchlegacyfinalController extends AppController {

    //public $components = array('Session');
    public $components = array('Flash');

    public function index1() 
    {
        $this->loadModel('taluka');
        $result = $this->taluka->find('all');


        // pr( $result );exit;
        // if()
        // $district_name=  $result[]['taluka']['district_id'];
        // pr($district_name);
        // exit;
        $this->set('result', $result);
    }

    public function add() {
        try {
            $this->loadModel('district');
            $this->loadModel('taluka');
            // $this->loadModel('stateModel');

         //   $this->set('statelist', $this->stateModel->find('list', array('fields' => array('state_id', 'state_name'))));


            $distrecord = $this->district->find('list', array('fields' => array('district_id', 'district_name_en')));
            $this->set('district', $distrecord);
            
            if ($this->request->is('post')) 
            {
              // pr($this->request->data);exit;
                if ($this->taluka->save($this->request->data['searchlegacyfinal'])) {
                    pr('Record Saved Sucessfully');

                    return $this->redirect(array('controller' => 'Searchlegacyfinal','action' => 'index1'));
                } else {
                    pr('Record Not Saved');
                }
            }
        } catch (Exception $e) {
            pr($e);
        }
    }

    public function getDist() {

        $this->loadModel('dist_Model');
        $this->loadModel('state_Model');
        $statelist = $_GET('state_id');
        $distlist = ClassRegistry::init('distModel')->find('list', array('fields' => array('dist_id', 'dist_name'), 'condition' => array('stateid', $statelist)));
        echo json_encode($distlist);
        exit;
    }

    public function edit($taluka_name_en = NULL) 
    {
        $this->loadModel('district');
        $this->loadModel('taluka');
        $getid = $this->taluka->query("select taluka_id from ngdrstab_conf_admblock5_taluka where taluka_name_en = '$taluka_name_en'");
     //   pr(  $getid );
        $taluka_id = $getid[0][0]['taluka_id'];
      //  pr($taluka_id);
      
    
     
        if ($this->request->is('post') || $this->request->is('put')) 
        {
          //  pr('post c;lick');

           pr($this->request->data); 

          //  $this->taluka->save($this->request->data);
       IF($this->request->data['taluka_code']==NULL)
       {
        $this->request->data['taluka_code']=0;   
       }

            $string = $this->taluka->query("update ngdrstab_conf_admblock5_taluka
             set district_id =" . "'" . $this->request->data['district_id'] . "'" . ",
             taluka_code=" . "'" . $this->request->data['taluka_code']. "'" . ",
             taluka_name_en=" . "'" . $this->request->data['taluka_name_en'] . "'" . "where taluka_id=" . "'" .  $taluka_id. "'" );

          //  $this->Flash->set(__('Record Updated Sucessfully'));
          return $this->redirect(array('controller' => 'Searchlegacyfinal', 'action' => 'index1'));
        
        }
        //  if (is_numeric($id)) {
        //     $result = $this->taluka->find('all', array('conditions' => array('id' => $id)));
        //  pr($result);
        //     $this->request->data['taluka'] = $result[0]['taluka'];
        // }

        $getinfo1 = $this->taluka->query("select * from ngdrstab_conf_admblock5_taluka where taluka_name_en = '$taluka_name_en'");
        $district_id =  $getinfo1[0][0]['district_id'];

        $district = $this->district->find('list', array('fields' => array('district_name_en'), 'conditions' => array('district_id' => $district_id)));
      //  pr($district);
      $this->set('district_id', $district);

      $district_id = $this->district->find('list', array('fields' => array('district_name_en')));
      $this->set('district_id', $district);
      $this->request->data['taluka_code'] =$getinfo1[0][0]['taluka_code'];
      $this->request->data['taluka_name_en'] =$getinfo1[0][0]['taluka_name_en'];
    }

    public function delete($taluka_name_en = null) {
        $this->loadModel('taluka');
    
        $getid = $this->taluka->query("select taluka_id from ngdrstab_conf_admblock5_taluka where taluka_name_en = '$taluka_name_en'");
       // pr(  $getid );
        $taluka_id = $getid[0][0]['taluka_id'];
        //pr($taluka_id);

      $a= $this->taluka->query("delete from ngdrstab_conf_admblock5_taluka where taluka_id =$taluka_id");
       // $this->taluka->delete($taluka_code);
      //alert('Record Deleted');
       //$this->Flash->set('Record Deleted Sucessfully');
       // return $this->redirect(array('action' => 'index'));
        return $this->redirect(array('controller' => 'Searchlegacyfinal', 'action' => 'index1'));
    }

}
