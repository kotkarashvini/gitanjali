<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OfficeController
 *
 * @author Admin
 */
class OfficeController extends AppController {

    //put your code here


    public function officenew($officeid = NULL) {
        try {
            $this->check_role_escalation();
            array_map(array($this, 'loadModel'), array('NGDRSErrorCode', 'mainlanguage', 'office', 'language', 'mainlanguage', 'adminLevelConfig', 'divisionnew', 'department', 'circle', 'District', 'taluka', 'Subdivision', 'Developedlandtype'));

            $userid = $this->Session->read("session_user_id");
//            pr();exit;
            $result = substr($userid, 4);
            $userid = substr($result, 0, -4);
            $stateid = $this->Auth->User("state_id");
            $lang = $this->Session->read("sess_langauge");
            $this->set('lang', $lang);
            $created_date = date('Y/m/d');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->set('actiontypeval', NULL);
            $this->set('hfactionval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);

//            $officerecord = $this->office->query("select distinct of.*,of.office_name_ll,of.office_name_en,of.dept_id,of.flat,of.building,of.road,of.locality,of.city,
//                                                of.taluka_id,of.district_id,of.state_id,of.pincode,of.officc_contact_no,of.office_email_id,
//                                                of.reporting_office_id,of.shift_id,of.hierarchy_id,of.id,of.locality,
//                                                
//                                                        dep.dept_name_en,of.office_name_en,of.city,state.state_name_en,dis.district_name_en from ngdrstab_mst_office of
//                                                        join ngdrstab_mst_department dep on dep.dept_id=of.dept_id 
//                                                        join ngdrstab_conf_admblock1_state state on state.state_id=of.state_id
//                                                        join ngdrstab_conf_admblock3_district dis on dis.district_id=of.district_id");

            $officerecord = $this->office->query("select distinct of.*,of.office_name_ll,of.office_name_en,of.dept_id,of.flat,of.building,of.road,of.locality,of.city,village.village_name_ll,village.village_name_en,
                                                of.taluka_id,of.district_id,of.state_id,state.state_name_en,state.state_name_ll,of.pincode,of.officc_contact_no,of.office_email_id,
                                                of.reporting_office_id,of.shift_id,of.hierarchy_id,of.id,of.locality,
                                                
                                                        dep.dept_name_en,of.office_name_en,of.city,dis.district_name_en from ngdrstab_mst_office of
                                                        join ngdrstab_mst_department dep on dep.dept_id=of.dept_id 
                                                        join ngdrstab_conf_admblock1_state state on state.state_id=of.state_id
                                                        join ngdrstab_conf_admblock3_district dis on dis.district_id=of.district_id
 join ngdrstab_conf_admblock7_village_mapping village on village.village_id=of.village_id::integer");


            $this->set('officerecord', $officerecord);
            //languages are loaded firstly from config (from table)
            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
                    )), 'order' => 'conf.language_id ASC'));
            $this->set('languagelist', $languagelist);
            $this->set('hierarchydata', ClassRegistry::init('officehierarchy')->find('list', array('fields' => array('hierarchy_id', 'hierarchy_desc_' . $lang), 'order' => array('hierarchy_desc_' . $lang => 'ASC'))));

            $this->set('reportingofficedata', ClassRegistry::init('office')->find('list', array('fields' => array('office_name_' . $lang), 'order' => array('office_name_' . $lang => 'ASC'))));

            $this->set('department', ClassRegistry::init('department')->find('list', array('fields' => array('dept_id', 'dept_name_' . $lang), 'order' => array('dept_name_' . $lang => 'ASC'))));
            $this->set('State', ClassRegistry::init('State')->find('list', array('fields' => array('state_id', 'state_name_' . $lang), 'order' => array('state_name_' . $lang => 'ASC'))));
//            $this->set('District', ClassRegistry::init('District')->find('list', array('fields' => array('district_id', 'district_name_' . $language), 'order' => array('district_name_' . $language => 'ASC'))));
//            $this->set('taluka', ClassRegistry::init('taluka')->find('list', array('fields' => array('taluka_id', 'taluka_name_' . $language), 'order' => array('taluka_name_' . $language => 'ASC'))));
            $this->set('officesift', ClassRegistry::init('officeshift')->find('list', array('fields' => array('shift_id', 'desc_' . $lang), 'order' => array('desc_' . $lang => 'ASC'))));
            $this->set('timeslot', ClassRegistry::init('timeslot')->find('list', array('fields' => array('slot_id', 'slot_time_minute'))));
//
//            $this->set('village', ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('village_id', 'village_name_' . $language), 'order' => array('village_name_' . $language => 'ASC'))));

            $subdivisiondata = '';
            //  $subdivisiondata = $this->Subdivision->find('list', array('fields' => array('subdivision_id', 'subdivision_name_en'), 'order' => array('subdivision_id' => 'ASC')));
            $this->set('subdivisiondata', $subdivisiondata);
            $talukadata = '';
            $this->set('talukadata', $talukadata);
            $circledata = '';
            $this->set('circledata', $circledata);
            $villagedata = '';
            $this->set('villagedata', $villagedata);
            $is_div_flag = $this->adminLevelConfig->find('first', array('conditions' => array('state_id' => $stateid)));
            $this->set('is_div_flag', $is_div_flag);
//  $distdata = '';
//  $this->set('distdata', $distdata);

            if ($is_div_flag['adminLevelConfig']['is_div'] == 'Y') {
                $divisiondata = $this->divisionnew->find('list', array('fields' => array('division_id', 'division_name_' . $lang), 'order' => array('division_id' => 'ASC')));
                $this->set('divisiondata', $divisiondata);
                $distdata = '';
                $this->set('distdata', $distdata);
         
            }
            else if ($is_div_flag['adminLevelConfig']['is_dist'] == 'Y') {
                $distdata = $this->District->find('list', array('fields' => array('district_id', 'district_name_' . $lang), 'order' => array('district_id' => 'ASC')));
                $this->set('distdata', $distdata);
            }

            else if ($is_div_flag['adminLevelConfig']['is_subdiv'] == 'Y') {
                $subdivisiondata = $this->Subdivision->find('list', array('fields' => array('subdivision_id', 'subdivision_name_' . $lang), 'order' => array('subdivision_id' => 'ASC')));
                $this->set('subdivisiondata', $subdivisiondata);
            }
//            else if ($is_div_flag['adminLevelConfig']['is_dist'] == 'Y') {
//                $distdata = $this->District->find('list', array('fields' => array('district_id', 'district_name_' . $lang), 'order' => array('district_id' => 'ASC')));
//                $this->set('distdata', $distdata);
//            }

            $officecount = $this->office->find('first', array('conditions' => array('state_id' => $stateid)));
            if ($officecount == NULL) {
                $officecount = 0;
                $this->set('officecount', $officecount);
            } else {
                $officecount = 1;
                $this->set('officecount', $officecount);
            }


            $corp_id = $this->Developedlandtype->find('list', array('fields' => array('developed_land_types_id', 'developed_land_types_desc_' . $lang), 'order' => array('developed_land_types_desc_en' => 'ASC')));
            $this->set('corp_id', $corp_id);

