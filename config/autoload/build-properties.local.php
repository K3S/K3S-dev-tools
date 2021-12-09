<?php
return [
    'build-properties' => [
        'development' => [
            'source-library' => [
                'name' => 'ACS_5DEV',
                'source-physical-files' => [
                    'RPG' => 'QRPGLESRC',
                    'CL' => 'QCLLESRC',
                    'service-program-signature-symbols' => 'QSRVSRC',
                ],
            ],
            'object-library' => [
                'name' => 'ACS_5OBJ',
            ],
        ],
        'testing' => [
            'source-library' => [
                'name' => 'WEB_5TDV',
                'source-physical-files' => [
                    'RPG' => 'QRPGLESRC',
                    'CL' => 'QCLLESRC',
                    'service-program-signature-symbols' => 'QSRVSRC',
                ],
            ],
            'object-library' => [
                'name' => 'WEB_5TST',
            ],
        ],
        'deployment' => [
            'source-library' => [
                'name' => 'WEB_5DEV',
                'source-physical-files' => [
                    'RPG' => 'QRPGLESRC',
                    'CL' => 'QCLLESRC',
                    'service-program-signature-symbols' => 'QSRVSRC',
                ],
            ],
            'object-library' => [
                'name' => 'WEB_5OBJ',
            ],
        ],
    ],
];