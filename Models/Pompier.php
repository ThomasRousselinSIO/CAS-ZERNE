<?php

declare(strict_types=1);

require_once __DIR__ . '/Collection.php';

final class Pompier
{
    public function __construct(
        private readonly string $nom,
        private readonly string $prenom,
        private readonly string $numeroBip
    ) {
        if ($nom === '' || $prenom === '' || $numeroBip === '') {
            throw new InvalidArgumentException('Le nom, le prénom et le numéro de bip sont obligatoires.');
        }
    }

    public function getNumeroBip(): string
    {
        return $this->numeroBip;
    }

    public function getStatut(Periode $periode): string
    {
        return $periode->getStatut($this);
    }

    public function missionner(Periode $periode): void
    {
        $periode->missionner($this);
    }

    public function __toString(): string
    {
        return "{$this->prenom} {$this->nom}";
    }
}
