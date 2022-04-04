<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of articletrnfields
 *
 * @author nic
 */
class articletrnfields extends AppModel {

    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_articledepfields';

    function get_articledependent_feild($lang,$article, $token = null) {
        if($article=='')
        {
            $article=68;
        }
        if ($token == null ) {
            $feild = $this->query(" select Distinct a.fee_param_code,b.articledepfield_id,b.articledepfield_value ,c.fee_item_desc_$lang,c.list_flag,c.fee_item_id,c.display_order,c.is_date
from ngdrstab_conf_article_feerule_items a
 left outer join ngdrstab_mst_article_fee_items c on c.fee_param_code=a.fee_param_code 
 left outer join ngdrstab_trn_articledepfields b on a.fee_param_code=b.articledepfield_id and b.token_no is NULL where a.level1_flag='Y' and a.article_id=? and c.gen_dis_flag=? ORDER BY c.display_order ASC", array($article, 'Y'));
        } else {
            $feild = $this->query("select Distinct a.fee_param_code,b.articledepfield_id,b.articledepfield_value ,c.fee_item_desc_$lang,c.list_flag,c.fee_item_id,c.display_order,c.is_date
from ngdrstab_conf_article_feerule_items a
 left outer join ngdrstab_mst_article_fee_items c on c.fee_param_code=a.fee_param_code 
 left outer join ngdrstab_trn_articledepfields b on a.fee_param_code=b.articledepfield_id and b.token_no=? where a.level1_flag='Y' and a.article_id=? and c.gen_dis_flag=? ORDER BY c.display_order ASC", array($token, $article, 'Y'));
        }
      
        return $feild;
    }
    
     function get_articledependent_feild_level1($lang,$article, $token = null,$code,$type) {
        if($article=='')
        {
            $article=68;
        }
        $id=$this->query(" select l.id from ngdrstab_conf_article_fee_items_list l,ngdrstab_conf_article_feerule_items a where l.fee_item_id=a.fee_item_id  and a.fee_param_code=? and l.fee_item_list_id=?",array($code,$type));
 
        if(!empty($id)){
        if ($token == null ) {
            $feild = $this->query(" select Distinct a.fee_param_code,b.articledepfield_id,b.articledepfield_value ,c.fee_item_desc_$lang,c.list_flag,c.fee_item_id,a.display_order,c.is_date,a.separate_table_flag,a.readonly_flag
from ngdrstab_conf_article_feerule_items a
 left outer join ngdrstab_mst_article_fee_items c on c.fee_param_code=a.fee_param_code 
 left outer join ngdrstab_trn_articledepfields b on a.fee_param_code=b.articledepfield_id and b.token_no is NULL where a.level2_flag='Y' and a.levellist_id=? and a.article_id=? and c.gen_dis_flag=? ORDER BY a.display_order ASC", array($id[0][0]['id'],$article, 'Y'));
        } else {
            $feild = $this->query("select Distinct a.fee_param_code,b.articledepfield_id,b.articledepfield_value ,c.fee_item_desc_$lang,c.list_flag,c.fee_item_id,a.display_order,c.is_date,a.separate_table_flag,a.readonly_flag
from ngdrstab_conf_article_feerule_items a
 left outer join ngdrstab_mst_article_fee_items c on c.fee_param_code=a.fee_param_code 
 left outer join ngdrstab_trn_articledepfields b on a.fee_param_code=b.articledepfield_id and b.token_no=? where a.level2_flag='Y' and a.levellist_id=? and a.article_id=? and c.gen_dis_flag=? ORDER BY a.display_order ASC", array($token,$id[0][0]['id'], $article, 'Y'));
        }
      
        return $feild;
   }else{
       return TRUE;
   }
    }

     function savedependent_field($lang,$formdata, $token, $fields,$user_type) {
        $field = ClassRegistry::init('conf_article_feerule_items')->find('all', array('fields' => array('DISTINCT conf_article_feerule_items.fee_param_code', 'item.fee_item_desc_'.$lang,'item.list_flag'),
            'conditions' => array('conf_article_feerule_items.article_id' => $formdata['article_id'], 'item.gen_dis_flag' => 'Y'), 'order' => 'item.fee_item_desc_'.$lang,
            'joins' => array(array('table' => 'ngdrstab_mst_article_fee_items', 'alias' => 'item',
                    'conditions' => array('item.fee_item_id=conf_article_feerule_items.fee_item_id')))));
      

        if (!empty($field)) {

          
             $c = 0;
            $data = array();
            for ($i = 0; $i < count($field); $i++) {
              if(isset($formdata['fieldval_' . $field[$i]['conf_article_feerule_items']['fee_param_code']])){
                $data[$i] = array('token_no' => $token,
                    'article_id' => $formdata['article_id'],
                    'articledepfield_id' => $field[$i]['conf_article_feerule_items']['fee_param_code'],
                    'articledepfield_value' => $formdata['fieldval_' . $field[$i]['conf_article_feerule_items']['fee_param_code']],
                    'state_id' => $fields['stateid'],
                    'req_ip' => $fields['ip'],
                    'user_type' => $user_type,
                    'user_id' => $fields['user_id']);
                if ($formdata['fieldval_' . $field[$i]['conf_article_feerule_items']['fee_param_code']] != NULL) {
                    if($field[$i]['item']['list_flag']=='Y'){

                    $prop_app = $this->query("select  DISTINCT(i.fee_param_code),property_applicable,property_check_flag from ngdrstab_conf_article_fee_items_list l ,ngdrstab_conf_article_feerule_items i
                              where i.fee_item_id= l.fee_item_id and i.fee_param_code='" . $field[$i]['conf_article_feerule_items']['fee_param_code'] . "' and l.list_item_value='" . $formdata['fieldval_' . $field[$i]['conf_article_feerule_items']['fee_param_code']] . "'");
                  
				   if (!empty($prop_app)) {
					    $screen=$this->query("select * from ngdrstab_mst_article_screen_mapping where article_id=? and minorfun_id=? ",array($formdata['article_id'],2));
						  
					   if ($prop_app[0][0]['property_check_flag'] == 'Y') {
						  
                        if ($prop_app[0][0]['property_applicable'] == 'Y') {
							if(empty($screen)){
                           
							$this->query("insert into ngdrstab_mst_article_screen_mapping (article_id,minorfun_id) values(?,?)",array($formdata['article_id'],2));
							}
						}else{
							if(!empty($screen)){
							$this->query("delete from ngdrstab_mst_article_screen_mapping where article_id=? and minorfun_id=? ",array($formdata['article_id'],2));
						    }
						}
					   }
                    }
                }
                }
            }
            }


            if (isset($data)) {
                $this->saveAll($data);
            }
        }
        return true;
    }

}
