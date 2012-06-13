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
    )
);