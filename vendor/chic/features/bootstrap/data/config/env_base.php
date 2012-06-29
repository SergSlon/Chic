<?php

/**
 * This file is generated, if you add config data, they will be kept.
 * But commments won't.
 */
return array(
    'environments' => array(
        'dev' => function() {
            return isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] === 'localhost';
        }
    ),
            
    'i18n' => array(
        'langs' => array('en','fr'),
        'default' => 'en'
    ),
            
    'router' => array(
        'base' => 'http://alexgalinier.com',
        'defaultMethodName' => 'default',
        'defaultHttpMethods' => array('GET','POST'),
        'getHttpMethod' => function() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                return (isset($_POST['_method'])) ? $_POST['_method'] : 'POST';
            } else {
                return $_SERVER['REQUEST_METHOD'];
            }
        }
    )
);