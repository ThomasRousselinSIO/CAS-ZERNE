<?php

declare(strict_types=1);

require_once __DIR__ . '/../Models/Caserne.php';

/**
 * Contrôleur MVC : prépare les données et déclenche l'appel d'une équipe.
 */
final class CaserneController
{
    public function accueil(): array
    {
        $periode = new Periode(new DateTimeImmutable('2025-11-18'), 2);
        $appels = [];
        $caserne = new Caserne(
            static function (string $numero) use (&$appels): void {
                $appels[] = $numero;
            }
        );

        $disponible = new Pompier('Durand', 'François', '0600000001');
        $auTravail = new Pompier('Legall', 'Yasmina', '0600000002');
        $indisponible = new Pompier('Dubois', 'Yves', '0600000003');

        $periode->disponible->ajouter($disponible);
        $periode->auTravail->ajouter($auTravail);
        $caserne->ajouterPompier($disponible);
        $caserne->ajouterPompier($auTravail);
        $caserne->ajouterPompier($indisponible);

        $nombreAppeles = $caserne->appelEquipe($periode, 2);

        return [
            'periode' => $periode,
            'pompiers' => [$disponible, $auTravail, $indisponible],
            'nombreAppeles' => $nombreAppeles,
            'appels' => $appels,
        ];
    }
}
