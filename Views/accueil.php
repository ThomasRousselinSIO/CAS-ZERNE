<?php

declare(strict_types=1);

/** @var array<int, array<string, mixed>> $firefighters */
/** @var string $selectedDate */
/** @var int $selectedSlot */
/** @var string|null $message */
$slotLabels = [
    1 => '00h00 - 06h00',
    2 => '06h00 - 12h00',
    3 => '12h00 - 18h00',
    4 => '18h00 - 00h00',
];
$today = (new DateTimeImmutable('today'))->format('Y-m-d');
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cas ZERNE - Disponibilités</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1000px; margin: 2rem auto; padding: 0 1rem; color: #20252b; }
        header { border-bottom: 4px solid #e30613; margin-bottom: 2rem; }
        h1 { margin-bottom: .25rem; }
        .panel { background: #f4f6f8; border-left: 4px solid #e30613; padding: 1rem; margin-bottom: 1rem; }
        .success { border-left-color: #087f23; }
        .error { border-left-color: #b00020; color: #b00020; }
        form.filters { display: flex; gap: 1rem; align-items: end; flex-wrap: wrap; }
        label { display: flex; flex-direction: column; gap: .35rem; font-weight: bold; }
        input, select, button { font: inherit; padding: .5rem; }
        button { cursor: pointer; background: #20252b; color: white; border: 0; border-radius: 3px; }
        table { border-collapse: collapse; width: 100%; margin-top: 1rem; }
        th, td { border: 1px solid #cfd5da; padding: .7rem; text-align: left; }
        th { background: #20252b; color: white; }
        td form { display: flex; gap: .5rem; align-items: center; }
        .mission { color: #087f23; font-weight: bold; }
    </style>
</head>
<body>
<header>
    <h1>Cas ZERNE</h1>
    <p>Gestion des disponibilités des pompiers</p>
</header>
<main>
    <?php if ($message !== null): ?>
        <div class="panel <?= str_contains($message, 'enregistré') ? 'success' : 'error' ?>">
            <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>
    <section class="panel">
        <form class="filters" method="get">
            <label>
                Date
                <input type="date" name="date" value="<?= htmlspecialchars($selectedDate, ENT_QUOTES, 'UTF-8') ?>">
            </label>
            <label>
                Créneau de 6 heures
                <select name="slot">
                    <?php foreach ($slotLabels as $number => $label): ?>
                        <option value="<?= $number ?>" <?= $selectedSlot === $number ? 'selected' : '' ?>>
                            <?= $number ?> - <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <button type="submit">Afficher</button>
        </form>
        <?php if ($selectedDate !== $today): ?>
            <p>Cette feuille concerne une date passée ou future : elle est conservée dans l'historique.</p>
        <?php endif; ?>
    </section>
    <p>
        Date du <strong><?= htmlspecialchars((new DateTimeImmutable($selectedDate))->format('d/m/Y'), ENT_QUOTES, 'UTF-8') ?></strong>,
        créneau <strong><?= $selectedSlot ?> (<?= htmlspecialchars($slotLabels[$selectedSlot], ENT_QUOTES, 'UTF-8') ?>)</strong>.
    </p>
    <table>
        <thead><tr><th>Pompier</th><th>Numéro de bip</th><th>Statut enregistré</th><th>Modifier</th></tr></thead>
        <tbody>
        <?php foreach ($firefighters as $firefighter): ?>
            <tr>
                <td><?= htmlspecialchars($firefighter['prenom'] . ' ' . $firefighter['nom'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($firefighter['numeroBip'], ENT_QUOTES, 'UTF-8') ?></td>
                <td class="<?= (int) $firefighter['deGarde'] === 1 ? 'mission' : '' ?>">
                    <?= (int) $firefighter['deGarde'] === 1 ? 'En mission' : htmlspecialchars($firefighter['status_label'], ENT_QUOTES, 'UTF-8') ?>
                </td>
                <td>
                    <form method="post">
                        <input type="hidden" name="firefighter_id" value="<?= (int) $firefighter['id'] ?>">
                        <input type="hidden" name="date" value="<?= htmlspecialchars($selectedDate, ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="slot" value="<?= $selectedSlot ?>">
                        <select name="status" aria-label="Nouveau statut">
                            <?php foreach (['mission' => 'En mission', 'travail' => 'Au travail', 'indisponible' => 'Indisponible', 'disponible' => 'Disponible'] as $value => $label): ?>
                                <option value="<?= $value ?>"><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit">Enregistrer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</main>
</body>
</html>
