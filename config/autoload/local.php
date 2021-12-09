<?php
ini_set('date.timezone', 'America/Chicago');

$username = 'chuk';
$password = 'd15n3y1';

return [
    'db' => [
        'username' => $username,
        'password' => $password,
        'driver' => 'IbmDb2',
        'database' => '*LOCAL',
        'persistent' => true,
        'platform' => 'IbmDb2',
        'platform_options' => [
            'quote_identifiers' => true,
        ],
        'driver_options' => [
            'i5_naming' => 1,
            'i5_commit' => DB2_I5_TXN_READ_UNCOMMITTED,
            'autocommit' => DB2_AUTOCOMMIT_ON,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => true,
    ],
    'defaults' => [
        'development_library' => 'ACS_5DEV',
        'development_source_physical_file' => 'QRPGLESRC',
    ],
];
