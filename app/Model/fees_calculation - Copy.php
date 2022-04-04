<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of evalrule
 *
 * @author Administrator
 */
class fees_calculation extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_fee_calculation';
    public $primaryKey = 'fee_calc_id';

    public function get_fee_calculation($fee_calc_id, $lang = 'en') {

        return $this->find('all', array('fields' => array('article.article_desc_'.$lang, 'fees.fee_rule_desc_'.$lang, 'fee_calc_id'),
                    'joins' => array(
                        array('table' => 'ngdrstab_mst_article', 'alias' => 'article', 'conditions' => array('fees_calculation.article_id = article.article_id')),
                        array('table' => 'ngdrstab_mst_article_fee_rule', 'alias' => 'fees', 'conditions' => array('fees_calculation.fee_rule_id = fees.fee_rule_id'))
                    ),
                    'conditions' => array('fees_calculation.fee_calc_id' => $fee_calc_id)
        ));
    }

}
