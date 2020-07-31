<?php

$db = array(
    'database' => 'bets',
    'username' => 'azureuser',
    'password' => 'Azure1234567',
    'hostname' => 'bumasqlserver.database.windows.net'
);

return array(
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\Sqlsrv\Driver',
                'params' => array(
                    'host'     => $db['hostname'],
                    'port'     => $db['port'],
                    'user'     => $db['username'],
                    'password' => $db['password'],
                    'dbname'   => $db['database'],
                ),
            ),
        ),
        'entitymanager' => array(
            'orm_default' => array(
                'connection' => 'orm_default',
                'configuration' => 'orm_default',
            ),
        ),
        'configuration' => array(
            'orm_default' => array(
                'metadata_cache' => 'array',
                'query_cache' => 'array',
                'result_cache' => 'array',
                'hydration_cache' => 'array',
                'generate_proxies' => true,
            ),
        ),
    ),

    // 'service_manager' => array(
    //     'factories' => array(
    //         'zend_db_adapter' => function ($sm) use ($db){
    //             return new Zend\Db\Adapter\Adapter(array(
    //                'driver' => 'pdo',
    //                 'dsn' => 'mysql:dbname=' . $db['database'] . ';host=' . $db['hostname'],
    //                 'database' => $db['database'],
    //                 'username' => $db['username'],
    //                 'password' => $db['password'],
    //                 'hostname' => $db['hostname']
    //             ));
    //         }
    //     )
    // )
);