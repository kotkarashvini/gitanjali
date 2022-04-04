<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ValuationRulesController
 * va
 * @author nicsi
 */
class ValuationRulesController extends AppController {

    public function beforeFilter() {
        $this->loadModel('language');
        $langaugelist = $this->language->find('all', array('conditions' => array('state_id' => array(0, $this->Auth->user('state_id')))));
        $this->set('langaugelist', $langaugelist);
//        $this->Security->unlockedActions = array('evalrule', 'save_eval_subrule', 'remove_eval_subrule', 'getsubsubruledesc', 'getrulebycdrv', 'getcdrflags', 'getcategoryids', 'getMaxOutOrderId', 'get_subrule_list',
//            'get_usage_sub_category_list', 'get_usage_sub_sub_category_list', 'usage_linkage', 'remove_usage_link', 'usage_items', 'remove_usage_item', 'usage_items_list', 'remove_usage_list_item', 'rule_functions', 'index', 'valuation_rule', 'modifyfieldlist', 'copy_rule', 'get_rule_flags', 'remove_val_rule', 'rule_items_linkage', 'remove_rule_item', 'valuation_sub_rule', 'copy_subrule', 'remove_valuation_subrule', 'check_rule_already_present', 'replace_oprator_to_string'
//        );

        if (isset($this->Security)) { //&& isset($this->Auth)) {
            $this->Security->validatePost = false;
            $this->Security->enabled = false;
            $this->Security->csrfCheck = false;
        }
    }

    public function evalrule() {
        try {
            //load Models
            array_map([$this, 'loadModel'], ['evalrule', 'subrule', 'usagelinkcategory']);
            //Set Variables
            $name = array_keys($this->evalrule->getColumnTypes());
            $lang = $this->Session->read("sess_langauge");
            $stateid = $this->Auth->User("state_id");
            $hsrflg = $ruleid = $mcatg = $scatg = $sscatg = $scatglist = $sscatglist = $hfaction = NULL;
            $this->set(compact('name', 'lang', 'stateid', 'hsrflg', 'ruleid', 'mcatg', 'scatg', 'sscatg', 'scatglist', 'sscatglist', 'hfaction'));
            $this->set('finyearList', ClassRegistry::init('finyear')->find('list', array('fields' => array('finyear_id', 'finyear_desc'), 'order' => array('current_year DESC,finyear_id'))));
            $this->set('maincat_id', ClassRegistry::init('usage_main_category')->find('list', array('fields' => array('usage_main_catg_id', 'usage_main_catg_desc_' . $lang), 'conditions' => array('state_id' => $stateid), 'order' => array('usage_main_catg_desc_en' => 'ASC'))));
            $this->set('outitemlist', ClassRegistry::init('itemlist')->find('list', array('fields' => array('usage_param_id', 'usage_param_desc_' . $lang), 'conditions' => array('state_id' => $stateid, 'usage_param_type_id' => 2), 'order' => array('usage_param_desc_en' => 'ASC'))));
            $configure = ClassRegistry::init('damblkdpnd')->query("select * from ngdrstab_conf_state_district_div_level where state_id=?", array($stateid));
            $this->set('configure', $configure);
            $this->set('statelist', ClassRegistry::init('State')->find('list', array('fields' => array('state_id', 'state_name_' . $lang), 'order' => array('state_name_en' => 'ASC'), 'conditions' => array('state_id' => $stateid))));
            $this->set('divisionlist', ClassRegistry::init('division')->find('list', array('fields' => array('id', 'division_name_' . $lang), 'order' => array('division_name_en' => 'ASC'))));
            $this->set('districtlist', ClassRegistry::init('District')->find('list', array('fields' => array('id', 'district_name_' . $lang), 'order' => array('district_name_en' => 'ASC'))));
            $this->set('maxvalueparameterslist', ClassRegistry::init('itemlist')->find('list', array('fields' => array('usage_param_code', 'usage_param_desc_' . $lang), 'conditions' => array('state_id' => $stateid, 'usage_param_type_id' => array(4, 99)), 'order' => array('usage_param_desc_en' => 'ASC'))));
            $this->set('roadvicinitylist', ClassRegistry::init('roadvicinity')->find('list', array('fields' => array('id', 'road_vicinity_desc_' . $lang), 'order' => array('road_vicinity_desc_en' => 'ASC'))));
            $this->set('userdd1list', ClassRegistry::init('user_defined_dependancy1')->find('list', array('fields' => array('id', 'user_defined_dependency1_desc_' . $lang), 'order' => array('user_defined_dependency1_desc_en' => 'ASC'))));
            $this->set('userdd2list', ClassRegistry::init('user_defined_dependancy2')->find('list', array('fields' => array('id', 'user_defined_dependency2_desc_' . $lang), 'order' => array('user_defined_dependency2_desc_en' => 'ASC'))));
            $this->set('ulbname', ClassRegistry::init('corporationclass')->find('list', array('fields' => array('ulb_type_id', 'class_description_' . $lang), 'order' => array('class_description_en' => 'ASC'))));
            $this->set('dependancylist', ClassRegistry::init('dependencyitems')->find('list', array('fields' => array('id', 'item_desc_' . $lang), 'order' => array('item_desc' => 'ASC'))));
            $this->set('parameters', ClassRegistry::init('articleparameters')->find('list', array('fields' => array('id', 'parameter_desc_' . $lang), 'order' => array('parameter_desc_en' => 'ASC'))));
            $this->set('operators', ClassRegistry::init('operators')->find('list', array('fields' => array('operatorsign', 'optrname'), 'order' => array('operator_name_en' => 'ASC'))));
            $this->set('evalruledata', ClassRegistry::init('evalrule')->find('all', array('order' => 'created DESC')));

            //Code After Page Submit
            if ($this->request->is('POST')) {

                $frm = $this->request->data['frmevalrule'];
                $action = $frm['action'];
                $hfid = $frm['hid'];
                if ($action == 'U' || $action == 'SV') {
                    $frm['req_ip'] = $_SERVER['REMOTE_ADDR'];
                    $frm['user_id'] = $this->Auth->User('user_id');
                    //$frm['created_date'] = date('Y-m-d');
                    $frm['state_id'] = $this->Auth->User('state_id');
                    $this->set('hfaction', $action);
                    $subruleflg = $frm['subrule_flag'];
                    $frm['effective_date'] = date('Y-m-d', strtotime($frm['effective_date']));
                    for ($i = 2; $i < 11; $i += 2) {// for replacing && and or with preceding and ending space for conditions
                        $frm[$name[$i]] = preg_replace('/\s+/', '', $frm[$name[$i]]);
                        $frm[$name[$i]] = str_replace('&&', ' && ', $frm[$name[$i]]);
                        $frm[$name[$i]] = str_replace('||', ' || ', $frm[$name[$i]]);
                    }
                    if ($frm[$name[42]] == 'N') {//Comparision Rate Flag
                        $frm[$name[43]] = $frm[$name[44]] = $frm[$name[45]] = 0;
                    }
                    if ($frm[$name[47]] == 'N') {//Additional Rate Flag
                        $frm[$name[59]] = $frm[$name[60]] = $frm[$name[61]] = 0;
                    }
                    if ($frm[$name[36]] == 'N') {//Location Dependancy  Flag
                        $frm[$name[27]] = $frm[$name[28]] = $frm[$name[29]] = $frm[$name[30]] = $frm[$name[31]] = $frm[$name[32]] = $frm[$name[33]] = $frm[$name[34]] = $frm[$name[35]] = $frm[$name[12]] = 0;
                    }

                    $conditions = NULL;
                    $countconditions = NULL;
                    if ($frm['maincategory']) {
                        $conditions['usage_main_catg_id'] = $frm['maincategory'];
                    }
                    if ($frm['subcategory']) {
                        $conditions['usage_sub_catg_id'] = $frm['subcategory'];
                    }
                    if ($frm['subsbucategory']) {
                        $conditions['usage_sub_sub_catg_id'] = $frm['subsbucategory'];
                    }
                    if ($frm['dependency_item_list']) {
                        $frm['dependency_item_list'] = implode(',', $frm['dependency_item_list']);
                    }
                    if ($frm[$name[24]]) {//road vicinity
                        $countconditions[$name[24]] = $frm[$name[24]];
                    }
                    if ($frm[$name[25]]) {// user defined dependancy 1
                        $countconditions[$name[25]] = $frm[$name[25]];
                    }
                    if ($frm[$name[26]]) {// user defined dependancy 2
                        $countconditions[$name[26]] = $frm[$name[26]];
                    }


                    if ($action == 'U') {
                        $frm['evalrule_id'] = $hfid;
                        $savetype = "lbleditmsg";
                    } else {
                        $savetype = "lblsavemsg";
                    }

                    $subruleAction = "";
                    $this->set('mcatg', $frm['maincategory']);
                    $this->set('scatg', $frm['subcategory']);
                    $this->set('sscatg', $frm['subsbucategory']);
                    $this->set('rvid', $frm[$name[24]]);
                    $this->set('udd1id', $frm[$name[25]]);
                    $this->set('udd2id', $frm[$name[26]]);
                    $countconditions['evalrule_desc_en'] = $frm[$name[1]];
                    if ($hfid != NULL) {
                        if ($this->subrule->find('count', array('conditions' => array('evalrule_id' => $hfid))) > 0) {
                            $frm['subrule_flag'] = 'Y';
                        } else {
                            $frm['subrule_flag'] = 'N';
                        }
                    }
                    $linkcatConditions = $conditions;
                    $conditions['not'] = array('evalrule_id' => null);
                    if ($frm[$name[42]] == 'N' && $frm[$name[47]] == 'N') {
                        $frm[$name[43]] = $frm[$name[44]] = $frm[$name[45]] = 0;
                    }
                    if (($this->usagelinkcategory->find('count', array('conditions' => $conditions)) > 0) and $action != "U") {//If Rule Already Exists for Perticular Category i.e Main,sub,sub sub, Construction Type, Depreciation, Road Vicinity,UDD1,UDD2
                        $this->Session->setFlash("Rule Already Submitted... If U want to add subrule.. please Update Rule");
                    } else if ($this->evalrule->save($frm)) {

                        $lnkupdate = "";
                        if ($action == 'SV') {
                            $lastinsertid = $this->evalrule->getLastInsertId();
                            $evalruleid = $this->evalrule->find('all', array('fields' => array('evalrule_id'), 'conditions' => array('id' => $lastinsertid)));
                            $hfid = $evalruleid[0]['evalrule']['evalrule_id'];
                            if ($subruleflg == 'Y') {
                                $this->set('hsrflg', 'Y');
                                $this->set('ruleid', $evalruleid[0]['evalrule']['evalrule_id']);
                                // if ($this->subrule->saveSubrule($evalruleid[0]['evalrule']['evalrule_id'], NULL, $frm[$name[2]], $frm[$name[3]], $frm[$name[4]], $frm[$name[5]], $frm[$name[19]], $frm[$name[18]], $frm[$name[22]], $this->Auth->User('state_id'), $frm[$name[24]], $frm[$name[25]], $frm[$name[26]], $frm['out_item_order'], $frm[$name[6]], $frm[$name[7]], $frm[$name[8]], $frm[$name[9]], $frm[$name[10]], $frm[$name[11]], $frm[$name[62]], $frm['user_id'])) {
                                if ($this->subrule->saveSubrule($frm)) {
                                    $subruleAction = " With Subrule ";
                                    if ($this->subrule->find('count', array('conditions' => array('evalrule_id' => $hfid))) > 0) {
                                        $this->evalrule->updateAll(array("subrule_flag" => "'Y'"), array("evalrule_id" => $hfid));
                                    } else {
                                        $this->evalrule->updateAll(array("subrule_flag" => "'N'"), array("evalrule_id" => $hfid));
                                    }
                                }
                            } else {
                                $subruleAction = "";
                            }
                        }
                        if ($this->usagelinkcategory->updateAll(array("evalrule_id" => $hfid), $linkcatConditions)) {
                            $lnkupdate = ",Rule ID Updated in Usage Category Link";
                        } else {
                            $lnkupdate = ",Rule ID  Not Updated in Usage Category Link as Category Not Found";
                        }
                        if ($action == 'U') {
                            $this->Session->setFlash(__($savetype));
                            $this->redirect(array('action' => 'evalrule'));
                        }

                        if ($frm['subrule_flag'] === 'N') {
                            $this->Session->setFlash(__($savetype));
                            $this->redirect(array('action' => 'evalrule'));
                        }
                        $this->set('evalruledata', ClassRegistry::init('evalrule')->find('all', array('order' => 'created DESC')));
                        $this->Session->setFlash(__("$savetype  $subruleAction  $lnkupdate"));
                    } else {
                        $this->Session->setFlash("There is some error");
                    }
                } else if ($action == 'D') {
                    if ($this->evalrule->delete($hfid)) {
                        $this->subrule->deleteAll(array('evalrule_id' => $hfid), FALSE);
                        $this->usagelinkcategory->updateAll(array("evalrule_id" => NULL), array("evalrule_id" => $hfid));
                        $this->Session->setFlash(__('lbldeletemsg'));

                        $this->redirect(array('action' => 'evalrule'));
                        $this->set('ruleid', NULL);
                    } else {
                        $this->Session->setFlash(__('lblnotdeletemsg'));
                    }
                }
            }
        } catch (Exception $ex) {
            $this->Session->setFlash('Sorry! Error in fetching Valuation Data');
        }
    }

    public function save_eval_subrule() {
        try {
            array_map([$this, 'loadModel'], ['subrule', 'evalrule']);
            $name = array_keys($this->evalrule->getColumnTypes());

            $subRuleId = NULL;
            $frm = $this->request->data['frmevalrule'];
            // for replacing && and or with preceding and ending space for conditions
            for ($i = 2; $i < 11; $i += 2) {
                $frm[$name[$i]] = preg_replace('/\s+/', '', $frm[$name[$i]]);
                $frm[$name[$i]] = str_replace('&&', ' && ', $frm[$name[$i]]);
                $frm[$name[$i]] = str_replace('||', ' || ', $frm[$name[$i]]);
            }
            if ($frm['subruleid']) {
                $subRuleId = $frm['subruleid'];
            }
            $frm['req_ip'] = $_SERVER['REMOTE_ADDR'];
            $frm['user_id'] = $this->Auth->User('user_id');
            // $frm['created_date'] = date('Y-m-d H:i:s');
            $frm['state_id'] = $this->Auth->User('state_id');
            if ($this->subrule->saveSubrule($frm) == 1) {
                $this->evalrule->updateAll(array("subrule_flag" => "'Y'"), array("evalrule_id" => $frm['hid']));
                echo 1;
            } else {
                echo 0;
            }
            exit;
        } catch (Exception $ex) {
            $this->Session->setFlash('Sorry! Error in saving Subrule Data');
        }
    }

//-----------------------------remove Subrule---------------------------------------------------
    public function remove_eval_subrule() {
        try {
            $this->autoRender = FALSE;
            array_map([$this, 'loadModel'], ['subrule', 'evalrule']);
            if (isset($this->request->data['rule_id']) and isset($this->request->data['sub_rule_id'])) {
                if (ctype_digit($this->request->data['rule_id']) && ctype_digit($this->request->data['sub_rule_id'])) {
                    if ($this->subrule->delete($this->request->data['sub_rule_id'])) {
                        if ($this->subrule->find('count', array('conditions' => array('evalrule_id' => $this->request->data['rule_id']))) > 0) {
                            $this->evalrule->updateAll(array("subrule_flag" => "'Y'"), array("evalrule_id" => $this->request->data['rule_id']));
                        } else {
                            $this->evalrule->updateAll(array("subrule_flag" => "'N'"), array("evalrule_id" => $this->request->data['rule_id']));
                        }
                        return 1;
                    } else {
                        return 0;
                    }
                } else {
                    return 'invalid input';
                }
            } else {
                return "ruleid or subrule_id Missing";
            }
        } catch (Exception $ex) {
            $this->Session->setFlash('Sorry! Error in removing Valuation Subrule');
        }
    }

//-----------------------------Get Usage Sub Sub Category Desc---------------------------------------------------
    public function getsubsubruledesc() {
        try {
            $this->autoRender = FALSE;
            $ussc = $this->request->data['usage_sub_sub_catg_id'];
            if (ctype_digit($ussc)) {
                $result = ClassRegistry::init('usage_sub_sub_category')->find('all', array('fields' => array('usage_sub_sub_catg_desc_ll', 'usage_sub_sub_catg_desc_en'), 'conditions' => array('usage_sub_sub_catg_id' => $ussc), 'order' => 'usage_sub_sub_catg_id asc'));
                return json_encode($result[0]['usage_sub_sub_category']);
            } else {
                return 'invalid input';
            }
        } catch (Exception $e) {
            $this->Session->setFlash('Error in Fetching Data');
        }
    }

//------------------------------Get Usage Main,Sub,SubSub Categories----------------------------------------------
    public function getcategoryids() {
        try {
            $this->autoRender = FALSE;
            $ruleid = $this->request->data['evalruleid'];
            if (ctype_digit($ruleid)) {
                $ruleresult = ClassRegistry::init('evalrule')->find('all', array('fields' => array('evalrule_id'), 'conditions' => array('evalrule_id' => $ruleid), 'order' => 'evalrule_id asc'));
                $frm = $ruleresult[0]['evalrule'];
                $conditions['evalrule_id'] = $frm['evalrule_id'];
                $result = ClassRegistry::init('usagelinkcategory')->find('all', array('fields' => array('usage_main_catg_id', 'usage_sub_catg_id', 'usage_sub_sub_catg_id'), 'conditions' => $conditions, 'order' => 'usage_sub_sub_catg_id asc'));
                return json_encode($result[0]['usagelinkcategory']);
            } else {
                return 'wrong input';
            }
        } catch (Exception $e) {
            $this->Session->setFlash('Sorry! Error in Fetching Categories');
            //$this->redirect(array('action' => 'error404'));
        }
    }

//--------------------------get Construction,Depreciation,Road vicinity Flag for Usage Sub Sub Category----------------------------------------------------
    public function getcdrflags() {
        try {
            $this->autoRender = FALSE;
            $sub_sub_catg_id = $this->request->data['usage_sub_sub_catg_id'];
            if (ctype_digit($sub_sub_catg_id)) {
                $this->loadModel('usage_sub_sub_category');
                $result = $this->usage_sub_sub_category->find('all', array('fields' => array('contsruction_type_flag', 'depreciation_flag', 'road_vicinity_flag', 'user_defined_dependency1_flag', 'user_defined_dependency2_flag'), 'conditions' => array('usage_sub_sub_catg_id' => $sub_sub_catg_id)));
                return json_encode($result[0]['usage_sub_sub_category']);
            } else {
                return "invalid input";
            }
        } catch (Exception $ex) {
            $this->Session->setFlash("Sorry! Error in getting data");
        }
    }

