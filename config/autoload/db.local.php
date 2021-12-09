<?php
return [
    'db' => [
        'dsn' => 'odbc:DRIVER={IBM i Access ODBC Driver};SYSTEM=localhost;UID=chuk;PWD=d15n3y1;NAM=1;TSFT=1;DBQ=, QTEMP QGPL ACS_5OBJ ACS_5MOD ACS_5DTA ACS_5WEB ACS_5DEV;',
        'driver' => 'Pdo',
        'platform' => 'IbmDb2',
        'platform_options' => [
            'quote_identifiers' => true,
        ],
        'driver_options' => [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_EMULATE_PREPARES => true,
        ],
    ],
];