            $this->set("fieldlist", $fieldlist = $this->office->fieldlist($languagelist, $is_div_flag));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));


            if ($this->request->is('post') || $this->request->is('put')) {
//                  pr($this->request->data);exit;
                $actiontype = $_POST['actiontype'];
                $hfactionval = $_POST['hfaction'];
                $hfid = $_POST['hfid'];
                $this->set('hfid', $hfid);
                if ($actiontype == '1') {
                    $this->set('actiontypeval', $actiontype);
                    $this->set('hfactionval', $hfactionval);
                }
                //if ($hfactionval == 'S') {
                $duplicateflag = 'S';
                $this->request->data['officenew']['req_ip'] = $this->request->clientIp();
                $this->request->data['officenew']['user_id'] = $userid;
                $this->request->data['officenew']['created_date'] = $created_date;
                $this->request->data['officenew']['state_id'] = $stateid;
                if ($this->request->data['officenew']['reporting_office_id'] == 'empty') {
                    $this->request->data['officenew']['reporting_office_id'] = 0;
                }
//                    pr($this->request->data);exit;

                if ($this->request->data['hfupdateflag'] == 'Y') {
                    $this->request->data['officenew']['id'] = $this->request->data['hfid'];
                    $duplicateflag = 'U';
                    $actionvalue = "lbleditmsg";
                } else {
                    $actionvalue = "lblsavemsg";
                }
//                    pr($this->request->data['office']);exit;
//                    $this->request->data['officenew'] = $this->istrim($this->requests->data['officenew']);
                // pr($this->request->data['officenew']);exit;
                $verrors = $this->validatedata($this->request->data['officenew'], $fieldlist);
// pr($verrors);exit;
                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->office->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['officenew']);

                    if ($checkd) {

                        if ($this->office->save($this->request->data['officenew'])) {
                            $this->Session->setFlash(__($actionvalue));
                            $this->redirect(array('controller' => 'Office', 'action' => 'officenew'));
                            $this->set('officerecord', $this->office->find('all'));
                        } else {
                            $this->Session->setFlash(__('lblnotsavemsg '));
                        }
                    } else {
                        $this->Session->setFlash(__('lblduplicatemsg'));
                    }
                } else {
                    $this->Session->setFlash(__('Find validations '));
                }
                // }
