<?php

declare(strict_types=1);

require_once __DIR__ . '/../Models/Database.php';
require_once __DIR__ . '/../Models/PompierRepository.php';

final class CaserneController
{
    private PompierRepository $firefighterRepository;

    public function __construct()
    {
        $database = new Database();
        $this->firefighterRepository = new PompierRepository($database->connection());
    }

    /** @return array<string, mixed> */
    public function afficherPlanning(string $date, int $slot, ?string $message = null): array
    {
        $this->validateDateAndSlot($date, $slot);

        return [
            'firefighters' => $this->firefighterRepository->trouverParDateEtCreneau($date, $slot),
            'selectedDate' => $date,
            'selectedSlot' => $slot,
            'message' => $message,
        ];
    }

    public function enregistrerDisponibilite(
        int $firefighterId,
        string $date,
        int $slot,
        string $status
    ): void {
        $this->validateDateAndSlot($date, $slot);
        $this->firefighterRepository->enregistrerStatut($firefighterId, $date, $slot, $status);
    }

    private function validateDateAndSlot(string $date, int $slot): void
    {
        $parsedDate = DateTimeImmutable::createFromFormat('!Y-m-d', $date);
        if ($parsedDate === false || $parsedDate->format('Y-m-d') !== $date) {
            throw new InvalidArgumentException('La date sélectionnée est invalide.');
        }
        if ($slot < 1 || $slot > 4) {
            throw new InvalidArgumentException('Le créneau doit être compris entre 1 et 4.');
        }
    }
}
