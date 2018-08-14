<?php

include __DIR__ . '/db.php';

return [
    'components' => [
        'db' => ['dsn' => "mysql:host=127.0.0.1;dbname={$dbname}_test"],
    ],
];
