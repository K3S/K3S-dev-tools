<?php

use Laminas\Db\Adapter\Adapter;


return [
    'ibmi_toolkit' => [
        'system' => [

            /**
             * XML Service Library
             *
             * Library where XTOOLKIT lives, most likely XMLSERVICE
             * (testing) or ZENDSVR6 (production). Can be 'ZENDSVR6' or 'XMLSERVICE'
             */
            'XMLServiceLib' => 'QXMLSERV',

            /**
             * Helper Library
             *
             * Location of ZSXMLSRV "helper" service program. Overrides
             * ToolkitServiceSet's ZSTOOLKITLIB constant library of ZS
             * "helper" service program. ZENDSVR or ZENDSVR6
             */
            'HelperLib' => 'QXMLSERV',

            /**
             * Enable XMLSERVICE trace
             *
             * Enable internal, low-level XMLSERVICE trace into table XMLSERVLOG/LOG
             * (New in PHP tkit 1.3.1, requiring XMLSERVICE 1.7.1) default is false
             */
            'trace' => false,

            /**
             * Advanced CCSID Options
             *
             * Use all three options together. Details under the heading "CCSID user
             * override - xmlservice options (hex/before/after)" at
             * http://174.79.32.155/wiki/index.php/XMLSERVICE/XMLSERVICECCSID
             */
            // 'ccsidBefore' => '819/37',
            // 'ccsidAfter' => '37/819',
            // 'useHex' => true,

            /**
             * PASE CCSID
             *
             * Controls CCSID for <sh> type of functions such as WRKACTJOB ('system'
             * command in PASE) Default is 819. Another practical value is 1208 (UTF-8).
             */
            'paseCcsid' => '819',

            /**
             * V5R4 OS Level
             *
             * If you plan to connect to v5r4 IBM i systems from this toolkit
             * installation, set v5r4=true. The toolkit will then optimize program
             * calls for v5r4 (important when calling non-ILE programs on v5r4).
             * This setting is equivalent to the following PHP code:
             * $conn->setToolkitServiceParams(array('v5r4'=>true));
             * The default is false.
             */
            'v5r4' => false,

            /**
             * Data Structure Integrity
             *
             * Whether to retain the tree-like hierarchy of data structures
             * (dataStructureIntegrity = true) or to ignore DSes, flattening out all data
             * elements to a single level (dataStructureIntegrity = false)
             * Starting in Zend Server 6, the default here is true.
             * For backward compatibility with pre-1.4.0 behavior, change one or both to
             * false.
             */
            'dataStructureIntegrity' => true,

            /**
             * Debug
             *
             * Sets PHP toolkit's debug mode on or off (true/valse)
             */
            'debug' => true,

            /**
             * Debug Log File
             *
             * This log will grow large, so leave this false when you do not need to log
             * everything.
             */
            'debugLogFile' => '/home/chuk/toolkit_debug.log',

            /**
             * Encoding
             *
             * (ISO-8859-1 is default. For some languages, such as Japanese, UTF-8
             * seems better.)
             */
            'encoding' => 'ISO-8859-1',

            /**
             * Array Integrity
             *
             * Allow true array parameters with better functionality.
             * For backward compatibility with pre-1.4.0, set to false.
             */
            'arrayIntegrity' => true,

            /**
             * Stateless mode (CW Only)
             *
             * stateless mode is default for i5_connect (though automatically overridden
             * if private conns are used)
             */
            'stateless' => true,
//        ],
//
//        'transport' => [

            /**
             * Transport type
             *
             * allows configuration of transport from this INI.
             * ibm_db2 is default. Other choices: "odbc", "http", "https"
             */
            'transportType' => 'pdo',

            /**
             * HTTP Transport URL (for http and https transports only)
             */
            // 'httpTransportUrl' => 'https://example.com/cgi-bin/xmlcgi.pgm',

            /**
             * SSL Certificate Authority File (for https transport only)
             */
            // 'sslCaFile' => '/path/to/cert.pem',

            /**
             * Server name (for http and https transports only)
             */
            // 'serverName' => 'example.com',
//        ],
//
//        'log' => [

            /**
             * Log Compatibility Wrapper Connect
             *
             * CW only: If logCwConnect = true then CW connection events will be written
             * to the logfile. This log will grow large, so set logCwConnects=false in
             * production. Certain warnings and errors will be written regardless.
             */
            'logCwConnect' => false,
//        ],
//
//        /**
//         * Hosts (compatibility wrapper only)
//         *
//         * map hosts from ip/host to database names
//         * map ip/host names to database names (WRKDBRDIRE)
//         * because old toolkit used ip/host name; Zend's toolkit uses database name.
//         * Two common keys are set by default. In CW, specify 'localhost' as host name if
//         * running on local IBM i.
//         */
//        'hosts' => [

            'localhost' => '*LOCAL',
            '127.0.0.1' => '*LOCAL',

            // examples of other mappings
            // '1.2.3.4' => 'DB1',
            // 'myhost' => 'MAINDB',
            // 'example.com' => 'LPARDB',
//        ],
//
//        'testing' => [

            /**
             * Parse Only
             *
             * parse_only means do not run your program. Only parse the XML and return
             * debug info. Useful for testing dim/dou/counters. This setting is
             * equivalent to the following PHP code:
             * $conn->setToolkitServiceParams(array('parseOnly'=>true));
             * The default is false
             */
            'parse_only' => false,

            /**
             * Parse Debug Level
             *
             * Determines the amount of parsing detail to be logged in debug log (1-9:
             * 1=none,  9=all)
            // This setting is equivalent to the following PHP code:
             * $conn->setToolkitServiceParams(array('parseDebugLevel'=>1)); // any number 1-9
             * The default is null.
             */
            'parse_debug_level' => null,
//        ],
//
//        'cw' => [

            /**
             * Full DB Close
             *
             * if want to close db connection on i5_close()
             */
            'fullDbClose' => false,
//        ],
//
//        /**
//         * CW Demo Configuration (may be deprecated in a future release)
//         */
//        'demo' => [

            /**
             * Demo Library
             *
             * Library where the Compatibility Wrapper demo is located
             */
            'demo_library' => 'CWDEMO',

            /**
             * Optional settings for demo script
             *
             * initlibl, ccsid, jobname, idle_timeout, transport_type
             * These affect the demo script only.
             * May be helpful to specify QGPL if not ordinarily present because it's
             * required in liblist to enable spool file access (Qshell's catsplf
             * command used by the CW).
             */
            // 'initlibl' => 'QGPL',
            // 'ccsid' => '37',
            // 'jobname' => 'PHPJOBX',
            // 'idle_timeout' => '30',
            // 'transport_type' => 'odbc',

            /**
             * Private Connection (for demo script only)
             */
            'private' => false,

            /**
             * Private Connection Number
             *
             * for demo script, if specified private above, this is the conn# to use.
             * A value of 0 means the toolkit should generate the number.
             * Recommendation: use 0 the first time to allow the toolkit to generate a
             * number, then edit the INI file to specify the generated number, then run
             * demo again. Private connections are slow the first time, then fast
             * afterward.
             */
            'private_num' => 0,
        ],
        'paseCcsid' => '819',
        /**
         * Log File
         *
         * Both CW and regular toolkit: warnings and errors will be written to the
         * logfile (CW and regular toolkit).
         */
        'logfile' => '/usr/local/zendphp7/var/log/toolkit.log',

        /**
         * Persistent connection (for demo script only)
         */
        'persistent' => true,

        /**
         * Plug Size
         *
         * Default plug size, which is the expected output size. 4K, 32K, 512K
         * (default), 65K, 512K, 1M, 5M, 10M, 15M can also change in code with
         * $conn->setOptions(array('plugSize' => '4K')); or desired size
         */
        'plugSize' => '1M',

        /**
         * Submit Options
         *
         * Format: JOBDLIB/JOBD/JOBNAME. If not specified, will be
         * ZENDSVR6/ZSVR_JOBD/XTOOLKIT
         * Or specify another sbmjob combination, such as QSYS/QSRVJOB/XTOOLKIT,
         * if ZENDSVR6 library isn't present on the system or LPAR.
         */
        'sbmjobParams' => 'XMLSERVICE/ZSVR_JOBD/XTOOLKIT',

        /**
         * Database Adapter Service
         *
         * If using db2 transport, specify the name of the database adapter service
         * (registered in the service manager)
         */
        'databaseAdapterService' => Adapter::class,
    ],
];
