<?php

declare(strict_types=1);

require_once __DIR__ . '/../Controllers/CaserneController.php';

$controller = new CaserneController();
$data = $controller->accueil();

extract($data, EXTR_SKIP);
require __DIR__ . '/../Views/accueil.php';
