<?php

App::uses('Cache', 'Cache');
App::uses('Sanitize', 'Utility');

class AuditController extends AppController {

    //put your code here
    public $components = array('Security', 'RequestHandler', 'Captcha', 'Cookie');
    public $helpers = array('Js', 'Html', 'Form', 'Paginator');

    public function beforeFilter() {
        $this->loadModel('language');
        $langaugelist = $this->language->find('all', array('conditions' => array('state_id' => array(0, $this->Auth->user('state_id')))));
        $this->set('langaugelist', $langaugelist);
        $this->Security->unlockedActions = array('auditscreen', 'get_column', 'display_table', 'display_currenttable');
        //  $this->Auth->allow('auditscreen', 'get_column', 'display_table', 'display_currenttable');
        if (isset($this->Security)) { //&& isset($this->Auth)) {
            $this->Security->validatePost = false;
            $this->Security->enabled = false;
            $this->Security->csrfCheck = false;
        }
    }

    function auditscreen() {
        try {
            array_map(array($this, 'loadModel'), array('language', 'tablelist'));
            $lang = $this->Session->read("sess_langauge");
            $langaugelist = $this->language->find('all', array('conditions' => array('state_id' => array(0, $this->Auth->user('state_id')))));
            $tablelist = $this->tablelist->find('list', array('fields' => array('table_id', 'display_name_' . $lang), 'order' => array('display_name_' . $lang => 'ASC')));
            $this->set('tablelist', $tablelist);
            $this->set_csrf_token();
             $fieldlist = array();
            $fieldlist['table_id']['select'] = 'is_select_req';
            $fieldlist['column_name']['checkbox'] = 'is_select_req';
            $fieldlist['from']['text'] = 'is_required';
            $fieldlist['to']['text'] = 'is_required';
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
            
        } catch (Exception $e) {
           
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $e->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function get_column() {
        try {
            array_map(array($this, 'loadModel'), array('language', 'tablelist', 'tablenamepdf'));
            $this->check_csrf_token_withoutset($_POST['csrftoken']);
            if (isset($_POST['table_id']) && isset($_POST['token'])) {
                $table_id = $_POST['table_id'];
                $table = $this->tablelist->find('all', array('conditions' => array('table_id' => array(0, $table_id))));
                if (!empty($table)) {
                    $columnNames = $this->tablelist->getcolumns($table[0]['tablelist']['table_name']);
                    $a = array();
                    for ($i = 0; $i < count($columnNames); $i++) {
                        $a[$columnNames[$i][0]['column_name']] = $columnNames[$i][0]['column_comment'];
                    }

                    $this->set('columnNames', $a);
                    $this->set('audittablename', $table[0]['tablelist']['audit_table_name']);
                    $this->set('tablename', $table[0]['tablelist']['table_name']);
                    $this->set('token_flag', $_POST['token']);
                    $this->set('table_id', $_POST['table_id']);
                }
            }
        } catch (Exception $ex) {
          
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function display_table() {
        try {
 $this->check_csrf_token_withoutset($_POST['csrftoken']);
            array_map(array($this, 'loadModel'), array('language', 'tablelist', 'tablenamepdf'));
            if (isset($_POST['columnlist']) && isset($_POST['audittable']) && isset($_POST['token_no']) && isset($_POST['from']) && isset($_POST['to'])) {
                if ($_POST['token_no'] != '') {
                    if (!is_numeric($_POST['token_no'])) {
                       echo 'n';
                       exit;
                    }
                }

                $columnlist = $_POST['columnlist'];

                $arr = (explode(",", $columnlist));

                $sliced = array_slice($arr, 0, -1); // array ( "Hello", "World" )
                if (empty($sliced)) {
                    echo 'em';
                    exit;
                }

                $column = implode(",", $sliced);

                $record = $this->tablelist->get_record($column, $_POST['audittable'], $_POST['from'], $_POST['to'], $_POST['token_no']);
                $cnt1 = count($sliced);
                $sliced[$cnt1] = 'old_userid';
                $sliced[$cnt1 + 1] = 'new_userid';
                array_splice($sliced, 0, 0, 'updated_date');
                $this->set('colrec', $sliced);
                $this->set('record', $record);
                $this->set('current', 'N');
                $lable = $_POST['lable'];

                $cnt = count($lable);
                $lable[$cnt - 1] = 'Old User';
                $lable[$cnt + 1] = 'New User';
                array_splice($lable, 0, 0, 'Record Change Date');

                $this->set('column', $lable);
            } else {
                echo 'error';
                exit;
            }
        } catch (Exception $ex) {
            pr($ex);
            exit;
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function display_currenttable() {
        try {
             $this->check_csrf_token_withoutset($_POST['csrftoken']);
            array_map(array($this, 'loadModel'), array('language', 'tablelist', 'tablenamepdf'));
            if (isset($_POST['columnlist']) && isset($_POST['tablename']) && isset($_POST['token_no']) && isset($_POST['from']) && isset($_POST['to'])) {

                if ($_POST['token_no'] != '') {
                    if (!is_numeric($_POST['token_no'])) {
                       echo 'n';
                       exit;
                    }
                }
                $columnlist = $_POST['columnlist'];
                $arr = (explode(",", $columnlist));

                $sliced = array_slice($arr, 0, -1); // array ( "Hello", "World" )
                if (empty($sliced)) {
                    echo 'em';
                    exit;
                }
                $column = implode(",", $sliced);
                $record = $this->tablelist->get_curentrecord($column, $_POST['tablename'], $_POST['from'], $_POST['to'], $_POST['token_no']);
                $cnt1 = count($sliced);
                $this->set('colrec', $sliced);
                $this->set('record', $record);
                $this->set('current', 'Y');
                $lable = $_POST['lable'];
                $lable = array_slice($lable, 0, -1);


                $this->set('column', $lable);
            } else {
                echo 'error';
                exit;
            }
        } catch (Exception $e) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $e->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function document_audit() {
        try {
            array_map(array($this, 'loadModel'), array('language', 'tablelist'));
            $lang = $this->Session->read("sess_langauge");
            $langaugelist = $this->language->find('all', array('conditions' => array('state_id' => array(0, $this->Auth->user('state_id')))));
            $tablelist = $this->tablelist->find('list', array('fields' => array('table_id', 'display_name_' . $lang), 'conditions' => array('document_entry_flag' => 'Y'), 'order' => array('display_name_' . $lang => 'ASC')));
            $this->set('tablelist', $tablelist);
             $this->set_csrf_token();
            $fieldlist = array();
            $fieldlist['table_id']['select'] = 'is_select_req';
            $fieldlist['token_audit']['text'] = 'is_required';
            $fieldlist['token_curr']['text'] = 'is_required';
            $fieldlist['column_name']['select'] = 'is_select_req';
            $fieldlist['from']['text'] = 'is_required';
            $fieldlist['to']['text'] = 'is_required';
             $fieldlist['curfrom']['text'] = 'is_required';
            $fieldlist['curto']['text'] = 'is_required';
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));
             
            
        } catch (Exception $e) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $exc->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

}
