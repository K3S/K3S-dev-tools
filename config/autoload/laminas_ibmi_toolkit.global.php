<?php

use Laminas\Db\Adapter\Adapter;

return [
    'ibmi_toolkit' => [
        'system' => [
            'transportType' => 'db2',
            'XMLServiceLib' => 'QXMLSERV',
            'HelperLib' => 'QXMLSERV',
            'debug' => false,
//            'debug' => true,
//            'debugLogFile' => realpath(__DIR__ . '/../../logs/toolkit/debug.log'),
            'trace' => false,
            'sbmjobParams' => 'QSYS/QSRVJOB/XTOOLKIT',
            'stateless' => true,
        ],
        /**
         * Database Adapter Service
         *
         * If using db2, odbc, or pdo transport, specify the name of the database adapter service
         * (registered in the service manager)
         */
        'databaseAdapterService' => Adapter::class,
    ],
];
