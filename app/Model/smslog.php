<?php

App::uses('AuthComponent', 'Controller/Component');

class smslog extends AppModel {

    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_smslogs';
    
}