<?php

class FunctionsController extends AppController {

//--------------------------------Based on Village Mapping ------------------------ 
    //---------------------------------------------------Division-----------------------------------------------------------------
    function getdivisionlist() {
        try {
            $this->loadModel('damblkdpnd');
            if (isset($this->request->data['state_id'])) {
                $stateId = $this->request->data['state_id'];
                $ids = ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('division_id'), 'conditions' => array('state_id' => $stateId)));
                $list = ClassRegistry::init('division')->find('list', array('fields' => array('id', 'division_name_' . $this->Session->read("sess_langauge")), 'conditions' => array('id' => $ids)));
                echo json_encode($list);
                exit;
            } else {
                echo "State Id Missing";
                exit;
            }
        } catch (Exception $e) {
            pr($e);
            exit;
        }
    }

    //---------------------------------------------------District-----------------------------------------------------------------
    function getdistrictlist() {
        try {
            $this->loadModel('damblkdpnd');
            if (isset($this->request->data['state_id']) and isset($this->request->data['division_id'])) {
                $stateId = $this->request->data['state_id'];
                $divId = $this->request->data['division_id'];
                $conditions = "";
                if ($stateId) {
                    $conditions['state_id'] = $stateId;
                }
                if ($divId) {
                    $conditions['division_id'] = $divId;
                }
                $ids = ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('state_id'), 'conditions' => $conditions));
                $list = ClassRegistry::init('District')->find('list', array('fields' => array('id', 'district_name_' . $this->Session->read("sess_langauge")), 'conditions' => array('id' => $ids)));
                echo json_encode($list);
                exit;
            } else {
                echo "StateId Or DivisionId is Missing";
                exit;
            }
        } catch (Exception $e) {
            pr($e);
            exit;
        }
    }

    //--------------------------------------------------Sub Division--------------------------------------------------------------
    function getsubdivisionlist() {
        try {
            $this->loadModel('damblkdpnd');
            if (isset($this->request->data['state_id']) and isset($this->request->data['division_id']) and isset($this->request->data['state_id'])) {
                $stateId = $this->request->data['state_id'];
                $divId = $this->request->data['division_id'];
                $distId = $this->request->data['state_id'];

                $conditions = "";
                if ($stateId) {
                    $conditions['state_id'] = $stateId;
                }
                if ($divId) {
                    $conditions['division_id'] = $divId;
                }
                if ($distId) {
                    $conditions['state_id'] = $distId;
                }

                $ids = ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('subdivision_id'), 'conditions' => $conditions));
                $list = ClassRegistry::init('Subdivision')->find('list', array('fields' => array('id', 'subdivision_name_' . $this->Session->read("sess_langauge")), 'conditions' => array('id' => $ids)));
                echo json_encode($list);
                exit;
            } else {
                echo "StateId,DivisionId or DistrictId is Missing";
                exit;
            }
        } catch (Exception $e) {
            pr($e);
            exit;
        }
    }

    //--------------------------------------------------Taluka------------------------------------------------------------------------
    function gettalukalist() {
        try {
            $this->loadModel('damblkdpnd');
            if (isset($this->request->data['state_id']) and isset($this->request->data['division_id']) and isset($this->request->data['state_id'])and isset($this->request->data['subdivision_id'])) {
                $stateId = $this->request->data['state_id'];
                $divId = $this->request->data['division_id'];
                $distId = $this->request->data['state_id'];
                $subdivId = $this->request->data['subdivision_id'];

                $conditions = "";
                if ($stateId) {
                    $conditions['state_id'] = $stateId;
                }
                if ($divId) {
                    $conditions['division_id'] = $divId;
                }
                if ($distId) {
                    $conditions['state_id'] = $distId;
                }
                if ($subdivId) {
                    $conditions['subdivision_id'] = $subdivId;
                }

                $ids = ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('taluka_id'), 'conditions' => $conditions));
                $list = ClassRegistry::init('taluka')->find('list', array('fields' => array('id', 'taluka_name_' . $this->Session->read("sess_langauge")), 'conditions' => array('id' => $ids)));
                echo json_encode($list);
                exit;
            } else {
                echo "StateId,DivisionId or DistrictId is Missing";
                exit;
            }
        } catch (Exception $e) {
            pr($e);
            exit;
        }
    }

