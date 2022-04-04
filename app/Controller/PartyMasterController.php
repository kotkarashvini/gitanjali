<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RoadVicinityController
 *
 * @author admin
 */
class PartyMasterController extends AppController {

    public function IdentifierType($id = null) {
        $this->loadModel('identifire_type');
        $this->loadModel('mainlanguage');

        $this->request->data['Identifier_Type']['state_id'] = $this->Auth->User("state_id");

        if (!empty($id)) {
            $actionvalue = 'lbleditmsg';
        } else {
            $actionvalue = 'lblsavemsg';
        }

        $result = $this->identifire_type->find("all", array('order' => 'desc_en ASC'));
        $this->set("identifierTypeList", $result);

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

//pr($user_id." ".$stateid." ".$statename);exit;      
//pr($result);exit;
//pr($languagelist);exit;

        $this->set("fieldlist", $fieldlist = $this->identifire_type->fieldlist($languagelist));
        $this->set('result_codes', $this->getvalidationruleset($fieldlist));
        if ($this->request->is('post') || $this->request->is('put')) {
//pr($this->request->data);exit;
            $verrors = $this->validatedata($this->request->data['Identifier_Type'], $fieldlist);
            if ($this->ValidationError($verrors)) {
                $duplicate = $this->identifire_type->get_duplicate($languagelist);
                $checkd = $this->check_duplicate($duplicate, $this->request->data['Identifier_Type']);
                if ($checkd) {


                    if ($this->identifire_type->save($this->request->data['Identifier_Type'])) {
                        $this->Session->setFlash(
                                __($actionvalue)
                        );

                        return $this->redirect(array('controller' => 'PartyMaster', 'action' => 'identifiertype'));
                    } else {
                        $this->Session->setFlash(
                                __("lblnotsavemsg")
                        );
                    }
                } else {
                    $this->Session->setFlash(__('lblduplicatemsg'));
                }
            } else {
                $this->Session->setFlash(__('Find validations '));
            }
        }



        if (!is_null($id) && is_numeric($id)) {
            $resultedit = $this->identifire_type->find("first", array('conditions' => array('type_id' => $id)));
            if (!empty($resultedit)) {
                $this->set('editflag', 'Y');
                $this->request->data['Identifier_Type'] = $resultedit['identifire_type'];
            } else {
                $this->Session->setFlash(
                        __('lblnotfoundmsg')
                );
            }
//         pr($resultedit);exit;
        }
    }

    public function IdentifierType_Delete($id = NULL) {
        $this->loadModel('identifire_type');
        if (!is_null($id) && is_numeric($id)) {
            if ($this->identifire_type->deleteAll(array('type_id' => $id))) {
                $this->Session->setFlash(
                        __('lbldeletemsg')
                );
            } else {
                $this->Session->setFlash(
                        __('lblnotdeletemsg')
                );
            }
        }
        return $this->redirect(array('controller' => 'PartyMaster', 'action' => 'identifiertype'));
    }

    /* shaker ngdrstab_mst_party_category */

