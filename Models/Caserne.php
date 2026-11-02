<?php

declare(strict_types=1);

require_once __DIR__ . '/Collection.php';
require_once __DIR__ . '/Pompier.php';
require_once __DIR__ . '/Periode.php';

final class Caserne
{
    private Collection $lesPompiers;

    private Closure $appeler;

    /** @param callable(string): void|null $appeler */
    public function __construct(?callable $appeler = null)
    {
        $this->lesPompiers = new Collection();
        $this->appeler = $appeler === null
            ? static function (string $numero): void {}
            : Closure::fromCallable($appeler);
    }

    public function ajouterPompier(Pompier $pompier): void
    {
        if (!$this->lesPompiers->contient($pompier)) {
            $this->lesPompiers->ajouter($pompier);
        }
    }

    public function appelEquipe(Periode $periode, int $nbPompiers): int
    {
        $equipe = $periode->selectEquipe($nbPompiers);
        $nombreAppeles = 0;

        foreach ($equipe as $pompier) {
            if (!$this->lesPompiers->contient($pompier)) {
                continue;
            }

            $pompier->missionner($periode);
            ($this->appeler)($pompier->getNumeroBip());
            $nombreAppeles++;
        }

        return $nombreAppeles;
    }
}