//                    }
                //}
            }


            //update code
            if (!is_null($officeid) && is_numeric($officeid)) {
                $this->set('editflag', 'Y');

                $this->Session->write('office_id', $officeid);
                $result = $this->office->find("first", array('conditions' => array('office_id' => $officeid)));
//                pr($result);exit;
                $this->request->data['officenew'] = $result['office'];
                $disitid = $this->request->data['officenew']['district_id'];
//                    pr($disitid);exit;



                if ($is_div_flag['adminLevelConfig']['is_div'] == 'Y') {
                    $distdata = $this->District->find('list', array('fields' => array('District.district_id', 'District.district_name_' . $lang), 'conditions' => array('division_id' => $result['office']['division_id']), 'order' => array('district_name_' . $lang => 'ASC')));
                    $this->set('distdata', $distdata);
                } else {


                    $distdata = $this->District->find('list', array('fields' => array('District.district_id', 'District.district_name_' . $lang), 'order' => array('district_name_' . $lang => 'ASC')));
                    $this->set('distdata', $distdata);
                }

                $subdivisiondata = $this->Subdivision->find('list', array('fields' => array('Subdivision.subdivision_id', 'Subdivision.subdivision_name_' . $lang), 'conditions' => array('district_id' => $disitid), 'order' => array('subdivision_name_' . $lang => 'ASC')));
                $this->set('subdivisiondata', $subdivisiondata);

//                 $talukaid = $this->request->data['village']['taluka_id'];
                if ($is_div_flag['adminLevelConfig']['is_subdiv'] == 'Y') {
                    $talukadata = $this->taluka->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_' . $lang), 'conditions' => array('subdivision_id' => $result['office']['subdivision_id']), 'order' => array('taluka_name_' . $lang => 'ASC')));
                    $this->set('talukadata', $talukadata);
                } else {
                    $talukadata = $this->taluka->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_' . $lang), 'conditions' => array('district_id' => $result['office']['district_id']), 'order' => array('taluka_name_' . $lang => 'ASC')));
                    $this->set('talukadata', $talukadata);
                }

//                $this->request->data['village']['subdivision_id'] = $result['VillageMapping']['subdivision_id'];
                if ($is_div_flag['adminLevelConfig']['is_circle'] == 'Y') {
                    $villagedata = ClassRegistry::init('VillageMapping')->find('list', array('fields' => array('VillageMapping.village_id', 'VillageMapping.village_name_' . $lang), 'conditions' => array('circle_id' => $result['office']['circle_id'])));
                    $this->set('villagedata', $villagedata);
                } else {
                    $villagedata = ClassRegistry::init('VillageMapping')->find('list', array('fields' => array('VillageMapping.village_id', 'VillageMapping.village_name_' . $lang), 'conditions' => array('taluka_id' => $result['office']['taluka_id'])));
                    $this->set('villagedata', $villagedata);
                }

                if ($is_div_flag['adminLevelConfig']['is_circle'] == 'Y') {
                    //$circleid = $this->request->data['office']['circle_id'];
                    $circledata = $this->circle->find('list', array('fields' => array('circle.circle_id', 'circle.circle_name_' . $lang), 'conditions' => array('taluka_id' => $result['office']['taluka_id']), 'order' => array('circle_name_' . $lang => 'ASC')));
                    $this->set('circledata', $circledata);
                }


                // $this->set('department', ClassRegistry::init('department')->find('list', array('fields' => array('dept_id', 'dept_name_' . $lang), 'order' => array('dept_name_' . $lang => 'ASC'))));
//$department = $this->department->find('list', array('fields' => array('department.dept_id', 'department.dept_name_' . $lang), 'conditions' => array('dept_id' => $result['office']['dept_id']), 'order' => array('dept_name_' . $lang => 'ASC')));
//$this->set('department', $department);
//                $corp_id = ClassRegistry::init('corporationclasslist')->find('list', array('fields' => array('corp_id', 'governingbody_name_' . $lang), 'conditions' => array('district_id' => $disitid)));
//                //pr($corp_id);
//
//                $this->set('govbody', $corp_id);



                $this->request->data['office']['district_id'] = $disitid;
                // pr( $this->request->data['office']['district_id']);exit;
                if (!empty($districtnname)) {
                    $division_id = $this->District->query("SELECT division_id FROM ngdrstab_conf_admblock3_district where district_id=$disitid");
                    $divid = $division_id[0][0]['division_id'];
                    $this->request->data['office']['division_id'] = $divid;
                }
            }




            $this->set_csrf_token();
        } catch (Exception $ex) {

            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function delete_officenew($id = null) {
        try {

            $this->autoRender = false;
            $this->loadModel('office');

            if (isset($id) && is_numeric($id)) {

                $this->office->office_id = $id;

                if ($this->office->delete($id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'officenew'));
                }
            }
        } catch (Exception $ex) {
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

    public function get_district_name() {
        try {
            if (isset($_GET['state'])) {
//                $division = $_GET['division'];
                $state = $_GET['state'];
                // echo $state; exit;
                $districtname = ClassRegistry::init('District')->find('list', array('fields' => array('id', 'district_name_en'), 'conditions' => array('state_id' => array($state))));
//                pr($districtname);exit;
                echo json_encode($districtname);
                exit;
            } else {
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $e) {
            $this->redirect(array('action' => 'error404'));
        }
    }

    //---------------Division->District filteration
    public function getdist() {
        try {
            $this->autoRender = FALSE;
            $this->loadModel('District');
            $lang = $this->Session->read("sess_langauge");
            $data = $this->request->data;

            if (isset($data['division_id']) && is_numeric($data['division_id'])) {
                $distdata = ClassRegistry::init('District')->find('list', array('fields' => array('District.district_id', 'District.district_name_' . $lang), 'conditions' => array('division_id' => $data['division_id'])));
                echo json_encode($distdata);
                exit;
            }
        } catch (Exception $e) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function gettaluka() {
        try {
            $this->autoRender = FALSE;
            $this->loadModel('taluka');
            $lang = $this->Session->read("sess_langauge");
            $data = $this->request->data;


            if (isset($data['subdivision_id']) && is_numeric($data['subdivision_id'])) {
                $talukadata1 = ClassRegistry::init('taluka')->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_' . $lang), 'conditions' => array('subdivision_id' => $data['subdivision_id'])));
                echo json_encode($talukadata1);
                exit;
            } else if (isset($data['district_id']) && is_numeric($data['district_id'])) {
                $talukadata1 = ClassRegistry::init('taluka')->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_' . $lang), 'conditions' => array('district_id' => $data['district_id'])));
                echo json_encode($talukadata1);
                exit;
            }
        } catch (Exception $e) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    //---------------District->Subdivision filteration
    public function getsubdiv() {
        try {

            $this->autoRender = FALSE;
            $this->loadModel('Subdivision');
            $lang = $this->Session->read("sess_langauge");
            $data = $this->request->data;
            if (isset($data['district_id']) && is_numeric($data['district_id'])) {
                $subdivisiondata = ClassRegistry::init('Subdivision')->find('list', array('fields' => array('Subdivision.subdivision_id', 'Subdivision.subdivision_name_' . $lang), 'conditions' => array('district_id' => $data['district_id'])));
                echo json_encode($subdivisiondata);
                exit;
            }
        } catch (Exception $e) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function getcircle() {
        try {
            $this->autoRender = FALSE;
            $this->loadModel('circle');
            $lang = $this->Session->read("sess_langauge");
            $data = $this->request->data;
            if (isset($data['taluka_id']) && is_numeric($data['taluka_id'])) {
                $circledata = ClassRegistry::init('circle')->find('list', array('fields' => array('circle.circle_id', 'circle.circle_name_' . $lang), 'conditions' => array('taluka_id' => $data['taluka_id'])));
                echo json_encode($circledata);
                exit;
            }
        } catch (Exception $e) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function getgovtbody() {

        try {
            $this->autoRender = FALSE;
            $this->loadModel('corporationclasslist');
            $lang = $this->Session->read("sess_langauge");
            $data = $this->request->data;
            if (isset($data['district_id']) && is_numeric($data['district_id'])) {
                $corporation = ClassRegistry::init('corporationclasslist')->find('list', array('fields' => array('corp_id', 'governingbody_name_' . $lang), 'conditions' => array('district_id' => $data['district_id'])));
                echo json_encode($corporation);
                exit;
            }
        } catch (Exception $e) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function gettalukadist() {
        try {
            $this->autoRender = FALSE;
            $this->loadModel('taluka');
            $lang = $this->Session->read("sess_langauge");
            $data = $this->request->data;

            if (isset($data['district_id']) && is_numeric($data['district_id'])) {
                $talukadata = ClassRegistry::init('taluka')->find('list', array('fields' => array('taluka.taluka_id', 'taluka.taluka_name_' . $lang), 'conditions' => array('district_id' => $data['district_id'])));

                echo json_encode($talukadata);
                exit;
            }
        } catch (Exception $e) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function getvillage() {
        try {
            $this->autoRender = FALSE;
            $this->loadModel('VillageMapping');
            $lang = $this->Session->read("sess_langauge");
            $data = $this->request->data;


            if (isset($data['taluka_id']) && is_numeric($data['taluka_id'])) {
                // $this->set('village', ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('village_id', 'village_name_' . $language), 'order' => array('village_name_' . $language => 'ASC'))));
                $villagedata = ClassRegistry::init('VillageMapping')->find('list', array('fields' => array('VillageMapping.village_id', 'VillageMapping.village_name_' . $lang), 'conditions' => array('taluka_id' => $data['taluka_id'])));
                echo json_encode($villagedata);
                exit;
            } else if (isset($data['circle_id']) && is_numeric($data['circle_id'])) {
                $villagedata = ClassRegistry::init('VillageMapping')->find('list', array('fields' => array('VillageMapping.village_id', 'VillageMapping.village_name_' . $lang), 'conditions' => array('circle_id' => $data['circle_id'])));
                // $circledata = ClassRegistry::init('circle')->find('list', array('fields' => array('circle.circle_id', 'circle.circle_name_' . $lang), 'conditions' => array('circle_id' => $data['circle_id'])));
                echo json_encode($villagedata);
                exit;
            }
        } catch (Exception $e) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    //=============salim
    public function department($dept_id = NULL) {
        try {
            $this->check_role_escalation();
            $this->loadModel('adminLevelConfig');
            $this->loadModel('State');
            $this->loadModel('User');
            $user_id = $this->Auth->User("user_id");
            $date = date('Y/m/d H:i:s');
            $created_date = date('Y/m/d');
            $this->loadModel('NGDRSErrorCode');
            $this->loadModel('department');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);

            $this->set('department', $this->department->find('all'));

            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
                    )), 'order' => 'conf.language_id ASC'
            ));
            $this->set('languagelist', $languagelist);

            $this->set('talukarecord', NULL);
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $statename = $this->Session->read("state_name_en");

            $this->set("fieldlist", $fieldlist = $this->department->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            //pr($dept_id);
            if (!empty($dept_id)) {
                 $actionvalue = 'lbleditmsg';
            } else {
                 $actionvalue = 'lblsavemsg';
            }

            if ($this->request->is('post') || $this->request->is('put')) {

                $this->request->data['department']['ip_address'] = $this->request->clientIp();
                $this->request->data['department']['created_date'] = $created_date;
                $this->request->data['department']['user_id'] = $user_id;
                $verrors = $this->validatedata($this->request->data['department'], $fieldlist);


                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->department->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['department']);
                    if ($checkd) {
                        if ($this->department->save($this->request->data['department'])) {
                            $this->Session->setFlash(__($actionvalue));
                            //$this->Session->setFlash(__('Department $msg. Successfully.'));
                            return $this->redirect(array('action' => 'department'));
                            $lastid = $this->department->getLastInsertId();
                        } else {
                            $this->Session->setFlash(__('lblnotsavemsg'));
                        }
                    } else {
                        $this->Session->setFlash(__('lblduplicatemsg'));
                    }
                } else {
                    $this->Session->setFlash(__('Find validations '));
                }
            }
            if (!is_null($dept_id) && is_numeric($dept_id)) {
                $this->set('editflag', 'Y');
                $this->Session->write('dept_id', $dept_id);
                $result = $this->department->find("first", array('conditions' => array('dept_id' => $dept_id)));
                $this->request->data['department'] = $result['department'];
            }
        } catch (exception $ex) {

            pr($ex);
            exit;
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage()));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function delete_department($dept_id = null) {
        $this->autoRender = false;
        $this->loadModel('department');
        try {

            if (isset($dept_id) && is_numeric($dept_id)) {
                $this->department->dept_id = $dept_id;
                if ($this->department->delete($dept_id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'department'));
                }
                // }
            }
        } catch (exception $ex) {
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

    //===============kalyani
    public function officehierarchy($hierarchy_id = NULL) {
        try {
             $this->check_role_escalation();
            //   $this->loadModel('divisionnew');
            //  $this->loadModel('adminLevelConfig');
            $this->loadModel('State');
            $this->loadModel('OfficeCategory');
            // $this->loadModel('Subdivision');
            $this->loadModel('User');
            //  $this->loadModel('taluka');
            $this->loadModel('officehierarchy');

            $user_id = $this->Auth->User("user_id");
            $date = date('Y/m/d H:i:s');
            $created_date = date('Y/m/d');
            $this->loadModel('NGDRSErrorCode');
            $this->loadModel('Developedlandtype');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $officehierarchy = $this->officehierarchy->find('list', array('fields' => array('officehierarchy.hierarchy_id', 'officehierarchy.hierarchy_desc_' . $laug), 'order' => array('hierarchy_desc_en' => 'ASC')));
            $this->set('officehierarchy', $officehierarchy);
            $OfficeCategory = $this->OfficeCategory->find('list', array('fields' => array('OfficeCategory.office_cat_id', 'OfficeCategory.office_desc_' . $laug), 'order' => array('office_desc_en' => 'ASC')));
            $this->set('OfficeCategory', $OfficeCategory);
            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
                    )), 'order' => 'conf.language_id ASC'
            ));
            $this->set('languagelist', $languagelist);

            $this->set('talukarecord', NULL);
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $statename = $this->Session->read("state_name_en");

            $hierarchydata = $this->officehierarchy->query("select * from ngdrstab_mst_office_hierarchy");
            $this->set('hierarchydata', $hierarchydata);


            $this->set("fieldlist", $fieldlist = $this->officehierarchy->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
if (!empty($hierarchy_id)) {
                 $actionvalue = 'lbleditmsg';
            } else {
                 $actionvalue = 'lblsavemsg';
            }

            if ($this->request->is('post') || $this->request->is('put')) {
//pr($this->request->data);exit;
                $this->request->data['officehierarchy']['ip_address'] = $this->request->clientIp();
                $this->request->data['officehierarchy']['created_date'] = $created_date;
                $this->request->data['officehierarchy']['user_id'] = $user_id;
                $verrors = $this->validatedata($this->request->data['officehierarchy'], $fieldlist);
                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->officehierarchy->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['officehierarchy']);
                    if ($checkd) {
                        if ($this->officehierarchy->save($this->request->data['officehierarchy'])) {
                            $this->Session->setFlash(__($actionvalue));
                           // $this->Session->setFlash(__('lblsavemsg'));
                            return $this->redirect(array('action' => 'officehierarchy'));
                            $lastid = $this->officehierarchy->getLastInsertId();
                        } else {
                            $this->Session->setFlash(__('lblnotsavemsg'));
                        }
                    } else {
                        $this->Session->setFlash(__('lblduplicatemsg'));
                    }
                } else {
                    $this->Session->setFlash(__('Find validations '));
                }
            }
            if (!is_null($hierarchy_id) && is_numeric($hierarchy_id)) {
                $this->set('editflag', 'Y');
                $this->Session->write('hierarchy_id', $hierarchy_id);
                $result = $this->officehierarchy->find("first", array('conditions' => array('hierarchy_id' => $hierarchy_id)));
//                pr($result);exit;
                //$this->set('result', $result);
                $this->request->data['officehierarchy'] = $result['officehierarchy'];
            }
        } catch (exception $ex) {

            pr($ex);
            exit;
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage()));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function delete_officehierarchy($hierarchy_id = null) {
        // pr($id);exit;
        $this->autoRender = false;
        $this->loadModel('officehierarchy');
        try {

            if (isset($hierarchy_id) && is_numeric($hierarchy_id)) {
                //  if ($type = 'subdivision') {
                $this->officehierarchy->hierarchy_id = $hierarchy_id;
                if ($this->officehierarchy->delete($hierarchy_id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'officehierarchy'));
                }
                // }
            }
        } catch (exception $ex) {
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

    public function officecategory($office_cat_id = NULL) {
        try {
             $this->check_role_escalation();
            //   $this->loadModel('divisionnew');
            //  $this->loadModel('adminLevelConfig');
            $this->loadModel('State');
            $this->loadModel('OfficeCategory');
            // $this->loadModel('Subdivision');
            $this->loadModel('User');
            //  $this->loadModel('taluka');
            $this->loadModel('officehierarchy');
            $user_id = $this->Auth->User("user_id");
            $date = date('Y/m/d H:i:s');
            $created_date = date('Y/m/d');
            $this->loadModel('NGDRSErrorCode');
            $this->loadModel('Developedlandtype');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $officehierarchy = $this->officehierarchy->find('list', array('fields' => array('officehierarchy.hierarchy_id', 'officehierarchy.hierarchy_desc_' . $laug), 'order' => array('hierarchy_desc_en' => 'ASC')));
            $this->set('officehierarchy', $officehierarchy);
            $OfficeCategory = $this->OfficeCategory->find('list', array('fields' => array('OfficeCategory.office_cat_id', 'OfficeCategory.office_desc_' . $laug), 'order' => array('office_desc_en' => 'ASC')));
            $this->set('OfficeCategory', $OfficeCategory);
            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
                    )), 'order' => 'conf.language_id ASC'
            ));
            $this->set('languagelist', $languagelist);

            $this->set('talukarecord', NULL);
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $statename = $this->Session->read("state_name_en");

            $OfficeCategorydata = $this->OfficeCategory->query("select * from ngdrstab_mst_office_category");
            $this->set('OfficeCategorydata', $OfficeCategorydata);


            $this->set("fieldlist", $fieldlist = $this->OfficeCategory->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
if (!empty($office_cat_id)) {
                 $actionvalue = 'lbleditmsg';
            } else {
                 $actionvalue = 'lblsavemsg';
            }

            if ($this->request->is('post') || $this->request->is('put')) {
//pr($this->request->data);exit;
                $this->request->data['officecategory']['ip_address'] = $this->request->clientIp();
                $this->request->data['officecategory']['created_date'] = $created_date;
                $this->request->data['officecategory']['user_id'] = $user_id;
                $verrors = $this->validatedata($this->request->data['officecategory'], $fieldlist);
                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->OfficeCategory->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['officecategory']);
                    if ($checkd) {
                        if ($this->OfficeCategory->save($this->request->data['officecategory'])) {
                            $this->Session->setFlash(__($actionvalue));
                            //$this->Session->setFlash(__('lblsavemsg.'));
                            return $this->redirect(array('action' => 'officecategory'));
                            $lastid = $this->OfficeCategory->getLastInsertId();
                        } else {
                            $this->Session->setFlash(__('lblnotsavemsg'));
                        }
                    } else {
                        $this->Session->setFlash(__('lblduplicatemsg'));
                    }
                } else {
                    $this->Session->setFlash(__('Find validations '));
                }
            }
            if (!is_null($office_cat_id) && is_numeric($office_cat_id)) {
                $this->set('editflag', 'Y');
                $this->Session->write('office_cat_id', $office_cat_id);
                $result = $this->OfficeCategory->find("first", array('conditions' => array('office_cat_id' => $office_cat_id)));
                $this->request->data['officecategory'] = $result['OfficeCategory'];
            }
        } catch (exception $ex) {

            // pr($ex);
            //  exit;
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage()));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function delete_officecategory($office_cat_id = null) {
        // pr($id);exit;
        $this->autoRender = false;
        $this->loadModel('OfficeCategory');
        try {

            if (isset($office_cat_id) && is_numeric($office_cat_id)) {
                //  if ($type = 'subdivision') {
                $this->OfficeCategory->office_cat_id = $office_cat_id;
                if ($this->OfficeCategory->delete($office_cat_id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'officecategory'));
                }
                // }
            }
        } catch (exception $ex) {
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

    public function office_village_linking() {
        try {
             $this->check_role_escalation();
            $this->loadModel('office_village_linking');
            $this->loadModel('conf_reg_bool_info');
            $this->loadModel('divisionnew');
            $this->loadModel('District');
            $language = $this->Session->read("sess_langauge");
            $this->set('language', $language);
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $this->set('actiontypeval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            $this->set('hfactionval', NULL);
            $this->loadModel('adminLevelConfig');
            $adminLevelConfig = $this->adminLevelConfig->find('first', array('conditions' => array('state_id' => $stateid)));
            if ($adminLevelConfig['adminLevelConfig']['is_div'] == 'N') {
                $distdata = $this->District->find('list', array('fields' => array('district_id', 'district_name_en'), 'order' => array('district_id' => 'ASC')));
            } else {
                $distdata = NULL;
            }
            $this->set('distdata', $distdata);
            $this->set('subdivisiondata', NULL);
            $this->set('circledata', NULL);



            $this->set('adminLevelConfig', $adminLevelConfig);
            $divisiondata = $this->divisionnew->find('list', array('fields' => array('division_id', 'division_name_en'), 'order' => array('division_id' => 'ASC')));
            $this->set('divisiondata', $divisiondata);
            //$this->set('districtdata', ClassRegistry::init('District')->find('list', array('fields' => array('district_id', 'district_name_' . $language), 'order' => array('district_name_' . $language => 'ASC'))));
//            $this->set('corpclasslist', ClassRegistry::init('corporationclasslist')->find('list', array('fields' => array('corp_id', 'governingbody_name_' . $language), 'order' => array('governingbody_name_' . $language => 'ASC'))));

            $this->set('corpclasslist', NULL);
            $this->set('taluka', NULL);
            $this->set('officedata', ClassRegistry::init('office')->find('list', array('fields' => array('office_id', 'office_name_' . $language), 'order' => array('office_name_' . $language => 'ASC'))));
            $this->set("fieldlist", $fieldlist = $this->office_village_linking->fieldlist($adminLevelConfig));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if ($this->request->is('post')) {
//            pr($this->request->data);exit;
                $this->check_csrf_token($this->request->data['office_village_linking']['csrftoken']);
                $data ['district_id'] = $this->request->data['office_village_linking']['district_id'];
                $data ['taluka_id'] = $this->request->data['office_village_linking']['taluka_id'];
                $data ['office_id'] = $this->request->data['office_village_linking']['office_id'];
                $data ['corp_id'] = @$this->request->data['office_village_linking']['corp_id'];
                $data ['circle_id'] = @$this->request->data['office_village_linking']['circle_id'];
                //$data ['jurisdiction_flag'] =@$this->request->data['office_village_linking']['circle_id'];


                if (!is_numeric($data['corp_id'])) {
                    unset($data['corp_id']);
                }
                if (!is_numeric($data['circle_id'])) {
                    unset($data['circle_id']);
                }

                $actiontype = $_POST['actiontype'];
                $hfactionval = $_POST['hfaction'];
                $hfid = $_POST['hfid'];
                $this->set('hfid', $hfid);
                if ($actiontype == '1') {
                    $this->set('actiontypeval', $actiontype);
                    $this->set('hfactionval', $hfactionval);
                }

                if ($hfactionval == 'V') {

                    $regconf = $this->conf_reg_bool_info->find("all", array('conditions' => array('reginfo_id' => 63)));
//                pr($regconf);
                    $regconfvillagemappingcode = $regconf[0]['conf_reg_bool_info']['info_value'];
                    $confrmvillagemappingcode = $this->request->data['office_village_linking']['confrmvillagemappingcode1'];
                    if ($regconfvillagemappingcode == $confrmvillagemappingcode) {
//                     pr('ok');exit;
                        $hfactionval = 'S';
//                      pr($hfactionval);exit;
                    } else {
                        $this->Session->setFlash(__("Confirm Village Mapping Verification Code Not Match..!"));
                        $this->redirect(array('controller' => 'Masters', 'action' => 'office_village_linking'));
                    }

//                 pr($regconf);exit;
                }


                if ($hfactionval == 'S') {

//                 $regconf = $this->conf_reg_bool_info->find("all", array('conditions' => array('reginfo_id' => 60)));
//                 pr($regconf);exit;

                    $this->request->data['office_village_linking']['req_ip'] = $this->request->clientIp();
                    $this->request->data['office_village_linking']['user_id'] = $user_id;
                    // $this->request->data['language']['created_date'] = $created_date;
                    if ($this->request->data['hfupdateflag'] == 'Y') {
                        $this->request->data['office_village_linking']['id'] = $this->request->data['hfid'];
                        $actionvalue = "lbleditmsg";
                    } else {
                        $actionvalue = "lblsavemsg";
                    }
                    unset($data['zazzz']);

                    $deletevillage = $this->office_village_linking->deleteAll($data);

//                       pr($this->request->data);exit;
//                         unset($this->request->data['office_village_linking']['zazzz']);



                    if (isset($this->request->data['village_id'])) {
                        //pr($this->request->data);exit;
                        foreach ($this->request->data['village_id'] as $key => $value) {
                            $data['village_id'] = $value;
//                           pr($data);exit;
                            unset($data['zazzz']);

                            //$value=['village_id'][''];
                            $this->office_village_linking->create();
                            $this->office_village_linking->Save($data);
                        }
                    }
                    $this->Session->setFlash(__($actionvalue));
                    $this->redirect(array('controller' => 'Office', 'action' => 'office_village_linking'));
                }
            }
            $this->set_csrf_token();
        } catch (Exception $ex) {
            // pr($ex->getMessage());
            //  exit;
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function get_office_linked_villages() {

        $this->loadModel('office_village_linking');
        $this->autoRender = false;
        $lang = $this->Session->read("sess_langauge");
        $village = $this->office_village_linking->find('all', array('fields' => array('office_village_linking.village_id', 'office_village_linking.office_id', 'dist.district_name_' . $lang, 'taluka.taluka_name_' . $lang, 'village.village_name_' . $lang, 'jurisdiction_flag'),
            'joins' => array(
                array('table' => 'ngdrstab_conf_admblock7_village_mapping', 'alias' => 'village', 'conditions' => array('village.village_id=office_village_linking.village_id')),
                array('table' => 'ngdrstab_conf_admblock3_district', 'alias' => 'dist', 'conditions' => array('dist.district_id=office_village_linking.district_id')),
                array('table' => 'ngdrstab_conf_admblock5_taluka', 'alias' => 'taluka', 'conditions' => array('taluka.taluka_id=office_village_linking.taluka_id')),
            ),
            'conditions' => array('office_village_linking.office_id' => $_POST['office'])));
        $html = '';
        if (!empty($village)) {
            foreach ($village as $single) {
                if ($single['office_village_linking']['jurisdiction_flag'] == 'Y') {
                    $html.="<tr><td>" . $single['dist']['district_name_' . $lang] . "</td>" . "<td>" . $single['taluka']['taluka_name_' . $lang] . "</td>" . "<td>" . $single['village']['village_name_' . $lang] . "</td>";
                    $html.="<td><input type='radio' name='radio" . $single['office_village_linking']['village_id'] . "[]' value='" . $single['office_village_linking']['village_id'] . "'  checked='checked' onclick=jurisdictionsetyes(" . $single['office_village_linking']['office_id'] . "," . $single['office_village_linking']['village_id'] . ")>Yes ";
                    $html.="<input type='radio' name='radio" . $single['office_village_linking']['village_id'] . "[]' value='" . $single['office_village_linking']['village_id'] . "'   onclick=jurisdictionsetno(" . $single['office_village_linking']['office_id'] . "," . $single['office_village_linking']['village_id'] . ")>No</td></tr>";
                } else {
                    $html.="<tr><td>" . $single['dist']['district_name_' . $lang] . "</td>" . "<td>" . $single['taluka']['taluka_name_' . $lang] . "</td>" . "<td>" . $single['village']['village_name_' . $lang] . "</td>";
                    $html.="<td><input  type='radio' name='radio" . $single['office_village_linking']['village_id'] . "[]' value='" . $single['office_village_linking']['village_id'] . "' onclick=jurisdictionsetyes(" . $single['office_village_linking']['office_id'] . "," . $single['office_village_linking']['village_id'] . ")>Yes";
                    $html.="<input  type='radio' name='radio" . $single['office_village_linking']['village_id'] . "[]' value='" . $single['office_village_linking']['village_id'] . "' checked='checked' onclick=jurisdictionsetno(" . $single['office_village_linking']['office_id'] . "," . $single['office_village_linking']['village_id'] . ")>No</td></tr>";
                }
            }
        }
        echo $html;
    }

    public function set_jurisdiction_flag() {
        $this->loadModel('office_village_linking');
        $this->autoRender = FALSE;
        $data = $this->request->data;
        if (isset($data['office_id']) && is_numeric($data['office_id']) && isset($data['village_id']) && is_numeric($data['village_id'])) {
            if ($data['flag'] == 'Yes') {
                $this->office_village_linking->query("update ngdrstab_trn_office_village_linking set jurisdiction_flag=? where office_id=? and village_id=?", array('Y', $data['office_id'], $data['village_id']));
                echo 1;
            } else if ($data['flag'] == 'No') {
                $this->office_village_linking->query("update ngdrstab_trn_office_village_linking set jurisdiction_flag=? where office_id=? and village_id=?", array('N', $data['office_id'], $data['village_id']));
                echo 1;
            }
        } else {
            echo 2;
        }
    }

    public function getcirclevillage() {
        try {
            $this->loadModel('VillageMapping');

            $stateid = $this->Auth->User("state_id");
            $lang = $this->Session->read("sess_langauge");
            $villagelist = NULL;

            if (isset($this->request->data['circle_id']) and is_numeric($this->request->data['circle_id'])) {
                $villagelist = ClassRegistry::init('VillageMapping')->find('list', array('fields' => array('VillageMapping.village_id', 'VillageMapping.village_name_' . $lang), 'conditions' => array('circle_id' => $this->request->data['circle_id'])));
            }
            $result_array = array('village' => $villagelist);

            echo json_encode($result_array);
            exit;
        } catch (Exception $e) {
            
        }
    }

    public function timeslot($slot_id = NULL) {

        try {
             $this->check_role_escalation();
            $this->loadModel('adminLevelConfig');
            $this->loadModel('State');
            $this->loadModel('User');
            $user_id = $this->Auth->User("user_id");
            $date = date('Y/m/d H:i:s');
            $created_date = date('Y/m/d');
            $this->loadModel('NGDRSErrorCode');
            $this->loadModel('timeslot');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);

            $this->set('timeslotrecord', $this->timeslot->find('all', array('order' => array('slot_time_minute' => 'ASC'))));

            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
                    )), 'order' => 'conf.language_id ASC'
            ));
            $this->set('languagelist', $languagelist);

            $this->set('talukarecord', NULL);
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $statename = $this->Session->read("state_name_en");

            $this->set("fieldlist", $fieldlist = $this->timeslot->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
 if (!empty($slot_id)) {
                 $actionvalue = 'lbleditmsg';
            } else {
                 $actionvalue = 'lblsavemsg';
            }

            if ($this->request->is('post') || $this->request->is('put')) {

                // pr($this->request->data);exit;

                $this->request->data['timeslot']['ip_address'] = $this->request->clientIp();
                $this->request->data['timeslot']['created_date'] = $created_date;
                $this->request->data['timeslot']['user_id'] = $user_id;
                $verrors = $this->validatedata($this->request->data['timeslot'], $fieldlist);

                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->timeslot->get_duplicate();
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['timeslot']);
                    if ($checkd) {
                        if ($this->timeslot->save($this->request->data['timeslot'])) {
                            $this->Session->setFlash(__($actionvalue));
                           // $this->Session->setFlash(__('Time Slot saved Successful.'));
                            return $this->redirect(array('action' => 'timeslot'));
                            $lastid = $this->timeslot->getLastInsertId();
                        } else {
                            $this->Session->setFlash(__('lblnotsavemsg'));
                        }
                    } else {
                        $this->Session->setFlash(__('lblduplicatemsg'));
                    }
                } else {
                    $this->Session->setFlash(__('Find validations '));
                }
            }
            if (!is_null($slot_id) && is_numeric($slot_id)) {
                $this->set('editflag', 'Y');
                $this->Session->write('slot_id', $slot_id);
                $result = $this->timeslot->find("first", array('conditions' => array('slot_id' => $slot_id)));
                $this->request->data['timeslot'] = $result['timeslot'];
            }
        } catch (exception $ex) {

            pr($ex);
            exit;
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage()));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function timeslot_delete($slot_id = null) {
        $this->autoRender = false;
        $this->loadModel('timeslot');
        try {

            if (isset($slot_id) && is_numeric($slot_id)) {
                $this->timeslot->slot_id = $slot_id;
                if ($this->timeslot->delete($slot_id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'timeslot'));
                }
                // }
            }
        } catch (exception $ex) {
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

    public function holiday($holiday_id = NULL) {
        try {
            $this->check_role_escalation();
            //   $this->loadModel('divisionnew');
            //  $this->loadModel('adminLevelConfig');
            $this->loadModel('State');
            $this->loadModel('holiday');
            // $this->loadModel('Subdivision');
            $this->loadModel('User');
            //  $this->loadModel('taluka');
            $this->loadModel('officehierarchy');
            $this->loadModel('District');
            $user_id = $this->Auth->User("user_id");
            $date = date('Y/m/d H:i:s');
            $created_date = date('Y/m/d');
            $this->loadModel('NGDRSErrorCode');
            $this->loadModel('Developedlandtype');
            $this->loadModel('HolidayType');
            $this->loadModel('HolidayMapping');
            $this->loadModel('office');


            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            // $officehierarchy = $this->officehierarchy->find('list', array('fields' => array('officehierarchy.hierarchy_id', 'officehierarchy.hierarchy_desc_' . $laug), 'order' => array('hierarchy_desc_en' => 'ASC')));
            //  $this->set('officehierarchy', $officehierarchy);
            //      $OfficeCategory = $this->OfficeCategory->find('list', array('fields' => array('OfficeCategory.office_cat_id', 'OfficeCategory.office_desc_' . $laug), 'order' => array('office_desc_en' => 'ASC')));
            //  $this->set('OfficeCategory', $OfficeCategory);
            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
                    )), 'order' => 'conf.language_id ASC'
            ));
            $this->set('languagelist', $languagelist);
            $holidaytypedata = $this->HolidayType->find('list', array('fields' => array('holiday_type_id', 'holiday_type_en'), 'order' => array('holiday_type_id' => 'ASC')));
            $this->set('holidaytypedata', $holidaytypedata);
            $this->set('talukarecord', NULL);
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $statename = $this->Session->read("state_name_en");

            $holidaydata = $this->holiday->query("select holiday.*,holiday_type.holiday_type_en, dist.district_name_en from ngdrstab_mst_holiday as holiday
JOIN ngdrstab_mst_holiday_type as holiday_type ON holiday_type.holiday_type_id=holiday.holiday_type_id
LEFT JOIN  ngdrstab_conf_admblock3_district as dist ON dist.district_id=holiday.district_id
   ");
            $this->set('holidaydata', $holidaydata);



            $distdata = $this->District->find('list', array('fields' => array('District.district_id', 'District.district_name_' . $laug), 'order' => array('district_name_' . $laug => 'ASC')));
            $this->set('districtdata', $distdata);

            $this->set("fieldlist", $fieldlist = $this->holiday->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
 if (!empty($holiday_id)) {
                 $actionvalue = 'lbleditmsg';
            } else {
                 $actionvalue = 'lblsavemsg';
            }

            if ($this->request->is('post') || $this->request->is('put')) {
//pr($this->request->data);exit;
                $this->request->data['holiday']['ip_address'] = $this->request->clientIp();
                $this->request->data['holiday']['created_date'] = $created_date;
                $this->request->data['holiday']['user_id'] = $user_id;
                $this->request->data['holiday']['holiday_fdate']=date("Y-m-d", strtotime($this->request->data['holiday']['holiday_fdate']));
                $this->request->data['holiday']['holiday_tdate'] = $this->request->data['holiday']['holiday_fdate'];
                $holidaytype = $this->HolidayType->find('first', array('fields' => array('holiday_type_id', 'holiday_type_id'), 'conditions' => array('holiday_type_id' => $this->request->data['holiday']['holiday_type_id'], 'local_holiday_flag' => 'Y')));
                if (empty($holidaytype)) {
                    unset($fieldlist['district_id']);
                    unset($fieldlist['office_id']);
                }

                $verrors = $this->validatedata($this->request->data['holiday'], $fieldlist);


                if ($this->ValidationError($verrors)) {
                    $data = $this->request->data['holiday'];
                    $duplicate = $this->holiday->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['holiday']);
                    if ($checkd) {
                        if ($this->holiday->save($data)) {
                            if (isset($data['holiday_id']) && is_numeric($data['holiday_id'])) {
                                $this->HolidayMapping->deleteAll(array('holiday_id' => $data['holiday_id']));
                            } else {
                                $data['holiday_id'] = $lastid = $this->holiday->getLastInsertId();
                            }
                            if (isset($data['office_id']) && is_array($data['office_id'])) {
                                foreach ($data['office_id'] as $office) {
                                    $this->HolidayMapping->create();
                                    $datanew['office_id'] = $office;
                                    $datanew['holiday_id'] = $data['holiday_id'];
                                    $this->HolidayMapping->save($datanew);
                                }
                            }
                            $this->Session->setFlash(__($actionvalue));
                            //$this->Session->setFlash(__('Holiday saved Successful.'));
                            return $this->redirect(array('action' => 'holiday'));
                        } else {
                            $this->Session->setFlash(__('lblnotsavemsg'));
                        }
                    } else {
                        $this->Session->setFlash(__('lblduplicatemsg'));
                    }
                    return $this->redirect(array('action' => 'holiday'));
                } else {
                    $this->Session->setFlash(__('Find validations '));
                }
            }
            if (!is_null($holiday_id) && is_numeric($holiday_id)) {
                $this->set('editflag', 'Y');
                $this->Session->write('holiday_id', $holiday_id);
                $result = $this->holiday->find("first", array('conditions' => array('holiday_id' => $holiday_id)));
                if (empty($result)) {
                    $this->Session->setFlash(__('lblnotfoundmsg'));
                    return $this->redirect(array('action' => 'holiday'));
                }
                $result['holiday']['holiday_fdate'] = date("d-m-Y", strtotime($result['holiday']['holiday_fdate']));
                $this->request->data['holiday'] = $result['holiday'];
                $this->set("result", $result['holiday']); //office_id

                $HolidayMapping = $this->HolidayMapping->find('list', array('fields' => array('office_id', 'office_id'), 'conditions' => array('holiday_id' => $result['holiday']['holiday_id'])));
                $this->set("HolidayMapping", $HolidayMapping);
                if (is_numeric($result['holiday']['district_id'])) {
                    $office = $this->office->find('list', array('fields' => array('office_id', 'office_name_' . $laug), 'conditions' => array('district_id' => $result['holiday']['district_id'])));
                    $this->set("office", $office);
                }
            }
        } catch (exception $ex) {

            pr($ex);
            exit;
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage()));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function get_local_holiday_flag() {
        $this->loadModel('HolidayType');
        $this->autoRender = false;
        $holidaytype = $this->HolidayType->find('first', array('fields' => array('holiday_type_id', 'holiday_type_id'), 'conditions' => array('holiday_type_id' => $_POST['holiday_type_id'], 'local_holiday_flag' => 'Y')));
        if (!empty($holidaytype)) {
            echo 1;
        } else {
            echo 2;
        }
    }

    public function get_office_list_by_district() {
        $this->loadModel('office');
        $this->autoRender = false;
        $laug = $this->Session->read("sess_langauge");
        $office = array();

        $data = $this->request->data;

        if (isset($data['district_id']) && is_numeric($data['district_id'])) {
            $office = $this->office->find('list', array('fields' => array('office_id', 'office_name_' . $laug), 'conditions' => array('district_id' => $data['district_id'])));
        }
        echo json_encode($office);
    }

    public function delete_holiday($holiday_id = null) {

        $this->autoRender = false;
        $this->loadModel('holiday');
        $this->loadModel('HolidayMapping');
        try {

            if (isset($holiday_id) && is_numeric($holiday_id)) {
                $this->HolidayMapping->query("delete from ngdrstab_mst_holiday_mapping where holiday_id=? ", array($holiday_id));
                $this->holiday->holiday_id = $holiday_id;
                if ($this->holiday->delete($holiday_id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'holiday'));
                }
            }
        } catch (exception $ex) {
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

    public function officeshift($shift_id = NULL) {
        try {
             $this->check_role_escalation();
            $this->loadModel('officeshift');
            $this->loadModel('State');
            $this->loadModel('User');
            $user_id = $this->Auth->User("user_id");
            $this->set('hfupdateflag', NULL);
            $this->set('actiontypeval', NULL);
            $this->set('hfactionval', NULL);
            $this->set('hfid', NULL);
            $created_date = date('Y/m/d H:i:s');
            $stateid = $this->Auth->User("state_id");
            $ip = $_SERVER['REMOTE_ADDR'];
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);

            $officeshift = $this->officeshift->find("all");
            $this->set('officeshift', $officeshift);
            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
                    )), 'order' => 'conf.language_id ASC'
            ));

            $this->set('languagelist', $languagelist);

            $this->set("fieldlist", $fieldlist = $this->officeshift->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

 if (!empty($shift_id)) {
                 $actionvalue = 'lbleditmsg';
            } else {
                 $actionvalue = 'lblsavemsg';
            }
            
            if ($this->request->is('post') || $this->request->is('put')) {

                $this->request->data['officeshift']['req_ip'] = $ip;
                $this->request->data['officeshift']['user_id'] = $user_id;
                $this->request->data['officeshift']['state_id'] = $stateid;

                $verrors = $this->validatedata($this->request->data['officeshift'], $fieldlist);
                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->officeshift->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['officeshift']);
                    if ($checkd) {
                        if ($this->officeshift->save($this->request->data['officeshift'])) {
                            $this->Session->setFlash(__($actionvalue));
                            //$this->Session->setFlash(__("Record Saved  Successfully"));
                            $this->redirect(array('controller' => 'Office', 'action' => 'officeshift'));
                        } else {
                            $this->Session->setFlash(__('lblnotsavemsg'));
                        }
                    } else {
                        $this->Session->setFlash(__('lblduplicatemsg'));
                    }
                } else {
                    $this->Session->setFlash(__('Find validations '));
                }
            }

            if (is_numeric($shift_id)) {
                $this->set('editflag', 'Y');
                $officeshift = $this->officeshift->find("first", array('conditions' => array('shift_id' => $shift_id)));
                if (empty($officeshift)) {
                    $this->Session->setFlash(__('lblnotfoundmsg'));
                    return $this->redirect(array('action' => 'officeshift'));
                }

                $fields = array('from_time', 'to_time', 'lunch_from_time', 'lunch_to_time', 'tatkal_from_time', 'tatkal_to_time', 'appnt_from_time', 'appnt_to_time');
                foreach ($fields as $field) {
                    $data = $officeshift['officeshift'][$field];
                    if (!empty($data)) {
                        $officeshift['officeshift'][$field] = date('H:i', strtotime($data));
                    }
                }
                // pr($officeshift);exit;
                $this->request->data['officeshift'] = $officeshift['officeshift'];
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage()));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function delete_officeshift($id = null) {
        $this->autoRender = false;
        $this->loadModel('officeshift');
        try {
            if (isset($id) && is_numeric($id)) {
                $this->officeshift->shift_id = $id;
                if ($this->officeshift->delete($id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'officeshift'));
                }
            }
        } catch (exception $ex) {
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

    public function holiday_type($holiday_type_id = NULL) {
        try {
             $this->check_role_escalation();
            $this->loadModel('adminLevelConfig');
            $this->loadModel('State');
            $this->loadModel('User');
            $user_id = $this->Auth->User("user_id");
            $date = date('Y/m/d H:i:s');
            $created_date = date('Y/m/d');
            $this->loadModel('NGDRSErrorCode');
            $this->loadModel('HolidayType');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);

            $this->set('local_holiday_flag', null);
            $this->set('HolidayType', $this->HolidayType->find('all'));

            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
                    )), 'order' => 'conf.language_id ASC'
            ));
            $this->set('languagelist', $languagelist);

            $this->set('talukarecord', NULL);
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $statename = $this->Session->read("state_name_en");

            $this->set("fieldlist", $fieldlist = $this->HolidayType->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

 if (!empty($holiday_type_id)) {
                 $actionvalue = 'lbleditmsg';
            } else {
                 $actionvalue = 'lblsavemsg';
            }

            if ($this->request->is('post') || $this->request->is('put')) {

                //pr($this->request->data);exit;

                $this->request->data['holiday_type']['ip_address'] = $this->request->clientIp();
                $this->request->data['holiday_type']['created_date'] = $created_date;
                $this->request->data['holiday_type']['user_id'] = $user_id;
                $verrors = $this->validatedata($this->request->data['holiday_type'], $fieldlist);

                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->HolidayType->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['holiday_type']);
                    if ($checkd) {
                        if ($this->HolidayType->save($this->request->data['holiday_type'])) {
                            $this->Session->setFlash(__($actionvalue));
                            //$this->Session->setFlash(__('Holiday Type saved Successful.'));
                            return $this->redirect(array('action' => 'holiday_type'));
                            $lastid = $this->HolidayType->getLastInsertId();
                        } else {
                            $this->Session->setFlash(__('lblnotsavemsg'));
                        }
                    } else {
                        $this->Session->setFlash(__('lblduplicatemsg'));
                    }
                } else {
                    $this->Session->setFlash(__('Find validations '));
                }
            }
            if (!is_null($holiday_type_id) && is_numeric($holiday_type_id)) {
                $this->set('editflag', 'Y');
                $this->Session->write('holiday_type_id', $holiday_type_id);
                $result = $this->HolidayType->find("first", array('conditions' => array('holiday_type_id' => $holiday_type_id)));

                $this->set('local_flag', $result['HolidayType']['local_holiday_flag']);


                $this->request->data['holiday_type'] = $result['HolidayType'];
            }
        } catch (exception $ex) {

            // pr($ex);
            // exit;
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage()));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function delete_holiday_type($holiday_type_id = null) {
        $this->autoRender = false;
        $this->loadModel('HolidayType');
        try {
            if (isset($holiday_type_id) && is_numeric($holiday_type_id)) {
                $this->HolidayType->holiday_type_id = $holiday_type_id;
                if ($this->HolidayType->delete($holiday_type_id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'holiday_type'));
                }
            }
        } catch (exception $ex) {
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

}
