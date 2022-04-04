<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of areaconversion
 *
 * @author Administrator
 */
class rateconversion extends AppModel {

    //put your code here.
    public function standardrateconversion($ratearray = NULL) {
       //pr($ratearray);
        $unit = ClassRegistry::init('unit')->find('all', array('conditions' => array('unit_id' => $ratearray['prop_unit'])));
    //  pr($unit);
    //  exit;
        $rateformula = $ratearray['prop_rate'] / $unit[0]['unit']['conversion_formula'];
        $landrateformula = $ratearray['land_rate'] / $unit[0]['unit']['conversion_formula'];
        $constructionrateformula = $ratearray['construction_rate'] / $unit[0]['unit']['conversion_formula'];

        if ($ratearray['prop_rate'] != NULL) {
            $convertedrate['prop_rate'] = eval("return ($rateformula);");
        } else {
            $convertedrate['prop_rate'] = 0;
        }

        if ($ratearray['land_rate'] != NULL) {
            $convertedrate['land_rate'] = eval("return ($landrateformula);");
        } else {
            $convertedrate['land_rate'] = 0;
        }

        if ($ratearray['construction_rate'] != NULL) {
            $convertedrate['construction_rate'] = eval("return ($constructionrateformula);");
        } else {
            $convertedrate['construction_rate'] = 0;
        }
        
        return $convertedrate;
    }

}
