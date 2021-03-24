<?php

include __DIR__ . '/db.php';

return [
    'components' => [
        'db' => ['dsn' => "mysql:host=$dbhost;dbname={$dbname}_test"],
    ],
];
