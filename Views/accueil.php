<?php

declare(strict_types=1);

/** @var Periode $periode */
/** @var Pompier[] $pompiers */
/** @var int $nombreAppeles */
/** @var string[] $appels */
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cas ZERNE - Feuille de garde</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 900px; margin: 2rem auto; padding: 0 1rem; color: #20252b; }
        header { border-bottom: 4px solid #e30613; margin-bottom: 2rem; }
        h1 { margin-bottom: .25rem; }
        .resume { background: #f4f6f8; border-left: 4px solid #e30613; padding: 1rem; }
        table { border-collapse: collapse; width: 100%; margin-top: 1rem; }
        th, td { border: 1px solid #cfd5da; padding: .7rem; text-align: left; }
        th { background: #20252b; color: white; }
        .mission { color: #087f23; font-weight: bold; }
    </style>
</head>
<body>
<header>
    <h1>Cas ZERNE</h1>
    <p>Gestion d'une équipe d'intervention - architecture MVC</p>
</header>
<main>
    <section class="resume">
        <strong><?= $nombreAppeles ?> pompier(s) appelé(s)</strong>
        <p>
            Période du <?= htmlspecialchars($periode->date->format('d/m/Y'), ENT_QUOTES, 'UTF-8') ?>,
            tranche <?= $periode->tranche ?>.
            Les pompiers disponibles sont sélectionnés avant ceux qui sont au travail.
        </p>
        <p>
            Bips appelés :
            <?= htmlspecialchars(implode(', ', $appels), ENT_QUOTES, 'UTF-8') ?>
        </p>
    </section>
    <table>
        <thead><tr><th>Pompier</th><th>Numéro de bip</th><th>Statut</th></tr></thead>
        <tbody>
        <?php foreach ($pompiers as $pompier): ?>
            <?php $statut = $pompier->getStatut($periode); ?>
            <tr>
                <td><?= htmlspecialchars((string) $pompier, ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($pompier->getNumeroBip(), ENT_QUOTES, 'UTF-8') ?></td>
                <td class="<?= $statut === 'm' ? 'mission' : '' ?>">
                    <?= $statut === 'm' ? 'En mission (m)' : 'Non mobilisé (' . $statut . ')' ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</main>
</body>
</html>
