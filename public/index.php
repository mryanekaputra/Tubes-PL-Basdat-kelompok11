<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../config/config.php';
foreach (glob(__DIR__ . '/../app/core/*.php') as $file) require_once $file;
foreach (glob(__DIR__ . '/../app/models/*.php') as $file) require_once $file;
(new Router())->dispatch();