    public function party_category_old() {
        try {
            $this->loadModel('party_category');
            $this->loadModel('State');
            $this->loadModel('User');
            $this->loadModel('ErrorMessages');
            $this->set('selectdivisionnew', NULL);
            $this->set('actiontypeval', NULL);
            $this->set('hfactionval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);

            $this->loadModel('language');
            $this->loadModel('mainlanguage');
//languages are loaded firstly from config (from table)
            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
                    ))
                , 'order' => 'conf.language_id ASC'));
            $this->set('languagelist', $languagelist);

            $this->set('divisionrecord', NULL);
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $divisionrecord = $this->party_category->find('all');
            $this->set('divisionrecord', $divisionrecord);
            $fieldlist = array();
            $fielderrorarray = array();
            foreach ($languagelist as $languagecode) {
//                 pr($languagecode['mainlanguage']['language_code']);exit;
                if ($languagecode['mainlanguage']['language_code'] == 'en') {
//list for english single fields
                    $fieldlist['category_name_' . $languagecode['mainlanguage']['language_code']]['text'] = 'is_required,is_alphaspace,is_maxlength255';
                } else {
//                     pr($languagecode);
//list for all unicode fieldsis_alphaspacemaxlenghth
//                    $fieldlist['category_name_' . $languagecode['mainlanguage']['language_code']]['text'] = '';
                    $fieldlist['category_name_' . $languagecode['mainlanguage']['language_code']]['text'] = "unicode_rule_" . $languagecode['mainlanguage']['language_code'] . ",maxlength_unicode_0to255";
                }
            }
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            if ($this->request->is('post')) {
                $this->check_csrf_token($this->request->data['party_category']['csrftoken']);


// code for reading msgs from json file which are loaded in the json file only once after the login is done successfully from database table
                $file = new File(WWW_ROOT . 'files/jsonfile_alerts.json');
                $json = $file->read(true, 'r');
                $alerts = json_decode($json, TRUE);
                $date = date('Y/m/d H:i:s');
                $created_date = date('Y/m/d');
//   $req_ip = $_SERVER['REMOTE_ADDR'];
                $actiontype = $_POST['actiontype'];
                $hfid = $_POST['hfid'];
                $this->set('hfid', $hfid);
                $hfactionval = $_POST['hfaction'];
                $this->request->data['party_category']['state_id'] = $stateid;
                $stateid = $this->Auth->User("state_id");
                if ($actiontype == '1') {
                    $this->set('actiontypeval', $actiontype);
                    $this->set('hfactionval', $hfactionval);
                    if ($hfactionval == 'S') {
                        $duplicateflag = 'S';
                        $this->request->data['party_category']['req_ip'] = $this->request->clientIp();
                        $this->request->data['party_category']['user_id'] = $user_id;
                        $this->request->data['party_category']['created_date'] = $created_date;
                        $this->request->data['party_category']['state_id'] = $stateid;
                        if ($this->request->data['hfupdateflag'] == 'Y') {
                            $this->request->data['party_category']['id'] = $this->request->data['hfid'];
                            $duplicateflag = 'U';
                            $actionvalue = "lbleditmsg";
// $adbc = $alerts['cast_category']['btnupdate'][$laug];
                        } else {
                            $actionvalue = "lblsavemsg";
//   $adbc = $alerts['cast_category']['btnadd'][$laug];
                        }
                        $this->request->data['party_category'] = $this->istrim($this->request->data['party_category']);
//  pr($this->request->data['cast_category']);exit;
                        $errarr = $this->validatedata($this->request->data['party_category'], $fieldlist);
                        $flag = 0;
                        foreach ($errarr as $dd) {
                            if ($dd != "") {
                                $flag = 1;
                            }
                        }
                        if ($flag == 1) {
                            $this->set("errarr", $errarr);
                        } else {
                            $duplicate['Table'] = 'ngdrstab_mst_party_category';
                            $duplicate['Fields'] = array('category_name_en', 'category_name_ll');
                            $duplicate['Action'] = $duplicateflag; //U  
                            $duplicate['PrimaryKey'] = 'id';
                            $checkd = $this->check_duplicate($duplicate, $this->request->data['party_category']);
                            if ($checkd) {
                                if ($this->party_category->save($this->request->data['party_category'])) {
                                    $this->Session->setFlash(__($actionvalue));
                                    $this->redirect(array('controller' => 'PartyMaster', 'action' => 'party_category'));
                                    $this->set('unitrecord', $this->party_category->find('all'));
                                } else {
                                    $this->Session->setFlash(__('lblnotsavemsg'));
                                }
                            } else {
                                $this->Session->setFlash(__('lblduplicatemsg'));
                            }
//                            if ($this->cast_category->save($this->request->data['cast_category'])) {
////                                $this->Session->setFlash(__("$message"));
//                                $this->Session->setFlash(__("Record $actionvalue"));
////                                $this->Session->setFlash($adbc);
//                                $this->redirect(array('controller' => 'Masters', 'action' => 'cast_category'));
//                                $this->set('divisionrecord', $this->cast_category->find('all'));
//                            } else {
//                                $this->Session->setFlash("Record not saved");
//                            }
                        }
                    }
                    if ($actiontype == 2) {
                        $this->set('hfupdateflag', 'Y');
                    }
                }
            }
        } catch (Exception $exc) {

            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->set_csrf_token();
    }

    public function party_category_delete_old($id = null) {
// pr($id);exit;
        $this->autoRender = false;
        $this->loadModel('party_category');
        try {

            if (isset($id) && is_numeric($id)) {
//  if ($type = 'cast_category') {
                $this->party_category->id = $id;
                if ($this->party_category->delete($id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'party_category'));
                }
// }
            }
        } catch (exception $ex) {
// pr($ex);exit;
        }
    }

    /* End shaker ngdrstab_mst_party_category */

    public function partytype($party_type_id = NULL) {
        try {
//pr($party_type_id);exit;
            array_map(array($this, 'loadModel'), array('NGDRSErrorCode', 'partytype', 'mainlanguage'));
            $user_id = $this->Auth->User("user_id");
            $req_ip = $_SERVER['REMOTE_ADDR'];
            $stateid = $this->Auth->User("state_id");
            $created_date = date('Y/m/d');
            $lang = $this->Session->read("sess_langauge");
            $this->set('lang', $lang);
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->set('actiontypeval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            $this->set('hfactionval', NULL);
            $this->set('partytyperecord', $this->partytype->find('all'));

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





            $this->request->data['partytype']['req_ip'] = $req_ip;
            $this->request->data['partytype']['user_id'] = $user_id;
// $this->request->data['fee_type']['created_date'] = $created_date;
            $this->request->data['partytype']['state_id'] = $stateid;

            $this->set("fieldlist", $fieldlist = $this->partytype->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
//this array of error is set here to display those correspondent fields error  in the ctp.

            if (!empty($party_type_id)) {
                $actionvalue = 'lbleditmsg';
            } else {
                $actionvalue = 'lblsavemsg';
            }

            if ($this->request->is('post') || $this->request->is('put')) {
//pr($this->request->data);exit;
                $this->check_csrf_token($this->request->data['partytype']['csrftoken']);

                $verrors = $this->validatedata($this->request->data['partytype'], $fieldlist);
                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->partytype->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['partytype']);
                    if ($checkd) {

                        if ($this->partytype->save($this->request->data['partytype'])) {
                            $this->Session->setFlash(__($actionvalue));
                            $this->redirect(array('controller' => 'PartyMaster', 'action' => 'partytype'));
                            $this->set('partytyperecord', $this->partytype->find('all'));
                        } else {
                            $this->Session->setFlash(__('lblnotsavemsg'));
                        }
                    } else {
                        $this->Session->setFlash(__('lblduplicatemsg'));
                    }
                } else {
                    $this->Session->setFlash(__('Find validations '));
                }
//                    }
//                }
//                if ($actiontype == 2) {
//                    $this->set('hfupdateflag', 'Y');
//                }
            }

            if (!is_null($party_type_id) && is_numeric($party_type_id)) {
                $this->set('editflag', 'Y');
                $this->Session->write('party_type_id', $party_type_id);
                $result = $this->partytype->find("first", array('conditions' => array('party_type_id' => $party_type_id)));
                $this->set('result', $result);
// pr($result);exit;
//                $this->request->data['upload_documententry'] = $result['fee_type'];
                if (!empty($result)) {
                    $this->request->data['partytype'] = $result['partytype'];
                } else {
                    $this->Session->setFlash(
                            __('lblnotfoundmsg')
                    );
                }
            }
        } catch (Exception $exc) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->set_csrf_token();
    }

    public function delete_partytype($id = null) {
        try {

            $this->autoRender = false;
            $this->loadModel('partytype');

            if (isset($id) && is_numeric($id)) {

                $this->partytype->party_type_id = $id;

                if ($this->partytype->delete($id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'partytype'));
                }
            }
        } catch (Exception $ex) {
            
        }
    }

    public function maincast($maincastid = NULL) {
        try {
            $this->loadModel('State');
            $this->loadModel('User');
            $this->loadModel('maincast');
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);

            $this->loadModel('language');
            $this->loadModel('mainlanguage');
//languages are loaded firstly from config (from table)
            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
            ))));
            $this->set('languagelist', $languagelist);
            $this->set('actiontypeval', NULL);
            $this->set('hfactionval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $state = $this->State->find('all', array('conditions' => array('state_id' => $stateid)));
            $this->set('state', $state[0]['State']['state_name_' . $laug]);
            $this->set('maincastrecord', $this->maincast->find('all'));
            $created_date = date('Y/m/d H:i:s');
            $req_ip = $_SERVER['REMOTE_ADDR'];
            $this->request->data['maincast']['req_ip'] = $req_ip;
            $this->request->data['maincast']['user_id'] = $user_id;
// $this->request->data['maincast']['created_date'] = $created_date;
            $this->request->data['maincast']['state_id'] = $stateid;

            $this->set("fieldlist", $fieldlist = $this->maincast->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
//this array of error is set here to display those correspondent fields error  in the ctp.

            if (!empty($maincastid)) {
                $actionvalue = 'lbleditmsg';
            } else {
                $actionvalue = 'lblsavemsg';
            }

            if ($this->request->is('post') || $this->request->is('put')) {
//pr($this->request->data);exit;
                $this->check_csrf_token($this->request->data['maincast']['csrftoken']);

                $verrors = $this->validatedata($this->request->data['maincast'], $fieldlist);
                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->maincast->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['maincast']);
                    if ($checkd) {

                        if ($this->maincast->save($this->request->data['maincast'])) {
                            $this->Session->setFlash(__($actionvalue));
                            $this->redirect(array('controller' => 'PartyMaster', 'action' => 'maincast'));
                            $this->set('maincastrecord', $this->maincast->find('all'));
                        } else {
                            $this->Session->setFlash(__('lblnotsavemsg'));
                        }
                    } else {
                        $this->Session->setFlash(__('lblduplicatemsg'));
                    }
                } else {
                    $this->Session->setFlash(__('Find validations '));
                }
//                    }
//                }
//                if ($actiontype == 2) {
//                    $this->set('hfupdateflag', 'Y');
//                }
            }

            if (!is_null($maincastid) && is_numeric($maincastid)) {
                $this->set('editflag', 'Y');
                $this->Session->write('maincast_id', $maincastid);
                $result = $this->maincast->find("first", array('conditions' => array('maincast_id' => $maincastid)));
                $this->set('result', $result);
//pr($result);exit;
//                $this->request->data['upload_documententry'] = $result['maincast'];
                if (!empty($result)) {
                    $this->request->data['maincast'] = $result['maincast'];
                } else {
                    $this->Session->setFlash(
                            __('lblnotfoundmsg')
                    );
                }
            }
        } catch (Exception $exc) {
            pr($exc);
            exit;
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->set_csrf_token();
    }

    public function delete_maincast($id = null) {
// pr($id);exit;
        $this->autoRender = false;
        $this->loadModel('maincast');
        try {

            if (isset($id) && is_numeric($id)) {
//  if ($type = 'behavioural') {
                $this->maincast->maincast_id = $id;
                if ($this->maincast->delete($id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'maincast'));
                }
// }
            }
        } catch (exception $ex) {
// pr($ex);exit;
        }
    }

    public function OccupationList($id = null) {
        try {

//            pr($id);exit;
            $this->loadModel('occupation');
            $this->loadModel('mainlanguage');
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $this->request->data['occupationlist']['state_id'] = $this->Auth->User("state_id");
            $result = $this->occupation->find("all", array('order' => 'occupation_name_en ASC'));
            $this->set("OccupationListResult", $result);

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

//pr($user_id." ".$stateid." ".$statename);exit;      
//pr($result);exit;
//pr($languagelist);exit;

            $this->set("fieldlist", $fieldlist = $this->occupation->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if (!empty($id)) {
                $actionvalue = 'lbleditmsg';
            } else {
                $actionvalue = 'lblsavemsg';
            }

            if ($this->request->is('post') || $this->request->is('put')) {
//pr($this->request->data);exit;

                $verrors = $this->validatedata($this->request->data['occupationlist'], $fieldlist);
                //pr($verrors);exit;
                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->occupation->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['occupationlist']);
                    if ($checkd) {
                        if ($this->occupation->save($this->request->data['occupationlist'])) {
                            $this->Session->setFlash(__($actionvalue));

                            return $this->redirect(array('controller' => 'PartyMaster', 'action' => 'occupationlist'));
                        } else {
                            $this->Session->setFlash(
                                    __("lblnotsavemsg")
                            );
                        }
                        //}
                    } else {
                        $this->Session->setFlash(__('lblduplicatemsg'));
                    }
                } else {
                    $this->Session->setFlash(__('Find validations '));
                }
            }
            if (!is_null($id) && is_numeric($id)) {

                $resultedit = $this->occupation->find("first", array('conditions' => array('occupation_id' => $id)));

                // pr($resultedit);exit;
                if (!empty($resultedit)) {
                    $this->set('editflag', 'Y');
                    $this->request->data['occupationlist'] = $resultedit['occupation'];
                } else {
                    $this->Session->setFlash(
                            __('lblnotfoundmsg')
                    );
                }
//         pr($resultedit);exit;
            }
        } catch (exception $ex) {
// pr($ex);exit; pr($exc);
            exit;
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->set_csrf_token();
    }

    public function Occupation_Delete($id = NULL) {
        $this->loadModel('occupation');
        if (!is_null($id) && is_numeric($id)) {
            if ($this->occupation->deleteAll(array('id' => $id))) {
                $this->Session->setFlash(
                        __('lbldeletemsg')
                );
            } else {
                $this->Session->setFlash(
                        __('lblnotdeletemsg')
                );
            }
        }
        return $this->redirect(array('controller' => 'PartyMaster', 'action' => 'occupationlist'));
    }

    public function presentationexemption($paraexemption_id = null) {
        $this->loadModel('presentationexemption');
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
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
        $stateid = $this->Auth->User("state_id");

        //$result = $this->userdefdepe1->find("all", array('order' => 'id ASC'));
        //pr($this->request->data);exit();
        //pr($result);exit();

        $this->set("fieldlist", $fieldlist = $this->presentationexemption->fieldlist($languagelist));
        $this->set('result_codes', $this->getvalidationruleset($fieldlist));

        //$result = $this->userdefdepe1->find("all", array('order' => 'id ASC'));
        //pr($this->request->data);exit();
        //pr($result);exit();
        $result = $this->presentationexemption->find("all", array('order' => 'exemption_id ASC'));
        $this->set("result", $result);
        
          if (!empty($paraexemption_id)) {
                $actionvalue = 'lbleditmsg';
            } else {
                $actionvalue = 'lblsavemsg';
            }
            
        if ($this->request->is('post') || $this->request->is('put')) {
            //pr($this->request->data);exit;

            $this->request->data['state_id'] = $stateid;



            $verrors = $this->validatedata($this->request->data['presentationexemption'], $fieldlist);
            //pr($verrors);exit;
            if ($this->ValidationError($verrors)) {
                $duplicate = $this->presentationexemption->get_duplicate($languagelist);
                $checkd = $this->check_duplicate($duplicate, $this->request->data['presentationexemption']);
                if ($checkd) {
                    if ($this->presentationexemption->save($this->request->data['presentationexemption'])) {
                        $this->Session->setFlash(__($actionvalue));

                        return $this->redirect(array('controller' => 'PartyMaster', 'action' => 'presentationexemption'));
                    } else {
                        $this->Session->setFlash(
                                __("lblnotsavemsg")
                        );
                    }
                } else {
                    $this->Session->setFlash(__('lblduplicatemsg'));
                }
            } else {
                $this->Session->setFlash(__('Find validations '));
            }
        }

        // $this->District->find("all",array('conditions'=>array('state_id'=>27)));


        if (is_numeric($paraexemption_id)) {
            $this->set('editflag', 'Y');
            $resultedit = $this->presentationexemption->find("first", array('conditions' => array('exemption_id' => $paraexemption_id)));

//         pr($resultedit);exit;
            $this->request->data['presentationexemption'] = $resultedit['presentationexemption'];
        }
    }

    public function presentationexemption_delete($paraexemption_id = NULL) {
        $this->loadModel('presentationexemption');
        if (is_numeric($paraexemption_id)) {
            $this->presentationexemption->deleteAll(array('exemption_id' => $paraexemption_id));
        }
        $this->Session->setFlash(
                __('lbldeletemsg')
        );

        return $this->redirect(array('controller' => 'PartyMaster', 'action' => 'presentationexemption'));
    }
    
     public function party_category($categoryid = NULL) {
        try {
            $this->loadModel('State');
            $this->loadModel('User');
            $this->loadModel('party_category');
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);

            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            //languages are loaded firstly from config (from table)
            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
            ))));
            $this->set('languagelist', $languagelist);
            $this->set('actiontypeval', NULL);
            $this->set('hfactionval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $state = $this->State->find('all', array('conditions' => array('state_id' => $stateid)));
            $this->set('state', $state[0]['State']['state_name_' . $laug]);
            
            $this->set('categoryrecord', $this->party_category->find('all'));
            $created_date = date('Y/m/d H:i:s');
            $req_ip = $_SERVER['REMOTE_ADDR'];
            $this->request->data['party_category']['req_ip'] = $req_ip;
            $this->request->data['party_category']['user_id'] = $user_id;
            // $this->request->data['fee_type']['created_date'] = $created_date;
            $this->request->data['party_category']['state_id'] = $stateid;

            $this->set("fieldlist", $fieldlist = $this->party_category->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            //this array of error is set here to display those correspondent fields error  in the ctp.

            if (!empty($feetypeid)) {
                $actionvalue = 'lbleditmsg';
            } else {
                $actionvalue = 'lblsavemsg';
            }

            if ($this->request->is('post') || $this->request->is('put')) {
                //pr($this->request->data);exit;
                $this->check_csrf_token($this->request->data['party_category']['csrftoken']);

                $verrors = $this->validatedata($this->request->data['party_category'], $fieldlist);
                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->party_category->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['party_category']);
                    if ($checkd) {

                        if ($this->party_category->save($this->request->data['party_category'])) {
                            $this->Session->setFlash(__($actionvalue));
                            $this->redirect(array('controller' => 'PartyMaster', 'action' => 'party_category'));
                            $this->set('categoryrecord', $this->party_category->find('all'));
                        } else {
                            $this->Session->setFlash(__('lblnotsavemsg'));
                        }
                    } else {
                        $this->Session->setFlash(__('lblduplicatemsg'));
                    }
                } else {
                    $this->Session->setFlash(__('Find validations '));
                }
//                    }
//                }
//                if ($actiontype == 2) {
//                    $this->set('hfupdateflag', 'Y');
//                }
            }

            if (!is_null($categoryid) && is_numeric($categoryid)) {
                $this->set('editflag', 'Y');
                $this->Session->write('category_id', $categoryid);
                $result = $this->party_category->find("first", array('conditions' => array('category_id' => $categoryid)));
                $this->set('result', $result);
                //pr($result);exit;
//                $this->request->data['upload_documententry'] = $result['fee_type'];
                if (!empty($result)) {
                    $this->request->data['party_category'] = $result['party_category'];
                } else {
                    $this->Session->setFlash(
                            __('lblnotfoundmsg')
                    );
                }
            }
        } catch (Exception $exc) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->set_csrf_token();
    }

    public function delete_category($id = null) {
        // pr($id);exit;
        $this->autoRender = false;
        $this->loadModel('party_category');
        try {

            if (isset($id) && is_numeric($id)) {
                //  if ($type = 'behavioural') {
                $this->party_category->category_id = $id;
                if ($this->party_category->delete($id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'party_category'));
                }
                // }
            }
        } catch (exception $ex) {
            // pr($ex);exit;
        }
    }

    public function identificationtype($identificationtypeid = NULL) {
        try {
           
            $this->loadModel('State');
            $this->loadModel('User');
            $this->loadModel('identificatontype');
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);

            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            //languages are loaded firstly from config (from table)
            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
            ))));
            $this->set('languagelist', $languagelist);
            $this->set('actiontypeval', NULL);
            $this->set('hfactionval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $state = $this->State->find('all', array('conditions' => array('state_id' => $stateid)));
            $this->set('state', $state[0]['State']['state_name_' . $laug]);
            $this->set('divisionrecord', $this->identificatontype->find('all'));
            $created_date = date('Y/m/d H:i:s');
            $req_ip = $_SERVER['REMOTE_ADDR'];
            $this->request->data['identificatontype']['req_ip'] = $req_ip;
            $this->request->data['identificatontype']['user_id'] = $user_id;
            // $this->request->data['identificatontype']['created_date'] = $created_date;
            $this->request->data['identificatontype']['state_id'] = $stateid;

            $this->set("fieldlist", $fieldlist = $this->identificatontype->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            //this array of error is set here to display those correspondent fields error  in the ctp.

            if (!empty($identificationtypeid)) {
                $actionvalue = 'lbleditmsg';
            } else {
                $actionvalue = 'lblsavemsg';
            }

            if ($this->request->is('post') || $this->request->is('put')) {
                
                //pr($this->request->data);exit;
                $this->check_csrf_token($this->request->data['identificatontype']['csrftoken']);

                $verrors = $this->validatedata($this->request->data['identificatontype'], $fieldlist);
                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->identificatontype->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['identificatontype']);
                    if ($checkd) {

                        if ($this->identificatontype->save($this->request->data['identificatontype'])) {
                            $this->Session->setFlash(__($actionvalue));
                            $this->redirect(array('controller' => 'PartyMaster', 'action' => 'identificationtype'));
                            $this->set('divisionrecord', $this->identificationtype->find('all'));
                        } else {
                            $this->Session->setFlash(__('lblnotsavemsg'));
                        }
                    } else {
                        $this->Session->setFlash(__('lblduplicatemsg'));
                    }
                } else {
                    $this->Session->setFlash(__('Find validations '));
                }
//                    }
//                }
//                if ($actiontype == 2) {
//                    $this->set('hfupdateflag', 'Y');
//                }
            }

            if (!is_null($identificationtypeid) && is_numeric($identificationtypeid)) {
                $this->set('editflag', 'Y');
                $this->Session->write('identificatontype_id', $identificationtypeid);
                $result = $this->identificatontype->find("first", array('conditions' => array('identificationtype_id' => $identificationtypeid)));
                $this->set('result', $result);
                //pr($result);exit;
//                $this->request->data['upload_documententry'] = $result['identificatontype'];
                if (!empty($result)) {
                    $this->request->data['identificatontype'] = $result['identificatontype'];
                } else {
                    $this->Session->setFlash(
                            __('lblnotfoundmsg')
                    );
                }
            }
        } catch (Exception $exc) {
//            pr($exc);exit;
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->set_csrf_token();
    }

    public function identificatontype_delete($identificationtypeid = null) {
//         pr($id);exit;
        $this->autoRender = false;
        $this->loadModel('identificatontype');
        try {

            if (isset($identificationtypeid) && is_numeric($identificationtypeid)) {
                //  if ($type = 'identifire_type') {
                $this->identificatontype->identificationtype_id = $identificationtypeid;
                if ($this->identificatontype->delete($identificationtypeid)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'identificationtype'));
                }
                // }
            }
        } catch (exception $ex) {
            // pr($ex);exit;
        }
    }

    
    
}
