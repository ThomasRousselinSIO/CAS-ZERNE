<?php

declare(strict_types=1);

require_once __DIR__ . '/Collection.php';
require_once __DIR__ . '/Pompier.php';

final class Periode
{
    public readonly Collection $enMission;
    public readonly Collection $auTravail;
    public readonly Collection $disponible;

    public function __construct(
        public readonly DateTimeImmutable $date,
        public readonly int $tranche
    ) {
        if ($tranche < 1 || $tranche > 4) {
            throw new InvalidArgumentException('Une tranche doit être comprise entre 1 et 4.');
        }

        $this->enMission = new Collection();
        $this->auTravail = new Collection();
        $this->disponible = new Collection();
    }

    public function getStatut(Pompier $pompier): string
    {
        if ($this->enMission->contient($pompier)) {
            return 'm';
        }
        if ($this->auTravail->contient($pompier)) {
            return 't';
        }
        if ($this->disponible->contient($pompier)) {
            return 'd';
        }
        return '-';
    }

    public function missionner(Pompier $pompier): void
    {
        if ($this->enMission->contient($pompier)) {
            return;
        }

        if ($this->disponible->contient($pompier)) {
            $this->disponible->enlever($pompier);
        } elseif ($this->auTravail->contient($pompier)) {
            $this->auTravail->enlever($pompier);
        } else {
            throw new LogicException("$pompier n'est pas affecté à cette période.");
        }

        $this->enMission->ajouter($pompier);
    }

    /**
     * Sélectionne sans modifier les statuts : disponibles d'abord, puis au travail.
     */
    public function selectEquipe(int $nbPompiers): Collection
    {
        if ($nbPompiers < 0) {
            throw new InvalidArgumentException('Le nombre de pompiers ne peut pas être négatif.');
        }

        $equipe = new Collection();
        foreach ($this->disponible as $pompier) {
            if ($equipe->cardinal() >= $nbPompiers) {
                break;
            }
            $equipe->ajouter($pompier);
        }
        foreach ($this->auTravail as $pompier) {
            if ($equipe->cardinal() >= $nbPompiers) {
                break;
            }
            $equipe->ajouter($pompier);
        }

        return $equipe;
    }
}
