<?php

/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {
    //------------------------------By Shridahr--------------------------------------------------

    /**
     * by Shridhar
     * To get data in with index based array i.e indexfield as key and removing extra indexes like [0] or [modulename]
     * @param array $data
     * @param type $moduleName
     * @param type $indexField
     * @return type
     */
    function formatData(array $data, $moduleName = '', $indexField) {
        if(empty($data)){
            return [];
        }
        $result = array();
        foreach ($data as $record) {
            $key = empty($record[$moduleName][$indexField]) ? $record[0][$indexField] : $record[$moduleName][$indexField];
            $result[$key] = array_reduce($record, 'array_merge', []);
        }
        return $result;
    }

//---------------------------------------------------------------------------------------------
}
