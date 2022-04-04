<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PDEMaster
 *
 * @author Admin
 */
class PDEMasterController extends AppController {

    //put your code here
    //put your code here
    public $components = array('Security', 'RequestHandler', 'Captcha', 'Cookie');
    public $helpers = array('Js', 'Html', 'Form', 'Paginator');

    public function beforeFilter() {
        $this->loadModel('language');
        $langaugelist = $this->language->find('all', array('conditions' => array('state_id' => array(0, $this->Auth->user('state_id')))));
        $this->set('langaugelist', $langaugelist);

        if (isset($this->Security)) { //&& isset($this->Auth)) {
            $this->Security->validatePost = false;
            $this->Security->enabled = false;
            $this->Security->csrfCheck = false;
        }
    }

    public function minorfunction($id = NULL) {
        try {
            $this->check_role_escalation();
            array_map(array($this, 'loadModel'), array('NGDRSErrorCode', 'minorfunction', 'mainlanguage', 'majorfunction'));
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $created_date = date('Y-m-d H:s:i');
            $lang = $this->Session->read("sess_langauge");
            $this->set('lang', $lang);
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->set('actiontypeval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            $this->set('hfactionval', NULL);

            $minorfunction = $this->minorfunction->find("all");
            $this->set('minorfunction', $minorfunction);
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

            $this->set("fieldlist", $fieldlist = $this->minorfunction->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            if (!empty($id)) {
                $actionvalue = 'lbleditmsg';
            } else {
                $actionvalue = 'lblsavemsg';
            }

            if ($this->request->is('post') || $this->request->is('put')) {
                $this->check_csrf_token($this->request->data['minorfunction']['csrftoken']);
                $this->request->data['minorfunction']['req_ip'] = $this->request->clientIp();
                $this->request->data['minorfunitunction']['user_id'] = $user_id;
                $this->request->data['minorfunction']['created_date'] = $created_date;
                $verrors = $this->validatedata($this->request->data['minorfunction'], $fieldlist);

//                pr($this->request->data['minorfunction']);
//                pr($fieldlist);exit;
                // pr($verrors);exit;

                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->minorfunction->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['minorfunction']);
                    if ($checkd) {
                        if ($this->minorfunction->save($this->request->data['minorfunction'])) {
                            $this->Session->setFlash(__($actionvalue));
                            //$this->Session->setFlash(__("Record Saved Successfully"));
                            $this->redirect(array('controller' => 'PDEMaster', 'action' => 'minorfunction'));
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
                $record = $this->minorfunction->find("first", array('conditions' => array('minor_id' => $id)));
                if (!empty($record)) {
                    $this->request->data['minorfunction'] = $record['minorfunction'];
                } else {
                    $this->Session->setFlash(__("lblnotfoundmsg"));
                    $this->redirect(array('controller' => 'PDEMaster', 'action' => 'minorfunction'));
                }
            }

            $this->set_csrf_token();
        } catch (Exception $ex) {
//            pr($ex);exit
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

    public function minorfunction_dev($id = NULL) {
        try {
            $this->check_role_escalation();
            array_map(array($this, 'loadModel'), array('NGDRSErrorCode', 'minorfunction', 'mainlanguage', 'majorfunction'));
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $created_date = date('Y-m-d H:s:i');
            $lang = $this->Session->read("sess_langauge");
            $this->set('lang', $lang);
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->set('actiontypeval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            $this->set('hfactionval', NULL);

            $minorfunction = $this->minorfunction->find("all");
            $this->set('minorfunction', $minorfunction);
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

            $this->set("fieldlist", $fieldlist = $this->minorfunction->fieldlist_dev($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if (!empty($id)) {
                $actionvalue = 'lbleditmsg';
            } else {
                $actionvalue = 'lblsavemsg';
            }

            if ($this->request->is('post') || $this->request->is('put')) {
                $this->check_csrf_token($this->request->data['minorfunction']['csrftoken']);
                $this->request->data['minorfunction']['req_ip'] = $this->request->clientIp();
                $this->request->data['minorfunitunction']['user_id'] = $user_id;
                $this->request->data['minorfunction']['created_date'] = $created_date;
                $verrors = $this->validatedata($this->request->data['minorfunction'], $fieldlist);

                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->minorfunction->get_duplicate_dev($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['minorfunction']);
                    if ($checkd) {
                        if ($this->minorfunction->save($this->request->data['minorfunction'])) {
                            $this->Session->setFlash(__($actionvalue));
                            // $this->Session->setFlash(__("Record Saved Successfully"));
                            $this->redirect(array('controller' => 'PDEMaster', 'action' => 'minorfunction_dev'));
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
                $record = $this->minorfunction->find("first", array('conditions' => array('minor_id' => $id)));
                if (!empty($record)) {
                    $this->request->data['minorfunction'] = $record['minorfunction'];
                } else {
                    $this->Session->setFlash(__("lblnotfoundmsg"));
                    $this->redirect(array('controller' => 'PDEMaster', 'action' => 'minorfunction_dev'));
                }
            }

            $this->set_csrf_token();
        } catch (Exception $ex) {
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

    public function delete_minorfunction_dev($id = null) {
        $this->autoRender = false;
        $this->loadModel('minorfunction');

        $rparams = Router::parse($this->referer('/', true));
        $params = $this->request->params;

        if ($params['action'] != 'delete_' . $rparams['action']) {
            return $this->redirect(array('controller' => 'Error', 'action' => 'notfound'));
        }

        try {
            if (isset($id) && is_numeric($id)) {
                $this->minorfunction->id = $id;
                if ($this->minorfunction->delete($id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'minorfunction_dev'));
                }
            }
        } catch (exception $ex) {
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

    public function article($article_id = NULL) {
        try {
            $this->check_role_escalation();
            $this->loadModel('adminLevelConfig');
            $this->loadModel('State');
            $this->loadModel('User');
            $user_id = $this->Auth->User("user_id");
            $date = date('Y/m/d H:i:s');
            $created_date = date('Y/m/d');
            $this->loadModel('NGDRSErrorCode');
            $this->loadModel('article');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);

            $this->set('only_one_party_flag', null);
            $this->set('home_visit_flag', null);
            $this->set('dock_expiry_applicable_flag', null);
            $this->set('e_reg_applicable_flag', null);
            $this->set('e_file_applicable_flag', null);
            $this->set('property_applicable_flag', null);
            $this->set('template_applicable_flag', null);
            $this->set('leave_licence_flag_flag', null);
            $this->set('use_common_rule_flag_flag', null);
            $this->set('display_flag_flag', null);
            $this->set('index1_flag_flag', null);
            $this->set('index2_flag_flag', null);
            $this->set('index3_flag_flag', null);
            $this->set('index4_flag_flag', null);

            $this->set('index_reg_flag1_flag', null);
            $this->set('index_reg_flag2_flag', null);
            $this->set('index_reg_flag3_flag', null);
            $this->set('index_reg_flag4_flag', null);

            $this->set('titlewise_book_number_flag', null);


            $this->set('article', $this->article->find('all'));

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

            $this->set("fieldlist", $fieldlist = $this->article->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            $this->set('titlewise_book_number_flag', 'N');

            if (!empty($article_id)) {
                $actionvalue = 'lbleditmsg';
            } else {
                $actionvalue = 'lblsavemsg';
            }
            if ($this->request->is('post') || $this->request->is('put')) {

                //pr($this->request->data);exit;

                $this->request->data['article']['ip_address'] = $this->request->clientIp();
                $this->request->data['article']['created_date'] = $created_date;
                $this->request->data['article']['user_id'] = $user_id;
                if (isset($this->request->data['article']['titlewise_book_number']) && $this->request->data['article']['titlewise_book_number'] == 'Y') {
                    unset($fieldlist['book_number']);
                }
                $verrors = $this->validatedata($this->request->data['article'], $fieldlist);
//pr($verrors);exit;
                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->article->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['article']);
                    if ($checkd) {
//                        pr($this->request->data); exit;
                        if ($this->article->save($this->request->data['article'])) {

                            $this->Session->setFlash(__($actionvalue));
                            //$this->Session->setFlash(__('Article saved Successful.'));
                            return $this->redirect(array('action' => 'article'));
                            $lastid = $this->article->getLastInsertId();
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
            if (!is_null($article_id) && is_numeric($article_id)) {
                $this->set('editflag', 'Y');
                $this->Session->write('article_id', $article_id);
                $result = $this->article->find("first", array('conditions' => array('article_id' => $article_id)));
                if (empty($result)) {
                    $this->Session->setFlash(__('lblnotfoundmsg'));
                    return $this->redirect(array('action' => 'article'));
                }
                $this->request->data['article'] = $result['article'];
//pr($result);exit;
                $this->set('only_one_party_flag', $result['article']['only_one_party']);
                $this->set('home_visit_flag', $result['article']['home_visit']);
                $this->set('dock_expiry_applicable_flag', $result['article']['dock_expiry_applicable']);
                $this->set('e_reg_applicable_flag', $result['article']['e_reg_applicable']);
                $this->set('e_file_applicable_flag', $result['article']['e_file_applicable']);
                $this->set('property_applicable_flag', $result['article']['property_applicable']);
                $this->set('template_applicable_flag', $result['article']['template_applicable']);
                $this->set('leave_licence_flag_flag', $result['article']['leave_licence_flag']);
                $this->set('use_common_rule_flag_flag', $result['article']['use_common_rule_flag']);
                $this->set('display_flag_flag', $result['article']['display_flag']);

                $this->set('index1_flag_flag', $result['article']['index1_flag']);
                $this->set('index2_flag_flag', $result['article']['index2_flag']);
                $this->set('index3_flag_flag', $result['article']['index3_flag']);
                $this->set('index4_flag_flag', $result['article']['index4_flag']);

                $this->set('index_reg_flag1_flag', $result['article']['index_reg_flag1']);
                $this->set('index_reg_flag2_flag', $result['article']['index_reg_flag2']);
                $this->set('index_reg_flag3_flag', $result['article']['index_reg_flag3']);
                $this->set('index_reg_flag4_flag', $result['article']['index_reg_flag4']);

                $this->set('titlewise_book_number_flag', $result['article']['titlewise_book_number']);

                $this->request->data['article'] = $result['article'];
            }
        } catch (exception $ex) {

            // pr($ex);
            // exit;
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage()));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function delete_article($article_id = null) {
        $this->autoRender = false;
        $this->loadModel('article');
        try {
            if (isset($article_id) && is_numeric($article_id)) {

                $result = $this->article->find('all', array('conditions' => array('article_id' => $article_id, 'to_be_deleted_flag' => 'Y')));
                if (!empty($result)) {
                    $this->article->article_id = $article_id;
                    if ($this->article->delete($article_id)) {
                        $this->Session->setFlash(
                                __('lbldeletemsg')
                        );
                        return $this->redirect(array('action' => 'article'));
                    }
                } else {
                    $this->Session->setFlash(
                            __('Not Allow To Delete This Record !')
                    );
                    return $this->redirect(array('action' => 'article'));
                }
            }
        } catch (exception $ex) {

            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

    public function article_screen_mapping() {
        try {
            $this->check_role_escalation();
            array_map(array($this, 'loadModel'), array('article_screen_mapping', 'NGDRSErrorCode'));
            $user_id = $this->Auth->User("user_id");
            $req_ip = $_SERVER['REMOTE_ADDR'];
            $stateid = $this->Auth->User("state_id");
            $created_date = date('Y/m/d');
            $lang = $this->Session->read("sess_langauge");
            $this->set('lang', $lang);
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->set('articlelist', ClassRegistry::init('article')->find('list', array('fields' => array('article_id', 'article_desc_' . $lang), 'order' => array('article_desc_' . $lang => 'ASC'))));
            $this->set('minorlist', ClassRegistry::init('minorfunction')->find('list', array('fields' => array('id', 'function_desc_' . $lang), 'order' => array('function_desc_' . $lang => 'ASC'), 'conditions' => array('dispaly_flag' => 'O'))));
            $this->set('actiontypeval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);
            $this->set('hfactionval', NULL);
            $articlegrid = $this->article_screen_mapping->query("select distinct asm.article_id,asm.minorfun_id,asm.id,a.article_desc_en,mf.function_desc_en from ngdrstab_mst_article_screen_mapping asm
                                                    inner join ngdrstab_mst_article a on a.article_id=asm.article_id
                                                    inner join ngdrstab_mst_minorfunctions mf on mf.id=asm.minorfun_id");
            $this->set('articlegrid', $articlegrid);

            $fieldlist = array();
            $fieldlist['article_id']['select'] = 'is_select_req';
            $fieldlist['minorfun_id']['select'] = 'is_select_req';
            $this->set('fieldlist', $fieldlist);
            foreach ($fieldlist as $key => $valrule) {
                $errarr[$key . '_error'] = "";
            }
            $this->set("errarr", $errarr);
            if ($this->request->is('post')) {
                $duplicate = $this->article_screen_mapping->get_duplicate();
                // pr($duplicate);
                // pr($this->request->data['article_screen_mapping']);exit;
                $this->request->data['article_screen_mapping']['id'] = @$this->request->data['hfid'];
                $checkd = $this->check_duplicate($duplicate, $this->request->data['article_screen_mapping']);

                if ($checkd) {
                    $this->check_csrf_token($this->request->data['article_screen_mapping']['csrftoken']);
                    $actiontype = $_POST['actiontype'];
                    $hfactionval = $_POST['hfaction'];
                    $hfid = $_POST['hfid'];
                    $this->set('hfid', $hfid);
                    if ($actiontype == '1') {
                        $this->set('actiontypeval', $actiontype);
                        $this->set('hfactionval', $hfactionval);
                    }
                    if ($hfactionval == 'S') {
                        $this->request->data['article_screen_mapping']['req_ip'] = $this->request->clientIp();
                        $this->request->data['article_screen_mapping']['user_id'] = $user_id;
                        $this->request->data['article_screen_mapping']['state_id'] = $stateid;
                        $this->request->data['article_screen_mapping']['created_date'] = $created_date;
                        if ($this->request->data['hfupdateflag'] == 'Y') {
                            $this->request->data['article_screen_mapping']['id'] = $this->request->data['hfid'];

                            $actionvalue = "lbleditmsg";
                        } else {
                            $actionvalue = "lblsavemsg";
                        }
                        $this->request->data['article_screen_mapping'] = $this->istrim($this->request->data['article_screen_mapping']);
                        $errarr = $this->validatedata($this->request->data['article_screen_mapping'], $fieldlist);
                        $flag = 0;
                        foreach ($errarr as $dd) {
                            if ($dd != "") {
                                $flag = 1;
                            }
                        }
                        if ($flag == 1) {
                            $this->set("errarr", $errarr);
                        } else {
                            if ($this->article_screen_mapping->save($this->request->data['article_screen_mapping'])) {
                                $this->Session->setFlash(__($actionvalue));
                                $this->redirect(array('controller' => 'PDEMaster', 'action' => 'article_screen_mapping'));
                            } else {
                                $this->Session->setFlash(__('lblnotsavemsg'));
                            }
                        }
//                    }
                    }

                    if ($_POST['actiontype'] == 2) {
                        $this->redirect(array('controller' => 'PDEMaster', 'action' => 'article_screen_mapping'));
                    }
                } else {
                    $this->Session->setFlash(__('lblduplicatemsg'));
                }
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->set_csrf_token();
    }

    public function article_screen_mapping_delete($id = null) {
        try {

            $this->autoRender = false;
            $this->loadModel('article_screen_mapping');
            if (isset($id) && is_numeric($id)) {

                $this->article_screen_mapping->id = $id;
                if ($this->article_screen_mapping->delete($id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'article_screen_mapping'));
                }
            }
        } catch (Exception $ex) {
            
        }
    }

    public function document_title($articledescription_id = NULL) {
        try {
            $this->check_role_escalation();
            $this->loadModel('adminLevelConfig');
            $this->loadModel('State');
            $this->loadModel('User');
            $this->loadModel('articledescdetails');
            $user_id = $this->Auth->User("user_id");
            $date = date('Y/m/d H:i:s');
            $created_date = date('Y/m/d');
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('article');
            $this->loadModel('mainlanguage');
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);

            $articlelist = ClassRegistry::init('article')->find('list', array('fields' => array('article.article_id', 'article.article_desc_' . $laug), 'order' => array('article_desc_en' => 'ASC')));
            $this->set('articlelist', $articlelist);
            $document = $this->articledescdetails->find('all', array(
                'fields' => array('article.article_id', 'article.article_desc_' . $laug, 'articledescdetails.*'),
                'joins' => array(
                    array(
                        'table' => 'ngdrstab_mst_article',
                        'alias' => 'article',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions' => array('article.article_id = articledescdetails.article_id')
                    ),
                ),
            ));


            $this->set('document', $document);
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
//            $data = $this->article->query("select titlewise_book_number from ngdrstab_mst_article where titlewise_book_number='Y'");
//            $book_no = $data[0][0]['titlewise_book_number'];
//            if (isset($book_no) == 'Y') {
//                $this->set('data1', $book_no);
//            }

            $this->set("fieldlist", $fieldlist = $this->articledescdetails->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if (!empty($articledescription_id)) {
                $actionvalue = 'lbleditmsg';
            } else {
                $actionvalue = 'lblsavemsg';
            }

            if ($this->request->is('post') || $this->request->is('put')) {

                // pr($this->request->data['document_title']);
                $this->request->data['document_title']['ip_address'] = $this->request->clientIp();
                $this->request->data['document_title']['created_date'] = $created_date;
                $this->request->data['document_title']['user_id'] = $user_id;
                if (isset($this->request->data['document_title']['article_id']) && is_numeric($this->request->data['document_title']['article_id'])) {
                    $titlewise_book_number = $this->article->query("select titlewise_book_number from ngdrstab_mst_article where article_id=? and titlewise_book_number='N' ", array($this->request->data['document_title']['article_id']));
                    if (!empty($titlewise_book_number)){
                        unset($fieldlist['book_number']);
                    }
                }


                $verrors = $this->validatedata($this->request->data['document_title'], $fieldlist);
//pr($verrors);exit;
                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->articledescdetails->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['document_title']);
                    if ($checkd) {
                        if ($this->articledescdetails->save($this->request->data['document_title'])) {
                            //$this->Session->setFlash(__('Record saved Successful.'));

                            $this->Session->setFlash(__($actionvalue));
                            return $this->redirect(array('action' => 'document_title'));
                            $lastid = $this->articledescdetails->getLastInsertId();
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
            if (!is_null($articledescription_id) && is_numeric($articledescription_id)) {
                $this->Session->write('articledescription_id', $articledescription_id);
                $result = $this->articledescdetails->find("first", array('conditions' => array('articledescription_id' => $articledescription_id)));
                if (!empty($result)) {
                    $this->set('editflag', 'Y');
                    $this->request->data['document_title'] = $result['articledescdetails'];
                } else {
                    $this->Session->setFlash(
                            __('lblnotfoundmsg')
                    );
                }
            }
        } catch (exception $ex) {

            //   pr($ex);
            //   exit;
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage()));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function delete_document_title($articledescription_id = null) {
        $this->autoRender = false;
        $this->loadModel('articledescdetails');
        try {

            if (isset($articledescription_id) && is_numeric($articledescription_id)) {
                $this->articledescdetails->articledescription_id = $articledescription_id;
                if ($this->articledescdetails->delete($articledescription_id)) {
                    $this->Session->setFlash(
                            __('The Record Has Been Deleted')
                    );
                    return $this->redirect(array('action' => 'document_title'));
                }
                // }
            }
        } catch (exception $ex) {
            
        }
    }

    public function getbookno() {
        try {
            $this->autoRender = FALSE;
            $this->loadModel('article');
            $lang = $this->Session->read("sess_langauge");
            $data = $this->request->data;
            if (isset($data['article_id']) && is_numeric($data['article_id'])) {

                $result = $this->article->query("select titlewise_book_number from ngdrstab_mst_article where article_id=? and titlewise_book_number=?", array($data['article_id'], 'Y'));
                if (!empty($result)) {
                    echo '1';
                }
                exit;
            }
        } catch (Exception $e) {
            $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function document_execution_type($execution_type_id = NULL) {
        try {
            $this->loadModel('adminLevelConfig');
            $this->loadModel('State');
            $this->loadModel('User');
            $user_id = $this->Auth->User("user_id");
            $date = date('Y/m/d H:i:s');
            $created_date = date('Y/m/d');
            $this->loadModel('NGDRSErrorCode');
            $this->loadModel('document_execution_type');

            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);

            //        $this->set('local_holiday_flag', null);
            $this->set('executiontype', $this->document_execution_type->find('all'));

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

            $this->set("fieldlist", $fieldlist = $this->document_execution_type->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));


            if (!empty($execution_type_id)) {
                $actionvalue = 'lbleditmsg';
            } else {
                $actionvalue = 'lblsavemsg';
            }

            if ($this->request->is('post') || $this->request->is('put')) {

                //pr($this->request->data);exit;

                $this->request->data['document_execution_type']['ip_address'] = $this->request->clientIp();
                $this->request->data['document_execution_type']['created_date'] = $created_date;
                $this->request->data['document_execution_type']['user_id'] = $user_id;
                $verrors = $this->validatedata($this->request->data['document_execution_type'], $fieldlist);

                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->document_execution_type->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['document_execution_type']);
                    if ($checkd) {
                        if ($this->document_execution_type->save($this->request->data['document_execution_type'])) {

                            $this->Session->setFlash(__($actionvalue));

                            //$this->Session->setFlash(__('Document Execution Type saved Successful.'));
                            return $this->redirect(array('action' => 'document_execution_type'));
                            $lastid = $this->document_execution_type->getLastInsertId();
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
            if (!is_null($execution_type_id) && is_numeric($execution_type_id)) {
                $this->Session->write('execution_type_id', $execution_type_id);
                $result = $this->document_execution_type->find("first", array('conditions' => array('execution_type_id' => $execution_type_id)));
                // $this->set('local_flag', $result['HolidayType']['local_holiday_flag']);
                if (!empty($result)) {
                    $this->set('editflag', 'Y');
                    $this->request->data['document_execution_type'] = $result['document_execution_type'];
                } else {
                    $this->Session->setFlash(
                            __('lblnotfoundmsg')
                    );
                }
            }
        } catch (exception $ex) {
            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage()));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function delete_document_execution_type($execution_type_id = null) {
        $this->autoRender = false;
        $this->loadModel('document_execution_type');
        try {
            if (isset($execution_type_id) && is_numeric($execution_type_id)) {
                $this->document_execution_type->execution_type_id = $execution_type_id;
                if ($this->document_execution_type->delete($execution_type_id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'document_execution_type'));
                }
            }
        } catch (exception $ex) {
            
        }
    }

    public function usage_main_cat($usage_main_catg_id = NULL) {
        try {
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
            $this->loadModel('Usagemainmain');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->loadModel('language');
            $this->loadModel('mainlanguage');
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
//            $MainCat = $this->Usagemainmain->find('list', array('fields' => array('Usagemainmain.usage_main_catg_id', 'Usagemainmain.usage_main_catg_desc_' . $laug), 'order' => array('usage_main_catg_desc_en' => 'ASC')));
//            $this->set('MainCat', $MainCat);
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

            $this->set('MainCat', NULL);
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $statename = $this->Session->read("state_name_en");

            $MainCat = $this->Usagemainmain->query("select * from ngdrstab_mst_usage_main_category");
            $this->set('MainCat', $MainCat);


            $this->set("fieldlist", $fieldlist = $this->Usagemainmain->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if (!empty($usage_main_catg_id)) {
                $actionvalue = 'lbleditmsg';
            } else {
                $actionvalue = 'lblsavemsg';
            }
            if ($this->request->is('post') || $this->request->is('put')) {

                $this->request->data['usage_main_cat']['ip_address'] = $this->request->clientIp();
                $this->request->data['usage_main_cat']['created_date'] = $created_date;
                $this->request->data['usage_main_cat']['user_id'] = $user_id;
                $verrors = $this->validatedata($this->request->data['usage_main_cat'], $fieldlist);
                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->Usagemainmain->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['usage_main_cat']);
                    if ($checkd) {
                        if ($this->Usagemainmain->save($this->request->data['usage_main_cat'])) {
                            $this->Session->setFlash(__($actionvalue));
                            //$this->Session->setFlash(__('Usage Main Category saved Successful.'));
                            return $this->redirect(array('action' => 'usage_main_cat'));
                            $lastid = $this->Usagemainmain->getLastInsertId();
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
            if (!is_null($usage_main_catg_id) && is_numeric($usage_main_catg_id)) {

                $this->Session->write('usage_main_catg_id', $usage_main_catg_id);
                $result = $this->Usagemainmain->find("first", array('conditions' => array('usage_main_catg_id' => $usage_main_catg_id)));
                if (!empty($result)) {
                    $this->set('editflag', 'Y');
                    $this->request->data['usage_main_cat'] = $result['Usagemainmain'];
                } else {
                    $this->Session->setFlash(
                            __('lblnotfoundmsg')
                    );
                }
            }
        } catch (exception $ex) {

            $this->Session->setFlash(__('Record Cannot be displayed. Error :' . $ex->getMessage()));
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function delete_usage_main_cat($usage_main_catg_id = null) {
        $this->autoRender = false;
        $this->loadModel('Usagemainmain');
        try {

            if (isset($usage_main_catg_id) && is_numeric($usage_main_catg_id)) {
                $this->Usagemainmain->usage_main_catg_id = $usage_main_catg_id;
                if ($this->Usagemainmain->delete($usage_main_catg_id)) {
                    $this->Session->setFlash(
                            __('The Record Has Been Deleted')
                    );
                    return $this->redirect(array('action' => 'usage_main_cat'));
                }
                // }
            }
        } catch (exception $ex) {
            // pr($ex);exit;
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred', $ex->getCode()));
        }
    }

    public function article_doc_map($id = NULL) {
        try {
            array_map(array($this, 'loadModel'), array('article_doc_map', 'NGDRSErrorCode', 'mainlanguage', 'article', 'upload_document'));
            $user_id = $this->Auth->User("user_id");
            $req_ip = $_SERVER['REMOTE_ADDR'];
            $stateid = $this->Auth->User("state_id");
            $created_date = date('Y/m/d');
            $lang = $this->Session->read("sess_langauge");
            $this->set('lang', $lang);
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $this->set('articlelist', ClassRegistry::init('article')->find('list', array('fields' => array('article_id', 'article_desc_' . $lang), 'order' => array('article_desc_' . $lang => 'ASC'))));
            $this->set('documentlist', ClassRegistry::init('upload_document')->find('list', array('fields' => array('document_id', 'document_name_' . $lang), 'order' => array('document_name_' . $lang => 'ASC'))));
            $this->set('actiontypeval', NULL);
            $this->set('hfid', NULL);
            $this->set('hfupdateflag', NULL);

            $arrCategory = array('Y' => "Yes", 'N' => "No");
            $this->set('arrCategory', $arrCategory);

            $articlegrid = $this->article_doc_map->query("select distinct asm.is_required,asm.article_doc_map_id,asm.article_id,asm.document_id,asm.id,a.article_desc_en,mf.document_name_en from ngdrstab_mst_article_document_mapping asm
                                                    inner join ngdrstab_mst_article a on a.article_id=asm.article_id
                                                    inner join ngdrstab_mst_upload_document mf on mf.document_id=asm.document_id");
            $this->set('articlegrid', $articlegrid);

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

            $language = $this->Session->read("sess_langauge");

            $this->set("fieldlist", $fieldlist = $this->article_doc_map->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if (!empty($id)) {
                $actionvalue = 'lbleditmsg';
            } else {
                $actionvalue = 'lblsavemsg';
            }
            if ($this->request->is('post') || $this->request->is('put')) {
//pr($this->request->data['article_doc_map']);
                $this->request->data['article_doc_map']['article_doc_map_id'] = $id;
                $this->request->data['article_doc_map']['req_ip'] = $this->request->clientIp();
                $this->request->data['article_doc_map']['user_id'] = $user_id;
                $this->request->data['article_doc_map']['state_id'] = $stateid;
                $this->request->data['article_doc_map']['created_date'] = $created_date;
                $verrors = $this->validatedata($this->request->data['article_doc_map'], $fieldlist);
                //  pr($verrors);exit;
                if ($this->ValidationError($verrors)) {
                    //pr($this->request->data['article_doc_map']);exit;
                    $duplicate = $this->article_doc_map->get_duplicate($languagelist);
                    //  pr($duplicate);
                    // pr($this->request->data['article_doc_map']);exit;
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['article_doc_map']);

                    if ($checkd) {
//                        pr($this->request->data['article_doc_map']);exit;
                        if ($this->article_doc_map->save($this->request->data['article_doc_map'])) {
                            $this->Session->setFlash(__($actionvalue));
                            // $this->Session->setFlash(__("Record Saved Successfully"));
                            $this->redirect(array('controller' => 'PDEMaster', 'action' => 'article_doc_map'));
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
            if (!is_null($id) && is_numeric($id)) {
                $this->set('editflag', 'Y');
                $this->Session->write('article_doc_map_id', $id);
                $result = $this->article_doc_map->find("first", array('conditions' => array('article_doc_map_id' => $id)));
                //$this->set('result', $result);
                $this->request->data['article_doc_map'] = $result['article_doc_map'];
                if (!empty($result)) {
                    $this->request->data['article_doc_map'] = $result['article_doc_map'];
                    $articlelist = $this->article->find('list', array('fields' => array('article_id', 'article_desc_' . $lang), 'order' => array('article_desc_' . $language => 'ASC')));
                    $this->set('articlelist', $articlelist);

                    $documentlist = $this->upload_document->find('list', array('fields' => array('document_id', 'document_name_' . $lang), 'order' => array('document_name_' . $language => 'ASC')));
                    $this->set('documentlist', $documentlist);

                    $arrCategory = array('Y' => "Yes", 'N' => "No");
                    $this->set('arrCategory', $arrCategory);
                } else {
                    $this->Session->setFlash(
                            __('lblnotfoundmsg')
                    );
                }
            }
        } catch (Exception $ex) {
            pr($ex);
            exit;
//            $this->Session->setFlash(
//                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
//            );
//            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        $this->set_csrf_token();
    }

    public function article_doc_map_delete($id = null) {
        try {
            //pr($id);exit;
            $this->autoRender = false;
            $this->loadModel('article_doc_map');
            if (isset($id) && is_numeric($id)) {
                $this->article_doc_map->id = $id;
                //pr($s);exit;
                if ($this->article_doc_map->delete($id)) {
                    $this->Session->setFlash(
                            __('The Record Has Been Deleted')
                    );
                    return $this->redirect(array('action' => 'article_doc_map'));
                } else {
                    pr('$id');
                    exit;
                }
            }
        } catch (Exception $ex) {
            pr($ex);
            exit;
        }
    }

    public function upload_documententry($document_id = NULL) {
        array_map(array($this, 'loadModel'), array('article_doc_map', 'NGDRSErrorCode', 'mainlanguage', 'article', 'upload_document'));
        $user_id = $this->Auth->User("user_id");
        $req_ip = $_SERVER['REMOTE_ADDR'];
        $stateid = $this->Auth->User("state_id");
        $created_date = date('Y/m/d');
        $lang = $this->Session->read("sess_langauge");
        $this->set('lang', $lang);
        $result_codes = $this->NGDRSErrorCode->find("all");
        $this->set('result_codes', $result_codes);

        //$statename = $this->Session->read("state_name_en");
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
        $arrCategory = array('Y' => "Yes", 'N' => "No");
        $this->set('arrCategory', $arrCategory);

        $articlegrid = $this->upload_document->query("select * from ngdrstab_mst_upload_document");
        $this->set('articlegrid', $articlegrid);

        $this->set("fieldlist", $fieldlist = $this->upload_document->fieldlist($languagelist));
        $this->set('result_codes', $this->getvalidationruleset($fieldlist));
        if (!empty($document_id)) {
            $actionvalue = 'lbleditmsg';
        } else {
            $actionvalue = 'lblsavemsg';
        }

        if ($this->request->is('post') || $this->request->is('put')) {
//$this->request->data['upload_documententry']['document_id'] = $document_id;
            $this->request->data['upload_documententry']['ip_address'] = $this->request->clientIp();
            $this->request->data['upload_documententry']['created_date'] = $created_date;
            $this->request->data['upload_documententry']['user_id'] = $user_id;
            $this->request->data['upload_documententry']['state_id'] = $stateid;
            $verrors = $this->validatedata($this->request->data['upload_documententry'], $fieldlist);
            if ($this->ValidationError($verrors)) {
                $duplicate = $this->upload_document->get_duplicate($languagelist);
                $checkd = $this->check_duplicate($duplicate, $this->request->data['upload_documententry']);
                if ($checkd) {
                    if ($this->upload_document->save($this->request->data['upload_documententry'])) {
                        $this->Session->setFlash(__($actionvalue));
                        //$this->Session->setFlash(__('Upload Document saved Successful.'));
                        return $this->redirect(array('action' => 'upload_documententry'));
                        $lastid = $this->upload_document->getLastInsertId();
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
        if (!is_null($document_id) && is_numeric($document_id)) {
            $this->set('editflag', 'Y');
            $this->Session->write('document_id', $document_id);
            $result = $this->upload_document->find("first", array('conditions' => array('document_id' => $document_id)));
            $this->set('result', $result);
//                pr($result);exit;
//                $this->request->data['upload_documententry'] = $result['upload_document'];
            if (!empty($result)) {
                $this->request->data['upload_documententry'] = $result['upload_document'];
            } else {
                $this->Session->setFlash(
                        __('lblnotfoundmsg')
                );
            }
        }
    }

    public function upload_documententry_delete($document_id = NULL) {
        try {
            //pr($id);exit;
            $this->autoRender = false;
            $this->loadModel('upload_document');
            if (isset($document_id) && is_numeric($document_id)) {
                $this->upload_document->document_id = $document_id;
                //pr($s);exit;
                if ($this->upload_document->delete($document_id)) {
                    $this->Session->setFlash(
                            __('The Record Has Been Deleted')
                    );
                    return $this->redirect(array('action' => 'upload_documententry'));
                }
            }
        } catch (Exception $ex) {
            pr($ex);
            exit;
        }
    }
    
    
    //shrishail
    public function behavioural($behavioral_id=NULL) {
        try {
            $this->check_role_escalation();
            $this->loadModel('State');
            $this->loadModel('User');
            $this->loadModel('Behavioural');
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

            $this->set('behaviouralrecord', $this->Behavioural->find('all'));
            $date = date('Y/m/d H:i:s');
            $created_date = date('Y/m/d');
            $req_ip = $_SERVER['REMOTE_ADDR'];

            $this->set("fieldlist", $fieldlist = $this->Behavioural->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if ($this->request->is('post') || $this->request->is('put')) {
                $this->check_csrf_token($this->request->data['Behavioural']['csrftoken']);

                $verrors = $this->validatedata($this->request->data['Behavioural'], $fieldlist);
                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->Behavioural->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['Behavioural']);
                    if ($checkd) {
                        if ($this->Behavioural->save($this->request->data['Behavioural'])) {
                            $last = $this->Behavioural->getLastInsertId();
                            if ($last) {
                                $this->Session->setFlash(__("lblsavemsg"));
                            } else {
                                $this->Session->setFlash(__("lbleditmsg"));
                            }

                            $this->redirect(array('controller' => 'PDEMaster', 'action' => 'behavioural'));
                        } else {
                            $this->Session->setFlash(__('lblnotsavemsg'));
                        }
                    }else {
                            $this->Session->setFlash(__('lblduplicatemsg'));
                        }
                }else {
                            $this->Session->setFlash(__('Please find Validations'));
                        }
            }
            
             if (!is_null($behavioral_id) && is_numeric($behavioral_id)) { 
                $result = $this->Behavioural->find("first", array('conditions' => array('behavioral_id' => $behavioral_id)));
                if (!empty($result)) {
                    $this->set('editflag', 'Y');
                    $this->request->data['Behavioural'] = $result['Behavioural'];
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
    
    public function delete_behavioural($behavioral_id = NULL) {
        $this->loadModel('Behavioural');
        if (is_numeric($behavioral_id)) {
            if($this->Behavioural->deleteAll(array('behavioral_id' => $behavioral_id)))
            {
                $this->Session->setFlash(
                    __('Record Deleted Sucessfully')
                );
                
            }
            else {
                $this->Session->setFlash(
                    __('Record Not Deleted ')
                );
            }
        }
        
         return $this->redirect(array('controller' => 'PDEMaster', 'action' => 'behavioural'));
        
    }
    
    
    
    //vishal behavioural
       
    public function behaviouraldetails($id = NULL) {
        try {
//            pr($id);exit;
               array_map(array($this, 'loadModel'), array('User','State','NGDRSErrorCode','Usagemainmain','Developedlandtype','BehaviouralDetails', 'mainlanguage'));
         
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
                    )), 'order' => 'conf.language_id ASC'
            ));
            $this->set('languagelist', $languagelist);
             $this->set('actiontypeval', NULL);
//            $this->set('hfactionval', NULL);
            $this->set('usage_flag', NULL);
            $this->set('hfupdateflag', NULL);
            $Behaviourallist = ClassRegistry::init('Behavioural')->find('list', array('fields' => array('Behavioural.behavioral_id', 'Behavioural.behavioral_desc_en'), 'order' => array('behavioral_desc_en' => 'ASC')));
            $this->set('Behaviourallist', $Behaviourallist);

            $behaviouraldetailsrecord = $this->BehaviouralDetails->query("select distinct a.behavioral_details_id,a.behavioral_details_desc_en,a.behavioral_details_desc_ll,a.behavioral_details_desc_ll1 ,a.behavioral_details_desc_ll2,a.behavioral_details_desc_ll3,a.behavioral_details_desc_ll4,a.id,a.behavioral_id,b.behavioral_desc_en from ngdrstab_conf_behavioral_details a
                                                 inner join ngdrstab_conf_behavioral b on b.behavioral_id=a.behavioral_id");
            $this->set('behaviouraldetailsrecord', $behaviouraldetailsrecord);

            
             $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $state = $this->State->find('all', array('conditions' => array('state_id' => $stateid)));
            $this->set('state', $state[0]['State']['state_name_' . $laug]);

             $Developedlandtype = $this->Developedlandtype->find('list', array('fields' => array('developed_land_types_id', 'developed_land_types_desc_' . $laug), 'order' => array('developed_land_types_desc_en' => 'ASC')));
            $this->set('Developedlandtype', $Developedlandtype);
            
               $Usagemainmain = $this->Usagemainmain->find('list', array('fields' => array('usage_main_catg_id', 'usage_main_catg_desc_' . $laug), 'order' => array('usage_main_catg_desc_en' => 'ASC')));
            $this->set('Usagemainmain', $Usagemainmain);
            $created_date = date('Y/m/d');
            $req_ip = $_SERVER['REMOTE_ADDR'];
            $this->request->data['BehaviouralDetails']['req_ip'] = $req_ip;
            $this->request->data['BehaviouralDetails']['user_id'] = $user_id;
            // $this->request->data['BehaviouralDetails']['created_date'] = $created_date;
            $this->request->data['BehaviouralDetails']['state_id'] = $stateid;

            $this->set("fieldlist", $fieldlist = $this->BehaviouralDetails->fieldlist($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            //this array of error is set here to display those correspondent fields error  in the ctp.

            if (!empty($id)) {
                $actionvalue = 'lbleditmsg';
            } else {
                $actionvalue = 'lblsavemsg';
            }

            if ($this->request->is('post') || $this->request->is('put')) {
//                pr($this->request->data);exit;
                $this->check_csrf_token($this->request->data['BehaviouralDetails']['csrftoken']);

                $verrors = $this->validatedata($this->request->data['BehaviouralDetails'], $fieldlist);
                //pr($verrors);exit;
                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->BehaviouralDetails->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['BehaviouralDetails']);
                   
                    if ($checkd) {
                         $this->request->data['BehaviouralDetails']['state_id'] = $stateid;

                        if ($this->BehaviouralDetails->save($this->request->data['BehaviouralDetails'])) {
                            $this->Session->setFlash(__($actionvalue));
                            $this->redirect(array('controller' => 'PDEMaster', 'action' => 'behaviouraldetails'));
                             $this->set('behaviouraldetailsrecord', $this->BehaviouralDetails->find('all'));
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

            if (!is_null($id) && is_numeric($id)) {
                $this->set('editflag', 'Y');
                $this->Session->write('behavioral_details_id', $id);
                $result = $this->BehaviouralDetails->find("first", array('conditions' => array('behavioral_details_id' => $id)));
                $this->set('result', $result);
//                pr($result);exit;
//                $this->request->data['upload_documententry'] = $result['fee_type'];
                if (!empty($result)) {
                    $this->request->data['BehaviouralDetails'] = $result['BehaviouralDetails'];
                    if($result['BehaviouralDetails']['usage_flag']=='Y'){
                     $this->set('usage_flag', 'Y');
                      //$this->set('Developedlandtype', $result['BehaviouralDetails']['main_usage_id']);
                    }else{
                         $this->set('usage_flag','N');
                    }
                } else {
                    $this->Session->setFlash(__('lblnotfoundmsg'));
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
   
    public function delete_behavioural_details($id = null) {
        try {

            $this->autoRender = false;
            $this->loadModel('BehaviouralDetails');

            if (isset($id) && is_numeric($id)) {

                $this->BehaviouralDetails->behavioral_details_id = $id;

                if ($this->BehaviouralDetails->delete($id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'behaviouraldetails'));
                }
            }
        } catch (Exception $ex) {
            
        }
    } 
    
        public function behaviouralpattern($id = NULL) {
        try {

            array_map(array($this, 'loadModel'), array('User', 'State', 'NGDRSErrorCode', 'BehavioralPattens', 'BehaviouralDetails', 'mainlanguage'));

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
                    ))
                , 'order' => 'conf.language_id ASC'
            ));
            $this->set('languagelist', $languagelist);
            $this->set('actiontypeval', NULL);
//            $this->set('hfactionval', NULL);
            $this->set('usage_flag', NULL);
            $this->set('hfupdateflag', NULL);

            $Is_required = array('Y' => "Yes", 'N' => "No");
            $this->set('Is_required', $Is_required);
            $vrule = array('P' => "Pincode", 'N' => "Numeric", 'S' => "String");
            $this->set('vrule', $vrule);
            $Behaviourallist = ClassRegistry::init('Behavioural')->find('list', array('fields' => array('Behavioural.behavioral_id', 'Behavioural.behavioral_desc_en'), 'order' => array('behavioral_desc_en' => 'ASC')));
            $this->set('Behaviourallist', $Behaviourallist);

//            $behaviouraldetailsrecord = $this->BehaviouralDetails->query("select distinct a.behavioral_details_id,a.behavioral_details_desc_en,a.behavioral_details_desc_ll,a.behavioral_details_desc_ll1 ,a.behavioral_details_desc_ll2,a.behavioral_details_desc_ll3,a.behavioral_details_desc_ll4,a.id,a.behavioral_id,b.behavioral_desc_en from ngdrstab_conf_behavioral_details a
//                                                 inner join ngdrstab_conf_behavioral b on b.behavioral_id=a.behavioral_id");
//            $this->set('behaviouraldetailsrecord', $behaviouraldetailsrecord);
            $BehaviouralDetails = ClassRegistry::init('BehaviouralDetails')->find('list', array('fields' => array('BehaviouralDetails.behavioral_details_id', 'BehaviouralDetails.behavioral_details_desc_en'), 'order' => array('behavioral_details_desc_en' => 'ASC')));
            $this->set('Behaviouraldetailsdata', $BehaviouralDetails);

            $behaviouralpattenrecord = $this->BehavioralPattens->query("select distinct a.pattern_id,a.behavioral_details_id, a.id,a.behavioral_id, a.pattern_desc_en,a.pattern_desc_ll, a.pattern_desc_ll1,a.pattern_desc_ll2,a.pattern_desc_ll3,a.pattern_desc_ll4, c.behavioral_details_desc_en, b.behavioral_desc_en from ngdrstab_conf_behavioral_patterns a
                                                 inner join ngdrstab_conf_behavioral b on b.behavioral_id=a.behavioral_id
                                                 inner join ngdrstab_conf_behavioral_details c on c.behavioral_details_id=a.behavioral_details_id");
//            pr($behaviouralpattenrecord);exit;
            $this->set('behaviouralpattenrecord', $behaviouralpattenrecord);
            $user_id = $this->Auth->User("user_id");
            $stateid = $this->Auth->User("state_id");
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);
            $state = $this->State->find('all', array('conditions' => array('state_id' => $stateid)));
            $this->set('state', $state[0]['State']['state_name_' . $laug]);

            $created_date = date('Y/m/d');
            $req_ip = $_SERVER['REMOTE_ADDR'];
            $this->request->data['behaviouralpattern']['req_ip'] = $req_ip;
            $this->request->data['behaviouralpattern']['user_id'] = $user_id;
            // $this->request->data['BehaviouralDetails']['created_date'] = $created_date;
            $this->request->data['behaviouralpattern']['state_id'] = $stateid;

            $this->set("fieldlist", $fieldlist = $this->BehavioralPattens->fieldlistmaster($languagelist));
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            //this array of error is set here to display those correspondent fields error  in the ctp.

            if (!empty($id)) {
                $actionvalue = 'lbleditmsg';
            } else {
                $actionvalue = 'lblsavemsg';
            }
//pr($this->request->data);exit;
            if ($this->request->is('post') || $this->request->is('put')) {
//pr($this->request->data);exit;
//                $this->check_csrf_token($this->request->data['BehavioralPattens']['csrftoken']);
                

                $verrors = $this->validatedata($this->request->data['behaviouralpattern'], $fieldlist);
//                pr($verrors);exit;
                if ($this->ValidationError($verrors)) {
                    $duplicate = $this->BehavioralPattens->get_duplicate($languagelist);
                    $checkd = $this->check_duplicate($duplicate, $this->request->data['behaviouralpattern']);

                    if ($checkd) {
                        //validation  vrule
                if ($this->request->data['behaviouralpattern']['is_required'] == 'Y') {
                    $required = 'is_required';
                    $rule = '';
                    if ($this->request->data['behaviouralpattern']['vrule_en'] == 'P') {
                        $rule = 'is_pincode';
                        $this->request->data['behaviouralpattern']['vrule'] = 'P';
                    } else if ($this->request->data['behaviouralpattern']['vrule_en'] == 'N') {
                        $rule = 'is_digit';
                         $this->request->data['behaviouralpattern']['vrule'] = 'N';
                    } else if ($this->request->data['behaviouralpattern']['vrule_en'] == 'S') {
                        $rule = 'is_alphaspace';
                         $this->request->data['behaviouralpattern']['vrule'] = 'S';
                    }
                    $this->request->data['behaviouralpattern']['vrule_en'] = $required . ',' . $rule;
                } else {
                     $rule = '';
                    if ($this->request->data['behaviouralpattern']['vrule_en'] == 'P') {
                        $rule = 'is_pincode';
                         $this->request->data['behaviouralpattern']['vrule'] = 'P';
                    } else if ($this->request->data['behaviouralpattern']['vrule_en'] == 'N') {
                        $rule = 'is_digit';
                         $this->request->data['behaviouralpattern']['vrule'] = 'N';
                    } else if ($this->request->data['behaviouralpattern']['vrule_en'] == 'S') {
                        $rule = 'is_alphaspace';
                         $this->request->data['behaviouralpattern']['vrule'] = 'S';
                    }
                    $this->request->data['behaviouralpattern']['vrule_en'] = $rule;
                }

                        if ($this->BehavioralPattens->save($this->request->data['behaviouralpattern'])) {
                            $this->Session->setFlash(__($actionvalue));
                            $this->redirect(array('controller' => 'PDEMaster', 'action' => 'behaviouralpattern'));
                            $this->set('behaviouralpattenrecord', $this->BehavioralPattens->find('all'));
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

            if (!is_null($id) && is_numeric($id)) {
                $this->set('editflag', 'Y');
                $this->Session->write('pattern_id', $id);
                $result = $this->BehavioralPattens->find("first", array('conditions' => array('pattern_id' => $id)));
                $this->set('result', $result);
//                 pr($result);exit;
//                $this->request->data['upload_documententry'] = $result['fee_type'];
                if (!empty($result)) {
                   
                      $this->request->data['behaviouralpattern'] = $result['BehavioralPattens'];
                    
                    $Is_required = array('Y' => "Yes", 'N' => "No");
            $this->set('Is_required', $Is_required);
           
              //validation  vrule
             if ($this->request->data['behaviouralpattern']['vrule'] == 'P') {
                      
                        $this->request->data['behaviouralpattern']['vrule_en'] = 'P';
                    } else if ($this->request->data['behaviouralpattern']['vrule'] == 'N') {
                       
                         $this->request->data['behaviouralpattern']['vrule_en'] = 'N';
                    } else if ($this->request->data['behaviouralpattern']['vrule'] == 'S') {
                        
                         $this->request->data['behaviouralpattern']['vrule_en'] = 'S';
                    }
                  $vrule = array('P' => "Pinecode", 'N' => "Numeric", 'S' => "String");
            $this->set('vrule', $vrule);
               
           
                } else {
                    $this->Session->setFlash(__('lblnotfoundmsg'));
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

    public function delete_behavioural_pattens($id = null) {
        try {

            $this->autoRender = false;
            $this->loadModel('BehavioralPattens');

            if (isset($id) && is_numeric($id)) {

                $this->BehavioralPattens->pattern_id = $id;

                if ($this->BehavioralPattens->delete($id)) {
                    $this->Session->setFlash(
                            __('lbldeletemsg')
                    );
                    return $this->redirect(array('action' => 'behaviouralpattern'));
                }
            }
        } catch (Exception $ex) {
            
        }
    }
//end
}