    //------------------------------------  Get Subrule List 24-Jan-2017------------------------------------------------------------------------------------------
    public function get_subrule_list() {
        try {
            $rule_id = $this->request->data['ruleid'];
            if (ctype_digit($rule_id)) {
                $subruleList = ClassRegistry::init('subrule')->Query("select evalrule_id, subrule_id,rv.road_vicinity_desc_en, evalsubrule_cond1, evalsubrule_formula1, evalsubrule_cond2, evalsubrule_formula2,evalsubrule_cond3,evalsubrule_formula3,evalsubrule_cond4,evalsubrule_formula4,evalsubrule_cond5,evalsubrule_formula5,rate_revision_flag,rate_revision_formula1,rate_revision_formula2,rate_revision_formula3,rate_revision_formula4,rate_revision_formula5, max_value_condition_flag, max_value_formula, sbr.output_item_id,iL.usage_param_desc_en,iL.usage_param_desc_ll,sbr.out_item_order
                        from ngdrstab_mst_evalsubrule sbr
                        left outer join ngdrstab_mst_usage_items_list iL on iL.usage_param_id=sbr.output_item_id
                        left outer join ngdrstab_mst_road_vicinity rv on rv.road_vicinity_id=sbr.road_vicinity_id
                        where evalrule_id=? order by subrule_id,out_item_order", array($rule_id));
                $this->set('subrule_list', $subruleList);
            } else {
                return 'Invalid Input';
            }
        } catch (Exception $ex) {
            $this->Session->setFlash('Sorry! Error fetching Subrule List');
        }
    }

    //----------------------------------------------------------Get Max Orderid of Output Item in Subrule-----------------------------------------

    public function getMaxOutOrderId() {
        try {
            $this->autoRender = FALSE;
            if (isset($this->request->data['ruleid']) && ctype_digit($this->request->data['ruleid'])) {
                $itpid = ClassRegistry::init('subrule')->find('first', array('fields' => array('out_item_order'), 'conditions' => array('evalrule_id' => $this->request->data['ruleid']), 'order' => 'out_item_order DESC'));
                if ($itpid) {
                    return json_encode(++$itpid['subrule']['out_item_order']);
                } else {
                    return json_encode(1);
                }
            } else {
                return 'Invalid Input';
            }
        } catch (Exception $e) {
            $this->Session->setFlash('Sorry! Error in Getting max order Id');
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------
    public function getrulebycdrv() {
        try {
            $this->autoRender = FALSE;
            $cid = $this->request->data['constuction_id'];
            $did = $this->request->data['deprecition_id'];
            $rvid = $this->request->data['rvicinity_id'];
            if (ctype_digit($cid) && ctype_digit($did) && ctype_digit($rvid)) {
                $result = ClassRegistry::init('evalrule')->find('list', array('fields' => array('evalrule_id', 'evalrule_desc_' . $this->Session->read("sess_langauge")), 'conditions' => array('construction_type_id' => $cid, 'depreciation_id' => $did, 'road_vicinity_id' => $rvid), 'order' => 'evalrule_id asc'));
                return json_encode($result);
            }
        } catch (Exception $e) {
            $this->Session->setFlash('Sorry! Error gettind data');
            //$this->redirect(array('action' => 'error404'));
        }
    }

    /* -----*-------*----------------------------------------------------New Valuation Rule (7-15 Feb-2017)-------------------------------------------------------------------- 
      Created on 7- 15 Feb 2017 by Shridhar & Madhuri
     * Last Updated on 3-June-2017 by Shridhar
     *  
     */

    //------------------------------Get Usage Sub Category List by Main Category----------------------------------------------
    public function get_usage_sub_category_list() {
        try {
            $this->autoRender = FALSE;
            if (isset($this->request->data['usage_main_catg_id'])) {
                $usage_main_catg_id = $this->request->data['usage_main_catg_id'];
                $lang = $this->Session->read("sess_langauge");
                if (ctype_digit($usage_main_catg_id)) {
                    $subid = ClassRegistry::init('usage_category')->find('list', array('fields' => array('usage_sub_catg_id'), 'conditions' => array('usage_main_catg_id' => $usage_main_catg_id)));
                    $usagesubname = ClassRegistry::init('usage_sub_category')->find('list', array('fields' => array('usage_sub_catg_id', 'usage_sub_catg_desc_' . $lang), 'conditions' => array('usage_sub_catg_id' => $subid)));
                    return json_encode($usagesubname);
                } else {
                    return 'Wrong Input';
                }
            } else {
                return "provide main category";
            }
        } catch (Exception $e) {
            $this->Session->setFlash('Sorry! Error in getting Category List');
        }
    }

//------------------------------Get Usage Sub Category List by Main Category----------------------------------------------
    public function get_usage_sub_sub_category_list() {
        try {
            $this->autoRender = FALSE;
            if (isset($this->request->data['usage_main_catg_id'])and isset($this->request->data['usage_sub_catg_id'])) {
                $usage_main_catg_id = $this->request->data['usage_main_catg_id'];
                $usage_sub_catg_id = $this->request->data['usage_sub_catg_id'];
                if (ctype_digit($usage_main_catg_id) && ctype_digit($usage_sub_catg_id)) {
                    $subsubid = ClassRegistry::init('usage_category')->find('list', array('fields' => array('usage_sub_sub_catg_id'), 'conditions' => array('usage_main_catg_id' => $usage_main_catg_id, 'usage_sub_catg_id' => $usage_sub_catg_id)));
                    $usagesubsubname = ClassRegistry::init('usage_sub_sub_category')->find('list', array('fields' => array('usage_sub_sub_catg_id', 'usage_sub_sub_catg_desc_en'), 'conditions' => array('usage_sub_sub_catg_id' => $subsubid)));
                    return json_encode($usagesubsubname);
                } else {
                    return 'invalid input';
                }
            } else {
                return "provide main and sub category";
            }
        } catch (Exception $e) {
            $this->Session->setFlash('Sorry! Error in getting subsub category');
            $this->redirect(array('action' => 'error404'));
        }
    }

//------------------------Usage Linkage(Main & Sub) with duplication checking----------------------------------------------------------------------------------------
    public function usage_linkage() {
        try {
            $lang = $this->Session->read("sess_langauge");
            $state_id = $this->Auth->User("state_id");
            array_map([$this, 'loadModel'], ['usage_main_category', 'usage_sub_category', 'usagelnk']);
            $usage_main_list = $this->usage_main_category->find('list', array('fields' => array('usage_main_catg_id', 'usage_main_catg_desc_' . $lang), 'conditions' => array('state_id' => $state_id)));
            $usage_sub_list = $this->usage_sub_category->find('list', array('fields' => array('usage_sub_catg_id', 'usage_sub_catg_desc_' . $lang), 'conditions' => array('state_id' => $state_id)));
            $usage_linkage_list = $this->usagelnk->find('all', array('fields' => array('usagelnk.usage_main_catg_id', 'usage_main_catg_desc_' . $lang, 'usagelnk.usage_sub_catg_id', 'sub.usage_sub_catg_desc_' . $lang),
                'joins' => array(
                    array('table' => 'ngdrstab_mst_usage_main_category', 'alias' => 'main', 'conditions' => 'main.usage_main_catg_id=usagelnk.usage_main_catg_id'),
                    array('table' => 'ngdrstab_mst_usage_sub_category', 'alias' => 'sub', 'conditions' => 'sub.usage_sub_catg_id=usagelnk.usage_sub_catg_id')
                ), 'conditions' => array('state_id' => $state_id)
            ));
            $this->set(compact('lang', 'usage_main_list', 'usage_sub_list', 'usage_linkage_list'));
            //--------code after form submission--------------------
            if ($this->request->is('post')) {
                
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(__('Sorry! .... Error :' . $ex->getCode()));
            return $this->redirect('usage_linkage');
        }
    }

//------------------------ remove usage Link-------------------------------------------------------------------------------------------------------------------------
    public function remove_usage_link() {
        $this->autoRender = false;

        try {
            if (isset($this->request->data['usage_link_id']) && is_numeric($this->request->data['usage_link_id'])) {
                $this->loadModel('usagelnk');
                $usage_link_id = $this->request->data['usage_link_id'];
                if ($this->usagelnk->deleteAll(array('usage_cat_id' => $usage_link_id))) {
                    $this->Session->setFlash(__('lbldeletemsg'));
                    return $this->redirect(array('action' => 'usage_items'));
                }
            }
        } catch (exception $ex) {
            
        }
    }

//-------------------------------------------------------------Usage Item (16_June-2017) by Shridhar--------------------------------------------------------------------------------
    public function usage_items_OLD() {//upadted on 31 March 2017 by Shridhar Update on 03-June-2017
        try {
            $this->check_role_escalation(); // for invalid user checking
            //load Models
            array_map([$this, 'loadModel'], ['itemlist', 'unit', 'State', 'User', 'paramtype', 'language', 'mainlanguage', 'UnitCategory', 'NGDRSErrorCode']);
            //Declate & Initialise Variable
            $selectitemlist = $actiontypeval = $hfid = $itemlistrecord = NULL;
            $result_codes = $this->NGDRSErrorCode->find("all");

            //languages are loaded firstly from config (from table)
            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'),
                'joins' => array(
                    array('table' => 'ngdrstab_conf_language', 'alias' => 'conf', 'type' => 'inner', 'foreignKey' => false, 'conditions' => array('conf.language_id = mainlanguage.id'))
                )
                , 'order' => 'conf.language_id ASC'
            ));

            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $lang = $laug = $this->Session->read("sess_langauge");
            $outputitem = $this->itemlist->find('list', array('fields' => array('usage_param_id', 'usage_param_desc_en'), 'conditions' => array('usage_param_type_id' => 2)));
            $UnitCategory = $this->UnitCategory->find("list", array('fields' => array('unit_cat_id', 'unit_cat_desc_' . $laug)));
            $state = $this->State->find('all', array('conditions' => array('state_id' => $stateid)));
            $this->set('paramtype', $this->paramtype->find('list', array('fields' => array('usage_param_type_id', 'usage_param_type_desc_en'), 'conditions' => array('usage_param_type_id' => array(1, 2, 99)), 'order' => array('usage_param_type_desc_en' => 'ASC'))));

            $itemlistrecord = $this->itemlist->find('all', array('fields' => array('itemlist.usage_param_id', 'itemlist.display_order', 'itemlist.unit_cat_id', 'itemlist.output_item_id', 'itemlist.item_rate_flag', 'itemlist.usage_param_type_id', 'itemlist.area_field_flag', 'itemlist.single_unit_flag', 'itemlist.unit_id', 'itemlist.range_field_flag', 'itemlist.is_list_field_flag', 'paramtype.usage_param_type_desc_' . $laug, 'itemlist.usage_param_code', 'itemlist.usage_param_desc_en', 'itemlist.usage_param_desc_ll', 'itemlist.usage_param_desc_ll1', 'itemlist.usage_param_desc_ll2', 'itemlist.usage_param_desc_ll3', 'usage_param_desc_ll4'),
                'joins' => array(
                    array('table' => 'ngdrstab_mst_items_types', 'alias' => 'paramtype', 'conditions' => array('paramtype.usage_param_type_id=itemlist.usage_param_type_id'))
                ),
                'order' => 'paramtype.usage_param_type_id DESC,itemlist.usage_param_id'
            ));

            $this->request->data['itemlist']['req_ip'] = $this->request->clientIp();
            $this->request->data['itemlist']['user_id'] = $user_id;

            $fieldlist = array();
            foreach ($languagelist as $languagecode) {
                if ($languagecode['mainlanguage']['language_code'] == 'en') {
                    //list for english single fields
                    $fieldlist['usage_param_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'is_required,is_alphanumspace,is_maxlength255';
                } else {
                    //list for all unicode fields
                    $fieldlist['usage_param_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'unicode_rule_' . $languagecode['mainlanguage']['language_code'];
                }
            }
            $fieldlist['usage_param_type_id']['select'] = 'is_select_req';
            $fieldlist['unit_cat_id']['select'] = 'is_numeric';
            $fieldlist['display_order']['text'] = 'is_required,is_numeric';
            $fieldlist['range_field_flag']['radio'] = 'is_radioboxstring';
            $fieldlist['is_list_field_flag']['radio'] = 'is_radioboxstring';
//            $fieldlist['is_list_field_flag']['radio'] = 'is_radioboxstring';
            foreach ($fieldlist as $key => $valrule) {
                $errarr[$key . '_error'] = "";
            }
            $areaUnits = $this->unit->find('list', array('fields' => array('unit_id', 'unit_desc_' . $lang), 'conditions' => array('state_id' => $stateid, 'unit_cat_id' => 1), 'order' => 'id'));
            //set variable to ctp
            $this->set(compact('selectitemlist', 'actiontypeval', 'hfid', 'laug', 'result_codes', 'lang', 'languagelist', 'itemlistrecord', 'outputitem', 'UnitCategory', 'fieldlist', 'errarr', 'areaUnits'));
//-----------------------------------------------Code after PosT----------------------------------------
            if ($this->request->is('post')) {
                $this->check_csrf_token($this->request->data['itemlist']['csrftoken']);

                $hfid = base64_decode($_POST['hfid']);

                $formData = $this->request->data['itemlist'];
                $formData['state_id'] = $this->Auth->User("state_id");
                $save_flag = 'Y';

                $formData = $this->istrim($formData);

                $errarr = $this->validatedata($formData, $fieldlist);
                $flag = 0;
                foreach ($errarr as $dd) {
                    if ($dd != "") {
                        $flag = 1;
                    }
                }
                if ($flag == 1) {
                    $this->set("errarr", $errarr);
                } else {
                    if ($hfid) {
                        if (is_numeric($hfid)) {

                            $record_count = $this->itemlist->find('count', array('conditions' => array('usage_param_id' => $hfid)));
                            if ($record_count == 1) {
                                $formData['usage_param_id'] = $hfid;
                                $actionvalue = "lbleditmsg";
                            }
                            unset($record_count);
                        } else {
                            $save_flag = 'I';
                            $hfid = NULL;
                        }
                    } else {
                        $actionvalue = "lblsavemsg";
                        $save_flag = 'Y';
                    }

                    //check for duplication of Item Case Insensitive
                    $record_count = $this->itemlist->find('count', array('conditions' => array('UPPER(usage_param_desc_en)' => strtoupper($formData['usage_param_desc_en']), 'usage_param_type_id' => $formData['usage_param_type_id'])));
                    if (($record_count > 1 && $hfid) || ($record_count > 0 && $hfid == NULL)) {
                        $save_flag = 'A';
                    }
                    unset($record_count);
                    if ($save_flag == 'Y') {
                        //------------------------------------------Add Param Code----------------------------------------------------------------------------------                  
                        if ($this->request->data['itemlist']['usage_param_type_id'] == 1 || $this->request->data['itemlist']['usage_param_type_id'] == 99) {
                            $prmCode = $this->itemlist->find('first', array('fields' => array('MAX(usage_param_code) AS param_code'), 'conditions' => array('usage_param_type_id' => 1)));
                            $prmCode = $prmCode[0]['param_code'];
                            $paramcodeval = ($hfid) ? ($this->itemlist->field('usage_param_code', array('usage_param_id' => $hfid))) : NULL;
                            $paramcodeval = ($paramcodeval) ? $paramcodeval : ((is_numeric($prmCode) || !$prmCode) ? 'AAA' : ++$prmCode);
                            if (!$hfid && ($paramcodeval == 'RRR' || $paramcodeval == 'RRL' || $paramcodeval == 'RRC')) {
                                $paramcodeval++;
                            }
                            $formData['usage_param_code'] = $paramcodeval;
                        }
                        //---------------------------------------------------------------------------------------------------------------                    
                        if ($this->itemlist->save($formData)) {
                            $this->Session->setFlash(__($actionvalue));
                            $this->redirect(array('controller' => 'ValuationRules', 'action' => 'usage_items'));
                        } else {
                            $this->Session->setFlash(__('lblnotsavemsg'));
                        }
                    } else if ($save_flag == 'I') {
                        $this->Session->setFlash('Invalid Data Provided for Updation');
                    } else if ($save_flag == 'A') {
                        $this->Session->setFlash('Record already exists with this name');
                    } else {
                        $this->Session->setFlash('Invalid Input provided');
                    }
                }
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getCode()));
            pr($ex);
            exit;
            $this->redirect(array('controller' => 'ValuationRules', 'action' => 'usage_items'));
        }
    }

    public function usage_items($usage_param_id = NULL) {//upadted on 31 March 2017 by Shridhar Update on 03-June-2017
        try {
            //$this->check_role_escalation(); // for invalid user checking
            //load Models
            array_map([$this, 'loadModel'], ['itemlist', 'unit', 'State', 'User', 'paramtype', 'language', 'mainlanguage', 'UnitCategory', 'NGDRSErrorCode']);
            //Declate & Initialise Variable
            $selectitemlist = $actiontypeval = $hfid = $itemlistrecord = NULL;
            $result_codes = $this->NGDRSErrorCode->find("all");

            //languages are loaded firstly from config (from table)
            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'),
                'joins' => array(
                    array('table' => 'ngdrstab_conf_language', 'alias' => 'conf', 'type' => 'inner', 'foreignKey' => false, 'conditions' => array('conf.language_id = mainlanguage.id'))
                )
                , 'order' => 'conf.language_id ASC'
            ));

            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $lang = $laug = $this->Session->read("sess_langauge");
            $outputitem = $this->itemlist->find('list', array('fields' => array('usage_param_id', 'usage_param_desc_en'), 'conditions' => array('usage_param_type_id' => 2)));
            $UnitCategory = $this->UnitCategory->find("list", array('fields' => array('unit_cat_id', 'unit_cat_desc_' . $laug)));
            $state = $this->State->find('all', array('conditions' => array('state_id' => $stateid)));
            $this->set('paramtype', $this->paramtype->find('list', array('fields' => array('usage_param_type_id', 'usage_param_type_desc_en'), 'conditions' => array('usage_param_type_id' => array(1, 2, 99)), 'order' => array('usage_param_type_desc_en' => 'ASC'))));

            $itemlistrecord = $this->itemlist->find('all', array('fields' => array('itemlist.usage_param_id', 'itemlist.display_order', 'itemlist.unit_cat_id', 'itemlist.output_item_id', 'itemlist.item_rate_flag', 'itemlist.usage_param_type_id', 'itemlist.area_field_flag', 'itemlist.single_unit_flag', 'itemlist.unit_id', 'itemlist.range_field_flag', 'itemlist.is_list_field_flag', 'paramtype.usage_param_type_desc_' . $laug, 'itemlist.usage_param_code', 'itemlist.usage_param_desc_en', 'itemlist.usage_param_desc_ll', 'itemlist.usage_param_desc_ll1', 'itemlist.usage_param_desc_ll2', 'itemlist.usage_param_desc_ll3', 'usage_param_desc_ll4'),
                'joins' => array(
                    array('table' => 'ngdrstab_mst_items_types', 'alias' => 'paramtype', 'conditions' => array('paramtype.usage_param_type_id=itemlist.usage_param_type_id and itemlist.usage_param_type_id=1'))
                ),
                'order' => 'paramtype.usage_param_type_id DESC,itemlist.usage_param_id'
            ));

            $this->request->data['itemlist']['req_ip'] = $this->request->clientIp();
            $this->request->data['itemlist']['user_id'] = $user_id;

            $areaUnits = $this->unit->find('list', array('fields' => array('unit_id', 'unit_desc_' . $lang), 'conditions' => array('state_id' => $stateid, 'unit_cat_id' => 1), 'order' => 'id'));
            $this->set(compact('selectitemlist', 'actiontypeval', 'hfid', 'laug', 'result_codes', 'lang', 'languagelist', 'itemlistrecord', 'outputitem', 'UnitCategory', 'fieldlist', 'errarr', 'areaUnits'));

            $response = $this->itemlist->fieldlist($languagelist);
            // pr($response);exit;
            $this->set("fieldlist", $fieldlist = $response['fieldlist']);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));


//-----------------------------------------------Code after PosT----------------------------------------
            if ($this->request->is('post') || $this->request->is('put')) {
                $this->check_csrf_token($this->request->data['itemlist']['csrftoken']);
                $this->request->data['itemlist']['usage_param_type_id'] = 1;
                $hfid = $this->request->data['itemlist']['hfid'];
                $this->request->data['itemlist']['usage_param_id'] = $hfid;
                $formData = $this->request->data['itemlist'];


                $formData['state_id'] = $this->Auth->User("state_id");
                $save_flag = 'Y';

                $formData = $this->istrim($formData);
                $response = $this->itemlist->fieldlist($languagelist, $formData);
                $verrors = $this->validatedata($response['data'], $response['fieldlist']);
//                pr($verrors);exit;
                if ($this->ValidationError($verrors)) {
                    $formData = $response['data'];
                    $formData = array_merge($formData, $response['vrule']);
                    $duplicate = $this->itemlist->get_duplicate($languagelist);

                    // pr($formData);exit;
                    $checkd = $this->check_duplicate($duplicate, $formData);
                    if ($checkd) {
                        //------------------------------------------Add Param Code----------------------------------------------------------------------------------                  
                        if ($this->request->data['itemlist']['usage_param_type_id'] == 1 || $this->request->data['itemlist']['usage_param_type_id'] == 99) {
                            $prmCode = $this->itemlist->find('first', array('fields' => array('MAX(usage_param_code) AS param_code'), 'conditions' => array('usage_param_type_id' => 1)));
                            $prmCode = $prmCode[0]['param_code'];
                            $paramcodeval = ($hfid) ? ($this->itemlist->field('usage_param_code', array('usage_param_id' => $hfid))) : NULL;
                            $paramcodeval = ($paramcodeval) ? $paramcodeval : ((is_numeric($prmCode) || !$prmCode) ? 'AAA' : ++$prmCode);
                            if (!$hfid && ($paramcodeval == 'RRR' || $paramcodeval == 'RRL' || $paramcodeval == 'RRC')) {
                                $paramcodeval++;
                            }
                            $formData['usage_param_code'] = $paramcodeval;
                        }
                        //---------------------------------------------------------------------------------------------------------------                    
                        if ($this->itemlist->save($formData)) {
                            if (isset($formData['usage_param_id']) && is_numeric($formData['usage_param_id'])) {
                                $this->Session->setFlash(__("lbleditmsg"));
                            } else {
                                $this->Session->setFlash(__("lblsavemsg"));
                            }
                            $this->redirect(array('controller' => 'ValuationRules', 'action' => 'usage_items'));
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


            if (is_numeric($usage_param_id)) {
                $this->set('editflag', 'Y');
                $usageitem = $this->itemlist->find('all', array('fields' => array('itemlist.*'),
                    'joins' => array(
                        array('table' => 'ngdrstab_mst_items_types', 'alias' => 'paramtype', 'conditions' => array('paramtype.usage_param_type_id=itemlist.usage_param_type_id'))
                    ),
                    'conditions' => array('usage_param_id' => $usage_param_id, 'itemlist.usage_param_type_id' => 1),
                    'order' => 'paramtype.usage_param_type_id DESC,itemlist.usage_param_id'
                ));
                if (empty($usageitem)) {
                    $this->Session->setFlash(__('lblnotfoundmsg'));
                    $this->redirect(array('controller' => 'ValuationRules', 'action' => 'usage_items'));
                }
                // pr($usageitem);
                if ($usageitem[0]['itemlist']['is_list_field_flag'] == 'Y') {
                    $usageitem[0]['itemlist']['fieldtype'] = 2;
                } else if ($usageitem[0]['itemlist']['is_string'] == 'Y') {
                    $usageitem[0]['itemlist']['fieldtype'] = 4;
                } else if ($usageitem[0]['itemlist']['area_field_flag'] == 'Y') {
                    $usageitem[0]['itemlist']['fieldtype'] = 1;
                } else {
                    $usageitem[0]['itemlist']['fieldtype'] = 3;
                }



                $this->request->data['itemlist'] = $usageitem[0]['itemlist'];
                $this->request->data['itemlist']['hfid'] = $usageitem[0]['itemlist']['usage_param_id'];
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getCode()));
            // pr($ex);
            // exit;
            $this->redirect(array('controller' => 'ValuationRules', 'action' => 'usage_items'));
        }
    }

    public function usage_items_output($usage_param_id = NULL) {
        try {
            //$this->check_role_escalation(); // for invalid user checking
            //load Models
            array_map([$this, 'loadModel'], ['itemlist', 'unit', 'State', 'User', 'paramtype', 'language', 'mainlanguage', 'UnitCategory', 'NGDRSErrorCode']);
            //Declate & Initialise Variable
            $selectitemlist = $actiontypeval = $hfid = $itemlistrecord = NULL;
            $result_codes = $this->NGDRSErrorCode->find("all");

            //languages are loaded firstly from config (from table)
            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'),
                'joins' => array(
                    array('table' => 'ngdrstab_conf_language', 'alias' => 'conf', 'type' => 'inner', 'foreignKey' => false, 'conditions' => array('conf.language_id = mainlanguage.id'))
                )
                , 'order' => 'conf.language_id ASC'
            ));

            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $lang = $laug = $this->Session->read("sess_langauge");
            $outputitem = $this->itemlist->find('list', array('fields' => array('usage_param_id', 'usage_param_desc_en'), 'conditions' => array('usage_param_type_id' => 2)));
            $UnitCategory = $this->UnitCategory->find("list", array('fields' => array('unit_cat_id', 'unit_cat_desc_' . $laug)));
            $state = $this->State->find('all', array('conditions' => array('state_id' => $stateid)));
            $this->set('paramtype', $this->paramtype->find('list', array('fields' => array('usage_param_type_id', 'usage_param_type_desc_en'), 'conditions' => array('usage_param_type_id' => array(1, 2, 99)), 'order' => array('usage_param_type_desc_en' => 'ASC'))));

            $itemlistrecord = $this->itemlist->find('all', array('fields' => array('itemlist.usage_param_id', 'itemlist.display_order', 'itemlist.unit_cat_id', 'itemlist.output_item_id', 'itemlist.item_rate_flag', 'itemlist.usage_param_type_id', 'itemlist.area_field_flag', 'itemlist.single_unit_flag', 'itemlist.unit_id', 'itemlist.range_field_flag', 'itemlist.is_list_field_flag', 'paramtype.usage_param_type_desc_' . $laug, 'itemlist.usage_param_code', 'itemlist.usage_param_desc_en', 'itemlist.usage_param_desc_ll', 'itemlist.usage_param_desc_ll1', 'itemlist.usage_param_desc_ll2', 'itemlist.usage_param_desc_ll3', 'usage_param_desc_ll4'),
                'joins' => array(
                    array('table' => 'ngdrstab_mst_items_types', 'alias' => 'paramtype', 'conditions' => array('paramtype.usage_param_type_id=itemlist.usage_param_type_id and itemlist.usage_param_type_id=2'))
                ),
                'order' => 'paramtype.usage_param_type_id DESC,itemlist.usage_param_id'
            ));

            $this->request->data['itemlist']['req_ip'] = $this->request->clientIp();
            $this->request->data['itemlist']['user_id'] = $user_id;

            $areaUnits = $this->unit->find('list', array('fields' => array('unit_id', 'unit_desc_' . $lang), 'conditions' => array('state_id' => $stateid, 'unit_cat_id' => 1), 'order' => 'id'));
            $this->set(compact('selectitemlist', 'actiontypeval', 'hfid', 'laug', 'result_codes', 'lang', 'languagelist', 'itemlistrecord', 'outputitem', 'UnitCategory', 'fieldlist', 'errarr', 'areaUnits'));


            $this->set("fieldlist", $fieldlist = $this->itemlist->fieldlist_output($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));


//-----------------------------------------------Code after PosT----------------------------------------
            if ($this->request->is('post') || $this->request->is('put')) {
                $this->check_csrf_token($this->request->data['itemlist']['csrftoken']);
                $this->request->data['itemlist']['usage_param_type_id'] = 2;
                $hfid = $this->request->data['itemlist']['hfid'];
                $this->request->data['itemlist']['usage_param_id'] = $hfid;
                $formData = $this->request->data['itemlist'];


                $formData['state_id'] = $this->Auth->User("state_id");
                $save_flag = 'Y';

                $formData = $this->istrim($formData);

                $fieldlist = $this->itemlist->fieldlist_output($languagelist);
                //pr($fieldlist);
                //  exit;
                $verrors = $this->validatedata($formData, $fieldlist);
                // pr($verrors);exit;
                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->itemlist->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $formData);
                    if ($checkd) {
                        if ($this->itemlist->save($formData)) {
                            //  pr($formData);exit;
                            if (isset($formData['usage_param_id']) && is_numeric($formData['usage_param_id'])) {
                                $this->Session->setFlash(__("lbleditmsg"));
                            } else {
                                $this->Session->setFlash(__("lblsavemsg"));
                            }
                            $this->redirect(array('controller' => 'ValuationRules', 'action' => 'usage_items_output'));
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


            if (is_numeric($usage_param_id)) {
                $this->set('editflag', 'Y');
                $usageitem = $this->itemlist->find('all', array('fields' => array('itemlist.*'),
                    'joins' => array(
                        array('table' => 'ngdrstab_mst_items_types', 'alias' => 'paramtype', 'conditions' => array('paramtype.usage_param_type_id=itemlist.usage_param_type_id'))
                    ),
                    'conditions' => array('usage_param_id' => $usage_param_id, 'itemlist.usage_param_type_id' => 2),
                    'order' => 'paramtype.usage_param_type_id DESC,itemlist.usage_param_id'
                ));
                if (empty($usageitem)) {
                    $this->Session->setFlash(__('lblnotfoundmsg'));
                    $this->redirect(array('controller' => 'ValuationRules', 'action' => 'usage_items_output'));
                }
                // pr($usageitem);
                if ($usageitem[0]['itemlist']['is_list_field_flag'] == 'Y') {
                    $usageitem[0]['itemlist']['fieldtype'] = 2;
                } else if ($usageitem[0]['itemlist']['is_string'] == 'Y') {
                    $usageitem[0]['itemlist']['fieldtype'] = 4;
                } else if ($usageitem[0]['itemlist']['area_field_flag'] == 'Y') {
                    $usageitem[0]['itemlist']['fieldtype'] = 1;
                } else {
                    $usageitem[0]['itemlist']['fieldtype'] = 3;
                }



                $this->request->data['itemlist'] = $usageitem[0]['itemlist'];
                $this->request->data['itemlist']['hfid'] = $usageitem[0]['itemlist']['usage_param_id'];
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getCode()));
            // pr($ex);
            // exit;
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function input_usage_items_remove($usage_param_id = null) {//upadted on 05 June 2017 Shridhar
        $this->autoRender = false;
        $this->loadModel('itemlist');
        $this->loadModel('configlistitems');
        try {
            if (isset($usage_param_id) && is_numeric($usage_param_id)) {
                if ($this->itemlist->deleteAll(array('usage_param_id' => $usage_param_id, 'usage_param_type_id' => 1))) {
                    $this->configlistitems->deleteAll(array('item_id' => $usage_param_id));
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                } else {
                    $this->Session->setFlash(
                            __('lblnotdeletemsg')
                    );
                }
            } else {
                $this->Session->setFlash(
                        __('Invaliad Data')
                );
            }
            return $this->redirect(array('controller' => 'ValuationRules', 'action' => 'usage_items'));
        } catch (exception $ex) {
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

    public function output_usage_items_remove($usage_param_id = null) {//upadted on 05 June 2017 Shridhar
        $this->autoRender = false;
        $this->loadModel('itemlist');
        $this->loadModel('configlistitems');
        try {
            if (isset($usage_param_id) && is_numeric($usage_param_id)) {
                if ($this->itemlist->deleteAll(array('usage_param_id' => $usage_param_id, 'usage_param_type_id' => 2))) {
                    $this->configlistitems->deleteAll(array('item_id' => $usage_param_id));
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                } else {
                    $this->Session->setFlash(
                            __('lblnotdeletemsg')
                    );
                }
            } else {
                $this->Session->setFlash(
                        __('Invaliad Data')
                );
            }
            return $this->redirect(array('controller' => 'ValuationRules', 'action' => 'usage_items_output'));
        } catch (exception $ex) {
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

//-------------------------------------------------------------Remove Usage Item(5_June-2017)-------------------------------------------------------------------------
    public function remove_usage_item($id = null) {//upadted on 05 June 2017 Shridhar
        $this->autoRender = false;
        $this->loadModel('itemlist');
        $this->loadModel('configlistitems');
        $item_id = ($id) ? base64_decode($id) : base64_decode($this->request->data['remove_id']);
        try {
            if (isset($item_id) && is_numeric($item_id)) {
                $this->itemlist->id = $item_id;
                $list_flag = $this->itemlist->field('is_list_field_flag');
                if ($this->itemlist->delete($item_id)) {
                    if ($list_flag === 'Y') {//delete all list item for perticular property Item
                        $this->configlistitems->deleteAll(array('item_id' => $item_id));
                    }
                    return 0;
                }
            } else {
                return 'Sorry! Invalid Data';
            }
        } catch (exception $ex) {
            return 'Sorry! Invalid Data';
        }
    }

//-------------------------------------------------------------Usage_Items List(5_June-2017)---------------------------------------------------------------------------------------------
    public function usage_items_list_old() {
        try {
            $this->check_role_escalation(); // for invalid user checking

            $this->loadModel('itemlist');
            $this->loadModel('configlistitems');
            $this->loadModel('State');
            $this->loadModel('User');
            $this->loadModel('standard_units');
            $this->set('selectunit', NULL);
            $this->set('actiontypeval', NULL);
            $this->set('hfactionval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
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
            $this->set('unitrecord', NULL);
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $date = date('Y/m/d H:i:s');
            $created_date = date('Y/m/d');
            $this->request->data['unit']['req_ip'] = $this->request->clientIp();
            $this->request->data['unit']['user_id'] = $this->Auth->User("user_id");
            // $this->request->data['unit']['created_date'] = $created_date;
            $this->request->data['unit']['state_id'] = $stateid;
            $listoptions = $this->itemlist->find('list', array('fields' => array('usage_param_id', 'usage_param_desc_en'), 'conditions' => array('is_list_field_flag' => 'Y', 'state_id' => $stateid)));
            $this->set('listoptions', $listoptions);
            $state = $this->State->find('all', array('conditions' => array('state_id' => $stateid)));
            $configlistitems = $this->configlistitems->find('all', array('fields' => array('configlistitems.*', 'item.usage_param_desc_' . $laug, 'item.usage_param_code'),
                'joins' => array(
                    array('table' => 'ngdrstab_mst_usage_items_list', 'alias' => 'item', 'conditions' => array('item.usage_param_id=configlistitems.item_id'))
                )
            ));
            $this->set('configlistitems', $configlistitems);
            $fieldlist = array();
            $fieldlist['item_id']['select'] = 'is_select_req';
            $fieldlist['item_desc_id']['text'] = 'is_required,is_integer,is_maxlengthtimeslot5';
            foreach ($languagelist as $languagecode) {
                if ($languagecode['mainlanguage']['language_code'] == 'en') {
                    $fieldlist['item_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'is_required,is_alphaspace,is_maxlength200';
                } else {
                    //list for all unicode fields
                    $fieldlist['item_desc_' . $languagecode['mainlanguage']['language_code']]['text'] = 'unicode_rule_' . $languagecode['mainlanguage']['language_code'];
                }
            }
            $this->set('fieldlist', $fieldlist);
            foreach ($fieldlist as $key => $valrule) {
                $errarr[$key . '_error'] = "";
            }
            $this->set("errarr", $errarr);
            if ($this->request->is('post')) {
                $this->check_csrf_token($this->request->data['configlistitems']['csrftoken']);
                $actiontype = $_POST['actiontype'];
                $hfid = $_POST['hfid'];
                $this->set('hfid', $hfid);
                $hfactionval = $_POST['hfaction'];
                if ($actiontype == '1') {
                    $this->set('actiontypeval', $actiontype);
                    $this->set('hfactionval', $hfactionval);
                    if ($hfactionval == 'S') {
                        if ($this->request->data['hfupdateflag'] == 'Y') {
                            $this->request->data['configlistitems']['id'] = $this->request->data['hfid'];
                            $actionvalue = "lbleditmsg";
                        } else {
                            $actionvalue = "lblsavemsg";
                        }
                        $this->request->data['configlistitems'] = $this->istrim($this->request->data['configlistitems']);
                        $errarr = $this->validatedata($this->request->data['configlistitems'], $fieldlist);
                        $flag = 0;
                        foreach ($errarr as $dd) {
                            if ($dd != "") {
                                $flag = 1;
                            }
                        }
                        if ($flag == 1) {
                            $this->set("errarr", $errarr);
                        } else {
                            $this->request->data['configlistitems']['state_id'] = $stateid;
                            if ($this->configlistitems->save($this->request->data['configlistitems'])) {
                                $this->Session->setFlash(__($actionvalue));
                                $this->redirect(array('controller' => 'ValuationRules', 'action' => 'usage_items_list'));
                            } else {
                                $this->Session->setFlash(__('lblnotsavemsg'));
                            }
                        }
                    }
                    if ($actiontype == 2) {
                        $this->set('hfupdateflag', 'Y');
                    }
                }
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function usage_items_list($id = NULL) {
        try {
            //  $this->check_role_escalation(); // for invalid user checking

            $this->loadModel('itemlist');
            $this->loadModel('configlistitems');
            $this->loadModel('State');
            $this->loadModel('User');
            $this->loadModel('standard_units');
            $this->set('selectunit', NULL);
            $this->set('actiontypeval', NULL);
            $this->set('hfactionval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
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
            $this->set('unitrecord', NULL);
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $date = date('Y/m/d H:i:s');
            $created_date = date('Y/m/d');
            $this->request->data['unit']['req_ip'] = $this->request->clientIp();
            $this->request->data['unit']['user_id'] = $this->Auth->User("user_id");
            // $this->request->data['unit']['created_date'] = $created_date;
            $this->request->data['unit']['state_id'] = $stateid;
            $listoptions = $this->itemlist->find('list', array('fields' => array('usage_param_id', 'usage_param_desc_en'), 'conditions' => array('is_list_field_flag' => 'Y', 'state_id' => $stateid)));
            $this->set('listoptions', $listoptions);
            $state = $this->State->find('all', array('conditions' => array('state_id' => $stateid)));
            $configlistitems = $this->configlistitems->find('all', array('fields' => array('configlistitems.*', 'item.usage_param_desc_' . $laug, 'item.usage_param_code'),
                'joins' => array(
                    array('table' => 'ngdrstab_mst_usage_items_list', 'alias' => 'item', 'conditions' => array('item.usage_param_id=configlistitems.item_id'))
                )
            ));
            $this->set('configlistitems', $configlistitems);

            $this->set("fieldlist", $fieldlist = $this->configlistitems->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if ($this->request->is('post') || $this->request->is('put')) {
                $this->check_csrf_token($this->request->data['configlistitems']['csrftoken']);

                $this->request->data['configlistitems'] = $this->istrim($this->request->data['configlistitems']);
                $verrors = $this->validatedata($this->request->data['configlistitems'], $fieldlist);
                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->configlistitems->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['configlistitems']);
                    if ($checkd) {
                        if ($this->configlistitems->save($this->request->data['configlistitems'])) {
                            $lastid = $this->configlistitems->getLastInsertId();
                            if (is_numeric($lastid)) {
                                $this->Session->setFlash(__("lblsavemsg"));
                            } else {
                                $this->Session->setFlash(__("lbleditmsg"));
                            }

                            $this->redirect(array('controller' => 'ValuationRules', 'action' => 'usage_items_list'));
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

            if (is_numeric($id)) {
                $this->set('editflag', 'Y');
                $editresult = $this->configlistitems->find("first", array('conditions' => array('id' => $id)));
                if (empty($editresult)) {
                    $this->Session->setFlash(
                            __('lblnotsavemsg')
                    );
                    return $this->redirect(array('controller' => 'ValuationRules', 'action' => 'usage_items_list'));
                }
                $this->request->data['configlistitems'] = $editresult['configlistitems'];
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function remove_usage_item_list($id = null) {
        $this->autoRender = false;
        $this->loadModel('configlistitems');

        try {
            if (isset($id) && is_numeric($id)) {
                if ($this->configlistitems->deleteAll(array('id' => $id))) {
                    $this->Session->setFlash(__('lbldeletemsg'));
                } else {
                    $this->Session->setFlash(__('lblnotdeletemsg'));
                }
                $this->redirect(array('controller' => 'ValuationRules', 'action' => 'usage_items_list'));
            }
        } catch (exception $ex) {
            $this->Session->setFlash(__('lblnotdeletemsg'));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

//-----------------------------------Remove Usage_Items List item(5_June-2017)-------------------------------------------------------
    public function remove_usage_list_item($id = null) {
        $this->autoRender = false;
        $list_item = ($id) ? base64_decode($id) : base64_decode($this->request->data['id']);
        $this->loadModel('configlistitems');
        try {
            if (isset($list_item) && is_numeric($list_item)) {
                $this->configlistitems->id = $list_item;
                if ($this->configlistitems->delete($list_item)) {
                    $this->Session->setFlash(__('lbldeletemsg'));
                } else {
                    $this->Session->setFlash(__('lblnotdeletemsg'));
                }
                $this->redirect(array('action' => 'usage_items_list'));
            }
        } catch (exception $ex) {
            $this->Session->setFlash(__('lblnotdeletemsg'));
            $this->redirect(array('action' => 'usage_items_list'));
        }
    }

//-----------------------------------------------Display Menu of Rule----------------------------------------------
    public function rule_functions() {
        try {
            $this->loadModel('rule_functions');
            $result = $this->rule_functions->find("all", array('order' => array('mf_serial ASC')));
            return $result;
        } catch (Exception $ex) {
            $this->Session->setFlash('Sorry! Error Getting Data');
        }
    }

//----------------------------------------------------Display Index of Valuation Rule
    public function index($csrftoken = NULL) {
        try {
            $this->loadModel('evalrule');
            $name = array_keys($this->evalrule->getColumnTypes());
            $lang = $this->Session->read("sess_langauge");
            $ruleList = $this->evalrule->find('all', array('fields' => array('evalrule_id', 'reference_no', 'evalrule_desc_en', 'evalrule_desc_ll', 'additional_rate_flag', 'add_usage_main_catg_id', 'add_usage_sub_catg_id', 'rate_compare_flag', 'cmp_usage_main_catg_id', 'cmp_usage_sub_catg_id', 'tdr_flag', 'display_flag', 'is_urban', 'is_rural', 'is_influence', 'rate_revision_flag'), 'order' => 'created DESC'));
            $this->set(compact('ruleList', 'lang', 'name'));
            $this->Session->write('valuation_rule_id', NULL);
            if ($this->request->is('POST')) {
                $this->Session->write('valuation_rule_id', (isset($this->request->data['val_rule_id'])) ? $this->request->data['val_rule_id'] : NULL);
                $this->redirect(array('controller' => 'ValuationRules', 'action' => 'valuation_rule', $this->Session->read('csrftoken')));
            }
        } catch (Exception $ex) {
            $this->Session->setFlash('Error while Fetcing Rule List');
        }
    }

//---------------------------------//--1--\\---------------------Valuation Rule--------------------------------------------------------------------------------------
    public function valuation_rule($csrftoken = NULL) {
        try {
            //load Models
            array_map([$this, 'loadModel'], ['evalrule', 'usage_sub_sub_category', 'usagelnk']);
            //Set Variables
            $name = array_keys($this->evalrule->getColumnTypes());
            $lang = $this->Session->read("sess_langauge");
            $stateid = $this->Auth->User("state_id");
            $ip_address = $_SERVER['REMOTE_ADDR'];
            $user_id = $this->Auth->User('user_id');
            $hsrflg = $ruleid = $mcatg = $scatg = $sscatg = $scatglist = $sscatglist = $hfaction = NULL;

            $rrrscatglist = $rr1scatglist = $rr5scatglist = $cmpscatglist = NULL;
            $this->set(compact('rrrscatglist', 'rr1scatglist', 'rr5scatglist', 'cmpscatglist'));

            $this->set('finyearList', ClassRegistry::init('finyear')->find('list', array('fields' => array('finyear_id', 'finyear_desc'), 'order' => array('current_year DESC,finyear_id'))));
            $this->set('maincat_id', ClassRegistry::init('usage_main_category')->find('list', array('fields' => array('usage_main_catg_id', 'usage_main_catg_desc_' . $lang), 'conditions' => array('state_id' => $stateid), 'order' => array('usage_main_catg_desc_en' => 'ASC'))));
            $configure = ClassRegistry::init('damblkdpnd')->query("select * from ngdrstab_conf_state_district_div_level where state_id=?", array($stateid));
            $this->set(compact('name', 'configure', 'lang', 'stateid', 'hsrflg', 'ruleid', 'mcatg', 'scatg', 'sscatg', 'scatglist', 'sscatglist', 'hfaction'));
            $rule_id = $this->Session->read('valuation_rule_id');
            $this->evalrule->id = $rule_id;
            //--------------------------------validations kalyani 25/5/2017-------------------------------------------------------------------
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'), 'joins' => array(
                    array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
            ))));
            $this->set('languagelist', $languagelist);

            $this->set("fieldlist", $fieldlist = $this->evalrule->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            //----------------------------------------------- Code After Page Submit ---------------------------------------------------------------------------------------------
            if ($this->request->is('POST')) {
                $this->check_csrf_token($this->request->data['frmevalrule']['csrftoken']);
                $frm = $this->request->data['frmevalrule'];
                $frm['effective_date'] = date('Y-m-d', strtotime($frm['effective_date']));

                // $this->check_csrf_token($this->request->data['frmevalrule']['csrftoken']);
                $this->request->data['frmevalrule'] = $this->istrim($this->request->data['frmevalrule']);
                $fieldlistnew = $this->modifyfieldlist($fieldlist, $this->request->data['frmevalrule']);
                //------------------------------------------ Server side validation-----------------------------------------------------------
                $errarr = $this->validatedata($this->request->data['frmevalrule'], $fieldlistnew);
                if ($this->ValidationError($errarr)) {
                    //check for duplication of Valuation Rule
                    $count = $this->evalrule->find('count', array('conditions' => array('UPPER(evalrule_desc_en)' => strtoupper($frm['evalrule_desc_en']))));
                    if (($this->Session->read('valuation_rule_id') && $count > 1) || (!$this->Session->read('valuation_rule_id') && $count > 0)) {
                        $this->Session->setFlash('Rule Already Exists with this Name');
                    } else {
                        //checking invalid usage ids of main && sub 
                        $main_usage_check = $this->usagelnk->find('count', array('conditions' => array('usage_main_catg_id' => $frm['usage_main_catg_id'], 'usage_sub_catg_id' => $frm['usage_sub_catg_id'])));
                        $cmp_usage_check = (isset($frm['cmp_usage_main_catg_id']) && is_numeric($frm['cmp_usage_main_catg_id'])) ? $this->usagelnk->find('count', array('conditions' => array('usage_main_catg_id' => $frm['cmp_usage_main_catg_id'], 'usage_sub_catg_id' => $frm['cmp_usage_sub_catg_id']))) : 1;
                        $add_usage_check = (isset($frm['add_usage_main_catg_id']) && is_numeric($frm['add_usage_main_catg_id'])) ? $this->usagelnk->find('count', array('conditions' => array('usage_main_catg_id' => $frm['add_usage_main_catg_id'], 'usage_sub_catg_id' => $frm['add_usage_sub_catg_id']))) : 1;
                        $add1_usage_check = (isset($frm['add1_usage_main_catg_id']) && is_numeric($frm['add1_usage_main_catg_id'])) ? $this->usagelnk->find('count', array('conditions' => array('usage_main_catg_id' => $frm['add1_usage_main_catg_id'], 'usage_sub_catg_id' => $frm['add1_usage_sub_catg_id']))) : 1;
                        if ($main_usage_check < 1 && $cmp_usage_check < 1 && $add_usage_check < 1 && $add1_usage_check < 1) {
                            $this->Session->setFlash('Sorry,Invalid Usage Categories, Please Select Proper Data');
                        } else {
                            //  $this->check_csrf_token($frm['csrftoken']); //csrf token checking
                            $sub_sub_catg_id = 0;
                            //Step 1 ------- insert sub sub Category (Rule Details)--------------------
                            $sub_sub_catg_data = array(
                                'usage_sub_sub_catg_desc_en' => $frm['evalrule_desc_en'],
                                'usage_sub_sub_catg_desc_ll' => isset($frm['evalrule_desc_ll']) ? $frm['evalrule_desc_ll'] : NULL,
                                'usage_sub_sub_catg_desc_ll1' => isset($frm['evalrule_desc_ll1']) ? $frm['evalrule_desc_ll1'] : NULL,
                                'usage_sub_sub_catg_desc_ll2' => isset($frm['evalrule_desc_ll2']) ? $frm['evalrule_desc_ll2'] : NULL,
                                'usage_sub_sub_catg_desc_ll3' => isset($frm['evalrule_desc_ll3']) ? $frm['evalrule_desc_ll3'] : NULL,
                                'usage_sub_sub_catg_desc_ll4' => isset($frm['evalrule_desc_ll4']) ? $frm['evalrule_desc_ll4'] : NULL,
//                    'dolr_usage_code' => $frm['dolr_usage_code'],
                                'contsruction_type_flag' => $frm['contsruction_type_flag'],
                                'depreciation_flag' => $frm['depreciation_flag'],
                                'road_vicinity_flag' => $frm['road_vicinity_flag'],
                                'user_defined_dependency1_flag' => $frm['user_defined_dependency1_flag'],
                                'user_defined_dependency2_flag' => $frm['user_defined_dependency2_flag'],
                                'state_id' => $stateid,
                                'user_id' => $user_id,
                                'req_ip' => $ip_address
                            );
                            if ($frm['rule_id']) {
                                $frm['rule_id'] = $this->Session->read('valuation_rule_id');
                                $sub_sub_catg_id = $this->usagelnk->field('usage_sub_sub_catg_id', array('evalrule_id' => $frm['rule_id']));
                                $sub_sub_catg_data['usage_sub_sub_catg_id'] = $sub_sub_catg_id;
                            }

                            if ($this->usage_sub_sub_category->save($sub_sub_catg_data)) {
                                if (!$sub_sub_catg_id) {
                                    $sub_sub_catg_id = $this->usage_sub_sub_category->getLastInsertId();
                                }

//additional1_rate_flag
                                //Step:2 ----------------- Save Rule Data -------------------------------------
                                $rule_data = array(
                                    'evalrule_desc_en' => $frm['evalrule_desc_en'],
                                    'evalrule_desc_ll' => isset($frm['evalrule_desc_ll']) ? $frm['evalrule_desc_ll'] : NULL,
                                    'evalrule_desc_ll1' => isset($frm['evalrule_desc_ll1']) ? $frm['evalrule_desc_ll1'] : NULL,
                                    'evalrule_desc_ll2' => isset($frm['evalrule_desc_ll2']) ? $frm['evalrule_desc_ll2'] : NULL,
                                    'evalrule_desc_ll3' => isset($frm['evalrule_desc_ll3']) ? $frm['evalrule_desc_ll3'] : NULL,
                                    'evalrule_desc_ll4' => isset($frm['evalrule_desc_ll4']) ? $frm['evalrule_desc_ll4'] : NULL,
                                    'reference_no' => $frm['reference_no'],
                                    'finyear_id' => $frm['finyear_id'],
                                    'effective_date' => $frm['effective_date'],
                                    'effective_date' => $frm['effective_date'],
                                    //---------------Addition Rate 1
                                    'additional_rate_flag' => $frm['additional_rate_flag'],
                                    'add_usage_main_catg_id' => ($frm['additional_rate_flag'] == 'Y') ? $frm['add_usage_main_catg_id'] : 0,
                                    'add_usage_sub_catg_id' => ($frm['additional_rate_flag'] == 'Y') ? $frm['add_usage_sub_catg_id'] : 0,
                                    //---------------Additional Rate 2
                                    'additional1_rate_flag' => $frm['additional1_rate_flag'],
                                    'add1_usage_main_catg_id' => ($frm['additional1_rate_flag'] == 'Y') ? $frm['add1_usage_main_catg_id'] : 0,
                                    'add1_usage_sub_catg_id' => ($frm['additional1_rate_flag'] == 'Y') ? $frm['add1_usage_sub_catg_id'] : 0,
                                    'rate_compare_flag' => $frm['rate_compare_flag'],
                                    'cmp_usage_main_catg_id' => ($frm['rate_compare_flag'] == 'Y') ? $frm['cmp_usage_main_catg_id'] : 0,
                                    'cmp_usage_sub_catg_id' => ($frm['rate_compare_flag'] == 'Y') ? $frm['cmp_usage_sub_catg_id'] : 0,
                                    'subrule_flag' => 'Y',
                                    'tdr_flag' => $frm['tdr_flag'],
                                    'is_urban' => $frm['is_urban'],
                                    'is_rural' => $frm['is_rural'],
                                    'is_influence' => $frm['is_influence'],
                                    'skip_val_flag' => $frm['skip_val_flag'],
                                    'tah_process_flag' => $frm['tah_process_flag'],
                                    'state_id' => $stateid,
                                    'user_id' => $user_id,
                                    'req_ip' => $ip_address
                                );
                                if ($frm['rule_id']) {
                                    $rule_data['evalrule_id'] = $frm['rule_id'];
                                }
                                if ($this->evalrule->save($rule_data)) {
                                    $this->Session->setFlash(__('lblsavemsg'));
                                    $sub_sub_catg_data['evalrule_id'] = ($frm['rule_id']) ? $frm['rule_id'] : $this->evalrule->getLastInsertId();
                                    $this->Session->write('valuation_rule_id', $sub_sub_catg_data['evalrule_id']);

                                    /* Step:3 --------------- link Usage Category with Rule Id------------------------------------------------------- */

                                    $usage_link_data = $sub_sub_catg_data;
                                    $usage_link_data['evalrule_id'] = $sub_sub_catg_data['evalrule_id'];
                                    $usage_link_data['usage_main_catg_id'] = $frm['usage_main_catg_id'];
                                    $usage_link_data['usage_sub_catg_id'] = $frm['usage_sub_catg_id'];
                                    $usage_link_data['usage_sub_sub_catg_id'] = $sub_sub_catg_id;
                                    $usage_link_data['usage_cat_desc_en'] = $frm['evalrule_desc_en'];
                                    $usage_link_data['is_boundary_applicable'] = $frm['is_boundary_applicable'];
                                    $usage_link_data['usage_cat_desc_ll'] = isset($frm['evalrule_desc_ll']) ? $frm['evalrule_desc_ll'] : NULL;
                                    $usage_link_data['usage_cat_desc_ll1'] = isset($frm['evalrule_desc_ll1']) ? $frm['evalrule_desc_ll1'] : NULL;
                                    $usage_link_data['usage_cat_desc_ll2'] = isset($frm['evalrule_desc_ll2']) ? $frm['evalrule_desc_ll2'] : NULL;
                                    $usage_link_data['usage_cat_desc_ll3'] = isset($frm['evalrule_desc_ll3']) ? $frm['evalrule_desc_ll3'] : NULL;
                                    $usage_link_data['usage_cat_desc_ll4'] = isset($frm['evalrule_desc_ll4']) ? $frm['evalrule_desc_ll4'] : NULL;

                                    $this->usagelnk->deleteAll(array('evalrule_id' => $usage_link_data['evalrule_id']));
                                    if ($this->usagelnk->save($usage_link_data)) {
                                        $this->Session->setFlash(__('lblsavemsg'));
                                        $this->redirect(array('controller' => 'ValuationRules', 'action' => 'rule_items_linkage', $this->Session->read('csrftoken')));
                                    }//end of usage linkage
                                }//end of rule Save
                            }//End of subcategory Save
                        }//end of invalid Usage_items
                    }//end of duplication
                } else {//�?ंड ऑफ validation
                    $this->Session->setFlash('CHECK PROPER VALIDATION');
                }
            }

            // $this->check_csrf_token($csrftoken);
            $rule_id = $this->Session->read('valuation_rule_id');
            // pr($rule_id_session);exit;
            if (is_numeric($rule_id)) {

                $result_edit = $this->evalrule->find('first', array('conditions' => array('evalrule_id' => $this->Session->read('valuation_rule_id'))));
                if (!empty($result_edit)) {
                    $this->request->data['frmevalrule'] = $result_edit['evalrule'];
                }
                //pr($result_edit);exit;
                $this->request->data['frmevalrule']['usage_main_catg_id'] = $this->usagelnk->field('usage_main_catg_id', array('evalrule_id' => $rule_id));
                $this->request->data['frmevalrule']['us_id'] = $this->usagelnk->field('usage_sub_catg_id', array('evalrule_id' => $rule_id));
                $this->request->data['frmevalrule']['add_us_id'] = $this->evalrule->field('add_usage_sub_catg_id', array('evalrule_id' => $rule_id));
                $this->request->data['frmevalrule']['add1_us_id'] = $this->evalrule->field('add1_usage_sub_catg_id', array('evalrule_id' => $rule_id));
                $this->request->data['frmevalrule']['cmp_us_id'] = $this->evalrule->field('cmp_usage_sub_catg_id', array('evalrule_id' => $rule_id));
                $this->request->data['frmevalrule']['rule_id'] = $this->usagelnk->field('evalrule_id', array('evalrule_id' => $rule_id));
                $this->request->data['frmevalrule']['usage_sub_sub_catg_id'] = $this->usagelnk->field('usage_sub_sub_catg_id', array('evalrule_id' => $rule_id));

                if (is_numeric($this->request->data['frmevalrule']['usage_main_catg_id'])) {
                    $subid = ClassRegistry::init('usage_category')->find('list', array('fields' => array('usage_sub_catg_id'), 'conditions' => array('usage_main_catg_id' => $this->request->data['frmevalrule']['usage_main_catg_id'])));
                    $usagesubname = ClassRegistry::init('usage_sub_category')->find('list', array('fields' => array('usage_sub_catg_id', 'usage_sub_catg_desc_' . $lang), 'conditions' => array('usage_sub_catg_id' => $subid)));
                    $this->set("rrrscatglist", $usagesubname);
                    $this->request->data['frmevalrule']['usage_sub_catg_id'] = $this->usagelnk->field('usage_sub_catg_id', array('evalrule_id' => $rule_id));
                }

                if (is_numeric($this->request->data['frmevalrule']['add_usage_main_catg_id'])) {
                    $subid = ClassRegistry::init('usage_category')->find('list', array('fields' => array('usage_sub_catg_id'), 'conditions' => array('usage_main_catg_id' => $this->request->data['frmevalrule']['add_usage_main_catg_id'])));
                    $usagesubname = ClassRegistry::init('usage_sub_category')->find('list', array('fields' => array('usage_sub_catg_id', 'usage_sub_catg_desc_' . $lang), 'conditions' => array('usage_sub_catg_id' => $subid)));
                    $this->set("rr1scatglist", $usagesubname);
                    //pr($usagesubname);exit;
                }
                if (is_numeric($this->request->data['frmevalrule']['add1_usage_main_catg_id'])) {
                    $subid = ClassRegistry::init('usage_category')->find('list', array('fields' => array('usage_sub_catg_id'), 'conditions' => array('usage_main_catg_id' => $this->request->data['frmevalrule']['add1_usage_main_catg_id'])));
                    $usagesubname = ClassRegistry::init('usage_sub_category')->find('list', array('fields' => array('usage_sub_catg_id', 'usage_sub_catg_desc_' . $lang), 'conditions' => array('usage_sub_catg_id' => $subid)));
                    $this->set("rr5scatglist", $usagesubname);
                }
                if (is_numeric($this->request->data['frmevalrule']['cmp_usage_main_catg_id'])) {
                    $subid = ClassRegistry::init('usage_category')->find('list', array('fields' => array('usage_sub_catg_id'), 'conditions' => array('usage_main_catg_id' => $this->request->data['frmevalrule']['cmp_usage_main_catg_id'])));
                    $usagesubname = ClassRegistry::init('usage_sub_category')->find('list', array('fields' => array('usage_sub_catg_id', 'usage_sub_catg_desc_' . $lang), 'conditions' => array('usage_sub_catg_id' => $subid)));
                    $this->set("cmpscatglist", $usagesubname);
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

//validation function for dependent fields
    public function modifyfieldlist($fieldlist, $data) {

        if (isset($data['additional_rate_flag']) && $data['additional_rate_flag'] == 'N') {
            unset($fieldlist['add_usage_main_catg_id']);
            unset($fieldlist['add_usage_sub_catg_id']);
        }
        if (isset($data['rate_compare_flag']) && $data['rate_compare_flag'] == 'N') {
            unset($fieldlist['cmp_usage_main_catg_id']);
            unset($fieldlist['cmp_usage_sub_catg_id']);
        }

        if (isset($data['additional1_rate_flag']) && $data['additional1_rate_flag'] == 'N') {
            unset($fieldlist['add1_usage_main_catg_id']);
            unset($fieldlist['add1_usage_sub_catg_id']);
        }
        return $fieldlist;
    }

//-------------------------------------------------------Copy All Subrule from one rule to another-----------------------------------------------------------------------------------
    public function copy_rule() {
        try {
            $this->autoRender = FALSE;
            $from_id = $this->request->data['from_id'];
            $to_id = $this->request->data['to_id'];
            if (is_numeric($to_id) && is_numeric($from_id)) {
                $this->loadModel('evalrule');
                if (!$this->evalrule->copy_usage_items($from_id, $to_id)) {
                    if (!$this->evalrule->copy_rule($from_id, $to_id)) {//if update successfully
                        return 1;
                    } else {
                        return -1;
                    }
                } else {
                    return -1;
                }
            } else {
                return -1;
            }
        } catch (Exception $ex) {
            $this->Session->setFlash('Sorry! There is some error in copying Sub Rule');
        }
    }

//-------------------------------------------------------Get Usage Category Flags on Evalrule_id-----------------------------------------------------------------------------------
    public function get_rule_flags() {
        try {
            $this->autoRender = FALSE;
            $rule_id = $this->request->data['rule_id'];
            if (is_numeric($rule_id)) {
                $catg_ids = ClassRegistry::init('usagelnk')->Query("select 
                            lnk.contsruction_type_flag con, lnk.depreciation_flag dep, lnk.road_vicinity_flag rdv, lnk.user_defined_dependency1_flag udd1,lnk.user_defined_dependency2_flag udd2,
                            rule.max_value_condition_flag max, rule.rate_revision_flag rrf, rule.rate_compare_flag cmp, rule.additional_rate_flag add_ ,rule.additional1_rate_flag add_1,
                            rule.tdr_flag tdr, rule.is_urban urban, rule.is_rural rural, rule.is_influence influence,rule.skip_val_flag,lnk.is_boundary_applicable,rule.tah_process_flag,rule.display_flag
                          from ngdrstab_mst_evalrule_new rule
                          join ngdrstab_mst_usage_category lnk on rule.evalrule_id=lnk.evalrule_id
                          where rule.evalrule_id=?", array($rule_id));
                return json_encode($catg_ids[0][0]);
            } else {
                return -1;
            }
        } catch (Exception $ex) {
            $this->Session->setFlash('Sorry! Error in getting usage_Flags');
        }
    }

//-------------------------------------------------------Delete Rule---------------------------------------------------------------------------------------------------------
    public function remove_val_rule() {
        try {
            $this->autoRender = FALSE;
            array_map([$this, 'loadModel'], ['evalrule', 'subrule', 'usagelinkcategory', 'usagelnk', 'usage_sub_sub_category']);
            $hfid = $this->request->data['rule_id'];
            if ($this->evalrule->delete($hfid)) {//delete rule                
                if ($this->subrule->deleteAll(array('evalrule_id' => $hfid), FALSE)) {//delete subrule
                    if ($this->usagelinkcategory->updateAll(array('evalrule_id' => NULL), array('evalrule_id' => $hfid))) {//Set Rule to NULL for perticular linkage
                        $usage_sub_sub_catg_id = $this->usagelnk->field('usage_sub_sub_catg_id', array("evalrule_id" => $hfid)); // get usage_sub_sub_catg_id
                        if ($this->usagelnk->deleteAll(array("evalrule_id" => $hfid))) {//delete usage linkage
                            if ($this->usage_sub_sub_category->deleteAll(array("usage_sub_sub_catg_id" => $usage_sub_sub_catg_id))) {//delete usage linkage  
                                return 0;
                            } else {
                                return 5;
                            }
                        } else {
                            return 4;
                        }
                    } else {
                        return 3;
                    }
                } else {
                    return 2;
                }
            } else {
                return 1;
            }
        } catch (Exception $ex) {
            $this->Session->setFlash('Sorry! Error in deleting Valuation_rule');
        }
    }

    //-----------------------------//--2--\\-------------------------item Linkage to Valuation Rule by Shridhar,Madhuri -------------------------------------------------------
    public function rule_items_linkage($csrftoken = NULL) {
        try {
//load Models
            array_map([$this, 'loadModel'], ['itemlist', 'usagelnk', 'usage_category', 'subrule', 'usagelinkcategory', 'evalrule']);
            $lang = $this->Session->read("sess_langauge");
            $stateid = $this->Auth->User("state_id");
            $req_ip = $_SERVER['REMOTE_ADDR'];
            $ruleid = $this->Session->read('valuation_rule_id');
            if (!$ruleid) {
                $this->Session->setFlash('Please Select or Save Rule.');
                $this->redirect(array('controller' => 'ValuationRules', 'action' => 'valuation_rule', $this->Session->read('csrftoken')));
            }
            $user_id = $this->Auth->User('user_id');

            $this->itemlist->virtualFields = array(
                'usage_param_desc' => 'CONCAT(usage_param_code|| \' : \' || usage_param_desc_' . $lang . ')'
            );
            $linkedInputs = $this->usagelinkcategory->find('list', array('fields' => array('usage_param_id'), 'conditions' => array('evalrule_id' => $ruleid)));
            $inputitemlist = $this->itemlist->find('list', array('fields' => array('usage_param_id', 'usage_param_desc'), 'conditions' => array('usage_param_type_id' => 1, 'usage_param_id !=' => $linkedInputs), 'order' => array('usage_param_id' => 'ASC')));
            $outputitemlist = $this->itemlist->find('list', array('fields' => array('usage_param_id', 'usage_param_desc_' . $lang), 'conditions' => array('usage_param_type_id' => 2), 'order' => array('usage_param_id' => 'ASC')));
//            $linkedInputs = $this->usagelinkcategory->find('all', array('fields' => array('usagelinkcategory.usage_lnk_id', 'item.usage_param_code', 'item.usage_param_desc_' . $lang), 'conditions' => array('item.usage_param_id' => $linkedInputs, 'evalrule_id' => $ruleid),
//                'joins' => array(
//                    array('table' => 'ngdrstab_mst_usage_items_list', 'alias' => 'item', 'conditions' => array('item.usage_param_id=usagelinkcategory.usage_param_id'))
//            )));
            $rulename = $this->evalrule->field('evalrule_desc_' . $lang, array('evalrule_id' => $ruleid));
            $this->set(compact('fieldname', 'ruleid', 'rulename', 'lang', 'inputitemlist', 'outputitemlist', 'linkedInputs'));
            $this->set(compact('fieldname', 'ruleid', 'rulename', 'lang', 'inputitemlist', 'outputitemlist'));
            if ($this->request->is('POST')) {
                $this->check_csrf_token($this->request->data['frm']['csrftoken']);
                $result = $this->usage_category->find('all', array('fields' => array('usage_main_catg_id', 'usage_sub_catg_id', 'usage_sub_sub_catg_id'), 'conditions' => array('evalrule_id' => $ruleid)));
                $input = $this->request->data['frm']['usage_param_id'];
                //-------------Check If Already Items Linked----------------------------------------------------
                $item_link_count = $this->usagelinkcategory->find('count', array('conditions' => array('evalrule_id' => $ruleid)));

                if ($item_link_count || $input) {

                    //------------------------For Item Linkage to Rule -----------------------------------------------------------------------------
                    $output = $this->request->data['frm']['out_put_id'];
                    if ($input) {//changed on 03-April-2017 by Shrihdar
                        $listarray = $this->usage_category->get_count($this->request->data);
                        $conditions = $this->usage_category->get_condition($this->request->data);
                        $linkage_input_data = array();
                        foreach ($input as $input_item) {
                            $code = $this->itemlist->find('first', array('fields' => array('usage_param_code'), 'conditions' => array('usage_param_id' => $input_item)));
                            $data1 = array(
                                'usage_main_catg_id' => $result[0]['usage_category']['usage_main_catg_id'],
                                'usage_sub_catg_id' => $result[0]['usage_category']['usage_sub_catg_id'],
                                'usage_sub_sub_catg_id' => $result[0]['usage_category']['usage_sub_sub_catg_id'],
                                'usage_param_id' => $input_item,
                                'uasge_param_code' => $code['itemlist']['usage_param_code'],
                                'evalrule_id' => $ruleid,
                                'state_id' => $stateid,
                                'user_id' => $user_id,
                                'req_ip' => $req_ip
                            );
                            array_push($linkage_input_data, $data1);
                        }
                        $this->usagelinkcategory->saveAll($linkage_input_data);
                    }
                    //----*--*--*--*--*-----------Creating Subrule with Conditions (only when no subrule already exists)------------------------

                    if ($item_link_count < 1) {
                        $count = 1;
                        foreach ($listarray as $k => $v) {
                            $count = $count * $v;
                        }
                        $rec1 = $this->subrule->find('all', array('conditions' => array('evalrule_id' => $ruleid)));
                        if (count($rec1) > 0) {
                            $this->subrule->query('delete from ngdrstab_mst_evalsubrule where evalrule_id=?', array($ruleid));
                        }
                        if ($output) {
                            for ($i = 0; $i < count($output); $i++) {


                                $cond_flag = $this->itemlist->find('first', array('fields' => array('list_condition_flag'), 'conditions' => array('usage_param_id' => $output[$i])));
                                if ($cond_flag['itemlist']['list_condition_flag'] == 'Y') {
                                    for ($j = 0; $j < count($conditions); $j++) {

                                        $data = array(
                                            'output_item_id' => $output[$i],
                                            'evalsubrule_cond1' => $conditions[$j],
                                            // changed on 29-March-2017
                                            'evalsubrule_formula1' => 0, // changed on 29-March-2017
//                                    'evalsubrule_cond2' => $conditions[$j],
//                                    'evalsubrule_cond3' => $conditions[$j],
//                                    'evalsubrule_cond4' => $conditions[$j],
//                                    'evalsubrule_cond5' => $conditions[$j],
                                            'evalrule_id' => $ruleid,
                                            'state_id' => $stateid,
                                            'user_id' => $user_id,
                                            'req_ip' => $req_ip
                                        );

                                        $this->subrule->create(false);
                                        $this->subrule->save($data);
                                    }
                                } else {
                                    $data = array(
                                        'output_item_id' => $output[$i],
                                        'evalrule_id' => $ruleid,
                                        'state_id' => $stateid,
                                        'user_id' => $user_id,
                                        'req_ip' => $req_ip
                                    );

                                    $this->subrule->create(false);
                                    $this->subrule->save($data);
                                }
                            }
                        }
                    }
                    //---End of -*--*--*--*--*-----------Creating Subrule with Conditions (only when no subrule already exists)------------------------
                    $this->Session->setFlash(__('lblsavemsg'));
                    $this->redirect(array('controller' => 'ValuationRules', 'action' => 'linked_items_config', $this->Session->read('csrftoken')));
                } else {
                    $this->Session->setFlash('Please Select Input Item');
                }
            } else {
                $this->check_csrf_token($csrftoken);
            }
        } catch (Exception $ex) {
            //pr($ex);exit;
            $this->Session->setFlash('Sorry! error while Item Linkage with Rule');
        }
    }

    //-------------------------------------------------Remove Item From Valuation Rule---------------------------------------------------------------------------
    public function remove_rule_item() {
        try {
            $this->autoRender = FALSE;
            $this->loadModel('evalrule_item_linkage');
            $item_link_id = $this->request->data['item_id'];
            if (is_numeric($item_link_id) && ctype_digit($item_link_id)) {
                $this->evalrule_item_linkage->id = $item_link_id;
                if ($this->evalrule_item_linkage->delete()) {
                    return 1;
                } else {
                    return -1;
                }
            } else {
                return 'wrong Input';
            }
        } catch (Exception $ex) {
            $this->Session->setFlash('Sorry! Error in Removing Item');
        }
    }

//-------------------------------------------------------Valuation Sub Rule (Updated on 7-7-17)---------------------------------------------------------------------------------------------------------
    public function linked_items_config($csrftoken = NULL) {
        try {
            //load Models
            array_map([$this, 'loadModel'], ['itemlist', 'usagelnk', 'usage_category', 'usagelinkcategory', 'evalrule']);
            $lang = $this->Session->read("sess_langauge");
            $stateid = $this->Auth->User("state_id");
            $req_ip = $_SERVER['REMOTE_ADDR'];
            //checking Selection of rule Id
            $ruleid = $this->Session->read('valuation_rule_id');
            if (!$ruleid) {
                $this->Session->setFlash('Please Select or Save Rule.');
                $this->redirect(array('controller' => 'ValuationRules', 'action' => 'valuation_rule', $this->Session->read('csrftoken')));
            }

            $user_id = $this->Auth->User('user_id');

            $this->itemlist->virtualFields = array(
                'usage_param_desc' => 'CONCAT(usage_param_code|| \' : \' || usage_param_desc_' . $lang . ')'
            );

            $linkedInputsList = $this->usagelinkcategory->find('list', array('fields' => array('usage_param_id'), 'conditions' => array('evalrule_id' => $ruleid)));
            if (!$linkedInputsList) {
                $this->Session->setFlash('Please Add at least One Input Items..');
                $this->redirect(array('controller' => 'ValuationRules', 'action' => 'rule_items_linkage', $this->Session->read('csrftoken')));
            }
            $linkedInputs = $this->usagelinkcategory->find('all', array('fields' => array('usagelinkcategory.usage_lnk_id', 'usagelinkcategory.display_order', 'usagelinkcategory.mandate_flag', 'item.usage_param_code', 'item.usage_param_desc_' . $lang), 'conditions' => array('item.usage_param_id' => $linkedInputsList, 'evalrule_id' => $ruleid),
                'joins' => array(
                    array('table' => 'ngdrstab_mst_usage_items_list', 'alias' => 'item', 'conditions' => array('item.usage_param_id=usagelinkcategory.usage_param_id'))
                ), 'order' => array('usagelinkcategory.display_order', 'item.display_order')
            ));

            $rulename = $this->evalrule->field('evalrule_desc_' . $lang, array('evalrule_id' => $ruleid));

            $this->set(compact('fieldname', 'ruleid', 'rulename', 'lang', 'linkedInputs'));

            if ($this->request->is('POST')) {
                $this->check_csrf_token($this->request->data['frm']['csrftoken']);
                unset($this->request->data['frm']);
                $formData = $this->request->data;
                $item_link_ids = ($this->request->data) ? array_keys($this->request->data) : NULL;
                if (!$item_link_ids) {
                    $this->Session->setFlash('Please Add at least One Input Items..');
                    $this->redirect(array('controller' => 'ValuationRules', 'action' => 'rule_items_linkage', $this->Session->read('csrftoken')));
                }
                //-------------Check If Already Items Linked----------------------------------------------------
                $linkage_input_data = array();
                foreach ($item_link_ids as $item_link_id) {
                    $data1 = array(
                        'display_order' => $formData[$item_link_id]['display_order'],
                        'mandate_flag' => "'" . $formData[$item_link_id]['mandate_flag'] . "'",
                        'state_id' => $stateid,
                        'user_id' => $user_id,
                        'req_ip' => "'" . $_SERVER['REMOTE_ADDR'] . "'"
                    );
                    $this->usagelinkcategory->create();
                    $this->usagelinkcategory->updateAll($data1, array('usage_lnk_id' => $item_link_id));
                }


                $this->Session->setFlash(__('lblsavemsg'));
                $this->redirect(array('controller' => 'ValuationRules', 'action' => 'valuation_sub_rule', $this->Session->read('csrftoken')));
            } else {
                $this->check_csrf_token($csrftoken);
            }
        } catch (Exception $ex) {
            pr($ex);
            exit;
        }
    }

    public function valuation_sub_rule($csrftoken = NULL) {
        try {
            array_map([$this, 'loadModel'], ['evalrule', 'subrule', 'usagelnk', 'usagelinkcategory', 'itemlist', 'NGDRSErrorCode', 'configlistitems']);
            $stateid = $this->Auth->User("state_id");
            $ip_address = $_SERVER['REMOTE_ADDR'];
            $user_id = $this->Auth->User('user_id');
            $name = array_keys($this->subrule->getColumnTypes());
            $lang = $this->Session->read("sess_langauge");
            $this->set('lang', $lang);
            $ruleid = $this->Session->read('valuation_rule_id');
            if (!$ruleid) {
                $this->Session->setFlash('Please Select or Save Rule.');
                $this->redirect(array('controller' => 'ValuationRules', 'action' => 'valuation_rule', $this->Session->read('csrftoken')));
            }
            $this->Session->write('valuation_rule_id', $ruleid);
            $rulename = $this->evalrule->field('evalrule_desc_' . $lang, array('evalrule_id' => $ruleid));
            $usagelnk = $this->usagelnk->find('first', array('fields' => array('road_vicinity_flag', 'user_defined_dependency1_flag', 'user_defined_dependency2_flag'), 'conditions' => array('evalrule_id' => $ruleid)));

            if(empty($usagelnk)){
               $this->Session->setFlash('Please Select or Save Rule.');
               $this->redirect(array('controller' => 'ValuationRules', 'action' => 'valuation_rule', $this->Session->read('csrftoken'))); 
            }
            $usage_dependancy = $usagelnk['usagelnk'];
            $this->set(compact('name', 'lang', 'ruleid', 'rulename', 'usage_dependancy'));
            $itemlist = $this->usagelinkcategory->find('list', array('fields' => array('usage_param_id'), 'conditions' => array('evalrule_id' => $ruleid)));
            $this->set('operators', ClassRegistry::init('operators')->find('list', array('fields' => array('operatorsign', 'optrname'), 'order' => array('operator_name_en' => 'ASC'))));
            $this->set('outitemlist', $this->itemlist->find('list', array('fields' => array('usage_param_id', 'usage_param_desc_' . $lang), 'conditions' => array('state_id' => $stateid, 'usage_param_type_id' => 2), 'order' => array('usage_param_desc_en' => 'ASC'))));

            $inputlists = $this->itemlist->find('all', array('fields' => array('usage_param_code', 'usage_param_id', 'usage_param_desc_' . $lang, 'is_list_field_flag'), 'conditions' => array('state_id' => $stateid, 'or' => array('usage_param_id' => $itemlist, 'usage_param_type_id' => 99)), 'order' => array('usage_param_desc_en' => 'ASC')));
            $inputlistoptions = $this->itemlist->find('all', array('fields' => array('usage_param_code', 'usage_param_desc_' . $lang), 'conditions' => array('state_id' => $stateid, 'or' => array('usage_param_id' => $itemlist, 'usage_param_type_id' => 99)), 'order' => array('usage_param_desc_en' => 'ASC')));
            $this->set('inputlistoptions', $inputlistoptions);
            foreach ($inputlists as $key => $inputlist) {
                if ($inputlist['itemlist']['is_list_field_flag'] == 'Y') {
                    $options = $this->configlistitems->find('list', array('fields' => array('item_desc_id', 'item_desc_' . $lang), 'conditions' => array('item_id' => $inputlist['itemlist']['usage_param_id'])));
                    if (empty($options)) {
                        $inputlists[$key]['itemlist']['options'] = array('-' => 'No list Added');
                    } else {
                        $inputlists[$key]['itemlist']['options'] = $options;
                    }
                }
            }
            $this->set('inputlists', $inputlists);
            //pr($inputlists);exit;

            $this->set('maxvalueparameterslist', ClassRegistry::init('itemlist')->find('list', array('fields' => array('usage_param_code', 'usage_param_desc_' . $lang), 'conditions' => array('state_id' => $stateid, 'usage_param_type_id' => array(4, 99, 1)), 'order' => array('usage_param_desc_en' => 'ASC'))));
            $this->set('roadvicinitylist', ClassRegistry::init('roadvicinity')->find('list', array('fields' => array('id', 'road_vicinity_desc_' . $lang), 'order' => array('road_vicinity_desc_en' => 'ASC'))));
            $this->set('userdd1list', ClassRegistry::init('user_defined_dependancy1')->find('list', array('fields' => array('id', 'user_defined_dependency1_desc_' . $lang), 'order' => array('user_defined_dependency1_desc_en' => 'ASC'))));
            $this->set('userdd2list', ClassRegistry::init('user_defined_dependancy2')->find('list', array('fields' => array('id', 'user_defined_dependency2_desc_' . $lang), 'order' => array('user_defined_dependency2_desc_en' => 'ASC'))));
            $this->set('subrule_list', $this->subrule->Query("select evalrule_id, subrule_id,rv.road_vicinity_desc_en, evalsubrule_cond1, evalsubrule_formula1, evalsubrule_cond2, evalsubrule_formula2,evalsubrule_cond3,evalsubrule_formula3,evalsubrule_cond4,evalsubrule_formula4,evalsubrule_cond5,evalsubrule_formula5,
                rate_revision_flag,rate_revision_formula1,rate_revision_formula2,rate_revision_formula3,rate_revision_formula4,rate_revision_formula5, max_value_condition_flag, max_value_formula,alternate_formula,
                sbr.output_item_id,iL.usage_param_desc_en,iL.usage_param_desc_ll,sbr.out_item_order,sbr.road_vicinity_id,sbr.user_defined_dependency1_id,sbr.user_defined_dependency2_id
                        from ngdrstab_mst_evalsubrule sbr
                        left outer join ngdrstab_mst_usage_items_list iL on iL.usage_param_id=sbr.output_item_id
                        left outer join ngdrstab_mst_road_vicinity rv on rv.road_vicinity_id=sbr.road_vicinity_id
                        where evalrule_id=? order by subrule_id,out_item_order", array($ruleid)));
            $rule['evalrule_id'] = $ruleid;
            $this->set('fieldlist', $fieldlist = $this->subrule->fieldlist($rule));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));




            //code after Submitting Form
            if ($this->request->is('POST')) {
                $saveAction = 'lblsavemsg';
                $saveFlag = 'Y';
                $frmdata = $this->request->data['frmValSubRule'];
                $this->check_csrf_token($frmdata['csrftoken']);
                $this->request->data['frmValSubRule'] = $this->istrim($this->request->data['frmValSubRule']);
                $fieldlistnew = $this->modifyfieldlistdependent($fieldlist, $this->request->data['frmValSubRule']);
                $errarr = $this->validatedata($this->request->data['frmValSubRule'], $fieldlistnew);
                if ($this->ValidationError($errarr)) {
                    $frmdata['evalrule_id'] = $ruleid;
                    if ($frmdata['subruleid']) {
                        if (is_numeric($frmdata['subruleid']) && $this->subrule->find('count', array('conditions' => array('subrule_id' => $frmdata['subruleid']))) == 1) {
                            $frmdata['subrule_id'] = $frmdata['subruleid'];
                            $saveAction = 'with Id ' . $frmdata['subrule_id'] . ' Updated';
                        } else {
                            $saveFlag = 'I';
                        }
                    }
                    if ($frmdata['rate_revision_flag'] == 'N') {
                        $frmdata['rate_revision_formula1'] = $frmdata['rate_revision_formula2'] = $frmdata['rate_revision_formula3'] = $frmdata['rate_revision_formula4'] = $frmdata['rate_revision_formula5'] = NULL;
                    }

                    //------for replacing && and or with preceding and ending space for conditions---- 30 March 2017 -----------------               
                    for ($i = 3; $i < 37; $i++) {
                        $frmdata[$name[$i]] = preg_replace('/\s+/', '', $frmdata[$name[$i]]); //remove spaces
                        $frmdata[$name[$i]] = str_replace('&&', ' && ', $frmdata[$name[$i]]);
                        $frmdata[$name[$i]] = str_replace('||', ' || ', $frmdata[$name[$i]]);
                        if ($i == 6) {
                            $i = 25;
                        }
                    }
                    /* --------------------------------------Remove comment while Changing .=>[DOT]------------------------------  
                      $frmdata['evalsubrule_cond1'] = $this->replace_oprator_to_string($frmdata['evalsubrule_cond1']);
                      $frmdata['evalsubrule_cond2'] = $this->replace_oprator_to_string($frmdata['evalsubrule_cond2']);
                      $frmdata['evalsubrule_cond3'] = $this->replace_oprator_to_string($frmdata['evalsubrule_cond3']);
                      $frmdata['evalsubrule_cond4'] = $this->replace_oprator_to_string($frmdata['evalsubrule_cond4']);
                      $frmdata['evalsubrule_cond5'] = $this->replace_oprator_to_string($frmdata['evalsubrule_cond5']);

                      $frmdata['evalsubrule_formula1'] = $this->replace_oprator_to_string($frmdata['evalsubrule_formula1']);
                      $frmdata['evalsubrule_formula2'] = $this->replace_oprator_to_string($frmdata['evalsubrule_formula2']);
                      $frmdata['evalsubrule_formula3'] = $this->replace_oprator_to_string($frmdata['evalsubrule_formula3']);
                      $frmdata['evalsubrule_formula4'] = $this->replace_oprator_to_string($frmdata['evalsubrule_formula4']);
                      $frmdata['evalsubrule_formula5'] = $this->replace_oprator_to_string($frmdata['evalsubrule_formula5']);

                      $frmdata['max_value_formula'] = $this->replace_oprator_to_string($frmdata['max_value_formula']);

                      $frmdata['rate_revision_formula1'] = $this->replace_oprator_to_string($frmdata['rate_revision_formula1']);
                      $frmdata['rate_revision_formula2'] = $this->replace_oprator_to_string($frmdata['rate_revision_formula2']);
                      $frmdata['rate_revision_formula3'] = $this->replace_oprator_to_string($frmdata['rate_revision_formula3']);
                      $frmdata['rate_revision_formula4'] = $this->replace_oprator_to_string($frmdata['rate_revision_formula4']);
                      $frmdata['rate_revision_formula5'] = $this->replace_oprator_to_string($frmdata['rate_revision_formula5']);
                      ----------------------------------------------------------------------------------------------------------- */
                    //------------------------------------Triming All Values- Date 30 March 2017------------------------------------------------------

                    $frmdata['road_vicinity_id'] = (isset($frmdata['road_vicinity_id']) && $frmdata['road_vicinity_id']) ? $frmdata['road_vicinity_id'] : 0;
                    $frmdata['user_defined_dependency1_id'] = (isset($frmdata['user_defined_dependency1_id']) && $frmdata['user_defined_dependency1_id']) ? $frmdata['user_defined_dependency1_id'] : 0;
                    $frmdata['user_defined_dependency2_id'] = (isset($frmdata['user_defined_dependency2_id']) && $frmdata['user_defined_dependency2_id']) ? $frmdata['user_defined_dependency2_id'] : 0;
                    $frmdata = array_map('trim', $frmdata);
                    //check for duplication
                    $recordCount = $this->subrule->find('count', array('conditions' => array('evalrule_id' => $ruleid, 'output_item_id' => $frmdata['output_item_id'], 'evalsubrule_cond1' => $frmdata['evalsubrule_cond1'], 'evalsubrule_formula1' => $frmdata['evalsubrule_formula1'])));
                    if ((isset($frmdata['subrule_id']) && $recordCount <= 1) || (!isset($frmdata['subrule_id']) && $recordCount == 0)) {
                        //--------------------------------------------------------------------------------------------------------------
                        if ($saveFlag == 'Y') {
                            if ($this->subrule->save($frmdata)) {
                                $this->Session->setFlash(__($saveAction));
                                $this->redirect(array('controller' => 'ValuationRules', 'action' => 'valuation_sub_rule', $this->Session->read('csrftoken')));
                            } else {
                                $this->Session->setFlash('Sorry! There is Some Error');
                            }
                        } else {
                            $this->Session->setFlash('Invalid Data Provided for Updation');
                        }
                    } else {
                        $this->Session->setFlash('Record Already Exists with Condition,Formula,and Output Ids');
                    }
                }
            } else {
                $this->check_csrf_token($csrftoken);
            }
        } catch (Exception $ex) {
            $this->Session->setFlash('Sorry! Error in Fetching Valuation Sub Rule Data');
        }
    }

    public function modifyfieldlistdependent($fieldlist, $data) {
//pr($data);
        //   pr($fieldlist);
        if (isset($data['max_value_condition_flag']) && $data['max_value_condition_flag'] == 'N') {
            unset($fieldlist['maxvalueparameterlist']);
            unset($fieldlist['operatorsignmax']);
            unset($fieldlist['max_value_formula']);
        }
        if (isset($data['rate_revision_flag']) && $data['rate_revision_flag'] == 'N') {
            unset($fieldlist['rate_revision_formula1']);
            unset($fieldlist['rate_revision_formula2']);
            unset($fieldlist['rate_revision_formula3']);
            unset($fieldlist['rate_revision_formula4']);
            unset($fieldlist['rate_revision_formula5']);
        }
        // pr($fieldlist);
        //  exit;
        return $fieldlist;
    }

//-------------------------------------------------------copy Sub Rule----------------------------------------------------------------------------------------
    public function copy_subrule() {
        try {
            $this->autoRender = FALSE;
            $from_id = $this->request->data['from_id'];
            $to_id = $this->request->data['to_id'];
            $state_id = $this->Auth->User("state_id");
            $created_date = date('Y-m-d H:i:s');
            $req_ip = $_SERVER['REMOTE_ADDR'];
            $user_id = $this->Auth->User('user_id');
            if (is_numeric($to_id) && is_numeric($from_id)) {
                $this->loadModel('subrule');
                if (!$this->subrule->copy_subrule($from_id, $to_id, $state_id, $req_ip, $created_date, $user_id)) {//if update successfully
                    return 1;
                } else {
                    return -1;
                }
            } else {
                return -1;
            }
        } catch (Exception $ex) {
            $this->Session->setFlash('Sorry! Error in Copying Valuation Sub Rule');
        }
    }

//-------------------------------------------------------Delete Sub Rule----------------------------------------------------------------------------------------
    public function remove_valuation_subrule() {
        try {
            $this->autoRender = FALSE;
            array_map([$this, 'loadModel'], ['subrule']);
            $rule_id = $this->request->data['rule_id'];
            $sub_rule_id = $this->request->data['sub_rule_id'];
            if (is_numeric($rule_id) && is_numeric($sub_rule_id)) {
                if ($this->subrule->find('count', array('conditions' => array('evalrule_id' => $rule_id, 'subrule_id' => $sub_rule_id))) == 1) {
                    if ($this->subrule->delete($sub_rule_id)) {//delete rule                
                        return 0;
                    } else {
                        return -1;
                    }
                } else {
                    return -1;
                }
            } else {
                return -1;
            }
        } catch (Exception $ex) {
            $this->Session->setFlash('Sorry! Error in deleting Valuation Sub Rule');
        }
    }

//-----------------------------------------Check Rule Already Exists-----------------------------------------------------------
    function check_rule_already_present() {
        try {
            $this->autoRender = FALSE;
            array_map([$this, 'loadModel'], ['subrule']);
            $record = $this->subrule->find('all', array('conditions' => array('evalrule_id' => $this->Session->read('valuation_rule_id'))));
            return count($record);
        } catch (Exception $ex) {
            $this->Session->setFlash('Sorry! Error in Fetching Data');
        }
    }

    //-------------------------------------Replacing Operator with String --------------------------------------------
    function replace_oprator_to_string($originalString) {
        try {
            $find_what = array('.', '+', '-', '*', '/', '&&', '||', '==', '!=', '<', '<=', '>', '>=', '=');
            $replace_with = array('[DOT]', '[PLUS]', '[MINUS]', '[MULTIPLY]', '[DIVIDE]', '[AND]', '[OR]', '[EQUAL_TO]', '[NOT_EQUAL_TO]', '[LESS_THAN]', '[LESS_THAN_EQUAL]', '[GREATER_THAN]', '[GREATER_THAN_EQUAL]', '[EQUAL]');
            return str_replace($find_what, $replace_with, $originalString);
        } catch (Exception $ex) {
            
        }
    }

    //CONFIG KALYANI
    public function constructiontype($construction_type_id = NULL) {
        try {
            //  $this->check_role_escalation_tab();
            $this->loadModel('constructiontype');
            $this->loadModel('mainlanguage');
            $this->loadModel('language');

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
            $constructiontype = $this->constructiontype->find('list', array('fields' => array('constructiontype.construction_type_id', 'constructiontype.construction_type_desc_' . $laug), 'order' => array('construction_type_desc_en' => 'ASC')));
            $this->set('constructiontype', $constructiontype);
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

            $landtdata = $this->constructiontype->query("select * from ngdrstab_mst_construction_type");
            $this->set('landtdata', $landtdata);


            $this->set("fieldlist", $fieldlist = $this->constructiontype->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));


            if ($this->request->is('post') || $this->request->is('put')) {

                $this->request->data['constructiontype']['ip_address'] = $this->request->clientIp();
                $this->request->data['constructiontype']['created_date'] = $created_date;
                $this->request->data['constructiontype']['user_id'] = $user_id;
                $verrors = $this->validatedata($this->request->data['constructiontype'], $fieldlist);
                // pr($verrors);

                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->constructiontype->get_duplicate($languagelist);
                    //    pr($duplicate);
                    //  pr($this->request->data['constructiontype']);
                    //  exit;
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['constructiontype']);
                    // pr($checkd);
                    //  exit;
                    if ($checkd) {
                        if ($this->constructiontype->save($this->request->data['constructiontype'])) {
                           $lastid = $this->constructiontype->getLastInsertId();
                            if (is_numeric($lastid)) {
                                $this->Session->setFlash(__('lblsavemsg'));
                            } else {
                                $this->Session->setFlash(__('lbleditmsg'));
                            }
                            return $this->redirect(array('controller' => 'ValuationRules', 'action' => 'constructiontype'));
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
            if (!is_null($construction_type_id) && is_numeric($construction_type_id)) {

                $this->Session->write('construction_type_id', $construction_type_id);
                $result = $this->constructiontype->find("first", array('conditions' => array('construction_type_id' => $construction_type_id)));
                if (!empty($result)) {
                    $this->set('editflag', 'Y');
                    $this->request->data['constructiontype'] = $result['constructiontype'];
                } else {
                    $this->Session->setFlash(
                            __('lblnotfoundmsg')
                    );
                }
            }
        } catch (exception $ex) {

            //  pr($ex);
            //  exit;
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage()));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function delete_constructiontype($construction_type_id = null) {
        // pr($id);exit;
        $this->autoRender = false;
        $this->loadModel('constructiontype');
        try {

            if (isset($construction_type_id) && is_numeric($construction_type_id)) {
                //  if ($type = 'subdivision') {
                $this->constructiontype->construction_type_id = $construction_type_id;
                if ($this->constructiontype->delete($construction_type_id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('controller' => 'ValuationRules', 'action' => 'constructiontype'));
                }
                // }
            }
        } catch (exception $ex) {
            // pr($ex);exit;
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

    //CONFIG KALYANI
    public function depreciation($deprication_type_id = NULL) {
        try {
            //  $this->check_role_escalation_tab();
            $this->loadModel('depreciation');
            $this->loadModel('mainlanguage');
            $this->loadModel('language');

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
            $depreciation = $this->depreciation->find('list', array('fields' => array('depreciation.deprication_type_id', 'depreciation.deprication_type_desc_' . $laug), 'order' => array('deprication_type_desc_en' => 'ASC')));
            $this->set('depreciation', $depreciation);
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

            $landtdata = $this->depreciation->query("select * from ngdrstab_mst_depreciation_type");
            $this->set('landtdata', $landtdata);


            $this->set("fieldlist", $fieldlist = $this->depreciation->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));


            if ($this->request->is('post') || $this->request->is('put')) {

                $this->request->data['depreciation']['ip_address'] = $this->request->clientIp();
                $this->request->data['depreciation']['created_date'] = $created_date;
                $this->request->data['depreciation']['user_id'] = $user_id;
                $verrors = $this->validatedata($this->request->data['depreciation'], $fieldlist);
                // pr($verrors);

                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->depreciation->get_duplicate($languagelist);
                    //    pr($duplicate);
                    //  pr($this->request->data['depreciation']);
                    //  exit;
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['depreciation']);
                    // pr($checkd);
                    // exit;
                    if ($checkd) {
                        if ($this->depreciation->save($this->request->data['depreciation'])) {
                            $lastid = $this->depreciation->getLastInsertId();
                            if (is_numeric($lastid)) {
                                $this->Session->setFlash(__('lblsavemsg'));
                            } else {
                                $this->Session->setFlash(__('lbleditmsg'));
                            }
                            return $this->redirect(array('controller' => 'ValuationRules', 'action' => 'depreciation'));
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
            if (!is_null($deprication_type_id) && is_numeric($deprication_type_id)) {

                $this->Session->write('deprication_type_id', $deprication_type_id);
                $result = $this->depreciation->find("first", array('conditions' => array('deprication_type_id' => $deprication_type_id)));
                if (!empty($result)) {
                    $this->set('editflag', 'Y');
                    $this->request->data['depreciation'] = $result['depreciation'];
                } else {
                    $this->Session->setFlash(
                            __('lblnotfoundmsg')
                    );
                }
            }
        } catch (exception $ex) {

            // pr($ex);
            //exit;
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage()));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function delete_depreciation($deprication_type_id = null) {
        // pr($id);exit;
        $this->autoRender = false;
        $this->loadModel('depreciation');
        try {

            if (isset($deprication_type_id) && is_numeric($deprication_type_id)) {
                //  if ($type = 'subdivision') {
                $this->depreciation->deprication_type_id = $deprication_type_id;
                if ($this->depreciation->delete($deprication_type_id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('controller' => 'ValuationRules', 'action' => 'depreciation'));
                }
                // }
            }
        } catch (exception $ex) {
            // pr($ex);exit;
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

    public function rate_factor($rate_factor_id = NULL) {
        try {
            //  $this->check_role_escalation_tab();
            $this->loadModel('depreciation');
            $this->loadModel('constructiontype');
            $this->loadModel('ratefactor');
            $this->loadModel('mainlanguage');
            $this->loadModel('language');

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
            $depreciation = $this->depreciation->find('list', array('fields' => array('deprication_type_id', 'deprication_type_desc_' . $laug), 'order' => array('deprication_type_desc_' . $laug => 'ASC')));
            $this->set('depreciation', $depreciation);
            $constructiontype = $this->constructiontype->find('list', array('fields' => array('constructiontype.construction_type_id', 'constructiontype.construction_type_desc_' . $laug), 'order' => array('construction_type_desc_' . $laug => 'ASC')));
            $this->set('constructiontype', $constructiontype);
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


            $ratefactors = $this->ratefactor->find('all', array('fields' => array('ratefactor.*', 'ctype.construction_type_desc_' . $laug, 'dtype.deprication_type_desc_' . $laug),
                'joins' => array(
                    array(
                        'table' => 'ngdrstab_mst_construction_type',
                        'alias' => 'ctype',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('ctype.construction_type_id = ratefactor.constructiontype_id')
                    ),
                    array(
                        'table' => 'ngdrstab_mst_depreciation_type',
                        'alias' => 'dtype',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('dtype.deprication_type_id = ratefactor.depreciation_id')
                    )
                ), 'order' => 'ctype.construction_type_desc_' . $laug . ' ASC'
            ));

            //pr($ratefactors);exit;

            $this->set('ratefactors', $ratefactors);
            $this->set("fieldlist", $fieldlist = $this->ratefactor->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            if ($this->request->is('post') || $this->request->is('put')) {

                //pr($this->request->is('post'));exit;
                $this->request->data['rate_factor']['ip_address'] = $this->request->clientIp();
                $this->request->data['rate_factor']['created_date'] = $created_date;
//               $this->validatedata
                $this->request->data['rate_factor']['user_id'] = $user_id;
                $verrors = $this->validatedata($this->request->data['rate_factor'], $fieldlist);
                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->ratefactor->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['rate_factor']);
                    if ($checkd) {
                        if ($this->ratefactor->save($this->request->data['rate_factor'])) {
                            $lastid = $this->ratefactor->getLastInsertId();
                            if (is_numeric($lastid)) {
                                $this->Session->setFlash(__('lblsavemsg'));
                            } else {
                                $this->Session->setFlash(__('lbleditmsg'));
                            }

                            return $this->redirect(array('controller' => 'ValuationRules', 'action' => 'rate_factor'));
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
            if (!is_null($rate_factor_id) && is_numeric($rate_factor_id)) {

                $this->Session->write('rate_factor_id', $rate_factor_id);
                $result = $this->ratefactor->find("first", array('conditions' => array('rate_factor_id' => $rate_factor_id)));
                // pr($result);exit;
                if (!empty($result)) {
                    $this->set('editflag', 'Y');
                    $this->request->data['rate_factor'] = $result['ratefactor'];
                } else {
                    $this->Session->setFlash(
                            __('lblnotfoundmsg')
                    );
                }
            }
        } catch (exception $ex) {

            // pr($ex);
            // exit;
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage()));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function delete_rate_factor($rate_factor_id = null) {
        // pr($id);exit;
        $this->autoRender = false;
        $this->loadModel('ratefactor');
        try {

            if (isset($rate_factor_id) && is_numeric($rate_factor_id)) {
                //  if ($type = 'subdivision') {
                $this->ratefactor->rate_factor_id = $rate_factor_id;
                if ($this->ratefactor->delete($rate_factor_id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('controller' => 'ValuationRules', 'action' => 'rate_factor'));
                }
                // }
            }
        } catch (exception $ex) {
            // pr($ex);exit;
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

    //CONFIG KALYANI
    public function builtupareatype($rate_built_area_type_id = NULL) {
        try {
            $this->check_role_escalation_tab();
            $this->loadModel('areatype');
            $this->loadModel('mainlanguage');
            $this->loadModel('language');

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
            $areatype = $this->areatype->find('list', array('fields' => array('areatype.rate_built_area_type_id', 'areatype.rate_built_area_type_desc_' . $laug), 'order' => array('rate_built_area_type_desc_en' => 'ASC')));
            $this->set('areatype', $areatype);
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

            $landtdata = $this->areatype->query("select * from ngdrstab_mst_rate_built_area_type");
            $this->set('landtdata', $landtdata);


            $this->set("fieldlist", $fieldlist = $this->areatype->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));


            if ($this->request->is('post') || $this->request->is('put')) {

                $this->request->data['builtupareatype']['ip_address'] = $this->request->clientIp();
                $this->request->data['builtupareatype']['created_date'] = $created_date;
                $this->request->data['builtupareatype']['user_id'] = $user_id;
                $verrors = $this->validatedata($this->request->data['builtupareatype'], $fieldlist);
                // pr($verrors);

                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->areatype->get_duplicate($languagelist);
                    //    pr($duplicate);
                    //  pr($this->request->data['builtupareatype']);
                    //  exit;
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['builtupareatype']);
                    // pr($checkd);
                    //  exit;
                    if ($checkd) {
                        if ($this->areatype->save($this->request->data['builtupareatype'])) {
                            $lastid = $this->areatype->getLastInsertId();
                            if (is_numeric($lastid)) {
                                $this->Session->setFlash(__('lblsavemsg'));
                            } else {
                                $this->Session->setFlash(__('lbleditmsg'));
                            }
                            return $this->redirect(array('controller' => 'ValuationRules', 'action' => 'builtupareatype'));
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
            if (!is_null($rate_built_area_type_id) && is_numeric($rate_built_area_type_id)) {

                $this->Session->write('rate_built_area_type_id', $rate_built_area_type_id);
                $result = $this->areatype->find("first", array('conditions' => array('rate_built_area_type_id' => $rate_built_area_type_id)));
                // pr($result);exit;
                if (!empty($result)) {
                    $this->set('editflag', 'Y');
                    $this->request->data['builtupareatype'] = $result['areatype'];
                } else {
                    $this->Session->setFlash(
                            __('lblnotfoundmsg')
                    );
                }
            }
        } catch (exception $ex) {

            // pr($ex);
            //   exit;
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage()));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function delete_builtupareatype($rate_built_area_type_id = null) {
        // pr($id);exit;
        $this->autoRender = false;
        $this->loadModel('areatype');
        try {

            if (isset($rate_built_area_type_id) && is_numeric($rate_built_area_type_id)) {
                //  if ($type = 'subdivision') {
                $this->areatype->rate_built_area_type_id = $rate_built_area_type_id;
                if ($this->areatype->delete($rate_built_area_type_id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('controller' => 'ValuationRules', 'action' => 'builtupareatype'));
                }
                // }
            }
        } catch (exception $ex) {
            // pr($ex);exit;
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

    public function road_vicinity($road_vicinity_id = NULL) {
        try {
            //  $this->check_role_escalation();
            $this->loadModel('State');
            $this->loadModel('User');
            $this->loadModel('roadvicinity');
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            //languages are loaded firstly from config (from table)
            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'),
                'joins' => array(array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
                    )), 'order' => 'conf.language_id ASC'));
            $this->set('languagelist', $languagelist);

            $this->set('actiontypeval', NULL);
            $this->set('hfactionval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);

            $this->set('behaviouralrecord', $this->roadvicinity->find('all'));
            $date = date('Y/m/d H:i:s');
            $created_date = date('Y/m/d');
            $req_ip = $_SERVER['REMOTE_ADDR'];

            $this->set("fieldlist", $fieldlist = $this->roadvicinity->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if ($this->request->is('post') || $this->request->is('put')) {
                $this->check_csrf_token($this->request->data['road_vicinity']['csrftoken']);

                $verrors = $this->validatedata($this->request->data['road_vicinity'], $fieldlist);
                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->roadvicinity->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['road_vicinity']);
                    if ($checkd) {
                        if ($this->roadvicinity->save($this->request->data['road_vicinity'])) {
                            $last = $this->roadvicinity->getLastInsertId();
                            if ($last) {
                                $this->Session->setFlash(__("lblsavemsg"));
                            } else {
                                $this->Session->setFlash(__("lbleditmsg"));
                            }

                            $this->redirect(array('controller' => 'ValuationRules', 'action' => 'road_vicinity'));
                        } else {
                            $this->Session->setFlash(__('lblnotsavemsg'));
                        }
                    } else {
                        $this->Session->setFlash(__('lblduplicatemsg'));
                    }
                } else {
                    $this->Session->setFlash(__('Please find Validations'));
                }
            }

            if (!is_null($road_vicinity_id) && is_numeric($road_vicinity_id)) {
                $result = $this->roadvicinity->find("first", array('conditions' => array('road_vicinity_id' => $road_vicinity_id)));
                if (!empty($result)) {
                    $this->set('editflag', 'Y');
                    $this->request->data['road_vicinity'] = $result['roadvicinity'];
                } else {
                    $this->Session->setFlash(
                            __('lblnotfoundmsg')
                    );
                }
            }


            $this->Session->write("randamkey", rand(111111, 999999));
        } catch (Exception $exc) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->set_csrf_token();
    }

    public function delete_road_vicinity($road_vicinity_id = NULL) {
        $this->loadModel('roadvicinity');
        if (is_numeric($road_vicinity_id)) {
            if ($this->roadvicinity->deleteAll(array('road_vicinity_id' => $road_vicinity_id))) {
                $this->Session->setFlash(
                        __('lbldeletemsg')
                );
            } else {
                $this->Session->setFlash(
                        __('lblnotdeletemsg')
                );
            }
        }

        return $this->redirect(array('controller' => 'ValuationRules', 'action' => 'road_vicinity'));
    }

    public function user_dependency1($user_defined_dependency1_id = NULL) {
        try {
            //  $this->check_role_escalation();
            $this->loadModel('State');
            $this->loadModel('User');
            $this->loadModel('user_defined_dependancy1');
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            //languages are loaded firstly from config (from table)
            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'),
                'joins' => array(array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
                    )), 'order' => 'conf.language_id ASC'));
            $this->set('languagelist', $languagelist);

            $this->set('actiontypeval', NULL);
            $this->set('hfactionval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);

            $this->set('records', $this->user_defined_dependancy1->find('all'));
            $date = date('Y/m/d H:i:s');
            $created_date = date('Y/m/d');
            $req_ip = $_SERVER['REMOTE_ADDR'];

            $this->set("fieldlist", $fieldlist = $this->user_defined_dependancy1->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if ($this->request->is('post') || $this->request->is('put')) {
                $this->check_csrf_token($this->request->data['user_dependency1']['csrftoken']);

                $verrors = $this->validatedata($this->request->data['user_dependency1'], $fieldlist);
                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->user_defined_dependancy1->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['user_dependency1']);
                    if ($checkd) {
                        if ($this->user_defined_dependancy1->save($this->request->data['user_dependency1'])) {
                            $last = $this->user_defined_dependancy1->getLastInsertId();
                            if ($last) {
                                $this->Session->setFlash(__("lblsavemsg"));
                            } else {
                                $this->Session->setFlash(__("lbleditmsg"));
                            }

                            $this->redirect(array('controller' => 'ValuationRules', 'action' => 'user_dependency1'));
                        } else {
                            $this->Session->setFlash(__('lblnotsavemsg'));
                        }
                    } else {
                        $this->Session->setFlash(__('lblduplicatemsg'));
                    }
                } else {
                    $this->Session->setFlash(__('Please find Validations'));
                }
            }

            if (!is_null($user_defined_dependency1_id) && is_numeric($user_defined_dependency1_id)) {
                $result = $this->user_defined_dependancy1->find("first", array('conditions' => array('user_defined_dependency1_id' => $user_defined_dependency1_id)));
                if (!empty($result)) {
                    $this->set('editflag', 'Y');
                    $this->request->data['user_dependency1'] = $result['user_defined_dependancy1'];
                } else {
                    $this->Session->setFlash(
                            __('lblnotfoundmsg')
                    );
                }
            }


            $this->Session->write("randamkey", rand(111111, 999999));
        } catch (Exception $exc) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->set_csrf_token();
    }

    public function delete_user_dependency1($user_defined_dependency1_id = NULL) {
        $this->loadModel('user_defined_dependancy1');
        if (is_numeric($user_defined_dependency1_id)) {
            if ($this->user_defined_dependancy1->deleteAll(array('user_defined_dependency1_id' => $user_defined_dependency1_id))) {
                $this->Session->setFlash(
                        __('lbldeletemsg')
                );
            } else {
                $this->Session->setFlash(
                        __('lblnotdeletemsg')
                );
            }
        }

        return $this->redirect(array('controller' => 'ValuationRules', 'action' => 'user_dependency1'));
    }

    public function user_dependency2($user_defined_dependency2_id = NULL) {
        try {
            //  $this->check_role_escalation();
            $this->loadModel('State');
            $this->loadModel('User');
            $this->loadModel('user_defined_dependancy2');
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            //languages are loaded firstly from config (from table)
            $languagelist = $this->mainlanguage->find('all', array('fields' => array('id', 'language_name', 'language_code'),
                'joins' => array(array(
                        'table' => 'ngdrstab_conf_language',
                        'alias' => 'conf',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('conf.language_id = mainlanguage.id')
                    )), 'order' => 'conf.language_id ASC'));
            $this->set('languagelist', $languagelist);

            $this->set('actiontypeval', NULL);
            $this->set('hfactionval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);

            $this->set('records', $this->user_defined_dependancy2->find('all'));
            $date = date('Y/m/d H:i:s');
            $created_date = date('Y/m/d');
            $req_ip = $_SERVER['REMOTE_ADDR'];

            $this->set("fieldlist", $fieldlist = $this->user_defined_dependancy2->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if ($this->request->is('post') || $this->request->is('put')) {
                $this->check_csrf_token($this->request->data['user_dependency2']['csrftoken']);

                $verrors = $this->validatedata($this->request->data['user_dependency2'], $fieldlist);
                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->user_defined_dependancy2->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['user_dependency2']);
                    if ($checkd) {
                        if ($this->user_defined_dependancy2->save($this->request->data['user_dependency2'])) {
                            $last = $this->user_defined_dependancy2->getLastInsertId();
                            if ($last) {
                                $this->Session->setFlash(__("lblsavemsg"));
                            } else {
                                $this->Session->setFlash(__("lbleditmsg"));
                            }

                            $this->redirect(array('controller' => 'ValuationRules', 'action' => 'user_dependency2'));
                        } else {
                            $this->Session->setFlash(__('lblnotsavemsg'));
                        }
                    } else {
                        $this->Session->setFlash(__('lblduplicatemsg'));
                    }
                } else {
                    $this->Session->setFlash(__('Please find Validations'));
                }
            }

            if (!is_null($user_defined_dependency2_id) && is_numeric($user_defined_dependency2_id)) {
                $result = $this->user_defined_dependancy2->find("first", array('conditions' => array('user_defined_dependency2_id' => $user_defined_dependency2_id)));
                if (!empty($result)) {
                    $this->set('editflag', 'Y');
                    $this->request->data['user_dependency2'] = $result['user_defined_dependancy2'];
                } else {
                    $this->Session->setFlash(
                            __('lblnotfoundmsg')
                    );
                }
            }


            $this->Session->write("randamkey", rand(111111, 999999));
        } catch (Exception $exc) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->set_csrf_token();
    }

    public function delete_user_dependency2($user_defined_dependency2_id = NULL) {
        $this->loadModel('user_defined_dependancy2');
        if (is_numeric($user_defined_dependency2_id)) {
            if ($this->user_defined_dependancy2->deleteAll(array('user_defined_dependency2_id' => $user_defined_dependency2_id))) {
                $this->Session->setFlash(
                        __('lbldeletemsg')
                );
            } else {
                $this->Session->setFlash(
                        __('lblnotdeletemsg')
                );
            }
        }

        return $this->redirect(array('controller' => 'ValuationRules', 'action' => 'user_dependency2'));
    }

    public function unit_category($unit_cat_id = NULL) {
        try {
            //$this->check_role_escacheck_role_escalationlation();
            $this->loadModel('divisionnew');
            $this->loadModel('adminLevelConfig');
            $this->loadModel('State');
            $this->loadModel('District');
            $this->loadModel('Subdivision');
            $this->loadModel('User');
            $this->loadModel('taluka');
            $user_id = $this->Auth->User("user_id");
            $date = date('Y/m/d H:i:s');
            $created_date = date('Y/m/d');
            $this->loadModel('NGDRSErrorCode');
            $this->loadModel('UnitCategory');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
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

            $this->set('talukarecord', NULL);
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $statename = $this->Session->read("state_name_en");

            $unitcatdata = $this->UnitCategory->query("select * from ngdrstab_mst_unit_category order by unit_cat_desc_en");
            $this->set('unitcatdata', $unitcatdata);

//            pr($propertydata);exit;

            $this->set("fieldlist", $fieldlist = $this->UnitCategory->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));


            if ($this->request->is('post') || $this->request->is('put')) {

                $this->request->data['unit_category']['ip_address'] = $this->request->clientIp();
                $this->request->data['unit_category']['created_date'] = $created_date;
                $this->request->data['unit_category']['user_id'] = $user_id;
                $verrors = $this->validatedata($this->request->data['unit_category'], $fieldlist);
                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->UnitCategory->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['unit_category']);
                    if ($checkd) {
                        if ($this->UnitCategory->save($this->request->data['unit_category'])) {

                            $this->Session->setFlash(__('lblsavemsg'));
                            return $this->redirect(array('action' => 'unit_category'));
                            $lastid = $this->UnitCategory->getLastInsertId();
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
            if (!is_null($unit_cat_id) && is_numeric($unit_cat_id)) {
                $this->set('editflag', 'Y');
                $this->Session->write('unit_cat_id', $unit_cat_id);
                $result = $this->UnitCategory->find("first", array('conditions' => array('unit_cat_id' => $unit_cat_id)));
                if (!empty($result)) {
                    $this->request->data['unit_category'] = $result['UnitCategory'];
                } else {
                    $this->Session->setFlash(
                            __('lblnotfoundmsg')
                    );
                }
            }
        } catch (exception $ex) {
            pr($ex);
            exit;
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage()));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function delete_unit_category($unit_cat_id = null) {
        $this->autoRender = false;
        $this->loadModel('UnitCategory');
        try {
            if (isset($unit_cat_id) && is_numeric($unit_cat_id)) {
                $this->UnitCategory->unit_cat_id = $unit_cat_id;
                if ($this->UnitCategory->delete($unit_cat_id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'unit_category'));
                }
                // }
            }
        } catch (exception $ex) {
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

    public function confratesearch($search_id = NULL) {
        try {
//            $this->check_role_escalation();
            $this->loadModel('RateSearch');
            $this->loadModel('Developedlandtype');
            $this->loadModel('usage_main_category');

            $user_id = $this->Auth->User("user_id");
            $date = date('Y/m/d H:i:s');
            $created_date = date('Y/m/d');
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('article');
            $this->loadModel('mainlanguage');
            $lang = $this->Session->read("sess_langauge");
            $this->set('lang', $lang);

            //       $confratesearch1 = $this->RateSearch->query("select * from ngdrstab_conf_rate_search");
            //       $this->set('confratesearch2', $confratesearch1);
            $developldata = ClassRegistry::init('Developedlandtype')->find('list', array('fields' => array('Developedlandtype.developed_land_types_id', 'Developedlandtype.developed_land_types_desc_' . $lang), 'order' => array('developed_land_types_desc_en' => 'ASC')));
            $this->set('developldata', $developldata);
            $maincdata = ClassRegistry::init('usage_main_category')->find('list', array('fields' => array('usage_main_category.usage_main_catg_id', 'usage_main_category.usage_main_catg_desc_' . $lang), 'order' => array('usage_main_catg_desc_en' => 'ASC')));
            $this->set('maincdata', $maincdata);


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
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $statename = $this->Session->read("state_name_en");


            $this->set("fieldlist", $fieldlist = $this->RateSearch->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            $result = $this->RateSearch->query("SELECT rc.*,lt.developed_land_types_desc_$lang, mc.usage_main_catg_desc_$lang,lt.developed_land_types_id,mc.usage_main_catg_id     
  FROM ngdrstab_conf_rate_search rc
  JOIN ngdrstab_mst_developed_land_types lt ON lt.developed_land_types_id = rc.developed_land_types_id 
  JOIN ngdrstab_mst_usage_main_category mc ON mc.usage_main_catg_id = rc.usage_main_cat_id");
            $this->set('result1', $result);
            if ($this->request->is('post') || $this->request->is('put')) {


                $this->request->data['confratesearch']['ip_address'] = $this->request->clientIp();
                $this->request->data['confratesearch']['created_date'] = $created_date;
                $this->request->data['confratesearch']['user_id'] = $user_id;
                $verrors = $this->validatedata($this->request->data['confratesearch'], $fieldlist);
//pr($verrors);exit;
                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->RateSearch->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['confratesearch']);
                    if ($checkd) {
//                          pr($this->request->data['confratesearch']);exit;
                        if ($this->RateSearch->save($this->request->data['confratesearch'])) {
                            $lastid = $this->RateSearch->getLastInsertId();
                            if (is_numeric($lastid)) {
                                $this->Session->setFlash(__('lblsavemsg'));
                            } else {
                                $this->Session->setFlash(__('lbleditmsg'));
                            }
                            $this->redirect(array('controller' => 'ValuationRules', 'action' => 'confratesearch'));
                        } else {
                            $this->Session->setFlash(__('lblnotsavemsg'));
                        }
                    } else {
                        $this->Session->setFlash(__('lblduplicatemsg'));
                    }
                } else {
                    $this->Session->setFlash(__('Field validations '));
                }
            }
            if (!is_null($search_id) && is_numeric($search_id)) {
                $this->Session->write('search_id', $search_id);
                $result = $this->RateSearch->find("first", array('conditions' => array('search_id' => $search_id)));
                if (!empty($result)) {
                    $this->set('editflag', 'Y');
                    $this->request->data['confratesearch'] = $result['RateSearch'];
                } else {
                    $this->Session->setFlash(
                            __('lblnotfoundmsg')
                    );
                }
            }
        } catch (exception $ex) {

            //pr($ex);
            //exit;
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage()));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function delete_confratesearch($search_id = null) {
        $this->autoRender = false;
        $this->loadModel('RateSearch');
        try {

            if (isset($search_id) && is_numeric($search_id)) {
                $this->RateSearch->search_id = $search_id;
                if ($this->RateSearch->delete($search_id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'confratesearch'));
                }
                // }
            }
        } catch (exception $ex) {
            // pr($ex);exit;
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

}
