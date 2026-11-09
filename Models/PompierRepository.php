<?php

declare(strict_types=1);

final class PompierRepository
{
    public function __construct(private readonly PDO $connection)
    {
    }

    /** @return array<int, array<string, mixed>> */
    public function trouverParDateEtCreneau(string $date, int $slot): array
    {
        $this->ensureSchedule($date, $slot);
        $query = $this->connection->prepare(
            'SELECT v.id, v.nom, v.prenom, v.numeroBip, d.id AS status_id,
                    d.libelle AS status_label, a.deGarde
             FROM volontaire v
             JOIN avoir_activite a
               ON a.idVolontaire = v.id
              AND a.datePeriode = :date
              AND a.idTranche = :slot
             JOIN disponibilite d ON d.id = a.idDisponibilite
             ORDER BY v.nom, v.prenom'
        );
        $query->execute(['date' => $date, 'slot' => $slot]);

        return $query->fetchAll();
    }

    public function enregistrerStatut(int $firefighterId, string $date, int $slot, string $status): void
    {
        $statusMap = [
            'indisponible' => ['id' => 1, 'onMission' => 0],
            'travail' => ['id' => 2, 'onMission' => 0],
            'disponible' => ['id' => 3, 'onMission' => 0],
            'mission' => ['id' => 3, 'onMission' => 1],
        ];
        if (!isset($statusMap[$status])) {
            throw new InvalidArgumentException('Le statut choisi est invalide.');
        }

        $this->ensureSchedule($date, $slot);
        $query = $this->connection->prepare(
            'UPDATE avoir_activite
             SET idDisponibilite = :status, deGarde = :onMission
             WHERE idVolontaire = :firefighter
               AND datePeriode = :date
               AND idTranche = :slot'
        );
        $query->execute([
            'status' => $statusMap[$status]['id'],
            'onMission' => $statusMap[$status]['onMission'],
            'firefighter' => $firefighterId,
            'date' => $date,
            'slot' => $slot,
        ]);
        if ($query->rowCount() !== 1) {
            throw new RuntimeException('Le pompier demandé est introuvable.');
        }
    }

    private function ensureSchedule(string $date, int $slot): void
    {
        $period = $this->connection->prepare(
            'INSERT IGNORE INTO periode_garde (idTranche, datePeriode, nbPompiers)
             VALUES (:slot, :date, 0)'
        );
        $period->execute(['slot' => $slot, 'date' => $date]);

        $activities = $this->connection->prepare(
            'INSERT IGNORE INTO avoir_activite
                (idVolontaire, idTranche, datePeriode, idDisponibilite, deGarde)
             SELECT id, :slot, :date, 3, 0 FROM volontaire'
        );
        $activities->execute(['slot' => $slot, 'date' => $date]);
    }
}
