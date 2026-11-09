<?php

declare(strict_types=1);

require_once __DIR__ . '/../Controllers/CaserneController.php';

try {
    $controller = new CaserneController();
} catch (Throwable $exception) {
    http_response_code(500);
    echo '<h1>Base de données indisponible</h1><p>'
        . htmlspecialchars($exception->getMessage(), ENT_QUOTES, 'UTF-8')
        . '</p>';
    exit;
}
$date = $_POST['date'] ?? $_GET['date'] ?? (new DateTimeImmutable('today'))->format('Y-m-d');
$slot = (int) ($_POST['slot'] ?? $_GET['slot'] ?? 1);
$message = null;

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->enregistrerDisponibilite(
            (int) ($_POST['firefighter_id'] ?? 0),
            $date,
            $slot,
            (string) ($_POST['status'] ?? '')
        );
        $message = 'Le statut a été enregistré dans la base de données.';
    }
    $data = $controller->afficherPlanning($date, $slot, $message);
} catch (Throwable $exception) {
    http_response_code(400);
    $safeDate = DateTimeImmutable::createFromFormat('!Y-m-d', $date);
    $data = [
        'firefighters' => [],
        'selectedDate' => $safeDate !== false
            ? $date
            : (new DateTimeImmutable('today'))->format('Y-m-d'),
        'selectedSlot' => max(1, min(4, $slot)),
        'message' => $exception->getMessage(),
    ];
}

extract($data, EXTR_SKIP);
require __DIR__ . '/../Views/accueil.php';
