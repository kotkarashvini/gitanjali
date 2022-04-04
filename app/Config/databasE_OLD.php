<?php

class DATABASE_CONFIG {

    public $default = array(
        'datasource' => 'Database/Postgres',
        'persistent' => false,
        'host' => 'localhost',
        'login' => 'postgres',
        'password' => 'postgres',
        'database' => 'ngdrs_tripura',
        'port' => '5433',
        'encoding' => 'utf8',
        'charset' => 'UTF8',
        'cacheMetadata' => true
    );
    public $ngprs = array(
        'datasource' => 'Database/Postgres',
        'persistent' => false,
        'host' => 'localhost',
        'login' => 'postgres',
        'password' => 'postgres',
        'database' => 'ngdrs_tripura',
        'port' => '5433',
        'encoding' => 'utf8',
        'charset' => 'UTF8',
        'cacheMetadata' => true
    );
    /*
    public $testcon = array(
        'datasource' => 'Database/Postgres',
        'persistent' => false,
        'host' => '10.153.45.31',
        'login' => 'postgres',
        'password' => 'postgres',
        'database' => 'ngdrs_interface_tr',
        'port' => '5432',
        'encoding' => 'utf8',
        'charset' => 'UTF8',
        'cacheMetadata' => false
    );
*/
}