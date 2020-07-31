<?php

return array(
    'modules' => array(
        'DoctrineModule',
        'DoctrineORMModule',   
        'Application',
        'Score',     
        'Bets',
    ),

    // These are various options for the listeners attached to the ModuleManager
    'module_listener_options' => array(
        'module_paths' => array(
            './module',
            './vendor',
        ),

        'config_glob_paths' => array(
            'config/autoload/{{,*.}global,{,*.}local,{,*.}config}.php',
        ),
    ),

    // Used to create an own service manager. May contain one or more child arrays.
    //'service_listener_options' => array(
    //     array(
    //         'service_manager' => $stringServiceManagerName,
    //         'config_key'      => $stringConfigKey,
    //         'interface'       => $stringOptionalInterface,
    //         'method'          => $stringRequiredMethodName,
    //     ),
    // ),

   // Initial configuration with which to seed the ServiceManager.
   // Should be compatible with Zend\ServiceManager\Config.
   // 'service_manager' => array(),
);