//--------------------------------------------------Land Type--------------------------------------------------------------------
    public function getlandtype() {
        try {
            $this->loadModel('damblkdpnd');
            $stateid = $this->Auth->User("state_id");
            if (isset($this->request->data['tal'])) {
                $tal = $this->request->data['tal'];
                $landtypelist = ClassRegistry::init('Developedlandtype')->find('list', array('fields' => array('Developedlandtype.id', 'Developedlandtype.developed_land_types_desc_' . $this->Session->read("sess_langauge")), 'conditions' => array('id' => ClassRegistry::init('villagemapping')->find('list', array('fields' => array('villagemapping.developed_land_types_id'), 'conditions' => array('taluka_id' => $tal))))));
                echo json_encode($landtypelist);
                exit;
            } else {
                echo "No Record found";
                exit;
            }
        } catch (Exception $e) {
            pr($e);
            exit;
            $this->redirect(array('action' => 'error404'));
        }
    }

    //--------------------------------------------------Circle------------------------------------------------------------------------
    function getcirclelist() {
        try {
            $this->loadModel('damblkdpnd');
            if (isset($this->request->data['state_id']) and isset($this->request->data['division_id']) and isset($this->request->data['state_id']) and isset($this->request->data['subdivision_id']) and isset($this->request->data['taluka_id'])) {
                $stateId = $this->request->data['state_id'];
                $divId = $this->request->data['division_id'];
                $distId = $this->request->data['state_id'];
                $subdivId = $this->request->data['subdivision_id'];
                $talId = $this->request->data['taluka_id'];

                $conditions = "";
                if ($stateId) {
                    $conditions['state_id'] = $stateId;
                }
                if ($divId) {
                    $conditions['division_id'] = $divId;
                }
                if ($distId) {
                    $conditions['state_id'] = $distId;
                }
                if ($subdivId) {
                    $conditions['subdivision_id'] = $subdivId;
                }
                if ($talId) {
                    $conditions['taluka_id'] = $talId;
                }

                $ids = ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('circle_id'), 'conditions' => $conditions));
                $list = ClassRegistry::init('circle')->find('list', array('fields' => array('id', 'circle_name_' . $this->Session->read("sess_langauge")), 'conditions' => array('id' => $ids)));
                echo json_encode($list);
                exit;
            } else {
                echo "StateId,DivisionId or DistrictId is Missing";
                exit;
            }
        } catch (Exception $e) {
            pr($e);
            exit;
        }
    }

    //--------------------------------------------------Circle------------------------------------------------------------------------
    function getvillagelist() {
        try {
            $this->loadModel('damblkdpnd');
            if (isset($this->request->data['state_id']) and isset($this->request->data['division_id']) and isset($this->request->data['state_id']) and isset($this->request->data['subdivision_id']) and isset($this->request->data['taluka_id']) and isset($this->request->data['circle_id'])) {
                $stateId = $this->request->data['state_id'];
                $divId = $this->request->data['division_id'];
                $distId = $this->request->data['state_id'];
                $subdivId = $this->request->data['subdivision_id'];
                $talId = $this->request->data['taluka_id'];
                $circleId = $this->request->data['circle_id'];
                $conditions = "";
                if ($stateId) {
                    $conditions['state_id'] = $stateId;
                }
                if ($divId) {
                    $conditions['division_id'] = $divId;
                }
                if ($distId) {
                    $conditions['state_id'] = $distId;
                }
                if ($subdivId) {
                    $conditions['subdivision_id'] = $subdivId;
                }
                if ($talId) {
                    $conditions['taluka_id'] = $talId;
                }
                if ($circleId) {
                    $conditions['circle_id'] = $circleId;
                }

                $ids = ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('village_id'), 'conditions' => $conditions));
                $list = ClassRegistry::init('villagemapping')->find('list', array('fields' => array('id', 'village_name_' . $this->Session->read("sess_langauge")), 'conditions' => array('id' => $ids)));
                echo json_encode($list);
                exit;
            } else {
                echo "StateId,DivisionId or DistrictId is Missing";
                exit;
            }
        } catch (Exception $e) {
            pr($e);
            exit;
        }
    }

    //---------------------------------------------------item type-----------------------------------------------------------------
    function getitemtype() {
        try {
            if (isset($this->request->data['itemid'])) {
                $itemId = $this->request->data['itemid'];
                $itpid = ClassRegistry::init('usagelnkitemlist')->find('all', array('fields' => array('usage_param_type_id'), 'conditions' => array('usage_param_id' => $itemId)));
                echo json_encode($itpid[0]['usagelnkitemlist']['usage_param_type_id']);
                exit;
            } else {
                echo "Item Not Found";
                exit;
            }
        } catch (Exception $e) {
            pr($e);
            exit;
        }
    }

    //---------------------------------------------------getsubruleDetails All Formula and Conditions -----------------------------------------------------------------
    function getsubrule() {
        try {
            if (isset($this->request->data['ruleid']) && isset($this->request->data['subruleid'])) {
                $condition['evalrule_id'] = $this->request->data['ruleid'];
                if ($this->request->data['subruleid'] != '0') {
                    $condition['subrule_id'] = $this->request->data['subruleid'];
                    $itpid = ClassRegistry::init('subrule')->find('all', array('conditions' => $condition));
                    echo json_encode($itpid[0]['subrule']);
                    exit;
                } else {
                    //$itpid = ClassRegistry::init('evalsubrule')->find('all', array('fields' => array('evalrule_id', 'subrule_id', 'evalsubrule_cond1', 'evalsubrule_formula1', 'evalsubrule_cond2', 'evalsubrule_formula2', 'max_value_condition_flag', 'max_value_formula', 'output_item_id'), 'conditions' => $condition));
                    $itpid = ClassRegistry::init('subrule')->Query("select evalrule_id, subrule_id,rv.road_vicinity_desc_en, evalsubrule_cond1, evalsubrule_formula1, evalsubrule_cond2, evalsubrule_formula2,evalsubrule_cond3,evalsubrule_formula3,evalsubrule_cond4,evalsubrule_formula4,evalsubrule_cond5,evalsubrule_formula5,rate_revision_flag,rate_revision_formula1,rate_revision_formula2,rate_revision_formula3,rate_revision_formula4,rate_revision_formula5, max_value_condition_flag, max_value_formula, output_item_id,iL.usage_param_desc_en,iL.usage_param_desc_ll,out_item_order
                        from ngdrstab_mst_evalsubrule sbr
                        left outer join ngdrstab_mst_usage_items_list iL on iL.usage_param_id=output_item_id
                        left outer join ngdrstab_mst_road_vicinity rv on rv.road_vicinity_id=sbr.road_vicinity_id
                        where evalrule_id=? order by out_item_order", array($this->request->data['ruleid']));
                    echo json_encode($itpid);
                    exit;
                }
            } else {
                echo "Item Not Found";
                exit;
            }
        } catch (Exception $e) {
            pr($e);
            exit;
        }
    }

//----------------------------------------------------------Get Linked Input Item List----------------------------------------------------------
    public function getLinkedInputItemList() {
        try {
            if (isset($this->request->data['mcat_id']) && isset($this->request->data['scat_id']) && isset($this->request->data['sscat_id'])) {
                $condition = "";
                if ($this->request->data['mcat_id']) {
                    $condition.='this.usage_main_catg_id=' . $this->request->data['mcat_id'];
                }
                if ($this->request->data['scat_id']) {
                    $condition.='and this.usage_sub_catg_id=' . $this->request->data['scat_id'];
                }
                if ($this->request->data['sscat_id']) {
                    $condition.='and this.usage_sub_sub_catg_id= ?' . $this->request->data['sscat_id'];
                }

                $itpid = ClassRegistry::init('usagelinkcategory')->Query("Select this.usage_lnk_id,this.usage_main_catg_id,this.usage_sub_catg_id,this.usage_sub_sub_catg_id,this.usage_param_id,this.uasge_param_code,this.state_id,this.id,this.evalrule_id,this.range_field_flag,this.construction_type_id,this.depreciation_id,this.road_vicinity_id,this.user_defined_dependency1_id,this.user_defined_dependency2_id,this.item_rate_flag 
                ,m.usage_main_catg_desc_en,m.usage_main_catg_desc_ll,
                s.usage_sub_catg_desc_ll,s.usage_sub_catg_desc_en,
                ss.usage_sub_sub_catg_desc_ll,ss.usage_sub_sub_catg_desc_en,
                this.main_cat_id,this.sub_cat_id,this.sub_sub_cat_id,
                i.usage_param_desc_en,i.usage_param_desc_ll
                from ngdrstab_mst_usage_lnk_category this
                left outer join ngdrstab_mst_usage_main_category m on m.usage_main_catg_id=this.usage_main_catg_id
                left outer join ngdrstab_mst_usage_sub_category s on s.usage_sub_catg_id=this.usage_sub_catg_id
                left outer join ngdrstab_mst_usage_sub_sub_category ss on ss.usage_sub_sub_catg_id=this.usage_sub_sub_catg_id
                left outer join ngdrstab_mst_usage_items_list i on i.usage_param_id=this.usage_param_id
                where $condition
                order by 1,2,3 desc");
                echo json_encode($itpid);
                exit;
            } else {
                echo "Item Not Found";
                exit;
            }
        } catch (Exception $e) {
            pr($e);
            exit;
        }
    }
	
	////////------------------------------- For Survey No form -------------------------------------
    function getvillagelist_surveyno() {
       $this->loadModel('damblkdpnd');
            try {
            if (isset($_GET['taluka_id'])) {
                $taluka = $_GET['taluka_id'];
                $villagename = ClassRegistry::init('damblkdpnd')->find('list', array('fields' => array('village_id', 'village_name_en'), 'conditions' => array('taluka_id' => array($taluka))));
                echo json_encode($villagename);
                exit;
            } else {
                header('Location:../cterror.html');
                exit;
            }
        } catch (Exception $e) {
            print_r($e);
            $this->redirect(array('action' => 'error404'));
        }
            
    }

}
